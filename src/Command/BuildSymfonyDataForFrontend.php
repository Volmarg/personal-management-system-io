<?php


namespace App\Command;

use App\Command\Frontend\BuildRoutingMatrixCommand;
use App\Command\Frontend\BuildTranslationMessagesCommand;
use App\Controller\Core\Services;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TypeError;

/**
 * Handles building data used in symfony for frontend
 *
 * Class BuildSymfonyDataForFrontend
 * @package App\Command
 */
class BuildSymfonyDataForFrontend extends Command
{
    protected static $defaultName = 'pms-io:rebuild-frontend-logic';

    /**
     * @var BuildRoutingMatrixCommand $buildRoutingMatrixCommand
     */
    private BuildRoutingMatrixCommand $buildRoutingMatrixCommand;

    /**
     * @var BuildTranslationMessagesCommand $buildTranslationMessagesCommand
     */
    private BuildTranslationMessagesCommand $buildTranslationMessagesCommand;

    /**
     * @var Services $services
     */
    private Services $services;

    public function __construct(
        BuildRoutingMatrixCommand       $buildRoutingMatrixCommand,
        BuildTranslationMessagesCommand $buildTranslationMessagesCommand,
        Services                        $services,
        string $name = null
    )
    {
        parent::__construct($name);

        $this->services                        = $services;
        $this->buildRoutingMatrixCommand       = $buildRoutingMatrixCommand;
        $this->buildTranslationMessagesCommand = $buildTranslationMessagesCommand;
    }

    /**
     * Execute the main logic
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->services->getLoggerService()->getLogger()->info("Started executing frontend logic commands");
        {
            try{
                $this->buildRoutingMatrixCommand->execute($input, $output);
                $this->buildTranslationMessagesCommand->execute($input, $output);
            }catch(Exception | TypeError $e){
                $message = "Something went wrong while calling the frontend logic command";
                $this->services->getLoggerService()->logException($e, [
                    "info"       => $message,
                    "calledFrom" => __CLASS__,
                ]);
                throw new Exception($message);
            }

        }
        $this->services->getLoggerService()->getLogger()->info("Finished executing frontend logic commands");

        return Command::SUCCESS;
    }
}