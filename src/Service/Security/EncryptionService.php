<?php


namespace App\Service\Security;

use App\Controller\System\PmsConfigController;
use Exception;
use SpecShaper\EncryptBundle\Encryptors\OpenSslEncryptor;

/**
 * This class utilizes the package:
 * @link https://github.com/mogilvie/EncryptBundle
 *
 * Class EncryptionService
 * @package App\Service\Security
 */
class EncryptionService
{
    /**
     * @var PmsConfigController $pmsConfigController
     */
    private PmsConfigController $pmsConfigController;

    /**
     * EncryptionService constructor.
     * @param PmsConfigController $pmsConfigController
     */
    public function __construct(PmsConfigController $pmsConfigController)
    {
        $this->pmsConfigController = $pmsConfigController;
    }

    /**
     * @var OpenSslEncryptor $openSslEncryptor
     */
    private OpenSslEncryptor $openSslEncryptor;

    /**
     * Will initialize the state of the service,
     * This cannot be set in the constructor as the logic is way to broad,
     * @throws Exception
     */
    public function initialize()
    {
        $pmsConfigEncryptKey    = $this->pmsConfigController->getEncryptionKey();
        $this->openSslEncryptor = new OpenSslEncryptor($pmsConfigEncryptKey->getValue());
    }

    /**
     * Will decrypt the string
     * @throws Exception
     */
    public function decryptString(string $stringToDecrypt): string
    {
        return $this->openSslEncryptor->decrypt($stringToDecrypt);
    }

}