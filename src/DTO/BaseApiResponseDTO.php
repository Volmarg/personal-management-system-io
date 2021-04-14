<?php


namespace App\DTO;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base ResponseDTO used for each response in the API calls
 * Each api dto should extend from this class as the fronted will try to build same dto on its side
 *
 * Class BaseInternalApiResponseDto
 * @package App\DTO\API\Internal
 */
class BaseApiResponseDTO extends AbstractDTO
{
    const KEY_CODE           = "code";
    const KEY_MESSAGE        = "message";
    const KEY_SUCCESS        = "success";
    const KEY_INVALID_FIELDS = "invalidFields";

    const DEFAULT_CODE         = Response::HTTP_BAD_REQUEST;
    const DEFAULT_MESSAGE      = "Bad request";
    const MESSAGE_INVALID_JSON = "INVALID_JSON";
    const MESSAGE_OK           = "OK";

    /**
     * @var int $code
     */
    private int $code = Response::HTTP_BAD_REQUEST;

    /**
     * @var string $message
     */
    private string $message = "";

    /**
     * @var bool $success
     */
    private bool $success = false;

    /**
     * @var array $invalidFields
     */
    private array $invalidFields = [];

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return array
     */
    public function getInvalidFields(): array
    {
        return $this->invalidFields;
    }

    /**
     * @param array $invalidFields
     */
    public function setInvalidFields(array $invalidFields): void
    {
        $this->invalidFields = $invalidFields;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_CODE           => $this->getCode(),
            self::KEY_MESSAGE        => $this->getMessage(),
            self::KEY_SUCCESS        => $this->isSuccess(),
            self::KEY_INVALID_FIELDS => json_encode($this->getInvalidFields()),
        ];
    }

    /**
     * Will set the field of this dto to success response so that classes which extend this method will have
     * the base dto response `set to success`
     */
    public function prefillBaseFieldsForSuccessResponse(): void
    {
        $this->setCode(Response::HTTP_OK);;
        $this->setSuccess(true);
    }

    /**
     * Will set the field of this dto to bad request response so that classes which extend this method will have
     * the base dto response `set to bad request`
     */
    public function prefillBaseFieldsForBadRequestResponse(): void
    {
        $this->setCode(Response::HTTP_BAD_REQUEST);;
        $this->setSuccess(false);
    }

    /**
     * Will build internal server error response
     *
     * @return static
     */
    public static function buildInternalServerErrorResponse(): static
    {
        $dto = new static();
        $dto->setCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        $dto->setSuccess(false);

        return $dto;
    }

    /**
     * Will build internal server error response
     *
     * @param string $message
     * @return static
     */
    public static function buildBadRequestErrorResponse(string $message = ""): static
    {
        $dto = new static();
        $dto->setCode(Response::HTTP_BAD_REQUEST);
        $dto->setSuccess(false);
        $dto->setMessage($message);

        return $dto;
    }

    /**
     * Will build ok response
     */
    public static function buildOkResponse(): static
    {
        $dto = new static();
        $dto->setCode(Response::HTTP_OK);
        $dto->setSuccess(true);

        return $dto;
    }


    /**
     * Will build invalid json response
     *
     * @return static
     */
    public static function buildInvalidJsonResponse(): static
    {
        $dto = static::buildBadRequestErrorResponse();
        $dto->setMessage(self::MESSAGE_INVALID_JSON);
        return $dto;
    }

    /**
     * Will build internal server error response
     *
     * @param string $message
     * @param array  $invalidFields
     * @return static
     */
    public static function buildInvalidFieldsRequestErrorResponse(string $message = "", array $invalidFields = []): static
    {
        $dto = new static();
        $dto->setCode(Response::HTTP_BAD_REQUEST);
        $dto->setSuccess(false);
        $dto->setMessage($message);
        $dto->setInvalidFields($invalidFields);

        return $dto;
    }

    /**
     * @param int $responseCode
     * @return JsonResponse
     */
    public function toJsonResponse(int $responseCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse($this->toArray(), $responseCode);
    }

    /**
     * Will build the dto from json
     *
     * @param string $json
     * @return static
     */
    public static function fromJson(string $json): static
    {
        $dataArray = json_decode($json, true);

        $message = self::checkAndGetKey($dataArray, self::KEY_MESSAGE, self::DEFAULT_MESSAGE);
        $code    = self::checkAndGetKey($dataArray, self::KEY_CODE, self:: DEFAULT_CODE);

        $dto = new BaseApiResponseDTO();
        $dto->setMessage($message);
        $dto->setCode($code);

        return $dto;
    }

}