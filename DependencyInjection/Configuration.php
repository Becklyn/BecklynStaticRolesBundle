<?php

namespace Becklyn\StaticRolesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder ()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root("becklyn_static_roles");

        $root
            ->children()
                ->arrayNode("roles")
                    ->useAttributeAsKey("role")
                    ->prototype('array')
                        ->children()
                            ->scalarNode("title")->isRequired()->end()
                            ->scalarNode("description")->end()
                            ->booleanNode("hidden")->defaultFalse()->end()
                            ->arrayNode("included_roles")
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
