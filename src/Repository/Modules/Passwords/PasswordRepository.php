<?php

namespace App\Repository\Modules\Passwords;

use App\Entity\Modules\Passwords\Password;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @param Password $password
     * @throws ORMException
     */
    public function save(Password $password): void
    {
        $this->_em->persist($password);;
        $this->_em->flush();
    }

    /**
     * Will return passwords which description or url consist given string
     *
     * @param string $searchedString - is automatically wrapped in LIKE % syntax
     * @return Password[]
     */
    public function getPasswordByDescriptionOrUrlContainingUrl(string $searchedString): array
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select("pswd")
            ->from(Password::class, "pswd")
            ->where("pswd.description LIKE :searchedStringLike")
            ->orWhere("LOWER(pswd.url) LIKE LOWER(:searchedStringLike)")
            ->setParameter("searchedStringLike", "%" . $searchedString . "%");

        $results = $qb->getQuery()->getResult();
        return $results;
    }

}
