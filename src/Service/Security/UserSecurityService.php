<?php


namespace App\Service\Security;

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
    public function encodeRawPassword(string $plainPassword): string
    {
        // it's required to use even blank user entity to fetch the encoder from it
        $user = new User();

        $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $plainPassword);
        return $encodedPassword;
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
}