<?php


namespace App\DTO\Request\Modules\Passwords;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class InsertPasswordsRequestDTO
 * @package App\DTO\Request
 */
class InsertPasswordsRequestDTO
{
    /**
     * @var string[]
     */
    private array $passwordsJsons = [];

    /**
     * @return string[]
     */
    public function getPasswordsJsons(): array
    {
        return $this->passwordsJsons;
    }

    /**
     * @param string[] $passwordsJsons
     */
    public function setPasswordsJsons(array $passwordsJsons): void
    {
        $this->passwordsJsons = $passwordsJsons;
    }

    /**
     * Will build the request Dto from the foundation request
     *
     * @param Request $request
     * @return InsertPasswordsRequestDTO|null
     */
    public static function fromRequest(Request $request): ?InsertPasswordsRequestDTO
    {
        $requestContent      = $request->getContent();
        $passwordsJsonsArray = json_decode($requestContent, true);

        if( JSON_ERROR_NONE !== json_last_error() ){
            return null;
        }

        $insertPasswordsGroupsRequest = new InsertPasswordsRequestDTO();
        $insertPasswordsGroupsRequest->setPasswordsJsons($passwordsJsonsArray);

        return $insertPasswordsGroupsRequest;
    }
}