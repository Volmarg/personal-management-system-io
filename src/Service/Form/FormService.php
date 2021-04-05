<?php


namespace App\Service\Form;


use App\Controller\Core\Services;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormService
{

    /**
     * @var Services $services
     */
    private Services $services;

    public function __construct(Services $services)
    {
        $this->services = $services;
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
            $this->services->getLoggerService()->getLogger()->critical($message, [
                'jsonLastErrorMessage' => json_last_error_msg(),
            ]);

            throw new Exception($message, Response::HTTP_BAD_REQUEST);
        }

        return $dataArray;
    }

}