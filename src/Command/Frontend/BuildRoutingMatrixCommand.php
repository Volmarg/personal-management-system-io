<?php


namespace App\Command\Frontend;

use App\Controller\Core\ConfigLoader;
use App\Controller\Core\Services;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;
use TypeError;

/**
 * This command handles building json file which consist of backed (symfony) routes,
 * This way the urls can be changed any moment without the need to update these also on front,
 * Just like it works in symfony - names must be always changed manually, same goes to logic related to parameters
 *
 * Class BuildRoutingMatrixCommand
 * @package App\Command\Frontend
 */
class BuildRoutingMatrixCommand extends Command
{

    protected static $defaultName = 'pms-io:build-frontend-routing-file';

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @var SymfonyStyle $io
     */
    private SymfonyStyle $io;

    /**
     * @var RouterInterface $router
     */
    private RouterInterface $router;

    /**
     * @var ConfigLoader $configLoader
     */
    private ConfigLoader $configLoader;

    public function __construct(
        Services        $services,
        RouterInterface $router,
        ConfigLoader    $configLoader,
        string $name    = null)
    {
        parent::__construct($name);
        $this->configLoader = $configLoader;
        $this->services     = $services;
        $this->router       = $router;
    }

    protected function configure()
    {
        $this->setDescription("Will build matrix of Symfony Backend routing - used later in fronted by Vue");
    }

    /**
     * Initialize the logic
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
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
        $this->services->getLoggerService()->getLogger()->info("Started building routing file for frontend");
        {
            try{

                $routesNamesToPaths = [];
                $routesCollection   = $this->router->getRouteCollection()->all();

                foreach($routesCollection as $routeName => $route){
                    $routesNamesToPaths[$routeName] = $this->normalizePathForVueRouter($route->getPath());
                }

                $jsonRoutesMatrix = json_encode($routesNamesToPaths);
                file_put_contents($this->configLoader->getConfigLoaderPaths()->getRoutingFrontendFilePath(), $jsonRoutesMatrix);

            }catch(Exception | TypeError $e){
                $message = "Something went wrong while building the file";
                $this->services->getLoggerService()->logException($e, [
                    "info"       => $message,
                    "calledFrom" => __CLASS__,
                ]);
                throw new Exception($message);
            }

        }
        $this->services->getLoggerService()->getLogger()->info("Started building routing file for frontend");

        return Command::SUCCESS;
    }

    /**
     * Handles transforming paths to make them work with vue
     */
    private function normalizePathForVueRouter(string $path): string
    {
        //While Symfony uses {param}, vue router uses :param
        $normalizedPath = preg_replace("#\{(.*)\}#", ":$1", $path);
        return $normalizedPath;
    }

}