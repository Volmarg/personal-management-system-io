<?php

namespace App;

use SpecShaper\EncryptBundle\SpecShaperEncryptBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/'.$this->environment.'/*.yaml');

        // additional configuration loading
        $container->import("../config/packages/config/*.yaml");

        if (is_file(\dirname(__DIR__).'/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_'.$this->environment.'.yaml');
        } elseif (is_file($path = \dirname(__DIR__).'/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }

        $this->setDynamicParameters($container);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/'.$this->environment.'/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(\dirname(__DIR__).'/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } elseif (is_file($path = \dirname(__DIR__).'/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }

    /**
     * Will set dynamic parameters
     *
     * @param ContainerConfigurator $container
     */
    private function setDynamicParameters(ContainerConfigurator $container)
    {
        $this->setEncryptionKey($container);
    }

    /**
     * Will set the encryption key for @see SpecShaperEncryptBundle
     * - this must be done on fly where user provides the encryption key upon login
     * - if wrong key is provided then data won't simply be decrypted properly
     *
     * @param ContainerConfigurator $container
     */
    private function setEncryptionKey(ContainerConfigurator $container)
    {
        // prevent accessing container (throwing exception) when kernel is not yet booted
        if(!$this->booted){
            return;
        }

        $pathToFileWithKey = $this->getContainer()->getParameter("path_to_encryption_file_with_key");
        if( file_exists($pathToFileWithKey) ){
            $key = trim(file_get_contents($pathToFileWithKey));
            if( !empty($key) ){
                $container->parameters()->set("encrypt_key", $key);
            }
        }
    }
}
