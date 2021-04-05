<?php

namespace App\DTO\Internal;

use App\DTO\BaseApiResponseDto;

/**
 * This dto transfers the .env information about the APP_DEMO mode
 *
 * Class CsrfTokenResponseDto
 * @package App\DTO\API\Internal
 */
class DotenvIsDemoResponseDto extends BaseApiResponseDto
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