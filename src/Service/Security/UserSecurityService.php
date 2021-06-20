<?php


namespace App\Service\Security;

use App\Entity\ApiUser;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Consist of logic related to user authentication
 *
 * Class UserSecurityService
 * @package App\Service\Security
 */
class UserSecurityService
{

    /**
     * @var UserPasswordEncoderInterface $userPasswordEncoder
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * UserSecurityService constructor.
     *
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param LoggerInterface $logger
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, LoggerInterface $logger)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->logger              = $logger;
    }

    /**
     * Will encode plain password for standard login user interface
     *
     * @param string $plainPassword
     * @return string
     */
    public function encodeRawPasswordForUserEntity(string $plainPassword): string
    {
        // it's required to use even blank user entity to fetch the encoder from it
        $user = new User();
        return $this->encodePasswordForUserInterface($user, $plainPassword);
    }

    /**
     * Will encode plain password for API login user interface
     *
     * @param string $plainPassword
     * @return string
     */
    public function encodeRawPasswordForApiUserEntity(string $plainPassword): string
    {
        // it's required to use even blank user entity to fetch the encoder from it
        $user = new ApiUser();
        return $this->encodePasswordForUserInterface($user, $plainPassword);
    }

    /**
     * @param string $plainPassword
     * @param UserInterface $user
     * @return bool
     */
    public function validatePasswordForUser(string $plainPassword, UserInterface $user): bool
    {
        $isPasswordValid = $this->userPasswordEncoder->isPasswordValid($user, $plainPassword);
        return $isPasswordValid;
    }

    /**
     * Will encode plain password for user interface
     *
     * @param UserInterface $user
     * @param string $plainPassword
     * @return string
     */
    private function encodePasswordForUserInterface(UserInterface $user, string $plainPassword): string
    {
        $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $plainPassword);
        return $encodedPassword;
    }
}