<?php


namespace App\DTO\Internal\Module\Passwords;


use App\DTO\BaseApiDTO;

class AllPasswordGroupsDTO extends BaseApiDTO
{

    const KEY_PASSWORDS_GROUPS = "passwordsGroups";

    /**
     * @var array $passwordsGroups
     */
    private array $passwordsGroups = [];

    /**
     * @return array
     */
    public function getPasswordsGroups(): array
    {
        return $this->passwordsGroups;
    }

    /**
     * @param array $passwordsGroups
     */
    public function setPasswordsGroups(array $passwordsGroups): void
    {
        $this->passwordsGroups = $passwordsGroups;
    }

    /**
     * Will return array representation of current dto
     *
     * @return array
     */
    public function toArray(): array
    {
        $dataArray                             = parent::toArray();
        $dataArray[self::KEY_PASSWORDS_GROUPS] = $this->getPasswordsGroups();

        return $dataArray;
    }

}