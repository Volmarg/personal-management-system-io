<?php


namespace App\Action\System;


use App\Controller\Core\Form;
use App\DTO\BaseApiResponseDTO;
use App\DTO\Internal\Form\Security\LoginFormDataDTO;
use App\Entity\User;
use App\Service\Form\FormService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Consist of all user entity
 *
 * Class UserAction
 * @package App\Action\System
 */
#[Route("/system", name: "system_")]
class SecurityAction extends AbstractController
{
    /**
     * @var FormService $formService
     */
    private FormService $formService;

    /**
     * @var Form $form
     */
    private Form $form;

    public function __construct(FormService $formService, Form $form)
    {
        $this->form        = $form;
        $this->formService = $formService;
    }

    /**
     * Handles user login attempt
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route("/handle-login", name: "user_handle_login", methods: ["POST"])]
    public function handleLogin(Request $request): JsonResponse
    {
        $form = $this->formService->handlePostFormForAxiosCall($this->form->getLoginForm(), $request);

        //todo: finish
        if( $form->isSubmitted() && $form->isValid() ){
            /**@var LoginFormDataDTO $user*/
            $loginFormData = $form->getData();
        }

        return BaseApiResponseDTO::buildOkResponse()->toJsonResponse();
    }
}