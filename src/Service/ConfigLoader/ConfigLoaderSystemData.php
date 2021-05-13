<?php


namespace App\Service\ConfigLoader;

/**
 * Contains base data about the system
 *
 * Class ConfigLoaderSystemData
 * @package App\Service\ConfigLoader
 */
class ConfigLoaderSystemData
{
    /**
     * @var int $maxInactivityTime
     */
    private int $maxInactivityTime;

    /**
     * @return int
     */
    public function getMaxInactivityTime(): int
    {
        return $this->maxInactivityTime;
    }

    /**
     * @param int $maxInactivityTime
     */
    public function setMaxInactivityTime(int $maxInactivityTime): void
    {
        $this->maxInactivityTime = $maxInactivityTime;
    }

}