<?php


namespace App\Listener;


use App\Attribute\Action\ExternalActionAttribute;
use App\Attribute\Security\ValidateCsrfTokenAttribute;
use App\Controller\API\ApiController;
use App\Controller\Core\Services;
use App\DTO\BaseApiDTO;
use App\Service\Attribute\AttributeReaderService;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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
     * RequestListener constructor.
     *
     * @param AttributeReaderService $attributeReaderService
     * @param ApiController $apiController
     * @param Services $services
     */
    public function __construct(AttributeReaderService $attributeReaderService, ApiController $apiController, Services $services)
    {
        $this->services               = $services;
        $this->apiController          = $apiController;
        $this->attributeReaderService = $attributeReaderService;
    }

    /**
     * Handle logic upon request
     *
     * @throws ReflectionException
     */
    public function onRequest(RequestEvent $requestEvent): void
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