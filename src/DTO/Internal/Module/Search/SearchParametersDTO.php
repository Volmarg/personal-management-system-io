<?php


namespace App\DTO\Internal\Module\Search;

use App\DTO\AbstractDTO;

/**
 * Class SearchParametersDTO
 * @package App\DTO\Internal\Module\Search
 */
class SearchParametersDTO extends AbstractDTO
{
    const KEY_SEARCHED_STRING = "searchedString";
    const KEY_MODULE_NAME     = "moduleName";

    /**
     * @var string $searchedString
     */
    private string $searchedString = "";

    /**
     * @var string $moduleName
     */
    private string $moduleName = "";

    /**
     * @return string
     */
    public function getSearchedString(): string
    {
        return $this->searchedString;
    }

    /**
     * @param string $searchedString
     */
    public function setSearchedString(string $searchedString): void
    {
        $this->searchedString = $searchedString;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     */
    public function setModuleName(string $moduleName): void
    {
        $this->moduleName = $moduleName;
    }

    /**
     * This will build the `SearchParametersDTO` from the provided json
     *
     * @param string $json
     * @return SearchParametersDTO
     */
    public static function fromJson(string $json): SearchParametersDTO
    {
        $dataArray = json_decode($json, true);

        $searchedString = self::checkAndGetKey($dataArray, self::KEY_SEARCHED_STRING, "");
        $moduleName     = self::checkAndGetKey($dataArray, self::KEY_MODULE_NAME, "");

        $dto = new SearchParametersDTO();
        $dto->setSearchedString($searchedString);
        $dto->setModuleName($moduleName);

        return $dto;
    }

}