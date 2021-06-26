<?php

namespace App\Repository\System;

use App\Entity\System\SystemState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SystemState|null find($id, $lockMode = null, $lockVersion = null)
 * @method SystemState|null findOneBy(array $criteria, array $orderBy = null)
 * @method SystemState[]    findAll()
 * @method SystemState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SystemStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SystemState::class);
    }

    /**
     * Will set positive state (false) for name
     *
     * @throws Exception
     */
    public function setPositiveStateForName(string $stateName): void
    {
        $this->createOrUpdateState(1, $stateName);
    }

    /**
     * Will set negative state (false) for name
     *
     * @throws Exception
     */
    public function setNegativeStateForName(string $stateName): void
    {
        $this->createOrUpdateState(0, $stateName);
    }

    /**
     * Will check if given state has positive value (true) in DB.
     *
     * @param string $stateName
     * @return bool
     * @throws Exception
     */
    public function isPositiveState(string $stateName): bool
    {
        $settingValue = $this->getStateValueByName($stateName);
        if( is_null($settingValue) ){
            return false;
        }

        return $settingValue;
    }

    /**
     * Will create or update the state in database
     *
     * @param int $valueToSet
     * @param string $stateName
     * @throws Exception
     */
    private function createOrUpdateState(int $valueToSet, string $stateName): void
    {
        $connection = $this->_em->getConnection();

        $params = [
            "valueToSet" => $valueToSet,
            "name"       => $stateName,
        ];

        if( $this->isStateInDatabase($stateName) ){
            $sql = "
                UPDATE system_state
                SET `value`  = :valueToSet
                WHERE `name` = :name
            ";
        }else{
            $sql = "
                INSERT INTO system_state (`name`, `value`)
                VALUES (:name, :valueToSet)            
            ";
        }

        $connection->executeQuery($sql, $params);
    }

    /**
     * Will check if the state with given name is in database
     *
     * @param string $stateName
     * @return bool
     * @throws Exception
     */
    private function isStateInDatabase(string $stateName): bool
    {
        $settingValue = $this->getStateValueByName($stateName);
        if( is_null($settingValue) ){
            return false;
        }

        return true;
    }

    /**
     * Will return value of state by its name or null if no such state name was found
     *
     * @param string $stateName
     * @return bool|null
     * @throws Exception
     */
    private function getStateValueByName(string $stateName): ?bool
    {
        $connection = $this->_em->getConnection();

        $sql = "
            SELECT `value`
            FROM system_state
            WHERE `name` = :name
        ";

        $params = [
            "name" => $stateName,
        ];

        $result = $connection->executeQuery($sql, $params)->fetchColumn();
        if(
                !$result
            &&  0 != $result
        ){
            return null;
        }

        return $result;
    }
}
