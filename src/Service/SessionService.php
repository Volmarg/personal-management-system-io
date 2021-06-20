<?php

namespace App\Service;

use App\Exception\CouldNotUnsetEncryptionKeyException;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use TypeError;

/**
 * Handles setting and getting data from session
 *
 * Class SessionService
 * @package App\Service
 */
class SessionService
{

    /**
     * Setting totally not related key for security purpose
     */
    const SESSION_KEY_ENCRYPTION_KEY = "forcedCacheChecksum";

    /**
     * Will store encryption key in session
     */
    public static function setEncryptionKeyInSession(string $encryptionKey): void
    {
        $session = new Session();
        $session->set(self::SESSION_KEY_ENCRYPTION_KEY, $encryptionKey);
    }

    /**
     * Will return encryption key from session
     */
    public static function getEncryptionKeyInSession(): ?string
    {
        $session = new Session();
        if( !$session->has(self::SESSION_KEY_ENCRYPTION_KEY) ){
            return null;
        }

        $encryptionKey = $session->get(self::SESSION_KEY_ENCRYPTION_KEY);
        return $encryptionKey;
    }

    /**
     * Will check if encryption key is stored in session
     *
     * @return bool
     */
    public static function isValidEncryptionKeyInSession(): bool
    {
        if( !(new Session())->has(self::SESSION_KEY_ENCRYPTION_KEY) ){
            return false;
        }

        if( empty(self::getEncryptionKeyInSession()) ){
            return false;
        }

        return true;
    }

    /**
     * Will remove encryption key from session
     *
     * @throws CouldNotUnsetEncryptionKeyException
     */
    public static function removeEncryptionKeyFromSession(): void
    {
        try{
            $session = new Session();
            $session->remove(self::SESSION_KEY_ENCRYPTION_KEY);

            if( self::isValidEncryptionKeyInSession() ){
                $message = "The session key removal was triggered yet the encryption key is still presen in session!";
                throw new CouldNotUnsetEncryptionKeyException($message, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        }catch(Exception | TypeError $e){
            $message = "Original message: " . $e->getMessage() . ". Trace: " . $e->getTraceAsString();
            throw new CouldNotUnsetEncryptionKeyException($message, $e->getCode());
        }

    }

}