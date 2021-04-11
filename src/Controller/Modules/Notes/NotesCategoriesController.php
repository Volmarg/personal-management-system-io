<?php


namespace App\Controller\Modules\Notes;


use App\DTO\ParentChildDTO;
use App\Entity\Modules\Notes\MyNoteCategory;
use App\Repository\Modules\Notes\NoteCategoryRepository;

class NotesCategoriesController
{
    /**
     * @var NoteCategoryRepository $noteCategoryRepository
     */
    private NoteCategoryRepository $noteCategoryRepository;

    public function __construct(NoteCategoryRepository $noteCategoryRepository)
    {
        $this->noteCategoryRepository = $noteCategoryRepository;
    }

    /**
     * This function returns array of relations between categories (parent/child)
     *
     * @param bool $returnChildrenAsPlainArrays
     * @return ParentChildDTO[]
     */
    public function buildParentsChildrenCategoriesHierarchy(bool $returnChildrenAsPlainArrays = false): array
    {
        $categoriesDepths     = $this->buildCategoriesDepths();
        $parentsChildrenDtos  = [];
        $skippedCategoriesIds = [];

        foreach( $categoriesDepths as $categoryId => $depth ){

            $category   = $this->noteCategoryRepository->find($categoryId);
            $categoryId = $category->getId();

            $childCategoriesIds = $this->noteCategoryRepository->getChildrenCategoriesIdsForCategoriesIds([$categoryId]);
            $parentChildDto     = $this->buildParentChildDtoForHierarchy($category, $depth, $returnChildrenAsPlainArrays);

            //if we have a children then we already added it to parent so we don't want it as separated being
            $skippedCategoriesIds = array_merge($skippedCategoriesIds, $childCategoriesIds);

            if( in_array($categoryId, $skippedCategoriesIds) ){
                continue;
            }

            $parentsChildrenDtos[] = $parentChildDto;
        }

        // sort alphabetically by name
        uasort($parentsChildrenDtos, fn(ParentChildDTO $currentElement, ParentChildDTO $nextElement) =>
            $currentElement->getName() > $nextElement->getName()
        );

        return $parentsChildrenDtos;
    }

    /**
     * Will find one note category for id or null if nothing is found
     *
     * @param int $id
     * @return MyNoteCategory|null
     */
    public function getOneForId(int $id): ?MyNoteCategory
    {
        return $this->noteCategoryRepository->getOneForId($id);
    }

    /**
     * Build array where key is categoryId and value is depth level
     *
     * @return array
     */
    private function buildCategoriesDepths(): array
    {
        $notesCategories  = $this->noteCategoryRepository->findAll();
        $categoriesDepths = [];

        foreach( $notesCategories as $category ){
            $depth      = 0;
            $categoryId = $category->getId();

            $hasParent                = !empty($category->getParentId());
            $currentlyCheckedCategory = $category;
            while( $hasParent ){
                $parentId = $currentlyCheckedCategory->getParentId();

                if( empty($parentId) ){
                    break;
                }

                $parentCategory           = $this->noteCategoryRepository->find($parentId);
                $currentlyCheckedCategory = $parentCategory;

                $depth++;
            }

            $categoriesDepths[$categoryId] = $depth;
        }
        asort($categoriesDepths); // required to prevent child categories with depth 1+ being added to root (depth 0)
        return $categoriesDepths;
    }

    /**
     * Recursive call must be used here as category can have children and these children can also have children and so on.
     *
     * @param MyNoteCategory $category
     * @param int $depth
     * @param bool $returnChildrenAsPlainArrays
     * @return ParentChildDTO
     */
    private function buildParentChildDtoForHierarchy(MyNoteCategory $category, int $depth, bool $returnChildrenAsPlainArrays = false): ParentChildDTO
    {
        $parentChildDtos = [];

        $categoryId   = $category->getId();
        $categoryName = $category->getName();

        $childCategories = $this->noteCategoryRepository->getChildrenCategoriesForCategoriesIds([$categoryId]);

        foreach($childCategories as $childCategory){
            $childDepth        = $depth +1;
            $parentChildDto    = $this->buildParentChildDtoForHierarchy($childCategory, $childDepth, $returnChildrenAsPlainArrays);

            if($returnChildrenAsPlainArrays){
                $parentChildDto = $parentChildDto->toArray();
            }
            $parentChildDtos[] = $parentChildDto;
        }

        $parentChildDto = new ParentChildDTO();
        $parentChildDto->setId($categoryId);
        $parentChildDto->setName($categoryName);
        $parentChildDto->setDepth($depth);
        $parentChildDto->setChildren($parentChildDtos);

        return $parentChildDto;
    }

}