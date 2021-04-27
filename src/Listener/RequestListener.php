<?php


namespace App\Listener;


use App\Attribute\Action\ExternalActionAttribute;
use App\Controller\API\ApiController;
use App\Controller\Core\Services;
use App\DTO\BaseApiDTO;
use App\Service\Attribute\AttributeReaderService;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
        if( !$isJsonValid){
            $this->services->getLoggerService()->getLogger()->warning("Provided json in request is not valid");
            $jsonResponse = BaseApiDTO::buildInvalidJsonResponse()->toJsonResponse();
            $requestEvent->setResponse($jsonResponse);
            $requestEvent->stopPropagation();

            return;
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