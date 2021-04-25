<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{

    /**
     * @var UserRepository $userRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Will return one use for username (is unique) or null if nothing is found
     *
     * @param string $username
     * @return User|null
     */
    public function getOneByUsername(string $username): ?User
    {
        $user = $this->userRepository->findOneBy([
            User::FIELD_NAME_USERNAME => $username,
        ]);

        return $user;
    }
}