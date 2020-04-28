<?php declare(strict_types=1);

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
        $treeBuilder = new TreeBuilder("becklyn_static_roles");

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode("roles")
                    ->useAttributeAsKey("role")
                    ->prototype('array')
                        ->children()
                            ->scalarNode("title")->isRequired()->end()
                            ->scalarNode("description")->end()
                            ->booleanNode("hidden")->defaultFalse()->end()
                            ->arrayNode("included_roles")
                                ->validate()
                                    ->ifTrue(function (array $roles) { return !empty($roles); })
                                    ->thenInvalid("Don't define your included roles in the `becklyn_static_roles` config, but in symfony's role hierarchy instead")
                                ->end()
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode("tags")
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode("actions")
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
