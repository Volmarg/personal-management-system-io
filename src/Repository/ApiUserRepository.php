<?php

namespace App\Repository;

use App\Entity\ApiUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 * @method ApiUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiUser[]    findAll()
 * @method ApiUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiUser::class);
    }

    /**
     * Will return ApiUser by given ApiUsername
     *
     * @param string $ApiUsername
     * @return ApiUser|null
     */
    public function getByApiUsername(string $ApiUsername): ?ApiUser
    {
        $apiUser = $this->findOneBy([
            ApiUser::FIELD_NAME_USERNAME => $ApiUsername,
        ]);

        return $apiUser;
    }

    /**
     * Will either create new record in db or update existing one
     *
     * @param UserInterface $apiUser
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserInterface $apiUser): void
    {
        $this->_em->persist($apiUser);
        $this->_em->flush();
    }


}
