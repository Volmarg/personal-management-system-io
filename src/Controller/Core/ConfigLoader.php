<?php


namespace App\Controller\Core;


use App\Service\ConfigLoader\ConfigLoaderPaths;

class ConfigLoader
{

    /**
     * @var ConfigLoaderPaths $configLoaderPaths
     */
    private ConfigLoaderPaths $configLoaderPaths;

    /**
     * @return ConfigLoaderPaths
     */
    public function getConfigLoaderPaths(): ConfigLoaderPaths
    {
        return $this->configLoaderPaths;
    }

    /**
     * @param ConfigLoaderPaths $configLoaderPaths
     */
    public function setConfigLoaderPaths(ConfigLoaderPaths $configLoaderPaths): void
    {
        $this->configLoaderPaths = $configLoaderPaths;
    }

}