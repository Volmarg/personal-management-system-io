<?php

namespace App\Listener\Security;

use App\Controller\Core\ConfigLoader;
use App\Service\SessionService;
use SpecShaper\EncryptBundle\Event\EncryptKeyEvent;
use SpecShaper\EncryptBundle\Event\EncryptKeyEvents;
use SpecShaper\EncryptBundle\SpecShaperEncryptBundle;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles the @see SpecShaperEncryptBundle encryption key
 *
 * Class BeforeCreateEncryptionServiceListener
 * @package App\Listener\Security
 */
class BeforeCreateEncryptionServiceListener implements EventSubscriberInterface
{

    /**
     * Will set the proper encryption key on each call - as long as the encryption key file exist
     *
     * @param EncryptKeyEvent $event
     */
    public function onEncryptionLoadKey(EncryptKeyEvent $event): void
    {
        $encryptionKey = null;
        if( SessionService::isValidEncryptionKeyInSession() ){
            $encryptionKey = SessionService::getEncryptionKeyInSession();
        }

        if( !is_null($encryptionKey) ){
            $event->setKey($encryptionKey);
        }

    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EncryptKeyEvents::LOAD_KEY => "onEncryptionLoadKey",
        ];
    }
}