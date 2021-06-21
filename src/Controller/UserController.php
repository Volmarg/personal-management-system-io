<?php


namespace App\Controller;


use App\Controller\Core\ConfigLoader;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController implements UserControllerInterface
{

    /**
     * @var UserRepository $userRepository
     */
    private UserRepository $userRepository;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @var ConfigLoader $configLoader
     */
    private ConfigLoader $configLoader;

    public function __construct(UserRepository $userRepository, TokenStorageInterface $tokenStorage, ConfigLoader $configLoader)
    {
        $this->tokenStorage   = $tokenStorage;
        $this->userRepository = $userRepository;
        $this->configLoader   = $configLoader;
    }

    /**
     * Will return one use for username (is unique) or null if nothing is found
     *
     * @param string $username
     * @return User|null
     */
    public function getOneByUsername(string $username): ?User
    {
        $user = $this->userRepository->getByUsername($username);
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

    /**
     * Will either create new record in db or update existing one
     *
     * @param UserInterface $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserInterface $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Will return all users
     *
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    /**
     * Will return information if any user is still logged in
     *
     * @return bool
     */
    public function isAnyUserActive(): bool
    {
        $maxInactivityTime          = $this->configLoader->getConfigLoaderSystemData()->getMaxInactivityTime();
        $activityExpirationDateTime = (new \DateTime())->modify("-{$maxInactivityTime} MINUTES");

        $isAnyUserStillActive = false;
        $allUsers             = $this->getAllUsers();

        foreach($allUsers as $user){
            if( $user->getLastActivity() > $activityExpirationDateTime ){
                $isAnyUserStillActive = true;
                break;
            }
        }

        return $isAnyUserStillActive;
    }
}