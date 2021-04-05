<?php

namespace App\Entity\Modules\Notes;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Modules\Notes\MyNoteRepository")
 * @ORM\Table(name="my_note")
 */
class MyNote
{
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
}
