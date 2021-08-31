<?php

namespace App\Repository\Modules\Passwords;

use App\Entity\Modules\Passwords\PasswordGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\ORMException;
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
     * @param string $id
     * @return PasswordGroup|null
     */
    public function getOneForId(string $id): ?PasswordGroup
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

    /**
     * Will create new entity
     *
     * The data is being saved with plain sql due to the Issue / Bug in the spec sharper library
     * where with relation the related entity is decrypted with local key and then re-encrypted with it
     * this cause problems when the dynamic key is being used in login form
     *
     * @param PasswordGroup $passwordGroup
     * @throws Exception
     */
    public function createEntity(PasswordGroup $passwordGroup): void
    {
        $connection = $this->_em->getConnection();

        $sql = "
            INSERT INTO password_group(id, name)
            VALUES(:id, :name)
        ";

        $params = [
            "id"   => $passwordGroup->getId(),
            "name" => $passwordGroup->getName(),
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
            DELETE FROM `password_group`
        ";

        $connection->executeQuery($sql);
    }

}
