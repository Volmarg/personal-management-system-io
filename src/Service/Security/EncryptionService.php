<?php


namespace App\Service\Security;

use Exception;
use SpecShaper\EncryptBundle\Encryptors\EncryptorInterface;
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
     * @var EventDispatcherInterface $eventDispatcher
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @var EncryptorInterface $encryptor
     */
    private EncryptorInterface $encryptor;

    /**
     * EncryptionService constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param EncryptorInterface $encryptor
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, EncryptorInterface $encryptor)
    {
        $this->encryptor       = $encryptor;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Will decrypt the string
     *
     * @throws Exception
     */
    public function decryptString(string $stringToDecrypt): string
    {
        return $this->encryptor->decrypt($stringToDecrypt);
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