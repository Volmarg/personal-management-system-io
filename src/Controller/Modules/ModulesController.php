<?php


namespace App\Controller\Modules;


use App\Controller\Core\Services;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModulesController extends AbstractController
{
    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * ModulesController constructor.
     *
     * @param Services $services
     */
    public function __construct(Services $services)
    {
        $this->services = $services;
    }

    // this names are used in translations file
    const MODULE_NAME_PASSWORDS = "passwords";
    const MODULE_NAME_NOTES     = "notes";

    const ALL_MODULES_NAMES = [
        self::MODULE_NAME_PASSWORDS,
        self::MODULE_NAME_NOTES,
    ];

    /**
     * Will return all translated modules names
     */
    public function getAllTranslatedModulesNames(): array
    {
        $modulesNamesWithTranslations = [];
        foreach(self::ALL_MODULES_NAMES as $moduleName){
            $modulesNamesWithTranslations[$moduleName] = $this->services->getTranslator()->trans("modules." . $moduleName);
        }

        return $modulesNamesWithTranslations;
    }

}