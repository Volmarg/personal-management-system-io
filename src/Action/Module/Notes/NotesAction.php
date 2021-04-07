<?php

namespace App\Action\Module\Notes;

use App\Attribute\Action\InternalActionAttribute;
use App\Controller\Core\Services;
use App\DTO\Internal\Module\Notes\GetNotesForCategoryResponseDto;
use App\Entity\Modules\Notes\MyNote;
use App\Repository\Modules\Notes\NoteCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/module/notes/", name: "module_notes_")]
class NotesAction extends AbstractController
{
    /**
     * @var NoteCategoryRepository $noteCategoryRepository
     */
    private NoteCategoryRepository $noteCategoryRepository;

    /**
     * @var Services $services
     */
    private Services $services;

    public function __construct(NoteCategoryRepository $noteCategoryRepository, Services $services)
    {
        $this->noteCategoryRepository = $noteCategoryRepository;
        $this->services               = $services;
    }

    /**
     * Returns notes for category with given id
     *
     * @param string $categoryId
     * @return JsonResponse
     */
    #[Route("get-for-category/{categoryId}", name: "get_for_category", methods: ["GET"])]
    #[InternalActionAttribute]
    public function getNotesForCategory(string $categoryId): JsonResponse
    {
        $apiResponse = new GetNotesForCategoryResponseDto();
        $apiResponse->prefillBaseFieldsForSuccessResponse();

        $noteCategory = $this->noteCategoryRepository->getOneForId($categoryId);

        if( empty($noteCategory) ){
            $this->services->getLoggerService()->getLogger()->info("No note category was found for give id: {$categoryId}");
            $apiResponse->prefillBaseFieldsForBadRequestResponse();
            return $apiResponse->toJsonResponse();
        }

        $notesJsons = array_map(
            fn(MyNote $note) => $note->toJson(),
            $noteCategory->getNote()->getValues()
        );

        $apiResponse->setNotesJsons($notesJsons);
        return $apiResponse->toJsonResponse();
    }
}