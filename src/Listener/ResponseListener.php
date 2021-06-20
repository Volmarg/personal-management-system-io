<?php


namespace App\Listener;


use App\Controller\Core\Services;
use App\Exception\CouldNotUnsetEncryptionKeyException;
use App\Service\CookiesService;
use App\Service\SessionService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseListener implements EventSubscriberInterface
{

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
     * @throws CouldNotUnsetEncryptionKeyException
     */
    private function removeEncryptionFileForMissingUserSessionCookie(ResponseEvent $responseEvent)
    {
        $request = $responseEvent->getRequest();
        if( !CookiesService::isUserSessionCookieSet($request) ){
            SessionService::removeEncryptionKeyFromSession();
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