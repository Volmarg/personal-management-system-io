<?php

namespace App\Repository\Modules\Passwords;

use App\Entity\Modules\Passwords\PasswordGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PasswordGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordGroup[]    findAll()
 * @method PasswordGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordGroupRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, PasswordGroup::class);
    }

    /**
     * Will find one password group for id or null if nothing is found
     *
     * @param int $id
     * @return PasswordGroup|null
     */
    public function getOneForId(int $id): ?PasswordGroup
    {
        return $this->find($id);
    }

    /**
     * Returns all groups
     *
     * @return PasswordGroup[]
     */
    public function getAllGroups(): array
    {
        return $this->findAll();
    }

}
