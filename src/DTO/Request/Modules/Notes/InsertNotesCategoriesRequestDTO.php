<?php


namespace App\DTO\Request\Modules\Notes;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class InsertNotesCategoriesRequestDTO
 * @package App\DTO\Request
 */
class InsertNotesCategoriesRequestDTO
{
    /**
     * @var string[]
     */
    private array $notesCategoriesJsons = [];

    /**
     * @return string[]
     */
    public function getNotesCategoriesJsons(): array
    {
        return $this->notesCategoriesJsons;
    }

    /**
     * @param string[] $notesCategoriesJsons
     */
    public function setNotesCategoriesJsons(array $notesCategoriesJsons): void
    {
        $this->notesCategoriesJsons = $notesCategoriesJsons;
    }

    /**
     * Will build the request Dto from the foundation request
     *
     * @param Request $request
     * @return InsertNotesCategoriesRequestDTO|null
     */
    public static function fromRequest(Request $request): ?InsertNotesCategoriesRequestDTO
    {
        $requestContent           = $request->getContent();
        $noteCategoriesJsonsArray = json_decode($requestContent, true);

        if( JSON_ERROR_NONE !== json_last_error() ){
            return null;
        }

        $insertNotesCategoriesRequest = new InsertNotesCategoriesRequestDTO();
        $insertNotesCategoriesRequest->setNotesCategoriesJsons($noteCategoriesJsonsArray);

        return $insertNotesCategoriesRequest;
    }
}