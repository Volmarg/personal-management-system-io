<?php

namespace App\Command\Security;

use App\Controller\Core\Services;
use App\Controller\UserController;
use App\Entity\User;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TypeError;

/**
 * I know that it Would be safer to send the credentials from PMS but...
 * the PMS will need to have access to internet (sending data only) but still...
 * don't want to have a slightest chance that password will leak during transfer
 *
 * PMS is the weak chain here - has less encryption / security measures than here
 * Still is IP protected etc, so all should be fine, just don't want to risk due to weakest chain link rule
 *
 * Class GenerateAdminUserCommand
 * @package App\Command\Security
 */
class GenerateAdminUserCommand extends Command
{
    protected static $defaultName = "pms-io:create-admin-user";

    /**
     * UserController $userController
     */
    private UserController $userController;

    /**
     * @var SymfonyStyle $io
     */
    private SymfonyStyle $io;

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @param UserController $userController
     * @param Services $services
     * @param string|null $name
     */
    public function __construct(UserController $userController, Services $services, string $name = null)
    {
        parent::__construct($name);
        $this->userController = $userController;
        $this->services       = $services;
    }

    protected function configure()
    {
        $this->setDescription("Will create user in DB");
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

            if( !empty($this->userController->getOneByUsername($login)) ){
                $this->io->warning("User with this name already exists: ($login) - try again with other login / username");
                return self::SUCCESS;
            }

            $encodedPassword = $this->services->getUserSecurityService()->encodeRawPasswordForUserEntity($password);

            $user = new User();
            $user->setUsername($login);
            $user->setPassword($encodedPassword);
            $user->setRoles([User::ROLE_ADMIN]);

            $this->userController->save($user);

            $this->io->success("Done! Credentials have been saved ind DB");
        }catch(Exception | TypeError $e){
            $this->services->getLoggerService()->logException($e);
            $this->io->success("Failure! Something went wrong while trying to save user");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}