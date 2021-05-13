<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Will return user by given username
     *
     * @param string $username
     * @return User|null
     */
    public function getByUsername(string $username): ?User
    {
        $user = $this->findOneBy([
            User::FIELD_NAME_USERNAME => $username,
        ]);

        return $user;
    }

    /**
     * Will either create new record in db or update existing one
     *
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Will return all users
     *
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return $this->findAll();
    }
}
