<?php

namespace App\Listener;

use App\Attribute\Action\ExternalActionAttribute;
use App\Attribute\Action\InternalActionAttribute;
use App\Controller\Core\Services;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
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
        // ignore 404 exception
        if(
                $event->getThrowable() instanceof NotFoundHttpException
            ||  Response::HTTP_NOT_FOUND === $event->getThrowable()->getCode()
        ){
            return;
        }

        $calledUri = $event->getRequest()->getRequestUri();
        $this->services->getLoggerService()->logException($event->getThrowable());

        if( $this->services->getAttributeReader()->hasUriAttribute($calledUri, InternalActionAttribute::class) ){

            $this->handleInternalCallException($event);
        }elseif( $this->services->getAttributeReader()->hasUriAttribute($calledUri, ExternalActionAttribute::class) ){

            $this->handleExternalCallException($event);
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
     * Will handle exception thrown when calling internal route
     * @param ExceptionEvent $event
     */
    private function handleInternalCallException(ExceptionEvent $event): void
    {
        // todo: special handling of api response
    }

    /**
     * Will handle exception for external call
     * @param ExceptionEvent $event
     */
    private function handleExternalCallException(ExceptionEvent $event): void
    {
        // todo: special handling of api response
    }
}