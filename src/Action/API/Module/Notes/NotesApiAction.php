<?php


namespace App\Action\API\Module\Notes;

use App\Action\API\ApiAction;
use App\Attribute\Action\ExternalActionAttribute;
use App\Controller\API\ApiController;
use App\Controller\Core\Services;
use App\Controller\Modules\Notes\NotesCategoriesController;
use App\Controller\Modules\Notes\NotesController;
use App\DTO\BaseApiDTO;
use App\DTO\Request\Modules\Notes\InsertNotesCategoriesRequestDTO;
use App\DTO\Request\Modules\Notes\InsertNotesRequestDTO;
use App\Entity\Modules\Notes\MyNote;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TypeError;

/**
 * Action for api calls regarding notes logic insertions
 *
 * Class NotesApiAction
 * @package App\Action\API\Module\Notes
 */
#[Route("/api/notes", name: "api_notes_")]
class NotesApiAction extends ApiAction
{
    /**
     * @var NotesCategoriesController $notesCategoriesController
     */
    private NotesCategoriesController $notesCategoriesController;

    /**
     * @var NotesController $notesController
     */
    private NotesController $notesController;

    /**
     * NotesApiAction constructor.
     * @param ApiController $apiController
     * @param Services $services
     * @param NotesCategoriesController $notesCategoriesController
     * @param NotesController $notesController
     */
    public function __construct(
        ApiController             $apiController,
        Services                  $services,
        NotesCategoriesController $notesCategoriesController,
        NotesController           $notesController
    )
    {
        parent::__construct($apiController, $services);
        $this->notesCategoriesController = $notesCategoriesController;
        $this->notesController           = $notesController;
    }

    /**
     * Endpoint handling insertion of the notes categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/insert-categories", name: "insert_categories", methods: [Request::METHOD_POST])]
    #[ExternalActionAttribute]
    public function insertNotesCategories(Request $request): JsonResponse
    {
        $insertRequest = InsertNotesCategoriesRequestDTO::fromRequest($request);
        $this->services->getDatabaseService()->beginTransaction();
        {
            try{
                foreach($insertRequest->getNotesCategoriesJsons() as $noteCategoryJson){

                    $noteCategoryEntity = MyNote::fromJson($noteCategoryJson);
                    $validationDto      = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($noteCategoryEntity);

                    if( !$validationDto->isSuccess() ){
                        $response = BaseApiDTO::buildInvalidFieldsRequestErrorResponse();
                        $response->setInvalidFields($validationDto->getViolationsWithMessages());

                        $message = "One of the categories entity is invalid";

                        $this->services->getLoggerService()->getLogger()->critical($message, [
                            "jsonUsedForEntity" => $noteCategoryJson,
                            "violations"        => $validationDto->getViolationsWithMessages(),
                        ]);

                        $this->services->getDatabaseService()->rollbackTransaction();
                        return $response->toJsonResponse();
                    }

                    $this->notesController->save($noteCategoryEntity);
                }

            }catch(Exception|TypeError $e){

                $this->services->getDatabaseService()->rollbackTransaction();
                $this->services->getLoggerService()->logException($e, [
                    "info" => "Could not commit all notes to database!",
                ]);
                throw $e;
            }
        }
        $this->services->getDatabaseService()->commitTransaction();

        return BaseApiDTO::buildOkResponse()->toJsonResponse();
    }

    /**
     * Endpoint handles insertion of the notes
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/insert-notes", name: "insert_notes", methods: [Request::METHOD_POST])]
    #[ExternalActionAttribute]
    public function insertNotes(Request $request): JsonResponse
    {
        $insertRequest = InsertNotesRequestDTO::fromRequest($request);
        $this->services->getDatabaseService()->beginTransaction();
        {
            try{
                foreach($insertRequest->getNotesJsons() as $noteJson){

                    $noteEntity = MyNote::fromJson($noteJson);
                    $categoryId = $noteEntity->getDataBag()->get(MyNote::KEY_CATEGORY_ID);

                    try{
                        $category = $this->notesCategoriesController->getOneForId($categoryId);
                    }catch(Exception | TypeError $e){
                        $this->services->getLoggerService()->logException($e, [
                            "info"   => "Exception thrown while trying to find note category for provided id in request",
                            "noteId" => $categoryId,
                        ]);

                        return BaseApiDTO::buildBadRequestErrorResponse()->toJsonResponse();
                    }

                    $noteEntity->setCategory($category);
                    $validationDto = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($noteEntity);

                    if( !$validationDto->isSuccess() ){
                        $response = BaseApiDTO::buildInvalidFieldsRequestErrorResponse();
                        $response->setInvalidFields($validationDto->getViolationsWithMessages());

                        $message = "One of the note entity is invalid";

                        $this->services->getLoggerService()->getLogger()->critical($message, [
                            "jsonUsedForEntity" => $noteJson,
                            "violations"        => $validationDto->getViolationsWithMessages(),
                        ]);

                        $this->services->getDatabaseService()->rollbackTransaction();
                        return $response->toJsonResponse();
                    }

                    $this->notesController->save($noteEntity);
                }

            }catch(Exception|TypeError $e){

                $this->services->getDatabaseService()->rollbackTransaction();
                $this->services->getLoggerService()->logException($e, [
                    "info" => "Could not commit all notes to database!"
                ]);
                throw $e;
            }
        }
        $this->services->getDatabaseService()->commitTransaction();

        return BaseApiDTO::buildOkResponse()->toJsonResponse();
    }

}