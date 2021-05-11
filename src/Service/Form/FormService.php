<?php

namespace App\Service\Form;

use App\Controller\Core\Services;
use App\DTO\Internal\ValidationResultDTO;
use App\Service\Validation\ValidationService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormService
{

    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * @var ValidationService $validationService
     */
    private ValidationService $validationService;

    public function __construct(LoggerInterface $logger, ValidationService $validationService)
    {
        $this->validationService = $validationService;
        $this->logger            = $logger;
    }

    /**
     * Will handle the post submitted form by processing the data sent via axios post request
     * - will set the data bag,
     * - will set the csrf_token
     *
     * @param FormInterface $form
     * @param Request $request
     * @return FormInterface
     * @throws Exception
     */
    public function handlePostFormForAxiosCall(FormInterface $form, Request $request): FormInterface
    {
        $prefilledRequestDataBag = $this->jsonToRequestDataBag($request->getContent());

        $request->request->set($form->getName(), $prefilledRequestDataBag);
        $form->handleRequest($request);

        return $form;
    }

    /**
     * Will return violations of the object added to the `data_class`
     *
     * @param FormInterface $form
     * @return ValidationResultDTO
     */
    public function getFormViolations(FormInterface $form): ValidationResultDTO
    {
        $validationResult = $this->validationService->validateAndReturnArrayOfInvalidFieldsWithMessages($form->getData());
        return $validationResult;
    }

    /**
     * Will output an array which can be inserted into the @see Request::request::set
     * Such request can be then passed to proper form @see FormInterface::handleRequest()
     * With this - data sent via axios post can be processed like it normally should like via standard POST call
     *
     * @param string $json
     * @throws Exception
     * @return array
     */
    private function jsonToRequestDataBag(string $json): array
    {
        $dataArray = json_decode($json, true);

        if( JSON_ERROR_NONE !== json_last_error() ){
            $message = "Provided json is not valid";
            $this->logger->critical($message, [
                'jsonLastErrorMessage' => json_last_error_msg(),
            ]);

            throw new Exception($message, Response::HTTP_BAD_REQUEST);
        }

        return $dataArray;
    }

}