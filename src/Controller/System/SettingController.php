<?php


namespace App\Controller\System;


use App\Repository\System\SettingRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class SettingController
 * @package App\Controller\System
 */
class SettingController extends AbstractController
{
    /**
     * @var SettingRepository $settingRepository
     */
    private SettingRepository $settingRepository;

    /**
     * SettingController constructor.
     *
     * @param SettingRepository $settingRepository
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Will allow inserting data to the tables
     *
     * @throws Exception
     */
    public function allowDataInsertion()
    {
        $this->settingRepository->allowDataInsertion();
    }

    /**
     * Will deny inserting data to the tables
     *
     * @throws Exception
     */
    public function denyDataInsertion(): void
    {
        $this->settingRepository->denyDataInsertion();
    }

    /**
     * Will check if the data insertion in PMS-IO is allowed or not
     *
     * @return bool
     * @throws Exception
     */
    public function isAllowedToInsertData(): bool
    {
        return $this->settingRepository->isAllowedToInsertData();
    }
}