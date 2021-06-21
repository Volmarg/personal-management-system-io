<?php

namespace App\Repository\System;

use App\Entity\System\Setting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    /**
     * Will allow inserting data to the tables
     *
     * @throws Exception
     */
    public function allowDataInsertion()
    {
        $this->createOrUpdateDataInsertionSetting(true);
    }

    /**
     * Will deny inserting data to the tables
     *
     * @throws Exception
     */
    public function denyDataInsertion(): void
    {
        $this->createOrUpdateDataInsertionSetting(false);
    }

    /**
     * Will check if the data insertion in PMS-IO is allowed or not
     *
     * @return bool
     * @throws Exception
     */
    public function isAllowedToInsertData(): bool
    {
        $settingValue = $this->getSettingAllowInsertData();
        if( empty($settingValue) ){
            return false;
        }

        if( !is_numeric($settingValue) ){
            return false;
        }

        return ($settingValue == 1);
    }

    /**
     * Will create or update the setting @see Setting::SETTING_NAME_ALLOW_INSERT_DATA
     *
     * @param bool $valueToSet
     * @throws Exception
     */
    private function createOrUpdateDataInsertionSetting(bool $valueToSet): void
    {
        $connection = $this->_em->getConnection();

        $params = [
            "valueToSet" => $valueToSet,
        ];

        if( empty($this->getSettingAllowInsertData()) ){
            $sql = "
                UPDATE setting
                SET `value` = :valueToSet
            ";
        }else{
            $sql = "
                INSERT INTO setting (`name`, `value`)
                VALUES (:name, :valueToSet)            
            ";

            $params["name"] = Setting::SETTING_NAME_ALLOW_INSERT_DATA;
        }

        $connection->executeQuery($sql, $params);
    }

    /**
     * Will return the setting @see Setting::SETTING_NAME_ALLOW_INSERT_DATA
     *
     * @return int | null
     * @throws Exception
     */
    private function getSettingAllowInsertData(): ?int
    {
        $connection = $this->_em->getConnection();

        $sql = "
            SELECT `value`
            FROM setting
            WHERE name = :name
        ";

        $params = [
            "name" => Setting::SETTING_NAME_ALLOW_INSERT_DATA,
        ];

        $result = $connection->executeQuery($sql, $params)->fetchColumn();

        if( empty($result) ){
            return null;
        }

        return (int) $result;
    }

}
