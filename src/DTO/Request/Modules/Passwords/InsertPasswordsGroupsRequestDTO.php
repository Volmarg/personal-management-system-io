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
     * @var string[]
     */
    private array $passwordsGroupsJsons = [];

    /**
     * @return string[]
     */
    public function getPasswordsGroupsJsons(): array
    {
        return $this->passwordsGroupsJsons;
    }

    /**
     * @param string[] $passwordsGroupsJsons
     */
    public function setPasswordsGroupsJsons(array $passwordsGroupsJsons): void
    {
        $this->passwordsGroupsJsons = $passwordsGroupsJsons;
    }

    /**
     * Will build the request Dto from the foundation request
     *
     * @param Request $request
     * @return InsertPasswordsGroupsRequestDTO
     */
    public static function fromRequest(Request $request): InsertPasswordsGroupsRequestDTO
    {
        $requestContent            = $request->getContent();
        $passwordsGroupsJsonsArray = json_decode($requestContent);

        $insertPasswordsGroupsRequest = new InsertPasswordsGroupsRequestDTO();
        $insertPasswordsGroupsRequest->setPasswordsGroupsJsons($passwordsGroupsJsonsArray);

        return $insertPasswordsGroupsRequest;
    }
}