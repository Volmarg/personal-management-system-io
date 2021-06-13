<?php

namespace App\Entity\System;

use App\Repository\System\IncomingRequestRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contains data about requests done to the service
 *
 * @ORM\Entity(repositoryClass=IncomingRequestRepository::class)
 */
class IncomingRequest
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int $id
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string $ip
     */
    private string $ip;

    /**
     * @var DateTime|null $created
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $created;

    public function __construct()
    {
        $this->created = new DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return DateTime|null
     */
    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime|null $created
     */
    public function setCreated(?DateTime $created): void
    {
        $this->created = $created;
    }

}
