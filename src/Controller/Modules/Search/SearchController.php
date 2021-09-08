<?php


namespace App\Controller\Modules\Search;


use App\Controller\Core\Services;
use App\Controller\Modules\ModulesController;
use App\DTO\Internal\Module\Search\SearchParametersDTO;
use App\Entity\Modules\Notes\Note;
use App\Entity\Modules\Passwords\Password;
use App\Repository\Modules\Notes\NoteRepository;
use App\Repository\Modules\Passwords\PasswordRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @var NoteRepository $noteRepository
     */
    private NoteRepository $noteRepository;
    /**
     * @var PasswordRepository $passwordRepository
     */
    private PasswordRepository $passwordRepository;

    public function __construct(
        Services           $services,
        NoteRepository     $noteRepository,
        PasswordRepository $passwordRepository
    )
    {
        $this->noteRepository     = $noteRepository;
        $this->passwordRepository = $passwordRepository;
        $this->services           = $services;
    }

    /**
     * Will return the search results for given parameters
     * It's required to search in entities as these are returned decrypted from DB
     * else searching in DB is done over the encrypted values which will never return proper value
     *
     * @param SearchParametersDTO $searchParametersDto
     * @return array
     * @throws Exception
     */
    public function getSearchResults(SearchParametersDTO $searchParametersDto): array
    {
        $resultsJsons = [];

        // handle empty as concatenation of all possible module search result
        switch($searchParametersDto->getModuleName())
        {
            case ModulesController::MODULE_NAME_NOTES:

                $results = $this->noteRepository->getAll();
                foreach($results as $note) {
                    if( stristr($note->getTitle(), $searchParametersDto->getSearchedString()) ){
                    $resultsJsons[] = $note->toJson();
                }
            }
            break;

            case ModulesController::MODULE_NAME_PASSWORDS:
            {
                $results = $this->passwordRepository->getAll();
                foreach($results as $note) {
                    if (
                            stristr($note->getDescription(), $searchParametersDto->getSearchedString())
                        ||  stristr($note->getUrl(), $searchParametersDto->getSearchedString())
                    ) {
                        $resultsJsons[] = $note->toJson();
                    }
                }
            }
            break;

            default:
                $message = "This module is not supported for search action";
                $this->services->getLoggerService()->getLogger()->info($message, [
                    "moduleName" => $searchParametersDto->getModuleName(),
                ]);
                throw new Exception($message);
        }

        return $resultsJsons;
    }

}