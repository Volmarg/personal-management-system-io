<?php

namespace App\Repository\System;

use App\Entity\System\IncomingRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @see IncomingRequest for more information
 *
 * @method IncomingRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncomingRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncomingRequest[]    findAll()
 * @method IncomingRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncomingRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IncomingRequest::class);
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
        $this->_em->persist($incomingRequest);
        $this->_em->flush();
    }

    /**
     * Will return count of request done for current Year and Month
     *
     * @return int
     * @throws Exception
     */
    public function getCountOfRequestInCurrentYearAndMonth(): int
    {
        $currYearAndMonthString = (new \DateTime())->format("Y-m");
        $connection             = $this->_em->getConnection();

        $sql = "
            SELECT COUNT(*)
            FROM incoming_request
            WHERE DATE_FORMAT(`created`, '%Y-%m') = :currYearAndMonthString
        ";

        $params = [
            'currYearAndMonthString' => $currYearAndMonthString,
        ];

        $countOfRequests = $connection->executeQuery($sql, $params)->fetchColumn();
        if( empty($countOfRequests) ){
            return 0;
        }

        return $countOfRequests;
    }

}
