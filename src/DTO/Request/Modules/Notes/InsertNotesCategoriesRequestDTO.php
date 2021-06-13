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
     * @var array
     */
    private array $notesCategoriesArrays = [];

    /**
     * @return array
     */
    public function getNotesCategoriesArrays(): array
    {
        return $this->notesCategoriesArrays;
    }

    /**
     * @param array $notesCategoriesArrays
     */
    public function setNotesCategoriesArrays(array $notesCategoriesArrays): void
    {
        $this->notesCategoriesArrays = $notesCategoriesArrays;
    }

    /**
     * Will build the request Dto from the foundation request
     *
     * @param Request $request
     * @return InsertNotesCategoriesRequestDTO|null
     */
    public static function fromRequest(Request $request): ?InsertNotesCategoriesRequestDTO
    {
        $requestContent       = $request->getContent();
        $noteCategoriesArrays = json_decode($requestContent, true);

        if( JSON_ERROR_NONE !== json_last_error() ){
            return null;
        }

        $insertNotesCategoriesRequest = new InsertNotesCategoriesRequestDTO();
        $insertNotesCategoriesRequest->setNotesCategoriesArrays($noteCategoriesArrays);

        return $insertNotesCategoriesRequest;
    }
}