<?php

namespace App\Entity\Modules\Notes;

use Ambta\DoctrineEncryptBundle\Configuration\Encrypted;
use App\Entity\AbstractEntity;
use App\Service\ArrayService;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="PasswordRepository")
 * @ORM\Table(name="my_note")
 */
class MyNote extends AbstractEntity
{
    const KEY_ID          = "id";
    const KEY_TITLE       = "title";
    const KEY_BODY        = "body";
    const KEY_CATEGORY_ID = "categoryId";

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
    private string $title;

    /**
     * @Encrypted
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $body;

    /**
     * @ORM\ManyToOne(targetEntity="MyNoteCategory", inversedBy="note")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotNull]
    private MyNoteCategory $category;

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string {
        return $this->body;
    }

    public function setBody(?string $body): self {
        $this->body = $body;

        return $this;
    }

    public function getCategory(): MyNoteCategory {
        return $this->category;
    }

    public function setCategory(MyNoteCategory $category): self {
        $this->category = $category;

        return $this;
    }

    /**
     * Returns json representation of entity
     */
    public function toJson(): string
    {
        $dataArray = [
          self::KEY_ID          => $this->getId(),
          self::KEY_CATEGORY_ID => $this->getCategory()->getId(),
          self::KEY_BODY        => $this->getBody(),
          self::KEY_TITLE       => $this->getTitle(),
        ];

        $json = json_encode($dataArray);
        return $json;
    }

    /**
     * Creates entity from json
     *
     * @param string $json
     * @return MyNote
     */
    public static function fromJson(string $json): MyNote
    {
        $dataArray  = json_decode($json, true);

        $categoryId = ArrayService::getArrayValueForKey($dataArray, self::KEY_CATEGORY_ID);
        $title      = ArrayService::getArrayValueForKey($dataArray, self::KEY_TITLE, "");
        $body       = ArrayService::getArrayValueForKey($dataArray, self::KEY_BODY, "");

        $myNote = new MyNote();

        $myNote->setBody($body);
        $myNote->setTitle($title);
        $myNote->setBody($body);
        $myNote->dataBag->set(self::KEY_CATEGORY_ID, $categoryId);

        return $myNote;
    }
}
