<?php

namespace App\Controller\Core;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Env extends AbstractController {

    const APP_SECRET = 'APP_SECRET';

    /**
     * Will return the JWT secret used in APP
     *
     * @return string
     * @throws Exception
     */
    public static function getJwtSecret(): string
    {
        return $_ENV[self::APP_SECRET];
    }

}
