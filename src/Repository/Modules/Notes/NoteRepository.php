<?php

namespace App\Repository\Modules\Notes;

use App\Entity\Modules\Notes\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Note::class);
    }

    /**
     * Will save the new entity to db
     * The data is being saved with plain sql due to the Issue / Bug in the spec sharper library
     * where with relation the related entity is decrypted with local key and then re-encrypted with it
     * this cause problems when the dynamic key is being used in login form
     *
     * @param Note $myNote
     * @throws Exception
     */
    public function createEntity(Note $myNote): void
    {
        $connection = $this->_em->getConnection();

        $sql = "
            INSERT INTO note(`category_id`, `title`, `body`)
            VALUES (:categoryId, :title, :body)
        ";

        $params = [
            "categoryId" => $myNote->getCategory()->getId(),
            "title"      => $myNote->getTitle(),
            "body"       => $myNote->getBody(),
        ];

        $connection->executeQuery($sql, $params);
    }

    /**
     * Will returns all notes
     *
     * @return Note[]
     */
    public function getAll(): array
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select("n")
            ->from(Note::class, "n");

        $results = $qb->getQuery()->getResult();
        return $results;
    }

    /**
     * Will remove all entries from DB
     * @throws Exception
     */
    public function removeAll(): void
    {
        $connection = $this->_em->getConnection();

        $sql = "
            DELETE FROM `note`
        ";

        $connection->executeQuery($sql);
    }

}
