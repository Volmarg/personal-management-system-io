<?php


namespace App\DTO\Internal\Module\Passwords;


use App\DTO\BaseApiDTO;

class PasswordGroupWithPasswordsDTO extends BaseApiDTO
{

    const KEY_PASSWORD_GROUP_ID   = "passwordGroupId";
    const KEY_PASSWORD_GROUP_NAME = "passwordGroupName";
    const KEY_PASSWORDS_JSONS     = "passwordsJsons";

    /**
     * @var string $passwordGroupId
     */
    private string $passwordGroupId = "";

    /**
     * @var string $passwordGroupName
     */
    private string $passwordGroupName = "";

    /**
     * @var array $passwordsJsons
     */
    private array $passwordsJsons = [];

    /**
     * @return string
     */
    public function getPasswordGroupId(): string
    {
        return $this->passwordGroupId;
    }

    /**
     * @param string $passwordGroupId
     */
    public function setPasswordGroupId(string $passwordGroupId): void
    {
        $this->passwordGroupId = $passwordGroupId;
    }

    /**
     * @return string
     */
    public function getPasswordGroupName(): string
    {
        return $this->passwordGroupName;
    }

    /**
     * @param string $passwordGroupName
     */
    public function setPasswordGroupName(string $passwordGroupName): void
    {
        $this->passwordGroupName = $passwordGroupName;
    }

    /**
     * @return array
     */
    public function getPasswordsJsons(): array
    {
        return $this->passwordsJsons;
    }

    /**
     * @param array $passwordsJsons
     */
    public function setPasswordsJsons(array $passwordsJsons): void
    {
        $this->passwordsJsons = $passwordsJsons;
    }

    /**
     * Will return array representation of current dto
     *
     * @return array
     */
    public function toArray(): array
    {
        $dataArray = parent::toArray();
        $dataArray[self::KEY_PASSWORD_GROUP_ID]   = $this->getPasswordGroupId();
        $dataArray[self::KEY_PASSWORD_GROUP_NAME] = $this->getPasswordGroupName();
        $dataArray[self::KEY_PASSWORDS_JSONS]     = $this->getPasswordsJsons();

        return $dataArray;
    }

}