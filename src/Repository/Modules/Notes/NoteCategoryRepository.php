<?php

namespace App\Repository\Modules\Notes;

use App\Entity\Modules\Notes\NoteCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NoteCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteCategory[]    findAll()
 * @method NoteCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteCategoryRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, NoteCategory::class);
    }

    /**
     * Will find one note category for id or null if nothing is found
     *
     * @param int $id
     * @return NoteCategory|null
     */
    public function getOneForId(int $id): ?NoteCategory
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
            ->from(NoteCategory::class, "mnc")
            ->join(NoteCategory::class, "mnc_child", Join::WITH, "mnc_child.parentId = mnc.id")
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
     * @return NoteCategory[]
     */
    public function getChildrenCategoriesForCategoriesIds(array $categoriesIds): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select("mnc_child")
            ->from(NoteCategory::class, "mnc")
            ->join(NoteCategory::class, "mnc_child", Join::WITH, "mnc_child.parentId = mnc.id")
            ->where("mnc.id IN (:categoriesIds)")
            ->setParameter("categoriesIds", $categoriesIds);

        $query   = $queryBuilder->getQuery();
        $results = $query->execute();

        return $results;
    }

    /**
     * Will save the new entity or update the state of already existing one
     *
     * The data is being saved with plain sql due to the Issue / Bug in the spec sharper library
     * where with relation the related entity is decrypted with local key and then re-encrypted with it
     * this cause problems when the dynamic key is being used in login form
     *
     * @param NoteCategory $myNoteCategory
     * @throws Exception
     */
    public function createEntity(NoteCategory $myNoteCategory): void
    {
        $connection = $this->_em->getConnection();

        $sql = "
            INSERT INTO note_category(id, icon, name, color, parent_id)
            VALUES(:id, :icon, :name, :color, :parentId)
        ";

        $params = [
            "id"       => $myNoteCategory->getId(),
            "icon"     => $myNoteCategory->getIcon(),
            "name"     => $myNoteCategory->getName(),
            "color"    => $myNoteCategory->getColor(),
            "parentId" => $myNoteCategory->getParentId(),
        ];

        $connection->executeQuery($sql, $params);
    }

    /**
     * Will remove all entries from DB
     * @throws Exception
     */
    public function removeAll(): void
    {
        $connection = $this->_em->getConnection();

        $sql = "
            DELETE FROM `note_category`
        ";

        $connection->executeQuery($sql);
    }

}
