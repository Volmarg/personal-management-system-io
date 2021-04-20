<?php

namespace App\Repository\System;

use App\Entity\System\PmsConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @see PmsConfig for more information
 *
 * @method PmsConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method PmsConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method PmsConfig[]    findAll()
 * @method PmsConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PmsConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PmsConfig::class);
    }

    /**
     * Will return configuration of given name, or null if nothing is found for key
     *
     * @param string $configName
     * @return PmsConfig|null
     */
    public function getConfigurationForConfigName(string $configName): ?PmsConfig
    {
        return $this->findOneBy([
            PmsConfig::FIELD_NAME => $configName,
        ]);
    }

}
