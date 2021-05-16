<?php

namespace App\Listener\Security;

use App\Controller\Core\ConfigLoader;
use SpecShaper\EncryptBundle\Event\BeforeCreateServiceEvent;
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
     * @var ConfigLoader $configLoader
     */
    private ConfigLoader $configLoader;

    public function __construct(ConfigLoader $configLoader){
        $this->configLoader = $configLoader;
    }

    /**
     * Will set the proper encryption key on each call - as long as the encryption key file exist
     *
     * @param BeforeCreateServiceEvent $event
     */
    public function onBeforeCreateService(BeforeCreateServiceEvent $event): void
    {
        $encryptionKey = null;
        if( file_exists($this->configLoader->getConfigLoaderPaths()->getEncryptionFilePath()) ){
            $encryptionKey = file_get_contents($this->configLoader->getConfigLoaderPaths()->getEncryptionFilePath());
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
            BeforeCreateServiceEvent::NAME => "onBeforeCreateService",
        ];
    }
}