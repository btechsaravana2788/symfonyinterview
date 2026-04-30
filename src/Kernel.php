<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $confDir = $this->getProjectDir().'/config';

        $container->import($confDir.'/packages/*.yaml');

        $envPackagesDir = $confDir.'/packages/'.$this->environment;
        if (is_dir($envPackagesDir)) {
            $container->import($envPackagesDir.'/*.yaml');
        }

        $container->import($confDir.'/services.yaml');

        $servicesEnvFile = $confDir.'/services_'.$this->environment.'.yaml';
        if (is_file($servicesEnvFile)) {
            $container->import($servicesEnvFile, service: false);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/routes/*.yaml');

        $envRoutesDir = $confDir.'/routes/'.$this->environment;
        if (is_dir($envRoutesDir)) {
            $routes->import($envRoutesDir.'/*.yaml');
        }

        $routes->import($confDir.'/routes.yaml');
    }
}
