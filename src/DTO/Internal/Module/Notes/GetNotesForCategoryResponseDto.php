<?php


namespace App\DTO\Internal\Module\Notes;

use App\DTO\BaseApiResponseDto;
use App\Entity\Modules\Notes\MyNote;

/**
 * Class CsrfTokenResponseDto
 * @package App\DTO\API\Internal
 */
class GetNotesForCategoryResponseDto extends BaseApiResponseDto
{
    const KEY_NOTES_JSONS = "notesJsons";

    /**
     * Contains jsons representations of the @see MyNote
     *
     * @var string[]
     */
    private array $notesJsons = [];

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
     * Return array representation of the dto
     *
     * @return array
     */
    public function toArray(): array
    {
        $baseDataArray = parent::toArray();
        $baseDataArray[self::KEY_NOTES_JSONS] = $this->getNotesJsons();

        return $baseDataArray;
    }
}