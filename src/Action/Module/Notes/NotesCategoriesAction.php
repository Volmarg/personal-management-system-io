<?php

namespace App\Action\Module\Notes;

use App\Attribute\Action\InternalActionAttribute;
use App\Controller\Modules\Notes\NotesCategoriesController;
use App\DTO\Internal\Module\Notes\GetParentsChildrenCategoriesHierarchyResponse;
use App\DTO\ParentChildDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/module/notes-categories/", name: "module_notes_categories_")]
class NotesCategoriesAction extends AbstractController {

    /**
     * @var NotesCategoriesController $notesCategoriesController
     */
    private NotesCategoriesController $notesCategoriesController;

    public function __construct(NotesCategoriesController $notesCategoriesController)
    {
        $this->notesCategoriesController = $notesCategoriesController;
    }

    #[Route("get-parents-children-categories-hierarchy", name: "get_parents_children_categories_hierarchy", methods: ["GET"])]
    #[InternalActionAttribute]
    public function getParentsChildrenCategoriesHierarchy(): JsonResponse
    {
        $apiResponse = new GetParentsChildrenCategoriesHierarchyResponse();
        $apiResponse->prefillBaseFieldsForSuccessResponse();

        $plainArrayHierarchy = array_map(
            fn(ParentChildDTO $parentChildDto) => $parentChildDto->toArray(),
            $this->notesCategoriesController->buildParentsChildrenCategoriesHierarchy(true)
        );

        $apiResponse->setHierarchy($plainArrayHierarchy);
        return $apiResponse->toJsonResponse();
    }

}