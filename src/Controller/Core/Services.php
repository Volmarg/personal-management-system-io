<?php


namespace App\Controller\Core;


use App\Service\Attribute\AttributeReaderService;
use App\Service\Form\FormService;
use App\Service\Logger\LoggerService;
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
     * @var FormService $formService
     */
    private FormService $formService;

    /**
     * @var LoggerService $loggerService
     */
    private LoggerService $loggerService;

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

    /**
     * @return FormService
     */
    public function getFormService(): FormService
    {
        return $this->formService;
    }

    /**
     * @param FormService $formService
     */
    public function setFormService(FormService $formService): void
    {
        $this->formService = $formService;
    }

    /**
     * @return LoggerService
     */
    public function getLoggerService(): LoggerService
    {
        return $this->loggerService;
    }

    /**
     * @param LoggerService $loggerService
     */
    public function setLoggerService(LoggerService $loggerService): void
    {
        $this->loggerService = $loggerService;
    }

}