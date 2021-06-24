<?php

namespace App\Entity\System;

use App\Repository\System\SystemStateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SystemStateRepository::class)
 */
class SystemState
{
    /**
     * This setting expects a bool value,
     * decides if data can be inserted in the project
     */
    const STATE_NAME_ALLOW_INSERT_DATA = "allowInsertData";

    /**
     * Means that the data from external tool has been transferred
     */
    const STATE_NAME_IS_DATA_TRANSFERRED = "isDataTransferred";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValue(): bool
    {
        return $this->value;
    }

    /**
     * @param bool $value
     */
    public function setValue(bool $value): void
    {
        $this->value = $value;
    }
}
