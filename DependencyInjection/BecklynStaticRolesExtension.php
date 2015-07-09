<?php

namespace Becklyn\StaticRolesBundle\DependencyInjection;

use Becklyn\StaticRolesBundle\Role\RoleCollection;
use Becklyn\StaticRolesBundle\Role\RoleCollectionBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


/**
 *
 */
class BecklynStaticRolesExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load (array $config, ContainerBuilder $container)
    {
        // parse configuration
        $parsedConfiguration = $this->processConfiguration(new Configuration(), $config);

        // load services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // build role collection
        $builder = new RoleCollectionBuilder();

        $roleCollectionDefinition = $container->getDefinition("becklyn.static_roles.role_collection");
        $roleCollectionDefinition->replaceArgument(0, $builder->prepareRoleCollection($parsedConfiguration["roles"]));
    }
}
