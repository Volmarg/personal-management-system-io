<?php


namespace App\Action\Module\Passwords;

use App\Controller\Modules\Passwords\PasswordGroupController;
use App\DTO\Internal\Module\Passwords\GetAllPasswordGroupsResponseDTO;
use App\Entity\Modules\Passwords\PasswordGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contains logic for handling passwords groups routes
 *
 * Class PasswordsGroupsAction
 * @package App\Action\Module\Passwords
 */
#[Route("/module/passwords-groups", name: "module_passwords_groups_")]
class PasswordsGroupsAction extends AbstractController
{

    /**
     * @var PasswordGroupController $passwordGroupController
     */
    private PasswordGroupController $passwordGroupController;

    public function __construct(PasswordGroupController $passwordGroupController)
    {
        $this->passwordGroupController = $passwordGroupController;
    }

    /**
     * Will return all passwords groups
     *
     * @return JsonResponse
     */
    #[Route("/get-all-groups", name: "get_all_groups")]
    public function getAllPasswordGroups(): JsonResponse
    {
        $allGroups                 = $this->passwordGroupController->getAllGroups();
        $getPasswordGroupsResponse = new GetAllPasswordGroupsResponseDTO();
        $getPasswordGroupsResponse->prefillBaseFieldsForSuccessResponse();

        $arrayOfPasswordJsons = array_map( fn(PasswordGroup $password) => $password->toJson(), $allGroups );

        $getPasswordGroupsResponse->setPasswordsGroups($arrayOfPasswordJsons);

        return $getPasswordGroupsResponse->toJsonResponse();
    }

}