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
     * Will build the request Dto from the foundation request
     *
     * @param Request $request
     * @return InsertNotesRequestDTO
     */
    public static function fromRequest(Request $request): InsertNotesRequestDTO
    {
        $requestContent = $request->getContent();
        $noteJsonsArray = json_decode($requestContent);

        $insertNotesRequest = new InsertNotesRequestDTO();
        $insertNotesRequest->setNotesJsons($noteJsonsArray);

        return $insertNotesRequest;
    }
}