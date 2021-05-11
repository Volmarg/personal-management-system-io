<?php

namespace App\Action\Module;


use App\Controller\Modules\ModulesController;
use App\DTO\Internal\Module\ModulesNamesDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/module", name: "module_")]
class ModuleAction
{
    /**
     * @var ModulesController $modulesController
     */
    private ModulesController $modulesController;

    public function __construct(ModulesController $modulesController)
    {
        $this->modulesController = $modulesController;
    }

    /**
     * Returns translated modules names
     * @return JsonResponse
     */
    #[Route("/search/get-all-translated-modules-names", name: "search_get_all_translated_modules_names", methods: [Request::METHOD_GET])]
    public function getAllTranslatedModulesNames(): JsonResponse
    {
        $responseDto = new ModulesNamesDTO($this->modulesController->getAllTranslatedModulesNames());
        $responseDto->prefillBaseFieldsForSuccessResponse();

        return $responseDto->toJsonResponse();
    }

}