<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * Handles cookies related logic
 *
 * Class CookiesService
 * @package App\Service
 */
class CookiesService
{

    const USER_SESSION_ID_KEY = 'PHPSESSID';

    /**
     * Will check if user session id cookie exist in request
     *
     * @param Request $request
     * @return bool
     */
    public static function isUserSessionCookieSet(Request $request): bool
    {
        return $request->cookies->has(self::USER_SESSION_ID_KEY);
    }

}