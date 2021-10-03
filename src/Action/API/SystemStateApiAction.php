<?php


namespace App\Action\API;

use App\Attribute\Action\ExternalActionAttribute;
use App\Controller\API\ApiController;
use App\Controller\Core\Services;
use App\Controller\System\SystemStateController;
use App\DTO\BaseApiDTO;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SystemStateApiAction
 * @package App\Action\API
 */
#[Route("/api/system", name:"api_system_")]
class SystemStateApiAction extends ApiAction
{
    /**
     * @var SystemStateController $systemStateController
     */
    private SystemStateController $systemStateController;

    /**
     * SystemStateApiAction constructor.
     *
     * @param SystemStateController $systemStateController
     * @param ApiController $apiController
     * @param Services $services
     */
    public function __construct(SystemStateController $systemStateController, ApiController $apiController, Services $services)
    {
        parent::__construct($apiController, $services);
        $this->systemStateController = $systemStateController;
    }

    /**
     * Handles setting state of transfer as `done`
     *
     * @throws Exception
     */
    #[Route("/mark-state-as-transferred", name: "mark_state_as_transferred")]
    #[ExternalActionAttribute]
    public function markStateAsTransferred(): JsonResponse
    {
        // the transfer must have been allowed first to set the state
        if( $this->systemStateController->isAllowedToInsertData() ){
            $this->systemStateController->setDataIsTransferred();
        }

        $responseDto = BaseApiDTO::buildOkResponse();
        $responseDto->prefillBaseFieldsForSuccessResponse();

        return $responseDto->toJsonResponse();
    }

    /**
     * Handles checking if data can be inserted ata all
     *
     * @throws Exception
     */
    #[Route("/is-allowed-to-insert", name: "is_allowed_to_insert")]
    #[ExternalActionAttribute]
    public function isAllowedToInsert(): JsonResponse
    {
        $responseDto = BaseApiDTO::buildOkResponse();
        if( !$this->systemStateController->isAllowedToInsertData() ){
            $responseDto = BaseApiDTO::buildUnauthorizedResponse();
        }

        $responseDto->prefillBaseFieldsForSuccessResponse();
        return $responseDto->toJsonResponse();
    }
}