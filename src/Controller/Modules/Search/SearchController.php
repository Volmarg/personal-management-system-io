<?php


namespace App\Controller\Modules\Search;


use App\Controller\Core\Services;
use App\Controller\Modules\ModulesController;
use App\DTO\Internal\Module\Search\SearchParametersDTO;
use App\Entity\Modules\Notes\MyNote;
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
     *
     * @param SearchParametersDTO $searchParametersDto
     * @return array
     * @throws Exception
     */
    public function getSearchResults(SearchParametersDTO $searchParametersDto): array
    {

        // handle empty as concatenation of all possible module search result
        switch($searchParametersDto->getModuleName())
        {
            case ModulesController::MODULE_NAME_NOTES:
            {
                $results      = $this->noteRepository->getNotesContainingStringInTitle($searchParametersDto->getSearchedString());
                $resultsJsons = array_map(
                    fn(MyNote $myNote) => $myNote->toJson(),
                    $results
                );

                return $resultsJsons;
            }

            case ModulesController::MODULE_NAME_PASSWORDS:
            {
                $results = $this->passwordRepository->getPasswordByDescriptionOrUrlContainingUrl($searchParametersDto->getSearchedString());
                $resultsJsons = array_map(
                    fn(Password $password) => $password->toJson(),
                    $results
                );

                return $resultsJsons;
            }

            default:
                $message = "This module is not supported for search action";
                $this->services->getLoggerService()->getLogger()->info($message, [
                    "moduleName" => $searchParametersDto->getModuleName(),
                ]);
                throw new Exception($message);
        }

    }

}