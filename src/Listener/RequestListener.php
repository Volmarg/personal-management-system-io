<?php


namespace App\Listener;


use App\Attribute\Action\ExternalActionAttribute;
use App\Attribute\Security\ValidateCsrfTokenAttribute;
use App\Controller\API\ApiController;
use App\Controller\Core\ConfigLoader;
use App\Controller\Core\Services;
use App\Controller\UserController;
use App\DTO\BaseApiDTO;
use App\Service\Attribute\AttributeReaderService;
use App\Service\CookiesService;
use DateTime;
use Exception;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class RequestListener
 * @package App\Listener
 */
class RequestListener implements EventSubscriberInterface
{

    /**
     * @var AttributeReaderService $attributeReaderService
     */
    private AttributeReaderService $attributeReaderService;

    /**
     * @var ApiController $apiController
     */
    private ApiController $apiController;

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @var UserController $userController
     */
    private UserController $userController;

    /**
     * @var ConfigLoader $configLoader
     */
    private ConfigLoader $configLoader;

    /**
     * RequestListener constructor.
     *
     * @param AttributeReaderService $attributeReaderService
     * @param ApiController $apiController
     * @param Services $services
     * @param TokenStorageInterface $tokenStorage
     * @param UserController $userController
     */
    public function __construct(
        AttributeReaderService  $attributeReaderService,
        ApiController           $apiController,
        Services                $services,
        TokenStorageInterface   $tokenStorage,
        UserController          $userController,
        ConfigLoader            $configLoader
    )
    {
        $this->services               = $services;
        $this->configLoader           = $configLoader;
        $this->tokenStorage           = $tokenStorage;
        $this->apiController          = $apiController;
        $this->userController         = $userController;
        $this->attributeReaderService = $attributeReaderService;
    }

    /**
     * Handle logic upon request
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function onRequest(RequestEvent $requestEvent): void
    {
        $continueHandlingRequest = $this->handleRequestForLoggedInUserWithoutEncryptionKeyFile($requestEvent);
        if(!$continueHandlingRequest){
            return;
        }

        $this->validateRequest($requestEvent);
        $this->updateUserActivity();
    }

    /**
     * Will validate request in one or more ways
     *
     * @throws ReflectionException
     */
    private function validateRequest(RequestEvent $requestEvent): void
    {
        $request = $requestEvent->getRequest();
        if( $this->attributeReaderService->hasUriAttribute($request->getUri(), ExternalActionAttribute::class) ){

            $this->preValidateExternalActionRequest($requestEvent);
        }elseif(
                Request::METHOD_POST == $request->getMethod()
            &&  $this->attributeReaderService->hasUriAttribute($request->getUri(), ValidateCsrfTokenAttribute::class)
        ){
            $this->validateCsrfToken($requestEvent);
        }

        // just a safety check where for some reason file still remains but the request without session cookie was made
        if( !CookiesService::isUserSessionCookieSet($request) ){
            $this->services->getFilesService()->removeEncryptionFile();
        }
    }

    /**
     * Will handle case where user is logged in but the encryption key is missing (cron might've removed it by accident)
     * Return the information (bool) if request should continue or not
     *
     * @param RequestEvent $requestEvent
     * @return bool
     */
    private function handleRequestForLoggedInUserWithoutEncryptionKeyFile(RequestEvent $requestEvent): bool
    {

        if(
                !empty($this->tokenStorage->getToken())
            &&  !file_exists($this->configLoader->getConfigLoaderPaths()->getEncryptionFilePath())
        ){
            $unauthorizedMessage = $this->services->getTranslator()->trans('security.login.messages.UNAUTHORIZED');
            $this->services->getLoggerService()->getLogger()->info("User was still logged in but the encryption key has been invalidated");

            $this->userController->invalidateUser();

            $response = BaseApiDTO::buildUnauthorizedResponse($unauthorizedMessage)->toJsonResponse();
            $requestEvent->setResponse($response);
            return false;
        }

        return true;
    }

    /**
     * Handles pre validating external action request
     *
     * @param RequestEvent $requestEvent
     */
    private function preValidateExternalActionRequest(RequestEvent $requestEvent): void
    {
        $request = $requestEvent->getRequest();
        $json    = $request->getContent();

        $isJsonValid = $this->apiController->validateJson($json);
        if(!$isJsonValid){
            $this->services->getLoggerService()->getLogger()->warning("Provided json in request is not valid");
            $jsonResponse = BaseApiDTO::buildInvalidJsonResponse()->toJsonResponse();
            $requestEvent->setResponse($jsonResponse);
            $requestEvent->stopPropagation();

            return;
        }

    }

    /**
     * Will validate the csrf token, sets the unauthorized response on failure
     *
     * @param RequestEvent $requestEvent
     */
    private function validateCsrfToken(RequestEvent $requestEvent): void
    {
        $this->services->getLoggerService()->getLogger()->info("Validating CSRF Token in request");
        $unauthorizedMessage = $this->services->getTranslator()->trans('security.login.messages.UNAUTHORIZED');
        $request             = $requestEvent->getRequest();

        $isJsonValid = $this->apiController->validateJson($requestEvent->getRequest()->getContent());
        if(!$isJsonValid){
            $response = BaseApiDTO::buildUnauthorizedResponse($unauthorizedMessage)->toJsonResponse();
            $requestEvent->setResponse($response);
            return;
        }

        $isCsrfTokenValid = $this->services->getCsrfTokenValidatorService()->validateCsrfTokenInPostRequest($request);
        if(!$isCsrfTokenValid){
            $this->services->getLoggerService()->getLogger()->info("Could not authenticate incoming request", [
                'requestHeaders'  => $request->headers->all(),
                'requestPostData' => $request->request->all(),
            ]);

            $response = BaseApiDTO::buildUnauthorizedResponse($unauthorizedMessage)->toJsonResponse();
            $requestEvent->setResponse($response);
            return;
        }

        $this->services->getLoggerService()->getLogger()->info("CSRF authentication resulted in SUCCESS");
    }

    /**
     * If user is logged in then his activity will be updated in database on each request to track if he should be
     * maybe logged out or if the encryption should be later removed as user was inactive for to long
     *
     * @throws Exception
     */
    private function updateUserActivity(): void
    {
        if( !empty($this->tokenStorage->getToken()?->getUser()) )
        {
            $user     = $this->tokenStorage->getToken()->getUser();
            $realUser = $this->userController->getOneByUsername($user->getUsername());

            if( empty($realUser) ){
                throw new Exception("No real user has been found for username: {$user->getUsername()}");
            }

            $realUser->setLastActivity(new DateTime());
            $this->userController->save($realUser);
        }
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => "onRequest"
        ];
    }
}