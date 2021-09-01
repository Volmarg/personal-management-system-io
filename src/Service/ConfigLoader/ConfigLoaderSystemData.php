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
     * @var int $maxInsertWaitTime
     */
    private int $maxInsertWaitTime;

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

    /**
     * @return int
     */
    public function getMaxInsertWaitTime(): int
    {
        return $this->maxInsertWaitTime;
    }

    /**
     * @param int $maxInsertWaitTime
     */
    public function setMaxInsertWaitTime(int $maxInsertWaitTime): void
    {
        $this->maxInsertWaitTime = $maxInsertWaitTime;
    }

}