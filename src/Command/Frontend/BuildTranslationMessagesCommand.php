<?php

namespace App\Command\Frontend;


use App\Controller\Core\ConfigLoader;
use App\Controller\Core\Services;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BuildTranslationMessagesCommand
 * @package App\Command\Frontend
 */
class BuildTranslationMessagesCommand extends Command
{
    protected static $defaultName = 'pms-io:build-frontend-translation-file';

    const TRANSLATION_FILE_EXTENSION_YML = "yaml";

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @var SymfonyStyle $io
     */
    private $io = null;

    /**
     * @var Parser $yamlParser
     */
    private $yamlParser;

    /**
     * @var ConfigLoader $configLoader
     */
    private ConfigLoader $configLoader;

    public function __construct(Services $services, ConfigLoader $configLoader, string $name = null) {
        parent::__construct($name);
        $this->services     = $services;
        $this->yamlParser   = new YamlParser();
        $this->configLoader = $configLoader;
    }

    protected function configure()
    {
        $this
            ->setDescription("This command will get all the translations files from and will build output bundle file usable on front");
    }

    protected function initialize(InputInterface $input, OutputInterface $output) {
        parent::initialize($input, $output);
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note("Starting building translation messages from Yaml file");
        {
            $translationFilesData = $this->getBackendTranslationFilesData();

            if( !empty($translationFilesData) ){
                $this->buildFrontendTranslationFile($translationFilesData);
                $this->validateOutputFrontendTranslationFile();
            }else{
                $message = "Translation data array is empty - does Your files even exist and are located in correct directory?";
                $io->warning($message);
                $this->services->getLoggerService()->getLogger()->warning($message);
            }
        }
        $io->newLine();
        $io->success("Finished building translation messages from Yaml file");

        return 1;
    }

    /**
     * Will get the content of backend yml files
     *
     * @return array|null
     */
    private function getBackendTranslationFilesData(): ?array
    {
        $this->services->getLoggerService()->getLogger()->info("Started getting translation files data");

        $translationsDirectoryExist = file_exists($this->configLoader->getConfigLoaderPaths()->getTranslationBackendFolder());
        if( !$translationsDirectoryExist ){
            $this->services->getLoggerService()->getLogger()->critical("Translations directory does not exist: ",[
                "directory" => $this->configLoader->getConfigLoaderPaths()->getTranslationBackendFolder(),
            ]);
            return null;
        }

        $finder = new Finder();
        $finder->in($this->configLoader->getConfigLoaderPaths()->getTranslationBackendFolder());

        $translationFilesData = [];

        /**
         * Iterate over all files for all languages
         * @var SplFileInfo $file
         */
        foreach( $finder->files() as $file ){
            $groupName       = $file->getRelativePath();
            $fileExtension   = $file->getExtension();
            $translationFile = $file->getRealPath();

            if( self::TRANSLATION_FILE_EXTENSION_YML !== $fileExtension ){
                continue;
            }

            $this->services->getLoggerService()->getLogger()->info("Found file ({$translationFile}) for language ({$groupName})");

            $translationFileData = Yaml::parseFile($translationFile);

            // file might be empty, we must skip these or final parser will add invalid empty {}
            if( is_null($translationFileData) ){
                continue;
            }

            $translationFilesData[] = $translationFileData;
        }

        return $translationFilesData;
    }

    /**
     * Will put the files content in the json file
     *
     * @param array $translationFilesData
     * @return array
     */
    private function buildFrontendTranslationFile(array $translationFilesData): array
    {
        $this->services->getLoggerService()->getLogger()->info("Started building output messages file");

        $outputFilePath          = [];
        $allTranslationFilesData = [];

        // iterate over all groups
        foreach($translationFilesData as $filePath => $translationFileDataArrays ){
            $allTranslationFilesData = array_merge($allTranslationFilesData, $translationFileDataArrays);
        }

        $jsonData = json_encode($allTranslationFilesData);
        file_put_contents($this->configLoader->getConfigLoaderPaths()->getTranslationFrontendOutputFilePath(), $jsonData);

        $this->services->getLoggerService()->getLogger()->info("Finished building output messages file");

        return $outputFilePath;
    }

    /**
     * Will validate the output json translation
     */
    private function validateOutputFrontendTranslationFile(): void
    {
        $frontendTranslationFileContent = file_get_contents($this->configLoader->getConfigLoaderPaths()->getTranslationFrontendOutputFilePath());

        json_decode($frontendTranslationFileContent);
        if( JSON_ERROR_NONE !== json_last_error() ){
            $message = "Output json is not valid, please check it!";
            $this->io->error(json_last_error_msg());
            $this->services->getLoggerService()->getLogger()->critical($message, [
                "jsonLastErrorMessage" => json_last_error_msg()
            ]);
        }

    }
}