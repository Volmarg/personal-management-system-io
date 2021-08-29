<?php


namespace App\Controller\System;


use App\Entity\System\SystemState;
use App\Repository\System\SystemStateRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class SettingController
 * @package App\Controller\System
 */
class SystemStateController extends AbstractController
{
    /**
     * @var SystemStateRepository $systemStateRepository
     */
    private SystemStateRepository $systemStateRepository;

    /**
     * SettingController constructor.
     *
     * @param SystemStateRepository $systemStateRepository
     */
    public function __construct(SystemStateRepository $systemStateRepository)
    {
        $this->systemStateRepository = $systemStateRepository;
    }

    /**
     * Will allow inserting data to the tables
     *
     * @throws Exception
     */
    public function allowDataInsertion()
    {
        $this->systemStateRepository->setPositiveStateForName(SystemState::STATE_NAME_ALLOW_INSERT_DATA);
    }

    /**
     * Will deny inserting data to the tables
     *
     * @throws Exception
     */
    public function denyDataInsertion(): void
    {
        $this->systemStateRepository->setNegativeStateForName(SystemState::STATE_NAME_ALLOW_INSERT_DATA);
    }

    /**
     * Will check if the data insertion in PMS-IO is allowed or not
     *
     * @return bool
     * @throws Exception
     */
    public function isAllowedToInsertData(): bool
    {
        return $this->systemStateRepository->isPositiveStateOrExistsInDatabase(SystemState::STATE_NAME_ALLOW_INSERT_DATA);
    }

    /**
     * Will mark that data has NOT been transferred
     *
     * @throws Exception
     */
    public function setDataIsNotTransferred()
    {
        $this->systemStateRepository->setNegativeStateForName(SystemState::STATE_NAME_IS_DATA_TRANSFERRED);
    }

    // todo setting and clearing `transferred`

    /**
     * Will mark that data has been transferred
     *
     * @throws Exception
     */
    public function setDataIsTransferred(): void
    {
        $this->systemStateRepository->setPositiveStateForName(SystemState::STATE_NAME_IS_DATA_TRANSFERRED);
    }

    /**
     * Will check if the data has been transferred
     *
     * @return bool
     * @throws Exception
     */
    public function isDataTransferred(): bool
    {
        return $this->systemStateRepository->isPositiveStateOrExistsInDatabase(SystemState::STATE_NAME_IS_DATA_TRANSFERRED);
    }

}