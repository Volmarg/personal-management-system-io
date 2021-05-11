<?php


namespace App\DTO\Internal\Module;

use App\DTO\BaseApiDTO;

/**
 * Class ModulesNamesDTO
 * @package App\DTO\Internal\Module
 */
class ModulesNamesDTO extends BaseApiDTO
{

    const KEY_MODULES_NAMES = "modulesNames";

    /**
     * @var array $modulesNames
     */
    private array $modulesNames = [];

    /**
     * ModulesNamesDTO constructor.
     *
     * @param array $modulesNames
     */
    public function __construct(array $modulesNames)
    {
        $this->modulesNames = $modulesNames;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array                          = parent::toArray();
        $array[self::KEY_MODULES_NAMES] = $this->modulesNames;

        return $array;
    }
}