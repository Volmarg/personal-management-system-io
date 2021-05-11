<?php


namespace App\Action\Symfony;


use App\Attribute\Action\InternalActionAttribute;
use App\Controller\Core\Services;
use App\DTO\BaseApiDTO;
use App\DTO\Internal\CsrfTokenDTO;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route("/system", name: "system_")]
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
     * Will return the @see CsrfTokenDTO containing the csrf token for form submission
     *
     * @param string $tokenId
     * @return JsonResponse
     */
    #[Route("/get-csrf-token/{tokenId}", name: "get_csrf_token", methods: ["GET"])]
    #[InternalActionAttribute]
    public function getCsrfToken(string $tokenId): JsonResponse
    {
        try{
            $token = $this->csrfTokenManager->refreshToken($tokenId);

            $dto = new CsrfTokenDTO();
            $dto->prefillBaseFieldsForSuccessResponse();
            $dto->setCsrfToken($token->getValue());

            return $dto->toJsonResponse();
        }catch(Exception $e){
            $this->services->getLoggerService()->logException($e);
            return BaseApiDTO::buildInternalServerErrorResponse()->toJsonResponse();
        }

    }


}