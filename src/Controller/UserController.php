<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    private TokenStorageInterface $tokenStorage;

    public function __construct(UserRepository $userRepository, TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage   = $tokenStorage;
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

    /**
     * Will return logged in user - not just the UserInterface but the actual entity from database with all its data
     * If user is not logged in then null is returned
     */
    public function getLoggedInUser(): ?User
    {
        $loggedInBaseUserInterface = $this->getUser();
        if( empty($loggedInBaseUserInterface) ){
            return null;
        }

        $loggedInBaseUserInterface->getUsername();

        $userEntity = $this->getOneByUsername($loggedInBaseUserInterface->getUsername());

        return $userEntity;
    }

    /**
     * Will invalidate currently logged in user by cleaning up token
     */
    public function invalidateUser(): void
    {
        $this->tokenStorage->setToken(null);
    }
}