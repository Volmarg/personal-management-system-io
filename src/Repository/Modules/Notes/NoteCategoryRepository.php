<?php

namespace App\Repository\Modules\Notes;

use App\Entity\Modules\Notes\MyNoteCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MyNoteCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyNoteCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyNoteCategory[]    findAll()
 * @method MyNoteCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteCategoryRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, MyNoteCategory::class);
    }

    /**
     * Will find one note category for id or null if nothing is found
     *
     * @param int $id
     * @return MyNoteCategory|null
     */
    public function getOneForId(int $id): ?MyNoteCategory
    {
        return $this->find($id);
    }

    /**
     * Will return array of children ids for given categories ids
     *
     * @param array $categoriesIds
     * @return string[]
     */
    public function getChildrenCategoriesIdsForCategoriesIds(array $categoriesIds): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select("mnc_child.id")
            ->from(MyNoteCategory::class, "mnc")
            ->join(MyNoteCategory::class, "mnc_child", Join::WITH, "mnc_child.parentId = mnc.id")
            ->where("mnc.id IN (:categoriesIds)")
            ->setParameter("categoriesIds", $categoriesIds);

        $query   = $queryBuilder->getQuery();
        $results = $query->execute();
        $ids     = array_column($results, 'id');

        return $ids;
    }

    /**
     * Returns children (sub-)categories for categories ids
     *
     * @param array $categoriesIds
     * @return MyNoteCategory[]
     */
    public function getChildrenCategoriesForCategoriesIds(array $categoriesIds): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select("mnc_child")
            ->from(MyNoteCategory::class, "mnc")
            ->join(MyNoteCategory::class, "mnc_child", Join::WITH, "mnc_child.parentId = mnc.id")
            ->where("mnc.id IN (:categoriesIds)")
            ->setParameter("categoriesIds", $categoriesIds);

        $query   = $queryBuilder->getQuery();
        $results = $query->execute();

        return $results;
    }

    /**
     * Will save the new entity or update the state of already existing one
     *
     * @param MyNoteCategory $myNoteCategory
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(MyNoteCategory $myNoteCategory): void
    {
        $this->_em->persist($myNoteCategory);
        $this->_em->flush();
    }

    /**
     * Will remove all entries from DB
     * @throws Exception
     */
    public function removeAll(): void
    {
        $connection = $this->_em->getConnection();

        $sql = "
            DELETE FROM `my_note_category`
        ";

        $connection->executeQuery($sql);
    }

}
