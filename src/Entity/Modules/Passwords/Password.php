<?php

namespace App\Entity\Modules\Passwords;

use App\Entity\Modules\Passwords\PasswordGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Modules\Passwords\PasswordRepository")
 * @ORM\Table(name="password")
 */
class Password
{
    const KEY_LOGIN       = "login";
    const KEY_PASSWORD    = "password";
    const KEY_URL         = "url";
    const KEY_DESCRIPTION = "description";
    const KEY_GROUP_ID    = "groupId";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $login;

    // Encrypted
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    private string $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string  $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modules\Passwords\PasswordGroup", inversedBy="password")
     * @ORM\JoinColumn(nullable=false)
     */
    private $group;

    public function getId(): ?int {
        return $this->id;
    }

    public function getLogin(): ?string {
        return $this->login;
    }

    public function setLogin(string $login): self {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function setUrl(string $url): self {
        $this->url = $url;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;

        return $this;
    }

    /**
     * @return PasswordGroup
     */
    public function getGroup(): PasswordGroup
    {
        return $this->group;
    }

    /**
     * @param PasswordGroup $group
     * @return Password
     */
    public function setGroup(PasswordGroup $group): self {
        $this->group = $group;

        return $this;
    }

    /**
     * Return current dto in form of json string
     *
     * @return string
     */
    public function toJson(): string
    {
        $dataArray = [
            self::KEY_DESCRIPTION => $this->getDescription(),
            self::KEY_GROUP_ID    => $this->getGroup()->getId(),
            self::KEY_LOGIN       => $this->getLogin(),
            self::KEY_PASSWORD    => $this->getPassword(), // todo: must be decoded
            self::KEY_URL         => $this->getUrl(),
        ];

        $json = json_encode($dataArray);
        return $json;
    }

}
