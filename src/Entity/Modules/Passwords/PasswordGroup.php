<?php

namespace App\Entity\Modules\Passwords;

use App\DTO\AbstractDTO;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SpecShaper\EncryptBundle\Annotations\Encrypted;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Modules\Passwords\PasswordGroupRepository")
 * @ORM\Table(name="password_group")
 */
class PasswordGroup
{
    const KEY_ID   = "id";
    const KEY_NAME = "name";

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Encrypted
     * @ORM\Column(type="text", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity="Password", mappedBy="category", mappedBy="group")
     */
    private Collection $password;

    public function getId(): ?int
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPassword(): Collection
    {
        return $this->password;
    }

    /**
     * @param Collection $password
     */
    public function setPassword(Collection $password): void
    {
        $this->password = $password;
    }

    /**
     * Returns json representation of given entity
     *
     * @return string
     */
    public function toJson(): string
    {
       $dataArray = [
           self::KEY_ID   => $this->getId(),
           self::KEY_NAME => $this->getName(),
       ];

       $json = json_encode($dataArray);
       return $json;
    }

    /**
     * Returns the entity created from json
     *
     * @param array $dataArray
     * @return PasswordGroup
     */
    public static function fromArray(array $dataArray): PasswordGroup
    {
        $id   = AbstractDTO::checkAndGetKey($dataArray, self::KEY_ID, null);
        $name = AbstractDTO::checkAndGetKey($dataArray, self::KEY_NAME, "");

        $entity = new PasswordGroup();
        $entity->setId($id ?: null);
        $entity->setName($name);

        return $entity;
    }

}
