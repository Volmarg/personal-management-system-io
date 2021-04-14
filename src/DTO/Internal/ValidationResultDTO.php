<?php


namespace App\DTO\Internal;

/**
 * This DTO should be used in any logic performing validation
 *
 * Class ValidationResultDTO
 * @package App\DTO\Internal
 */
class ValidationResultDTO
{

    /**
     * @var bool $success
     */
    private bool $success;

    /**
     * @var array $violationsWithMessages
     */
    private array $violationsWithMessages = [];

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return array
     */
    public function getViolationsWithMessages(): array
    {
        return $this->violationsWithMessages;
    }

    /**
     * @param array $violationsWithMessages
     */
    public function setViolationsWithMessages(array $violationsWithMessages): void
    {
        $this->violationsWithMessages = $violationsWithMessages;
    }

}