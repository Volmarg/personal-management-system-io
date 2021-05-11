<?php

namespace App\Action\Module\Search;

use App\Attribute\Action\InternalActionAttribute;
use App\Controller\Core\Services;
use App\Controller\Modules\Search\SearchController;
use App\DTO\Internal\Module\Search\SearchParametersDTO;
use App\DTO\Internal\Module\Search\SearchResultsDTO;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchAction
 * @package App\Action\Module\Search
 */
#[Route("/module/search/", name: "module_search_")]
class SearchAction
{
    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @var SearchController $searchController
     */
    private SearchController $searchController;

    public function __construct(Services $services, SearchController $searchController)
    {
        $this->searchController = $searchController;
        $this->services         = $services;
    }

    /**
     * Will return search result for given request params
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[InternalActionAttribute]
    #[Route("get-results", name: "get_results", methods: [Request::METHOD_POST])]
    public function getSearchResults(Request $request): JsonResponse
    {
        $json = $request->getContent();
        json_decode($request->getContent());
        if( JSON_ERROR_NONE !== json_last_error() ){
            $message = "Invalid json was provided";
            $this->services->getLoggerService()->getLogger()->critical($message, [
                "requestContent" => $request->getContent(),
            ]);
            throw new Exception($message);
        }

        $searchParametersDto = SearchParametersDTO::fromJson($json);
        $searchResultsJsons  = $this->searchController->getSearchResults($searchParametersDto);

        $response = new SearchResultsDTO();
        $response->setResultsJsons($searchResultsJsons);
        $response->prefillBaseFieldsForSuccessResponse();

        return $response->toJsonResponse();
    }
}