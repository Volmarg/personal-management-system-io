<?php

namespace App\Repository\Modules\Notes;

use App\Entity\Modules\Notes\MyNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MyNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyNote[]    findAll()
 * @method MyNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, MyNote::class);
    }

    /**
     * Will save the new entity or update the state of already existing one
     *
     * @param MyNote $myNote
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(MyNote $myNote): void
    {
        $this->_em->persist($myNote);
        $this->_em->flush();
    }

}
