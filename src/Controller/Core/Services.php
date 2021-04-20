<?php


namespace App\Controller\Core;


use App\Service\Attribute\AttributeReaderService;
use App\Service\Database\DatabaseService;
use App\Service\Form\FormService;
use App\Service\Logger\LoggerService;
use App\Service\Routing\UrlMatcherService;
use App\Service\Security\EncryptionService;
use App\Service\Validation\ValidationService;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @var TranslatorInterface $translator
     */
    private TranslatorInterface $translator;

    /**
     * @var ValidationService $validationService
     */
    private ValidationService $validationService;

    /**
     * @var DatabaseService $databaseService
     */
    private DatabaseService $databaseService;

    /**
     * @var EncryptionService $encryptionService
     */
    private EncryptionService $encryptionService;

    /**
     * @return AttributeReaderService
     */
    public function getAttributeReader(): AttributeReaderService
    {
        return $this->attributeReaderService;
    }

    /**
     * @param AttributeReaderService $attributeReader
     */
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

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    /**
     * @return ValidationService
     */
    public function getValidationService(): ValidationService
    {
        return $this->validationService;
    }

    /**
     * @param ValidationService $validationService
     */
    public function setValidationService(ValidationService $validationService): void
    {
        $this->validationService = $validationService;
    }

    /**
     * @return DatabaseService
     */
    public function getDatabaseService(): DatabaseService
    {
        return $this->databaseService;
    }

    /**
     * @param DatabaseService $databaseService
     */
    public function setDatabaseService(DatabaseService $databaseService): void
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @return EncryptionService
     */
    public function getEncryptionService(): EncryptionService
    {
        return $this->encryptionService;
    }

    /**
     * @param EncryptionService $encryptionService
     */
    public function setEncryptionService(EncryptionService $encryptionService): void
    {
        $this->encryptionService = $encryptionService;
    }

}