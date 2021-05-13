<?php


namespace App\Listener;


use App\Controller\Core\Services;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

class CommandListener implements EventSubscriberInterface
{
    const MAP_EXIT_CODE_TO_CONSTANT_NAME = [
          Command::SUCCESS => "SUCCESS",
          Command::FAILURE => "FAILURE",
    ];

    /**
     * @var Services $services
     */
    private Services $services;

    public function __construct(Services $services)
    {
        $this->services = $services;
    }

    public function onConsoleEventTerminate(ConsoleTerminateEvent $event): void{
        $commandName = $event->getCommand()->getName();
        $exitStatus  = self::MAP_EXIT_CODE_TO_CONSTANT_NAME[$event->getExitCode()];

        $this->services->getLoggerService()->getLogger()->info("Command has been terminated: {$commandName}, with status: {$exitStatus}");
    }

    public function onBeforeCommandStart(ConsoleCommandEvent $event): void{
        $commandName = $event->getCommand()->getName();
        $this->services->getLoggerService()->getLogger()->info("Starting command: {$commandName}");
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::TERMINATE => "onConsoleEventTerminate",
            ConsoleEvents::COMMAND   => "onBeforeCommandStart",
        ];
    }
}