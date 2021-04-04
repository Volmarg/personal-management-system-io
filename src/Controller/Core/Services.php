<?php


namespace App\Controller\Core;


use App\Service\Attribute\AttributeReaderService;
use App\Service\Routing\UrlMatcherService;

/**
 * Contains all of the services created for this project
 *
 * Class Services
 * @package App\Controller\Core
 */
class Services
{
    /**
     * @var AttributeReaderService $attributeReader
     */
    private AttributeReaderService $attributeReaderService;

    /**
     * @var UrlMatcherService $urlMatcherService
     */
    private UrlMatcherService $urlMatcherService;

    /**
     * @return AttributeReaderService
     */
    public function getAttributeReader(): AttributeReaderService
    {
        return $this->attributeReaderService;
    }

    public function setAttributeReaderService(AttributeReaderService $attributeReader): void
    {
        $this->attributeReaderService = $attributeReader;
    }

    /**
     * @return UrlMatcherService
     */
    public function getUrlMatcherService(): UrlMatcherService
    {
        return $this->urlMatcherService;
    }

    /**
     * @param UrlMatcherService $urlMatcherService
     */
    public function setUrlMatcherService(UrlMatcherService $urlMatcherService): void
    {
        $this->urlMatcherService = $urlMatcherService;
    }

}