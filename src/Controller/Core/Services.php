<?php


namespace App\Controller\Core;


use App\Service\Attribute\AttributeReaderService;
use App\Service\CookiesService;
use App\Service\Database\DatabaseService;
use App\Service\Files\FilesService;
use App\Service\Form\FormService;
use App\Service\Logger\LoggerService;
use App\Service\Routing\UrlMatcherService;
use App\Service\Security\CsrfTokenValidatorService;
use App\Service\Security\EncryptionService;
use App\Service\Security\UserSecurityService;
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
     * @var UserSecurityService $userSecurityService
     */
    private UserSecurityService $userSecurityService;

    /**
     * @var CsrfTokenValidatorService $csrfTokenValidatorService
     */
    private CsrfTokenValidatorService $csrfTokenValidatorService;

    /**
     * @var FilesService $filesService
     */
    private FilesService $filesService;

    /**
     * @var CookiesService $cookiesService
     */
    private CookiesService $cookiesService;

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

    /**
     * @return UserSecurityService
     */
    public function getUserSecurityService(): UserSecurityService
    {
        return $this->userSecurityService;
    }

    /**
     * @param UserSecurityService $userSecurityService
     */
    public function setUserSecurityService(UserSecurityService $userSecurityService): void
    {
        $this->userSecurityService = $userSecurityService;
    }

    /**
     * @return CsrfTokenValidatorService
     */
    public function getCsrfTokenValidatorService(): CsrfTokenValidatorService
    {
        return $this->csrfTokenValidatorService;
    }

    /**
     * @param CsrfTokenValidatorService $csrfTokenValidatorService
     */
    public function setCsrfTokenValidatorService(CsrfTokenValidatorService $csrfTokenValidatorService): void
    {
        $this->csrfTokenValidatorService = $csrfTokenValidatorService;
    }

    /**
     * @return FilesService
     */
    public function getFilesService(): FilesService
    {
        return $this->filesService;
    }

    /**
     * @param FilesService $filesService
     */
    public function setFilesService(FilesService $filesService): void
    {
        $this->filesService = $filesService;
    }

    /**
     * @return CookiesService
     */
    public function getCookiesService(): CookiesService
    {
        return $this->cookiesService;
    }

    /**
     * @param CookiesService $cookiesService
     */
    public function setCookiesService(CookiesService $cookiesService): void
    {
        $this->cookiesService = $cookiesService;
    }

}