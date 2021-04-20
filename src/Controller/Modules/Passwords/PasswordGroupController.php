<?php


namespace App\Controller\Modules\Passwords;


use App\Entity\Modules\Passwords\PasswordGroup;
use App\Repository\Modules\Passwords\PasswordGroupRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PasswordGroupController extends AbstractController
{

    /**
     * @var PasswordGroupRepository $passwordGroupRepository
     */
    private PasswordGroupRepository $passwordGroupRepository;

    public function __construct(PasswordGroupRepository $passwordGroupRepository)
    {
        $this->passwordGroupRepository = $passwordGroupRepository;
    }

    /**
     * Returns all groups
     *
     * @return PasswordGroup[]
     */
    public function getAllGroups(): array
    {
        return $this->passwordGroupRepository->getAllGroups();
    }

    /**
     * Will return one password group for given id or null if none is found
     *
     * @param string $id
     * @return PasswordGroup|null
     */
    public function getOneForId(string $id): ?PasswordGroup
    {
        return $this->passwordGroupRepository->getOneForId($id);
    }

    /**
     * Will save the new entity or update the state of already existing one
     *
     * @param PasswordGroup $passwordGroup
     * @throws ORMException
     */
    public function save(PasswordGroup $passwordGroup): void
    {
        $this->passwordGroupRepository->save($passwordGroup);
    }

}