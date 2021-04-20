<?php


namespace App\Action\API\Module\Passwords;


use App\Action\API\ApiAction;
use App\Attribute\Action\ExternalActionAttribute;
use App\Controller\API\ApiController;
use App\Controller\Core\Services;
use App\Controller\Modules\Passwords\PasswordController;
use App\Controller\Modules\Passwords\PasswordGroupController;
use App\DTO\BaseApiResponseDTO;
use App\DTO\Request\Modules\Passwords\InsertPasswordsGroupsRequestDTO;
use App\DTO\Request\Modules\Passwords\InsertPasswordsRequestDTO;
use App\Entity\Modules\Passwords\Password;
use App\Entity\Modules\Passwords\PasswordGroup;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TypeError;

/**
 * External routes for insertion of passwords related data
 *
 * Class PasswordsApiAction
 * @package App\Action\API\Module\Passwords
 */
#[Route("/api/passwords", name: "api_passwords_")]
class PasswordsApiAction extends ApiAction
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
     * PasswordsApiAction constructor.
     *
     * @param Services $services
     * @param ApiController $apiController
     * @param PasswordGroupController $passwordGroupController
     * @param PasswordController $passwordController
     */
    public function __construct(
        Services                $services,
        ApiController           $apiController,
        PasswordGroupController $passwordGroupController,
        PasswordController      $passwordController
    )
    {
        parent::__construct($apiController, $services);
        $this->passwordGroupController = $passwordGroupController;
        $this->passwordController      = $passwordController;
    }

    /**
     * Handles insertion of password groups
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/insert-groups", name: "insert_groups", methods: ["POST"])]
    #[ExternalActionAttribute]
    public function insertPasswordGroups(Request $request): JsonResponse
    {
        $insertRequest = InsertPasswordsGroupsRequestDTO::fromRequest($request);
        $this->services->getDatabaseService()->beginTransaction();
        {
            try{
                foreach($insertRequest->getPasswordsGroupsJsons() as $passwordGroupJson){

                    $passwordGroupEntity = PasswordGroup::fromJson($passwordGroupJson);
                    $validationDto       = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($passwordGroupEntity);

                    if( !$validationDto->isSuccess() ){
                        $response = BaseApiResponseDTO::buildInvalidFieldsRequestErrorResponse();
                        $response->setInvalidFields($validationDto->getViolationsWithMessages());

                        $message = "One of the groups entity is invalid";

                        $this->services->getLoggerService()->getLogger()->critical($message, [
                            "jsonUsedForEntity" => $passwordGroupJson,
                            "violations"        => $validationDto->getViolationsWithMessages(),
                        ]);

                        $this->services->getDatabaseService()->rollbackTransaction();
                        return $response->toJsonResponse();
                    }

                    $this->passwordGroupController->save($passwordGroupEntity);
                }

            }catch(Exception| TypeError $e){

                $this->services->getDatabaseService()->rollbackTransaction();
                $this->services->getLoggerService()->logException($e, [
                    "info" => "Could not commit all passwords groups to database!",
                ]);
                throw $e;
            }
        }
        $this->services->getDatabaseService()->commitTransaction();

        return BaseApiResponseDTO::buildOkResponse()->toJsonResponse();
    }

    /**
     * Handles insertion of password groups
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/insert-passwords", name: "insert_passwords", methods: ["POST"])]
    #[ExternalActionAttribute]
    public function insertPasswords(Request $request): JsonResponse
    {
        $insertRequest = InsertPasswordsRequestDTO::fromRequest($request);
        $this->services->getDatabaseService()->beginTransaction();
        {
            try{
                foreach($insertRequest->getPasswordsJsons() as $passwordJson){

                    $passwordEntity = Password::fromJson($passwordJson);
                    $validationDto  = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($passwordEntity);

                    $passwordGroupEntity = $this->passwordGroupController->getOneForId($passwordEntity->getId());
                    $passwordEntity->setGroup($passwordGroupEntity);

                    if( !$validationDto->isSuccess() ){
                        $response = BaseApiResponseDTO::buildInvalidFieldsRequestErrorResponse();
                        $response->setInvalidFields($validationDto->getViolationsWithMessages());

                        $message = "One of the password entity is invalid";

                        $this->services->getLoggerService()->getLogger()->critical($message, [
                            "jsonUsedForEntity" => $passwordJson,
                            "violations"        => $validationDto->getViolationsWithMessages(),
                        ]);

                        $this->services->getDatabaseService()->rollbackTransaction();
                        return $response->toJsonResponse();
                    }

                    $this->passwordController->save($passwordEntity);
                }

            }catch(Exception| TypeError $e){

                $this->services->getDatabaseService()->rollbackTransaction();
                $this->services->getLoggerService()->logException($e, [
                    "info" => "Could not commit all passwords to database!",
                ]);
                throw $e;
            }
        }
        $this->services->getDatabaseService()->commitTransaction();

        return BaseApiResponseDTO::buildOkResponse()->toJsonResponse();
    }
}