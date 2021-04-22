<?php


namespace App\DTO\Internal\Form\Security;

/**
 * Represents the data obtained from login form
 *
 * Class LoginFormDTO
 * @package App\DTO\Internal\Form
 */
class LoginFormDataDTO
{
    /**
     * @var string $username
     */
    private string $username;

    /**
     * @var string $password
     */
    private string $password;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
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
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

}