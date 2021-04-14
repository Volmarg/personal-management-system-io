<?php


namespace App\DTO\Internal;

use App\DTO\BaseApiResponseDTO;

/**
 * This dto transfers the generated csrf token to the frontend
 * With this the forms submitted via VUE are valid and can be handled by internal symfony logic
 *
 * Class CsrfTokenResponseDto
 * @package App\DTO\API\Internal
 */
class CsrfTokenResponseDTO extends BaseApiResponseDTO
{

    const KEY_CSRF_TOKEN = "csrToken";

    /**
     * @var string $csrfToken
     */
    private string $csrfToken = "";

    /**
     * @return string
     */
    public function getCsrfToken(): string
    {
        return $this->csrfToken;
    }

    /**
     * @param string $csrfToken
     */
    public function setCsrfToken(string $csrfToken): void
    {
        $this->csrfToken = $csrfToken;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array                       = parent::toArray();
        $array[self::KEY_CSRF_TOKEN] = $this->getCsrfToken();

        return $array;
    }

}