<?php

namespace App\Repository\Modules\Passwords;

use App\Entity\Modules\Passwords\Password;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Password|null find($id, $lockMode = null, $lockVersion = null)
 * @method Password|null findOneBy(array $criteria, array $orderBy = null)
 * @method Password[]    findAll()
 * @method Password[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Password::class);
    }

    /**
     * Will save the new entity or update the state of already existing one
     *
     * @param Password $Password
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Password $Password): void
    {
        $this->_em->persist($Password);
        $this->_em->flush();
    }

}
