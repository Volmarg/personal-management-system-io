<?php

namespace App\Action\Module\Notes;

use App\Attribute\Action\InternalActionAttribute;
use App\Controller\Modules\Notes\NotesCategoriesController;
use App\DTO\Internal\Module\Notes\ParentsChildrenCategoriesHierarchyDTO;
use App\DTO\ParentChildDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contains logic for handling notes categories entries based routes
 *
 * Class NotesCategoriesAction
 * @package App\Action\Module\Notes
 */
#[Route("/module/notes-categories/", name: "module_notes_categories_")]
class NotesCategoriesAction extends AbstractController
{

    /**
     * @var NotesCategoriesController $notesCategoriesController
     */
    private NotesCategoriesController $notesCategoriesController;

    public function __construct(NotesCategoriesController $notesCategoriesController)
    {
        $this->notesCategoriesController = $notesCategoriesController;
    }

    /**
     * Will return notes categories hierarchical structure where one element is nested inside another
     * - that represents the parent/child hierarchy (category and it's parent category)
     *
     * @return JsonResponse
     */
    #[Route("get-parents-children-categories-hierarchy", name: "get_parents_children_categories_hierarchy", methods: ["GET"])]
    #[InternalActionAttribute]
    public function getParentsChildrenCategoriesHierarchy(): JsonResponse
    {
        $apiResponse = new ParentsChildrenCategoriesHierarchyDTO();
        $apiResponse->prefillBaseFieldsForSuccessResponse();

        $plainArrayHierarchy = array_map(
            fn(ParentChildDTO $parentChildDto) => $parentChildDto->toArray(),
            $this->notesCategoriesController->buildParentsChildrenCategoriesHierarchy(true)
        );

        $hierarchyArrayWithRenewedIndex = array_values($plainArrayHierarchy);

        $apiResponse->setHierarchy($hierarchyArrayWithRenewedIndex);
        return $apiResponse->toJsonResponse();
    }

//    /**
//     * Will return all categories without the hierarchy (no relation between parent-child, only categories themselves)
//     *
//     * @return JsonResponse
//     */
//    #[Route("/get-all", name: "get_all", methods: [Request::METHOD_GET])]
//    #[InternalActionAttribute]
//    public function getAllCategories(): JsonResponse
//    {
//
//        return ""; // todo
//    }
}