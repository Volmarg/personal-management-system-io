<?php


namespace App\DTO\Internal\Form\Security;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Represents the data obtained from login form
 *
 * Class LoginFormDTO
 * @package App\DTO\Internal\Form
 */
class LoginFormDataDTO
{
    /**
     * @var ?string $username
     */
    #[NotBlank]
    private ?string $username = "";

    /**
     * @var ?string $password
     */
    #[NotBlank]
    private ?string $password = "";

    /**
     * LoginFormDataDTO constructor.
     */
    public function __construct()
    {
        $this->username = "";
        $this->password = "";
    }

    /**
     * @return ?string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param ?string $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param ?string $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

}