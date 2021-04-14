<?php


namespace App\Action\API\Module\Notes;

use App\Action\API\ApiAction;
use App\Controller\API\ApiController;
use App\Controller\Core\Services;
use App\Controller\Modules\Notes\NotesCategoriesController;
use App\Controller\Modules\Notes\NotesController;
use App\DTO\BaseApiResponseDTO;
use App\DTO\Request\InsertNotesCategoriesRequestDTO;
use App\DTO\Request\InsertNotesRequestDTO;
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
    public function __construct(ApiController $apiController, Services $services, NotesCategoriesController $notesCategoriesController, NotesController $notesController)
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
    #[Route("/insert-categories", name: "insert_categories")]
    public function insertNotesCategories(Request $request): JsonResponse
    {
        $json        = $request->getContent();
        $isJsonValid = $this->apiController->validateJson($json);
        if( !$isJsonValid){
            $this->services->getLoggerService()->getLogger()->warning("Provided json in request is not valid");
            return BaseApiResponseDTO::buildInvalidJsonResponse()->toJsonResponse();
        }

        $insertRequest = InsertNotesCategoriesRequestDTO::fromRequest($request);

        $this->services->getDatabaseService()->beginTransaction();
        {
            try{
                foreach($insertRequest->getNotesCategoriesJsons() as $noteCategoryJson){

                    $noteCategoryEntity = MyNote::fromJson($noteCategoryJson);
                    $validationDto      = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($noteCategoryEntity);

                    if( !$validationDto->isSuccess() ){
                        $response = BaseApiResponseDTO::buildInvalidFieldsRequestErrorResponse();
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

        return BaseApiResponseDTO::buildOkResponse()->toJsonResponse();
    }

    /**
     * Endpoint handles insertion of the notes
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/insert-notes", name: "insert_notes")]
    public function insertNotes(Request $request): JsonResponse
    {
        $json        = $request->getContent();
        $isJsonValid = $this->apiController->validateJson($json);
        if( !$isJsonValid){
            $this->services->getLoggerService()->getLogger()->warning("Provided json in request is not valid");
            return BaseApiResponseDTO::buildInvalidJsonResponse()->toJsonResponse();
        }

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

                        return BaseApiResponseDTO::buildBadRequestErrorResponse()->toJsonResponse();
                    }

                    $noteEntity->setCategory($category);
                    $validationDto = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($noteEntity);

                    if( !$validationDto->isSuccess() ){
                        $response = BaseApiResponseDTO::buildInvalidFieldsRequestErrorResponse();
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

        return BaseApiResponseDTO::buildOkResponse()->toJsonResponse();
    }

}