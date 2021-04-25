<?php

namespace App\DTO\Internal;

use App\DTO\BaseApiResponseDTO;

/**
 * Transfer basic data about the user
 *
 * Class LoggedInUserDataDto
 * @package App\DTO\API\Internal
 */
class LoggedInUserDataDTO extends BaseApiResponseDTO
{
    const KEY_SHOWN_NAME = "shownName";
    const KEY_AVATAR     = "avatar";
    const KEY_LOGGED_IN  = "loggedIn";

    /**
     * @var string $shownName
     */
    private string $shownName = "";

    /**
     * @var string $avatar
     */
    private string $avatar = "";

    /**
     * @var bool $loggedIn
     */
    private bool $loggedIn = false;

    /**
     * @return string
     */
    public function getShownName(): string
    {
        return $this->shownName;
    }

    /**
     * @param string $shownName
     */
    public function setShownName(string $shownName): void
    {
        $this->shownName = $shownName;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }

    /**
     * @param bool $loggedIn
     */
    public function setLoggedIn(bool $loggedIn): void
    {
        $this->loggedIn = $loggedIn;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array                       = parent::toArray();
        $array[self::KEY_AVATAR]     = $this->getAvatar();
        $array[self::KEY_SHOWN_NAME] = $this->getShownName();
        $array[self::KEY_LOGGED_IN]  = $this->isLoggedIn();

        return $array;
    }
}