<?php


namespace App\Service\ConfigLoader;

/**
 * Contains configuration regarding anything related to paths like files locations / folders etc.
 *
 * Class ConfigLoaderPaths
 * @package App\Service\ConfigLoader
 */
class ConfigLoaderPaths
{

    /**
     * @var string $translationBackendFolder
     */
    private string $translationBackendFolder = "";

    /**
     * @var string $translationFrontendOutputFilePath
     */
    private string $translationFrontendOutputFilePath = "";
    /**
     * @var string $routingFrontendFilePath
     */
    private string $routingFrontendFilePath = "";

    /**
     * @var string $encryptionFilePath
     */
    private string $encryptionFilePath = "";

    /**
     * @return string
     */
    public function getTranslationBackendFolder(): string
    {
        return $this->translationBackendFolder;
    }

    /**
     * @param string $translationBackendFolder
     */
    public function setTranslationBackendFolder(string $translationBackendFolder): void
    {
        $this->translationBackendFolder = $translationBackendFolder;
    }

    /**
     * @return string
     */
    public function getTranslationFrontendOutputFilePath(): string
    {
        return $this->translationFrontendOutputFilePath;
    }

    /**
     * @param string $translationFrontendOutputFilePath
     */
    public function setTranslationFrontendOutputFilePath(string $translationFrontendOutputFilePath): void
    {
        $this->translationFrontendOutputFilePath = $translationFrontendOutputFilePath;
    }

    /**
     * @return string
     */
    public function getRoutingFrontendFilePath(): string
    {
        return $this->routingFrontendFilePath;
    }

    /**
     * @param string $routingFrontendFilePath
     */
    public function setRoutingFrontendFilePath(string $routingFrontendFilePath): void
    {
        $this->routingFrontendFilePath = $routingFrontendFilePath;
    }

    /**
     * @return string
     */
    public function getEncryptionFilePath(): string
    {
        return $this->encryptionFilePath;
    }

    /**
     * @param string $encryptionFilePath
     */
    public function setEncryptionFilePath(string $encryptionFilePath): void
    {
        $this->encryptionFilePath = $encryptionFilePath;
    }

}