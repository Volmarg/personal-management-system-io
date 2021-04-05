<?php

namespace App\Repository\Modules\Notes;

use App\Entity\Modules\Notes\MyNoteCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MyNoteCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyNoteCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyNoteCategory[]    findAll()
 * @method MyNoteCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyNoteCategoryRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, MyNoteCategory::class);
    }


}
