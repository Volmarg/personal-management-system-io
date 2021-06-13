<?php


namespace App\Listener;


use App\Attribute\Action\ExternalActionAttribute;
use App\Attribute\Security\ValidateCsrfTokenAttribute;
use App\Controller\API\ApiController;
use App\Controller\ApiUserController;
use App\Controller\Core\ConfigLoader;
use App\Controller\Core\Services;
use App\Controller\System\IncomingRequestController;
use App\Controller\UserController;
use App\DTO\BaseApiDTO;
use App\Entity\System\IncomingRequest;
use App\Entity\User;
use App\Service\Attribute\AttributeReaderService;
use App\Service\CookiesService;
use App\Service\External\IpInfoService;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use ipinfo\ipinfo\IPinfoException;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use TypeError;

/**
 * Class RequestListener
 * @package App\Listener
 */
class RequestListener implements EventSubscriberInterface
{

    const ALLOWED_COUNTRIES_IP_ACCESS = [
      IpInfoService::COUNTRY_POLAND_SHORTNAME,
      IpInfoService::COUNTRY_GERMANY_SHORTNAME,
    ];

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
     * @var ApiUserController $apiUserController
     */
    private ApiUserController $apiUserController;

    /**
     * @var IncomingRequestController $incomingRequestController
     */
    private IncomingRequestController $incomingRequestController;

    /**
     * RequestListener constructor.
     *
     * @param AttributeReaderService $attributeReaderService
     * @param ApiController $apiController
     * @param Services $services
     * @param TokenStorageInterface $tokenStorage
     * @param UserController $userController
     * @param ConfigLoader $configLoader
     * @param ApiUserController $apiUserController
     * @param IncomingRequestController $incomingRequestController
     */
    public function __construct(
        AttributeReaderService    $attributeReaderService,
        ApiController             $apiController,
        Services                  $services,
        TokenStorageInterface     $tokenStorage,
        UserController            $userController,
        ConfigLoader              $configLoader,
        ApiUserController         $apiUserController,
        IncomingRequestController $incomingRequestController
    )
    {
        $this->services                  = $services;
        $this->configLoader              = $configLoader;
        $this->tokenStorage              = $tokenStorage;
        $this->apiController             = $apiController;
        $this->userController            = $userController;
        $this->attributeReaderService    = $attributeReaderService;
        $this->apiUserController         = $apiUserController;
        $this->incomingRequestController = $incomingRequestController;
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
     * @throws IPinfoException
     */
    private function validateRequest(RequestEvent $requestEvent): void
    {
        $request = $requestEvent->getRequest();
        if( !$this->validateCountryForIp($request) ){
            $unauthorizedMessage = $this->services->getTranslator()->trans('security.login.messages.UNAUTHORIZED');
            $response = BaseApiDTO::buildUnauthorizedResponse($unauthorizedMessage)->toJsonResponse();
            $requestEvent->setResponse($response);
            return;
        }

        if( $this->attributeReaderService->hasUriAttribute($request->getRequestUri(), ExternalActionAttribute::class) ){
            return; // Security authenticator will handle this
        }elseif(
                Request::METHOD_POST == $request->getMethod()
            &&  $this->attributeReaderService->hasUriAttribute($request->getRequestUri(), ValidateCsrfTokenAttribute::class)
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
     * @throws ReflectionException
     */
    private function handleRequestForLoggedInUserWithoutEncryptionKeyFile(RequestEvent $requestEvent): bool
    {

        if(
                !empty($this->tokenStorage->getToken())
            &&  !file_exists($this->configLoader->getConfigLoaderPaths()->getEncryptionFilePath())
            &&  !$this->services->getAttributeReader()->hasUriAttribute($requestEvent->getRequest()->getRequestUri(), ExternalActionAttribute::class)
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
            $user = $this->tokenStorage->getToken()->getUser();
            if( $user instanceof User ){
                $realUser       = $this->userController->getOneByUsername($user->getUsername());
                $userController = $this->userController;
            }else{
                $realUser       = $this->apiUserController->getOneByUsername($user->getUsername());
                $userController = $this->apiUserController;
            }

            if( empty($realUser) ){
                throw new Exception("No real user has been found for username: {$user->getUsername()}");
            }

            $realUser->setLastActivity(new DateTime());
            $userController->save($realUser);
        }
    }

    /**
     * Will save incoming request data in DB
     *
     * @param Request $request
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function saveIncomingRequest(Request $request): void
    {
        $incomingRequest = new IncomingRequest();
        $incomingRequest->setIp($request->getClientIp());

        $this->incomingRequestController->save($incomingRequest);
    }

    /**
     * Will check if call was made from given country only - cannot apply IP access to network changes
     *
     * @param Request $request
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function validateCountryForIp(Request $request): bool
    {
        if( in_array($request->getClientIp(), IpInfoService::POSSIBLE_LOCALHOST_IPS) ){
            return true;
        }

        $this->saveIncomingRequest($request);

        try{
            $countryShortName = $this->services->getIpInfoService()->getCountryShortNameForIp($request->getClientIp());
            if( !in_array($countryShortName, self::ALLOWED_COUNTRIES_IP_ACCESS) ){
                $this->services->getLoggerService()->getLogger()->warning("Tried to access the project from blocked country", [
                    "allowedCountries" => self::ALLOWED_COUNTRIES_IP_ACCESS,
                    "countryShortName" => $countryShortName,
                    "ip"               => $request->getClientIp(),
                ]);
                return false;
            }
        }catch(Exception | TypeError $e){
                $this->services->getLoggerService()->logException($e, [
                    "info" => "Exception was thrown while trying to validate country for ip, probably the api could not resolve ip",
                    "ip"   => $request->getClientIp(),
                ]);
            return false;
        }

        return true;
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