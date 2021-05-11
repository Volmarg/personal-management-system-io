<?php

namespace App\DTO\Internal\Module\Search;

use App\DTO\BaseApiDTO;

/**
 * Class SearchResultsDTO
 * @package App\DTO\Internal\Module\Search
 */
class SearchResultsDTO extends BaseApiDTO
{

    const KEY_RESULTS_JSONS = "resultsJsons";

    /**
     * @var string[] $resultsJsons
     */
    private array $resultsJsons = [];

    /**
     * @return string[]
     */
    public function getResultsJsons(): array
    {
        return $this->resultsJsons;
    }

    /**
     * @param string[] $resultsJsons
     */
    public function setResultsJsons(array $resultsJsons): void
    {
        $this->resultsJsons = $resultsJsons;
    }

    /**
     * Returns array string representation of the dto
     *
     * @return array
     */
    public function toArray(): array
    {
        $dataArray = parent::toArray();
        $dataArray[self::KEY_RESULTS_JSONS] = $this->getResultsJsons();

        return $dataArray;
    }

}