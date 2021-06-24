<?php

namespace App\Action\Module;


use App\Controller\Modules\ModulesController;
use App\Controller\System\SystemStateController;
use App\DTO\BaseApiDTO;
use App\DTO\Internal\Module\ModulesNamesDTO;
use Doctrine\DBAL\Exception;
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

    /**
     * @var SystemStateController $systemStateController
     */
    private SystemStateController $systemStateController;

    /**
     * ModuleAction constructor.
     *
     * @param ModulesController $modulesController
     * @param SystemStateController $settingController
     */
    public function __construct(
        ModulesController     $modulesController,
        SystemStateController $settingController
    )
    {
        $this->systemStateController = $settingController;
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

    /**
     * Return information if there are any records in DB or not
     * 200 - yes there are
     * 404 - no, there are none
     *
     * Also, keep in mind, the validation in here has been skipped as there are some issues when putting the route to POST
     * and then using non AXIOS call (vanilla JS) and this results in exceptions / loops
     *
     * @param Request $request
     * @param string $isFirstCall
     * @return JsonResponse
     * @throws Exception
     */
    #[Route("/is-module-data-available/{isFirstCall}", name: "is_module_data_available", methods: [Request::METHOD_GET])]
    public function isModulesDataAvailable(Request $request, string $isFirstCall): JsonResponse
    {
        /**
         * The sleep was added to prevent excessive call to this route, as frontend makes while() loop
         * as long as nothing is returned (yes socked could be added here - but not for such small thing/project)
         */
        if(0 == $isFirstCall){
            sleep(5);
        }else{
            $this->systemStateController->allowDataInsertion();
        }

        if( $this->systemStateController->isDataTransferred() ){
            return BaseApiDTO::buildOkResponse()->toJsonResponse();
        }

        return BaseApiDTO::buildNotFoundResponse()->toJsonResponse();
    }

}