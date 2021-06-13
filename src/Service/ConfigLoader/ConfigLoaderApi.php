<?php

namespace App\Service\ConfigLoader;

/**
 * Contains configuration related to the API
 *
 * Class ConfigLoaderApi
 * @package App\Service\ConfigLoader
 */
class ConfigLoaderApi
{

    /**
     * @var string $ipInfoAccessToken
     */
    private string $ipInfoAccessToken;

    /**
     * @return string
     */
    public function getIpInfoAccessToken(): string
    {
        return $this->ipInfoAccessToken;
    }

    /**
     * @param string $ipInfoAccessToken
     */
    public function setIpInfoAccessToken(string $ipInfoAccessToken): void
    {
        $this->ipInfoAccessToken = $ipInfoAccessToken;
    }

}