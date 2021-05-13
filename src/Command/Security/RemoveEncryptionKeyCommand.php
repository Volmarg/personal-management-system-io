<?php


namespace App\Command\Security;


use App\Controller\Core\ConfigLoader;
use App\Controller\Core\Services;
use App\Controller\UserController;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TypeError;

class RemoveEncryptionKeyCommand extends Command
{
    protected static $defaultName = "pms-io:remove-encryption-key";

    /**
     * @var UserController $userController
     */
    private UserController $userController;

    /**
     * @var ConfigLoader $configLoader
     */
    private ConfigLoader $configLoader;

    /**
     * @var Services $services
     */
    private Services $services;

    public function __construct(UserController $userController, ConfigLoader $configLoader, Services $services, string $name = null)
    {
        parent::__construct($name);
        $this->userController = $userController;
        $this->configLoader   = $configLoader;
        $this->services       = $services;
    }

    protected function configure()
    {
        $this->setDescription("Will remove the encryption key file");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try{
            $maxInactivityTime          = $this->configLoader->getConfigLoaderSystemData()->getMaxInactivityTime();
            $activityExpirationDateTime = (new \DateTime())->modify("-{$maxInactivityTime} MINUTES");

            $isAnyUserStillActive = false;
            $allUsers             = $this->userController->getAllUsers();

            foreach($allUsers as $user){
                if( $user->getLastActivity() > $activityExpirationDateTime ){
                    $isAnyUserStillActive = true;
                    break;
                }
            }

            if($isAnyUserStillActive){
                $this->services->getLoggerService()->getLogger()->warning("There are still some users active - not removing encryption");
                return Command::FAILURE;
            }

            $isRemoved = $this->services->getFilesService()->removeEncryptionFile();
            if(!$isRemoved){
                throw new Exception("Could not remove the encryption file!");
            }

        }catch(Exception | TypeError $e){
            $this->services->getLoggerService()->logException($e);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}