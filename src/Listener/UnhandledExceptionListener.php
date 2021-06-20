<?php

namespace App\Listener;

use App\Attribute\Action\ExternalActionAttribute;
use App\Attribute\Action\InternalActionAttribute;
use App\Controller\Core\Services;
use App\DTO\BaseApiDTO;
use App\Exception\CouldNotUnsetEncryptionKeyException;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

/**
 * Handles catching and processing unhandled exceptions
 *
 * Class UnhandledExceptionListener
 * @package App\Listener
 */
class UnhandledExceptionListener implements EventSubscriberInterface
{

    /**
     * @var Services $services
     */
    private Services $services;

    public function __construct(Services $services)
    {
        $this->services = $services;
    }

    /**
     * Handle any types of previously unhandled exceptions
     *
     * @param ExceptionEvent $event
     * @throws ReflectionException
     * @throws Throwable
     */
    public function handleException(ExceptionEvent $event): void
    {

        if(
                $event->getThrowable() instanceof NotFoundHttpException         // ignore 404 exception
            ||  $event->getThrowable() instanceof ResourceNotFoundException     // mute favicon not found etc.
            ||  Response::HTTP_NOT_FOUND === $event->getThrowable()->getCode()
        ){
            return;
        }

        if( $event->getThrowable() instanceof CouldNotUnsetEncryptionKeyException ){
            $this->services->getLoggerService()->getLogger()->emergency("Could not remove the encryption key", [
                "exceptionMessage" => $event->getThrowable()->getMessage(),
                "exceptionCode"    => $event->getThrowable()->getCode(),
            ]);

            $event->setResponse($this->buildApiInternalServerErrorResponse());
            return;
        }

        $calledUri = $event->getRequest()->getRequestUri();
        $this->services->getLoggerService()->logException($event->getThrowable());

        if(
                $this->services->getAttributeReader()->hasUriAttribute($calledUri, InternalActionAttribute::class)
            ||  $this->services->getAttributeReader()->hasUriAttribute($calledUri, ExternalActionAttribute::class)
        ){
            $this->services->getLoggerService()->getLogger()->critical("Unhandled Exception was thrown", [
                "message" => $event->getThrowable()->getMessage(),
                "code"    => $event->getThrowable()->getCode(),
                "trace"   => $event->getThrowable()->getTrace(),
            ]);

            $event->setResponse($this->buildApiInternalServerErrorResponse());
        }else{

            $this->services->getLoggerService()->getLogger()->warning("Missing handler for exception", [
                "info" => "This exception could not be handled in the: " . __CLASS__ . " as no logic was prepared for it",
                "tip"  => "Did You forget to add the ActionAttribute maybe?",
            ]);
            throw $event->getThrowable();
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => "handleException"
        ];
    }

    /**
     * Will return the response returned in case of HTTP 500
     *
     * @return JsonResponse
     */
    private function buildApiInternalServerErrorResponse(): JsonResponse
    {
        $message      = $this->services->getTranslator()->trans("general.responseCodes.500");
        $baseResponse = BaseApiDTO::buildInternalServerErrorResponse();
        $baseResponse->setMessage($message);

        return $baseResponse->toJsonResponse();
    }

}