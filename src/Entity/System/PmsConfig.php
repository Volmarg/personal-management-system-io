<?php

namespace App\Entity\System;

use App\Repository\System\PmsConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contains variety of configuration information send directly from the PMS project
 *
 * @ORM\Entity(repositoryClass=PmsConfigRepository::class)
 */
class PmsConfig
{
    const CONFIG_NAME_ENCRYPTION_KEY = "encryptionKey";

    const FIELD_NAME = "name";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $value;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
