<?php
/**
 * Created by PhpStorm.
 * User: duveau
 * Date: 09/02/18
 * Time: 10:52
 */


namespace PasswordManager\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\Config\FileLocator;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Symfony\Component\DependencyInjection\Loader;


class PasswordManagerCoreExtension extends Extension

{


    public function load(array $configs, ContainerBuilder $container)

    {

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yml');

    }

}