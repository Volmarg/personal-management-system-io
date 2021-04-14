<?php


namespace App\Service\Database;


use Doctrine\ORM\EntityManagerInterface;

class DatabaseService
{

    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Will start (activate) new transaction
     */
    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    /**
     * Will commit active transaction
     */
    public function commitTransaction(): void
    {
        $this->entityManager->commit();
    }

    /**
     * Will rollback the active transaction
     */
    public function rollbackTransaction(): void
    {
        $this->entityManager->rollback();
    }

}