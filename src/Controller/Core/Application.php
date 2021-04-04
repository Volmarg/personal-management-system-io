<?php


namespace App\Controller\Core;


use Psr\Log\LoggerInterface;
use Throwable;

class Application
{

    private LoggerInterface $logger;

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Will log Throwable exception via Monolog
     *
     * @param Throwable $e
     * @param array $dataBag
     */
    public function logException(Throwable $e, array $dataBag = []): void
    {
        $this->logger->critical("Exception was thrown", [
            "exceptionClass"   => get_class($e),
            "exceptionMessage" => $e->getMessage(),
            "exceptionCode"    => $e->getCode(),
            "exceptionTrace"   => $e->getTraceAsString(),
            "dataBag"          => $dataBag,
        ]);
    }

}