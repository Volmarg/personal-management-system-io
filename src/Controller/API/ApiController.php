<?php


namespace App\Controller\API;


use App\Controller\Core\Services;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
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
     * Will validate the provided json string and return bool value:
     * - true if everything is ok
     * - false if something went wrong
     *
     * @param string $json
     * @return bool
     */
    public function validateJson(string $json): bool
    {
        json_decode($json);
        if( JSON_ERROR_NONE !== json_last_error() ){
            $this->services->getLoggerService()->getLogger()->critical("Provided json is not valid", [
                'jsonLastErrorMsg' => json_last_error_msg(),
            ]);

            return false;
        }

        return true;
    }

}