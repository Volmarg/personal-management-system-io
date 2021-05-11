<?php


namespace App\Service\Security;

use App\Controller\API\ApiController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfTokenValidatorService
{

    const KEY_CSRF_TOKEN = '_token';

    /**
     * @var CsrfTokenManagerInterface $csrfTokenManager
     */
    private CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * @var ApiController $apiController
     */
    private ApiController $apiController;

    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, ApiController $apiController, LoggerInterface $logger)
    {
        $this->logger           = $logger;
        $this->apiController    = $apiController;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * Checks the validity of a CSRF token.
     *
     * @param string $id    The id used when generating the token
     * @param string $token The actual token sent with the request that should be validated
     */
    public function isCsrfTokenValid(string $id, string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($id, $token));
    }

    /**
     * Will validate the csrf token in the POST request
     * Does not support other types than POST
     *
     * @param Request $request
     * @return bool
     */
    public function validateCsrfTokenInPostRequest(Request $request): bool
    {
        $requestContent = $request->getContent();
        $isJsonValid    = $this->apiController->validateJson($requestContent);

        if(!$isJsonValid){
            return false;
        }

        $dataArray   = json_decode($requestContent, true);
        $csrfToken   = $dataArray[CsrfTokenValidatorService::KEY_CSRF_TOKEN];
        $csrfTokenId = $request->headers->get("csrfTokenId", null);

        $isCsrfTokenValid = $this->isCsrfTokenValid($csrfTokenId, $csrfToken);
        if(!$isCsrfTokenValid){
            $this->logger->warning("Invalid csrf token has been provided", [
                "csrfToken"   => $csrfToken,
                "csrfTokenId" => $csrfTokenId,
            ]);
        }

        return $isCsrfTokenValid;
    }
}