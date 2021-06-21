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
use App\Entity\Modules\Notes\Note;
use App\Entity\Modules\Notes\NoteCategory;
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
    #[ExternalActionAttribute(type: ExternalActionAttribute::TYPE_INSERTION)]
    public function insertNotesCategories(Request $request): JsonResponse
    {
        $this->services->getDatabaseService()->beginTransaction();
        {
            try{
                $this->notesController->removeAll();
                $this->notesCategoriesController->removeAll();

                $insertRequest = InsertNotesCategoriesRequestDTO::fromRequest($request);
                if( is_null($insertRequest) ){
                    $this->services->getLoggerService()->getLogger()->warning("Could not build the insert request, maybe provided json in request is invalid");
                    return BaseApiDTO::buildBadRequestErrorResponse()->toJsonResponse();
                }

                foreach($insertRequest->getNotesCategoriesArrays() as $noteCategoryArray){
                    $noteCategoryEntity = NoteCategory::fromArray($noteCategoryArray);
                    $validationDto      = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($noteCategoryEntity);

                    $this->services->getLoggerService()->getLogger()->info("Inserting single note category with id: {$noteCategoryEntity->getId()}");
                    if( !$validationDto->isSuccess() ){
                        $response = BaseApiDTO::buildInvalidFieldsRequestErrorResponse();
                        $response->setInvalidFields($validationDto->getViolationsWithMessages());

                        $message = "One of the categories entity is invalid";

                        $this->services->getLoggerService()->getLogger()->critical($message, [
                            "arrayUsedForEntity" => $noteCategoryArray,
                            "violations"         => $validationDto->getViolationsWithMessages(),
                        ]);

                        $this->services->getDatabaseService()->rollbackTransaction();
                        return $response->toJsonResponse();
                    }

                    $this->notesCategoriesController->save($noteCategoryEntity);
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
    #[ExternalActionAttribute(type: ExternalActionAttribute::TYPE_INSERTION)]
    public function insertNotes(Request $request): JsonResponse
    {
        $this->services->getDatabaseService()->beginTransaction();
        {
            try{
                $this->notesController->removeAll();

                $insertRequest = InsertNotesRequestDTO::fromRequest($request);
                if( is_null($insertRequest) ){
                    $this->services->getLoggerService()->getLogger()->warning("Could not build the insert request, maybe provided json in request is invalid");
                    return BaseApiDTO::buildBadRequestErrorResponse()->toJsonResponse();
                }

                foreach($insertRequest->getNotesArrays() as $noteArray){

                    $noteEntity = Note::fromArray($noteArray);
                    $categoryId = $noteEntity->getDataBag()->get(Note::KEY_CATEGORY_ID);

                    $this->services->getLoggerService()->getLogger()->info("Inserting note");
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
                            "arrayUsedForEntity" => $noteArray,
                            "violations"         => $validationDto->getViolationsWithMessages(),
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