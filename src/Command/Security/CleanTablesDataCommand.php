<?php


namespace App\Command\Security;


use App\Controller\ApiUserController;
use App\Controller\Core\Services;
use App\Controller\Modules\Notes\NotesCategoriesController;
use App\Controller\Modules\Notes\NotesController;
use App\Controller\Modules\Passwords\PasswordController;
use App\Controller\Modules\Passwords\PasswordGroupController;
use App\Controller\System\SystemStateController;
use App\Controller\UserController;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TypeError;

/**
 * This class handles removing data in tables
 *
 * Class CleanTablesDataCommand
 * @package App\Command\Security
 */
class CleanTablesDataCommand extends Command
{
    protected static $defaultName = "pms-io:clean-tables-data";

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
     * @var NotesCategoriesController $notesCategoriesController
     */
    private NotesCategoriesController $notesCategoriesController;

    /**
     * @var NotesController $notesController
     */
    private NotesController $notesController;

    /**
     * @var PasswordGroupController $passwordGroupController
     */
    private PasswordGroupController $passwordGroupController;

    /**
     * @var PasswordController $passwordController
     */
    private PasswordController $passwordController;

    /**
     * @var SystemStateController $settingController
     */
    private SystemStateController $settingController;

    /**
     * @var UserController $userController
     */
    private UserController $userController;

    /**
     * GenerateApiUserCommand constructor.
     * @param NotesCategoriesController $notesCategoriesController
     * @param SystemStateController $settingController
     * @param UserController $userController
     * @param NotesController $notesController
     * @param PasswordGroupController $passwordGroupController
     * @param PasswordController $passwordController
     * @param Services $services
     * @param string|null $name
     */
    public function __construct(
        NotesCategoriesController $notesCategoriesController,
        SystemStateController         $settingController,
        UserController            $userController,
        NotesController           $notesController,
        PasswordGroupController   $passwordGroupController,
        PasswordController        $passwordController,
        Services                  $services,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->notesCategoriesController = $notesCategoriesController;
        $this->settingController         = $settingController;
        $this->userController            = $userController;
        $this->notesController           = $notesController;
        $this->passwordGroupController   = $passwordGroupController;
        $this->passwordController        = $passwordController;
        $this->services                  = $services;
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
            $this->io->info("Started removing data from tables");
            {
                // denny doing anything if any user is still logged in
                if( $this->userController->isAnyUserActive() ){
                    $this->io->info("Someone is still logged in - not removing anything. Stopping here.");
                    return self::SUCCESS;
                }

                // notes
                $this->io->info("Handling notes");

                $this->notesController->removeAll();
                $this->notesCategoriesController->removeAll();

                // passwords
                $this->io->info("Handling passwords");

                $this->passwordController->removeAll();
                $this->passwordGroupController->removeAll();

                // set system state
                $this->settingController->denyDataInsertion();
                $this->settingController->setDataIsNotTransferred();
            }
            $this->io->success("Done! All data has been removed from tables");
        }catch(Exception | TypeError $e){
            $this->services->getLoggerService()->logException($e);
            $this->io->success("Failure! Something went wrong while trying to remove data in tables");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}