<?php


namespace App\Controller\Core;


use App\Service\ConfigLoader\ConfigLoaderApi;
use App\Service\ConfigLoader\ConfigLoaderPaths;
use App\Service\ConfigLoader\ConfigLoaderSystemData;

class ConfigLoader
{

    /**
     * @var ConfigLoaderPaths $configLoaderPaths
     */
    private ConfigLoaderPaths $configLoaderPaths;

    /**
     * @var ConfigLoaderSystemData $configLoaderSystemData
     */
    private ConfigLoaderSystemData $configLoaderSystemData;

    /**
     * @var ConfigLoaderApi $configLoaderApi
     */
    private ConfigLoaderApi $configLoaderApi;

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

    /**
     * @return ConfigLoaderSystemData
     */
    public function getConfigLoaderSystemData(): ConfigLoaderSystemData
    {
        return $this->configLoaderSystemData;
    }

    /**
     * @param ConfigLoaderSystemData $configLoaderSystemData
     */
    public function setConfigLoaderSystemData(ConfigLoaderSystemData $configLoaderSystemData): void
    {
        $this->configLoaderSystemData = $configLoaderSystemData;
    }

    /**
     * @return ConfigLoaderApi
     */
    public function getConfigLoaderApi(): ConfigLoaderApi
    {
        return $this->configLoaderApi;
    }

    /**
     * @param ConfigLoaderApi $configLoaderApi
     */
    public function setConfigLoaderApi(ConfigLoaderApi $configLoaderApi): void
    {
        $this->configLoaderApi = $configLoaderApi;
    }

}