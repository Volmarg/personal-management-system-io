<?php

namespace App\DTO\Internal;

use App\DTO\BaseApiDTO;

/**
 * This dto transfers the .env information about the APP_DEMO mode
 *
 * Class DotenvIsDemoResponseDTO
 * @package App\DTO\API\Internal
 */
class DotenvIsDemoDTO extends BaseApiDTO
{
    const KEY_IS_DEMO = "isDemo";

    /**
     * @var bool $demo
     */
    private bool $demo = false;

    /**
     * @return bool
     */
    public function isDemo(): bool
    {
        return $this->demo;
    }

    /**
     * @param bool $demo
     */
    public function setDemo(bool $demo): void
    {
        $this->demo = $demo;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array                    = parent::toArray();
        $array[self::KEY_IS_DEMO] = $this->isDemo();

        return $array;
    }

}