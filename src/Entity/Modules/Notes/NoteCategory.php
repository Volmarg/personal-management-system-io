<?php

namespace App\Entity\Modules\Notes;

use App\Service\ArrayService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Exception;
use SpecShaper\EncryptBundle\Annotations\Encrypted;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Modules\Notes\NoteCategoryRepository")
 * @ORM\Table(name="note_category",
 *    indexes={
 *       @Index(
 *          name="my_note_category_index",
 *          columns={"id"}
 *        )
 *    }
 * )
 *
 */
class NoteCategory
{

    const KEY_ID        = "id";
    const KEY_ICON      = "icon";
    const KEY_NAME      = "name";
    const KEY_COLOR     = "color";
    const KEY_PARENT_ID = "parentId";

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $icon;

    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="category")
     */
    private Collection $note;

    /**
     * @Encrypted
     *
     * @ORM\Column(type="text", length=255)
     */
    #[NotBlank]
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
     * @return ?string
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @param ?string $parentId
     * @throws Exception
     */
    public function setParentId(?string $parentId): void {
        if (
                $this->id == $parentId
            &&  !empty($parentId)
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

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self{
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
     * @return Collection|Note[]
     */
    public function getNote(): Collection {
        return $this->note;
    }

    public function addNote(Note $note): self {
        if (!$this->note->contains($note)) {
            $this->note[] = $note;
            $note->setCategory($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self {
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

    /**
     * Creates entity from array
     *
     * @param array $dataArray
     * @return NoteCategory
     * @throws Exception
     */
    public static function fromArray(array $dataArray): NoteCategory
    {
        $id       = ArrayService::getArrayValueForKey($dataArray, self::KEY_ID, null);
        $icon     = ArrayService::getArrayValueForKey($dataArray, self::KEY_ICON, "");
        $name     = ArrayService::getArrayValueForKey($dataArray, self::KEY_NAME, "");
        $color    = ArrayService::getArrayValueForKey($dataArray, self::KEY_COLOR, "");
        $parentId = ArrayService::getArrayValueForKey($dataArray, self::KEY_PARENT_ID, null);

        $myNoteCategory = new NoteCategory();
        $myNoteCategory->setId($id);
        $myNoteCategory->setIcon($icon);
        $myNoteCategory->setName($name);
        $myNoteCategory->setColor($color);
        $myNoteCategory->setParentId($parentId);

        return $myNoteCategory;
    }

    /**
     * Will return string (json) representation of the entity
     *
     * @return string
     */
    public function toJson(): string
    {
        $dataArray = [
            self::KEY_ID        => $this->getId(),
            self::KEY_ICON      => $this->getIcon(),
            self::KEY_NAME      => $this->getName(),
            self::KEY_COLOR     => $this->getColor(),
            self::KEY_PARENT_ID => $this->getParentId(),
        ];

        return json_encode($dataArray);
    }
}
