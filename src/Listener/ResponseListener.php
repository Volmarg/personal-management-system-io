<?php


namespace App\Listener;


use App\Controller\Core\Services;
use App\Service\CookiesService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseListener implements EventSubscriberInterface
{

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * ResponseListener constructor.
     * @param Services $services
     */
    public function __construct(Services $services)
    {
        $this->services = $services;
    }

    /**
     * Handles the response event
     *
     * @param ResponseEvent $responseEvent
     */
    public function onResponse(ResponseEvent $responseEvent)
    {
        $this->removeEncryptionFileForMissingUserSessionCookie($responseEvent);
    }

    /**
     * Will remove the encryption key if user session id key is missing
     * This can happen if session expires and user refreshes the page
     *
     * @param ResponseEvent $responseEvent
     */
    private function removeEncryptionFileForMissingUserSessionCookie(ResponseEvent $responseEvent)
    {
        $request = $responseEvent->getRequest();
        if( !CookiesService::isUserSessionCookieSet($request) ){
            $this->services->getFilesService()->removeEncryptionFile();
        }
    }

    /**
     * @inheritDoc
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => "onResponse",
        ];
    }
}