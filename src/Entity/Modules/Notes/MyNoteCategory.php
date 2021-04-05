<?php

namespace App\Entity\Modules\Notes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Modules\Notes\MyNoteCategoryRepository")
 * @ORM\Table(name="my_note_category",
 *    indexes={
 *       @Index(
 *          name="my_note_category_index",
 *          columns={"id"}
 *        )
 *    }
 * )
 *
 */
class MyNoteCategory
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $icon;

    /**
     * @ORM\OneToMany(targetEntity="MyNote", mappedBy="category")
     */
    private string $note;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $parentId = NULL;

    /**
     * @return mixed
     */
    public function getParentId() {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     * @throws Exception
     */
    public function setParentId(string $parentId): void {
        if (
                $this->id == $parentId
            &&  !is_null($parentId)
        ) {
            throw new Exception('You cannot be children and parent at the same time!');
        }
        $this->parentId = $parentId;
    }

    /**
     * Fix for usage in FormType as EntityType, without it Entity type crashes
     */
    public function __toString() {
        return strval($this->id);
    }

    public function __construct() {
        $this->note = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }
    
    
    public function setId(int $id): self{
         $this->id = $id;
         return $this;
    }

    public function getIcon(): ?string {
        return $this->icon;
    }

    public function setIcon(?string $icon): self {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection|MyNote[]
     */
    public function getNote(): Collection {
        return $this->note;
    }

    public function addNote(MyNote $note): self {
        if (!$this->note->contains($note)) {
            $this->note[] = $note;
            $note->setCategory($this);
        }

        return $this;
    }

    public function removeNote(MyNote $note): self {
        if ($this->note->contains($note)) {
            $this->note->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getCategory() === $this) {
                $note->setCategory(null);
            }
        }

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getColor(): ?string {
        return $this->color;
    }

    public function setColor(string $color): self {
        $this->color = strtoupper(str_replace('#', '', $color));

        return $this;
    }
}
