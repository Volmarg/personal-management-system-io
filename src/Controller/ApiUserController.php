<?php


namespace App\Controller;

use App\Entity\ApiUser;
use App\Repository\ApiUserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ApiUserController
 * @package App\Controller
 */
class ApiUserController extends AbstractController implements UserControllerInterface
{

    /**
     * @var ApiUserRepository $apiUserRepository
     */
    private ApiUserRepository $apiUserRepository;

    public function __construct(ApiUserRepository $apiUserRepository)
    {
        $this->apiUserRepository = $apiUserRepository;
    }

    /**
     * Will return one use for username (is unique) or null if nothing is found
     *
     * @param string $username
     * @return null|ApiUser
     */
    public function getOneByUsername(string $username): ?ApiUser
    {
        $user = $this->apiUserRepository->getByApiUsername($username);
        return $user;
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
        $this->apiUserRepository->save($user);
    }

}