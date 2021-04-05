<?php

namespace App\DTO\Internal;

use App\DTO\BaseApiResponseDto;

/**
 * Transfer basic data about the user
 *
 * Class LoggedInUserDataDto
 * @package App\DTO\API\Internal
 */
class LoggedInUserDataDto extends BaseApiResponseDto
{
    const KEY_SHOWN_NAME = "shownName";
    const KEY_AVATAR     = "avatar";

    /**
     * @var string $shownName
     */
    private string $shownName = "";

    /**
     * @var string $avatar
     */
    private string $avatar    = "";

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
     * @return array
     */
    public function toArray(): array
    {
        $array                       = parent::toArray();
        $array[self::KEY_AVATAR]     = $this->getAvatar();
        $array[self::KEY_SHOWN_NAME] = $this->getShownName();

        return $array;
    }
}