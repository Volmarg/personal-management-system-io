<?php


namespace App\DTO\Request\Modules\Passwords;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class InsertPasswordsGroupsRequestDTO
 * @package App\DTO\Request
 */
class InsertPasswordsGroupsRequestDTO
{
    /**
     * @var array $passwordsGroupsArrays
     */
    private array $passwordsGroupsArrays = [];

    /**
     * @return array $passwordsGroupsArrays
     */
    public function getPasswordsGroupsArrays(): array
    {
        return $this->passwordsGroupsArrays;
    }

    /**
     * @param array $passwordsGroupsArrays
     */
    public function setPasswordsGroupsArrays(array $passwordsGroupsArrays): void
    {
        $this->passwordsGroupsArrays = $passwordsGroupsArrays;
    }

    /**
     * Will build the request Dto from the foundation request
     *
     * @param Request $request
     * @return InsertPasswordsGroupsRequestDTO|null
     */
    public static function fromRequest(Request $request): ?InsertPasswordsGroupsRequestDTO
    {
        $requestContent        = $request->getContent();
        $passwordsGroupsArrays = json_decode($requestContent, true);

        if( JSON_ERROR_NONE !== json_last_error() ){
            return null;
        }

        $insertPasswordsGroupsRequest = new InsertPasswordsGroupsRequestDTO();
        $insertPasswordsGroupsRequest->setPasswordsGroupsArrays($passwordsGroupsArrays);

        return $insertPasswordsGroupsRequest;
    }
}