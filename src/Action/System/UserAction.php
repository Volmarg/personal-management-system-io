<?php


namespace App\Action\System;
use App\Attribute\Action\InternalActionAttribute;
use App\Controller\Core\Services;
use App\Controller\UserController;
use App\DTO\BaseApiDTO;
use App\DTO\Internal\LoggedInUserDataDTO;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserAction
 * @package App\Action\System
 */
#[Route("/user", name:"user_")]
class UserAction extends AbstractController
{

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @var UserController $userController
     */
    private UserController $userController;

    public function __construct(Services $services, UserController $userController)
    {
        $this->userController = $userController;
        $this->services       = $services;
    }

    /**
     * Will return the @see LoggedInUserDataDto as the json
     * @return JsonResponse
     */
    #[Route("/get-logged-in-user-data", name:"get_logged_in_user_data", methods: [Request::METHOD_GET])]
    #[InternalActionAttribute]
    public function getLoggedInUserData(): JsonResponse
    {
        try{
            $loggedInUser = $this->userController->getLoggedInUser();

            $loggedInUserDataDto = new LoggedInUserDataDto();
            $loggedInUserDataDto->prefillBaseFieldsForSuccessResponse();

            if( empty($loggedInUser) ){
                $loggedInUserDataDto->setLoggedIn(false);
                $loggedInUserDataDto->setAvatar("");
                $loggedInUserDataDto->setShownName("");

                return $loggedInUserDataDto->toJsonResponse();
            }

            $loggedInUserDataDto->setAvatar("");
            $loggedInUserDataDto->setShownName($loggedInUser->getUsername());
            $loggedInUserDataDto->setLoggedIn(true);
        }catch(Exception $e){
            $this->services->getLoggerService()->logException($e);
            return BaseApiDTO::buildInternalServerErrorResponse()->toJsonResponse();
        }

        return $loggedInUserDataDto->toJsonResponse();
    }

    /**
     * Will return the @see LoggedInUserDataDto as the json
     * @return JsonResponse
     */
    #[Route("/invalidate-user", name:"invalidate_user", methods: [Request::METHOD_POST])]
    #[InternalActionAttribute]
    public function invalidateUser(): JsonResponse
    {
        try{
            $this->userController->invalidateUser();

            $isRemoved = $this->services->getFilesService()->removeEncryptionFile();
            if(!$isRemoved){
                throw new Exception("Could not remove the encryption file!");
            }

        }catch(Exception $e){
            $this->services->getLoggerService()->logException($e);
            return BaseApiDTO::buildInternalServerErrorResponse()->toJsonResponse();
        }

        return BaseApiDTO::buildOkResponse()->toJsonResponse();
    }
}