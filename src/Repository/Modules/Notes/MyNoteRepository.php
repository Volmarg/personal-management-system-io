<?php

namespace App\Repository\Modules\Notes;

use App\Entity\Modules\Notes\MyNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MyNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyNote[]    findAll()
 * @method MyNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyNoteRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, MyNote::class);
    }

}
