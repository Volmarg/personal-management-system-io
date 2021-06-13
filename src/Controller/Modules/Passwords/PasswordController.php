<?php


namespace App\Controller\Modules\Passwords;


use App\Entity\Modules\Passwords\Password;
use App\Repository\Modules\Passwords\PasswordRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PasswordController
 * @package App\Controller\Modules\Passwords
 */
class PasswordController extends AbstractController
{

    /**
     * @var PasswordRepository $passwordRepository
     */
    private PasswordRepository $passwordRepository;

    public function __construct(PasswordRepository $passwordRepository)
    {
        $this->passwordRepository = $passwordRepository;
    }

    /**
     * Will return one entity for id or null if nothing is found
     *
     * @param string $id
     * @return Password|null
     */
    public function getOneForId(string $id): ?Password
    {
        return $this->passwordRepository->find($id);
    }

    /**
     * Will save the new entity or update the state of already existing one
     *
     * @param Password $password
     * @throws ORMException
     */
    public function save(Password $password): void
    {
        $this->passwordRepository->save($password);
    }

    /**
     * Will remove all entries from DB
     * @throws Exception
     */
    public function removeAll(): void
    {
        $this->passwordRepository->removeAll();
    }

}