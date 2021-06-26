<?php


namespace App\Action\API\Module\Passwords;


use App\Action\API\ApiAction;
use App\Attribute\Action\ExternalActionAttribute;
use App\Controller\API\ApiController;
use App\Controller\Core\Services;
use App\Controller\Modules\Passwords\PasswordController;
use App\Controller\Modules\Passwords\PasswordGroupController;
use App\DTO\BaseApiDTO;
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
    #[Route("/insert-groups", name: "insert_groups", methods: [Request::METHOD_POST])]
    #[ExternalActionAttribute(type: ExternalActionAttribute::TYPE_INSERTION)]
    public function insertPasswordGroups(Request $request): JsonResponse
    {
        $this->services->getDatabaseService()->beginTransaction();
        {
            try{
                $this->passwordController->removeAll();
                $this->passwordGroupController->removeAll();

                $insertRequest = InsertPasswordsGroupsRequestDTO::fromRequest($request);
                if( is_null($insertRequest) ){
                    $this->services->getLoggerService()->getLogger()->warning("Could not build the insert request, maybe provided json in request is invalid");
                    return BaseApiDTO::buildBadRequestErrorResponse()->toJsonResponse();
                }

                foreach($insertRequest->getPasswordsGroupsArrays() as $passwordGroupArray){
                    $passwordGroupEntity = PasswordGroup::fromArray($passwordGroupArray);
                    $validationDto       = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($passwordGroupEntity);

                    $this->services->getLoggerService()->getLogger()->info("Inserting group password with id: {$passwordGroupEntity->getId()}");
                    if( !$validationDto->isSuccess() ){
                        $response = BaseApiDTO::buildInvalidFieldsRequestErrorResponse();
                        $response->setInvalidFields($validationDto->getViolationsWithMessages());

                        $message = "One of the groups entity is invalid";

                        $this->services->getLoggerService()->getLogger()->critical($message, [
                            "arrayUsedForEntity" => $passwordGroupArray,
                            "violations"         => $validationDto->getViolationsWithMessages(),
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

        return BaseApiDTO::buildOkResponse()->toJsonResponse();
    }

    /**
     * Handles insertion of password groups
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/insert-passwords", name: "insert_passwords", methods: [Request::METHOD_POST])]
    #[ExternalActionAttribute(type: ExternalActionAttribute::TYPE_INSERTION)]
    public function insertPasswords(Request $request): JsonResponse
    {
        $this->services->getDatabaseService()->beginTransaction();
        {
            try{
                $this->passwordController->removeAll();

                $insertRequest = InsertPasswordsRequestDTO::fromRequest($request);
                if( is_null($insertRequest) ){
                    $this->services->getLoggerService()->getLogger()->warning("Could not build the insert request, maybe provided json in request is invalid");
                    return BaseApiDTO::buildBadRequestErrorResponse()->toJsonResponse();
                }

                foreach($insertRequest->getPasswordsArrays() as $passwordArray){
                    $passwordEntity      = Password::fromArray($passwordArray);
                    $passwordGroupEntity = $this->passwordGroupController->getOneForId($passwordEntity->getGroupId());

                    $passwordEntity->setGroup($passwordGroupEntity);

                    $this->services->getLoggerService()->getLogger()->info("Inserting password");
                    $validationDto = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($passwordEntity);
                    if( !$validationDto->isSuccess() ){
                        $response = BaseApiDTO::buildInvalidFieldsRequestErrorResponse();
                        $response->setInvalidFields($validationDto->getViolationsWithMessages());

                        $message = "One of the password entity is invalid";

                        $this->services->getLoggerService()->getLogger()->critical($message, [
                            "arrayUsedForEntity" => $passwordArray,
                            "violations"         => $validationDto->getViolationsWithMessages(),
                        ]);

                        $this->services->getDatabaseService()->rollbackTransaction();
                        return $response->toJsonResponse();
                    }

                    $this->passwordController->createEntity($passwordEntity);
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

        return BaseApiDTO::buildOkResponse()->toJsonResponse();
    }
}