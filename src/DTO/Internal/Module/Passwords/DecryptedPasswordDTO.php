<?php


namespace App\DTO\Internal\Module\Passwords;


use App\DTO\BaseApiDTO;
use App\Entity\Modules\Passwords\Password;

/**
 * Consist of the decrypted password from @see Password
 *
 * Class DecryptedPasswordDTO
 * @package App\DTO\Internal\Module\Passwords
 */
class DecryptedPasswordDTO extends BaseApiDTO
{
    const KEY_DECRYPTED_PASSWORD = "decryptedPassword";

    /**
     * @var string $decryptedPassword
     */
    private string $decryptedPassword = "";

    /**
     * @return string
     */
    public function getDecryptedPassword(): string
    {
        return $this->decryptedPassword;
    }

    /**
     * @param string $decryptedPassword
     */
    public function setDecryptedPassword(string $decryptedPassword): void
    {
        $this->decryptedPassword = $decryptedPassword;
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function toArray(): array
    {
        $dataArray                               = parent::toArray();
        $dataArray[self::KEY_DECRYPTED_PASSWORD] = $this->getDecryptedPassword();
        return $dataArray;
    }

}