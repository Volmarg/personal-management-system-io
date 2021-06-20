<?php

namespace App\Command\Security;

use App\Controller\ApiUserController;
use App\Controller\Core\Services;
use App\Entity\ApiUser;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TypeError;

class GenerateApiUserCommand extends Command
{
    protected static $defaultName = "pms-io:create-api-user";

    /**
     * ApiUserController $apiUserController
     */
    private ApiUserController $apiUserController;

    /**
     * @var SymfonyStyle $io
     */
    private SymfonyStyle $io;

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * GenerateApiUserCommand constructor.
     * @param ApiUserController $apiUserController
     * @param Services $services
     * @param string|null $name
     */
    public function __construct(ApiUserController $apiUserController, Services $services, string $name = null)
    {
        parent::__construct($name);
        $this->apiUserController = $apiUserController;
        $this->services          = $services;
    }

    protected function configure()
    {
        $this->setDescription("Will create API user in DB");
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try{
            $login    = $this->io->ask("Enter login: ");
            $password = $this->io->askHidden("Enter the password (privacy - hidden field)");

            if( !empty($this->apiUserController->getOneByUsername($login)) ){
                $this->io->warning("User with this name already exists: ($login) - try again with other login / username");
                return self::SUCCESS;
            }

            $encodedPassword = $this->services->getUserSecurityService()->encodeRawPasswordForApiUserEntity($password);

            $apiUser = new ApiUser();
            $apiUser->setUsername($login);
            $apiUser->setPassword($encodedPassword);

            $this->apiUserController->save($apiUser);

            $this->io->success("Done! Credentials have been saved ind DB");
        }catch(Exception | TypeError $e){
            $this->services->getLoggerService()->logException($e);
            $this->io->success("Failure! Something went wrong while trying to save apiUser");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}