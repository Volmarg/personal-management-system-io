<?php

namespace App\Entity\Modules\Notes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Modules\Notes\NoteRepository")
 * @ORM\Table(name="my_note")
 */
class MyNote
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
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $body;

    /**
     * @ORM\ManyToOne(targetEntity="MyNoteCategory", inversedBy="note")
     * @ORM\JoinColumn(nullable=false)
     */
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
}
