<?php


namespace App\DTO\Internal\Module\Notes;

use App\DTO\BaseApiDTO;
use App\Entity\Modules\Notes\Note;

/**
 * Class NoteCategoryDTO
 * @package App\DTO\API\Internal
 */
class NotesInCategoryDTO extends BaseApiDTO
{
    const KEY_NOTES_JSONS = "notesJsons";
    const KEY_NAME        = "name";

    /**
     * Contains jsons representations of the @see Note
     *
     * @var string[]
     */
    private array $notesJsons = [];

    /**
     * @var string $name
     */
    private string $name = "";

    /**
     * @return string[]
     */
    public function getNotesJsons(): array
    {
        return $this->notesJsons;
    }

    /**
     * @param string[] $notesJsons
     */
    public function setNotesJsons(array $notesJsons): void
    {
        $this->notesJsons = $notesJsons;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Return array representation of the dto
     *
     * @return array
     */
    public function toArray(): array
    {
        $baseDataArray = parent::toArray();
        $baseDataArray[self::KEY_NOTES_JSONS] = $this->getNotesJsons();
        $baseDataArray[self::KEY_NAME]        = $this->getName();

        return $baseDataArray;
    }
}