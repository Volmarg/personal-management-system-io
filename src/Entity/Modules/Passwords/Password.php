<?php

namespace App\Entity\Modules\Passwords;

use App\DTO\AbstractDTO;
use Doctrine\ORM\Mapping as ORM;
use SpecShaper\EncryptBundle\Annotations\Encrypted;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Modules\Passwords\PasswordRepository")
 * @ORM\Table(name="password")
 */
class Password
{
    const KEY_ID          = "id";
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
     * @Encrypted
     * @ORM\Column(type="text", length=255)
     */
    #[Assert\NotBlank]
    private string $login;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    private string $password;

    /**
     * @Encrypted
     * @ORM\Column(type="text", length=255,  nullable=true)
     */
    private string $url;

    /**
     * @Encrypted
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private string  $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modules\Passwords\PasswordGroup", inversedBy="password")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotBlank]
    private $group;

    /**
     * NOT a database based field - helper field for transferring data
     *
     * @var int $groupId
     */
    private int $groupId;

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
     * @return int
     */
    public function getGroupId(): int
    {
        return $this->groupId;
    }

    /**
     * @param int $groupId
     */
    public function setGroupId(int $groupId): void
    {
        $this->groupId = $groupId;
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
            self::KEY_PASSWORD    => $this->getPassword(),
            self::KEY_URL         => $this->getUrl(),
            self::KEY_ID          => $this->getId(),
        ];

        $json = json_encode($dataArray);
        return $json;
    }

    /**
     * Will create entity from json string
     */
    public static function fromJson(string $json): Password
    {
        $dataArray = json_decode($json, true);

        $login       = AbstractDTO::checkAndGetKey($dataArray, self::KEY_LOGIN);
        $password    = AbstractDTO::checkAndGetKey($dataArray, self::KEY_PASSWORD);
        $url         = AbstractDTO::checkAndGetKey($dataArray, self::KEY_URL);
        $description = AbstractDTO::checkAndGetKey($dataArray, self::KEY_DESCRIPTION);
        $groupId     = AbstractDTO::checkAndGetKey($dataArray, self::KEY_GROUP_ID);

        $passwordEntity = new Password();
        $passwordEntity->setLogin($login);
        $passwordEntity->setPassword($password);
        $passwordEntity->setUrl($url);
        $passwordEntity->setDescription($description);
        $passwordEntity->setGroupId($groupId);

        return $passwordEntity;
    }
}
