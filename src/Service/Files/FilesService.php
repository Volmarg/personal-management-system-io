<?php


namespace App\Service\Files;


use App\Controller\Core\ConfigLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilesService extends AbstractController
{
    /**
     * @var ConfigLoader $configLoader
     */
    private ConfigLoader $configLoader;

    public function __construct(ConfigLoader $configLoader)
    {
        $this->configLoader = $configLoader;
    }

    /**
     * Will remove the encryption file
     */
    public function removeEncryptionFile(): bool
    {
        if( file_exists($this->configLoader->getConfigLoaderPaths()->getEncryptionFilePath()) ){
            return unlink($this->configLoader->getConfigLoaderPaths()->getEncryptionFilePath());
        }

        // doesn't exist anyway so it's fine
        return true;
    }

    // todo: add some logic to either store user uploaded file with encryption key OR get from input
}