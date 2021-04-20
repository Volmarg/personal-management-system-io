<?php


namespace App\Controller\Modules\Passwords;


use App\Entity\Modules\Passwords\Password;
use App\Repository\Modules\Passwords\PasswordRepository;
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

}