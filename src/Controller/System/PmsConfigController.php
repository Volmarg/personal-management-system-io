<?php


namespace App\Controller\System;

use App\Controller\Core\Services;
use App\Entity\System\PmsConfig;
use App\Repository\System\PmsConfigRepository;
use Exception;

/**
 * @see PmsConfig for more information
 *
 * Class PmsConfigController
 * @package App\Controller\System
 */
class PmsConfigController
{
    /**
     * @var PmsConfigRepository $pmsConfigRepository
     */
    private PmsConfigRepository $pmsConfigRepository;

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * PmsConfigController constructor.
     *
     * @param PmsConfigRepository $pmsConfigRepository
     * @param Services $services
     */
    public function __construct(PmsConfigRepository $pmsConfigRepository, Services $services)
    {
        $this->pmsConfigRepository = $pmsConfigRepository;
        $this->services            = $services;
    }

    /**
     * Will return the encryption key delivered from PMS
     *
     * @throws Exception
     */
    public function getEncryptionKey(): PmsConfig
    {
        $pmsConfig = $this->pmsConfigRepository->getConfigurationForConfigName(PmsConfig::CONFIG_NAME_ENCRYPTION_KEY);
        if(
                empty($pmsConfig)
            ||  empty($pmsConfig->getValue())
        ){
            $message = "Encryption key was not found or the configuration value is empty";

            $this->services->getLoggerService()->getLogger()->emergency($message, [
                "configKey" => PmsConfig::CONFIG_NAME_ENCRYPTION_KEY,
            ]);

            throw new Exception($message);
        }

        return $pmsConfig;
    }

}