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
     * Will save the new entity or update the state of already existing one
     *
     * @param Note $myNote
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Note $myNote): void
    {
        $this->_em->persist($myNote);
        $this->_em->flush();
    }

    /**
     * Will returns notes for which given string is present in the title
     *
     * @param string $searchedString - is automatically wrapped in LIKE % syntax
     * @return Note[]
     */
    public function getNotesContainingStringInTitle(string $searchedString): array
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select("n")
            ->from(Note::class, "n")
            ->where("LOWER(n.title) LIKE LOWER(:searchedString)")
            ->setParameter("searchedString", "%" . $searchedString . "%");

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
