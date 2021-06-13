<?php


namespace App\DTO\Request\Modules\Notes;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class InsertNotesRequestDTO
 * @package App\DTO\Request
 */
class InsertNotesRequestDTO
{
    /**
     * @var array $notesArrays
     */
    private array $notesArrays = [];

    /**
     * @return array
     */
    public function getNotesArrays(): array
    {
        return $this->notesArrays;
    }

    /**
     * @param array $notesArrays
     */
    public function setNotesArrays(array $notesArrays): void
    {
        $this->notesArrays = $notesArrays;
    }

    /**
     * Will build the request Dto from the foundation request
     *
     * @param Request $request
     * @return InsertNotesRequestDTO|null
     */
    public static function fromRequest(Request $request): ?InsertNotesRequestDTO
    {
        $requestContent = $request->getContent();
        $noteArrays = json_decode($requestContent, true);

        if( JSON_ERROR_NONE !== json_last_error() ){
            return null;
        }

        $insertNotesRequest = new InsertNotesRequestDTO();
        $insertNotesRequest->setNotesArrays($noteArrays);

        return $insertNotesRequest;
    }
}