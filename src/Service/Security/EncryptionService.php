<?php


namespace App\Service\Security;

use App\Controller\System\PmsConfigController;
use Exception;
use SpecShaper\EncryptBundle\Encryptors\OpenSslEncryptor;
use SpecShaper\EncryptBundle\SpecShaperEncryptBundle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var EventDispatcherInterface $eventDispatcher
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * EncryptionService constructor.
     * @param PmsConfigController $pmsConfigController
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(PmsConfigController $pmsConfigController, EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher     = $eventDispatcher;
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
        $this->openSslEncryptor = new OpenSslEncryptor($this->eventDispatcher, $pmsConfigEncryptKey->getValue());
    }

    /**
     * Will decrypt the string
     * @throws Exception
     */
    public function decryptString(string $stringToDecrypt): string
    {
        return $this->openSslEncryptor->decrypt($stringToDecrypt);
    }

    /**
     * Will check if the decryption key is valid
     * Logic extracted directly from @see SpecShaperEncryptBundle
     *
     * @throws Exception
     */
    public function isEncryptionKeyValid(string $key): bool
    {
        $decodedKey     = base64_decode($key);
        $keyLengthOctet = mb_strlen($decodedKey, '8bit');

        return ($keyLengthOctet === 32);
    }
}