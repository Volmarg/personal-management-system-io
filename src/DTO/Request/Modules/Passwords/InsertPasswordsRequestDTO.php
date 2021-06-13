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
     * @var array
     */
    private array $passwordsArrays = [];

    /**
     * @return array
     */
    public function getPasswordsArrays(): array
    {
        return $this->passwordsArrays;
    }

    /**
     * @param array $passwordsArrays
     */
    public function setPasswordsArrays(array $passwordsArrays): void
    {
        $this->passwordsArrays = $passwordsArrays;
    }

    /**
     * Will build the request Dto from the foundation request
     *
     * @param Request $request
     * @return InsertPasswordsRequestDTO|null
     */
    public static function fromRequest(Request $request): ?InsertPasswordsRequestDTO
    {
        $requestContent  = $request->getContent();
        $passwordsArrays = json_decode($requestContent, true);

        if( JSON_ERROR_NONE !== json_last_error() ){
            return null;
        }

        $insertPasswordsGroupsRequest = new InsertPasswordsRequestDTO();
        $insertPasswordsGroupsRequest->setPasswordsArrays($passwordsArrays);

        return $insertPasswordsGroupsRequest;
    }
}