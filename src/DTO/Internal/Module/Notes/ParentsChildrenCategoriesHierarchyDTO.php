<?php

namespace App\DTO\Internal\Module\Notes;

use App\DTO\BaseApiDTO;
use App\DTO\ParentChildDTO;

class ParentsChildrenCategoriesHierarchyDTO extends BaseApiDTO {

    const KEY_HIERARCHY = "hierarchy";

    /**
     * @var ParentChildDTO[]
     */
    private array $hierarchy = [];

    /**
     * @return ParentChildDTO[]
     */
    public function getHierarchy(): array
    {
        return $this->hierarchy;
    }

    /**
     * @param array $hierarchy
     */
    public function setHierarchy(array $hierarchy): void
    {
        $this->hierarchy = $hierarchy;
    }

    /**
     * Array representation of given response
     *
     * @return array
     */
    public function toArray(): array
    {

        $dataArray = parent::toArray();
        $dataArray[self::KEY_HIERARCHY] = $this->getHierarchy();

        return $dataArray;
    }
}