<?php


namespace App\Action\Module\Passwords;

use App\Attribute\Action\InternalActionAttribute;
use App\Controller\Core\Services;
use App\Controller\Modules\Passwords\PasswordController;
use App\Controller\Modules\Passwords\PasswordGroupController;
use App\DTO\BaseApiResponseDTO;
use App\DTO\Internal\Module\Passwords\GetDecryptedPasswordResponseDTO;
use App\DTO\Internal\Module\Passwords\GetPasswordGroupWithPasswordsResponseDTO;
use App\Entity\Modules\Passwords\Password;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contains logic for handling routes for passwords
 *
 * Class PasswordsAction
 * @package App\Action\Module\Passwords
 */
#[Route("/module/passwords", name: "module_passwords_")]
class PasswordsAction extends AbstractController
{

    /**
     * @var PasswordGroupController $passwordGroupController
     */
    private PasswordGroupController $passwordGroupController;

    /**
     * @var PasswordController $passwordController
     */
    private PasswordController $passwordController;

    /**
     * @var Services $services
     */
    private Services $services;

    public function __construct(PasswordGroupController $passwordGroupController, PasswordController $passwordController, Services $services)
    {
        $this->passwordGroupController = $passwordGroupController;
        $this->passwordController      = $passwordController;
        $this->services                = $services;
    }

    /**
     * Will return passwords for group id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route("/get-for-group-id/{id}", name: "get_for_group_id", methods: ["GET"])]
    #[InternalActionAttribute]
    public function getPasswordsForGroupId(string $id): JsonResponse
    {
        $group = $this->passwordGroupController->getOneForId($id);
        if( empty($group) ){
            $this->services->getLoggerService()->getLogger()->warning("No password group was found for id: {$id}");
            return BaseApiResponseDTO::buildNotFoundResponse()->toJsonResponse();
        }

        $allPasswordsJsons = array_map(
            fn(Password $password) => $password->toJson(),
            $group->getPassword()->getValues()
        );

        $responseDto = new GetPasswordGroupWithPasswordsResponseDTO();
        $responseDto->setPasswordsJsons($allPasswordsJsons);
        $responseDto->setPasswordGroupName($group->getName());
        $responseDto->setPasswordGroupId($group->getId());

        $responseDto->prefillBaseFieldsForSuccessResponse();
        return $responseDto->toJsonResponse();
    }

    /**
     * Will decrypt the password for given entity id
     * @throws Exception
     */
    #[Route("/decrypt-password/{passwordId}", name: "decrypt_password", methods: ["GET"])]
    #[InternalActionAttribute]
    public function decryptPasswordForId(string $passwordId): JsonResponse
    {
        $passwordEntity = $this->passwordController->getOneForId($passwordId);
        if( empty($passwordEntity) ){
            return BaseApiResponseDTO::buildNotFoundResponse()->toJsonResponse();
        }

        $decryptedPassword = $this->services->getEncryptionService()->decryptPassword($passwordEntity->getPassword());

        $responseDto = new GetDecryptedPasswordResponseDTO();
        $responseDto->prefillBaseFieldsForSuccessResponse();
        $responseDto->setDecryptedPassword($decryptedPassword);

        return $responseDto->toJsonResponse();
    }

}