<?php


namespace App\Controller\System;

use App\Entity\System\IncomingRequest;
use App\Entity\System\PmsConfig;
use App\Repository\System\IncomingRequestRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @see PmsConfig for more information
 *
 * Class PmsConfigController
 * @package App\Controller\System
 */
class IncomingRequestController
{
    /**
     * @var IncomingRequestRepository $incomingRequestRepository
     */
    private IncomingRequestRepository $incomingRequestRepository;

    /**
     * IncomingRequestController constructor.
     * @param IncomingRequestRepository $incomingRequestRepository
     */
    public function __construct(IncomingRequestRepository $incomingRequestRepository)
    {
        $this->incomingRequestRepository = $incomingRequestRepository;
    }

    /**
     * Will return count of request done for current Year and Month
     *
     * @return int
     * @throws Exception
     */
    public function getCountOfRequestInCurrentYearAndMonth(): int
    {
        return $this->incomingRequestRepository->getCountOfRequestInCurrentYearAndMonth();
    }

    /**
     * Will save or update the request entity
     *
     * @param IncomingRequest $incomingRequest
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(IncomingRequest $incomingRequest)
    {
        $this->incomingRequestRepository->save($incomingRequest);
    }

}