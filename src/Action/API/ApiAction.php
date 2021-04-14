<?php


namespace App\Action\API;


use App\Controller\API\ApiController;
use App\Controller\Core\Services;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Should not contain routes
 *
 * Class ApiAction
 * @package App\Action\API
 */
class ApiAction extends AbstractController
{

    /**
     * @var ApiController $apiController
     */
    protected ApiController $apiController;

    /**
     * @var Services $services
     */
    protected Services $services;

    public function __construct(ApiController $apiController, Services $services)
    {
        $this->apiController = $apiController;
        $this->services      = $services;
    }

}