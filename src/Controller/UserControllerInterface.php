<?php

namespace App\Controller;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserControllerInterface
{

    /**
     * Will either create new record in db or update existing one
     *
     * @param UserInterface $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserInterface $user): void;
}