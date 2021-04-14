<?php


namespace App\Action\Symfony;


use App\Controller\Core\Services;
use App\DTO\BaseApiResponseDTO;
use App\DTO\Internal\CsrfTokenResponseDTO;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route("/api-internal", name: "api_internal_")]
class FormInternalApiAction extends AbstractController
{

    /**
     * @var CsrfTokenManagerInterface $csrfTokenManager
     */
    private CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * @var Services $services
     */
    private Services $services;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, Services $services)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->services         = $services;
    }

    /**
     * Will return the @param string $formName
     * @return JsonResponse
     *@see CsrfTokenResponseDTO containing the csrf token for form submission
     *
     */
    #[Route("/get-csrf-token/{formName}", name: "get-csrf-token", methods: ["GET"])]
    public function getCsrfToken(string $formName): JsonResponse
    {
        try{
            $token = $this->csrfTokenManager->getToken($formName);

            $dto = new CsrfTokenResponseDTO();
            $dto->prefillBaseFieldsForSuccessResponse();
            $dto->setCsrfToken($token);

            return $dto->toJsonResponse();
        }catch(Exception $e){
            $this->services->getLoggerService()->logException($e);
            return BaseApiResponseDTO::buildInternalServerErrorResponse()->toJsonResponse();
        }

    }


}