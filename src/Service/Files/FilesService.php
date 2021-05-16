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

    /**
     * Will set the provided string as content of encryption file
     *
     * @param string $content
     * @return bool
     */
    public function setEncryptionFileContent(string $content): bool
    {
        $result = file_put_contents($this->configLoader->getConfigLoaderPaths()->getEncryptionFilePath(), $content);
        return (bool)$result;
    }
}