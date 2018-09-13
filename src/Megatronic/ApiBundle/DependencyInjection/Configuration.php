<?php

namespace MegatronicApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('megatronic_api');
        $this->getPaginationNode($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    protected function getPaginationNode($rootNode)
    {
        $rootNode
            ->children()
            ->arrayNode('pagination')
            ->children()
            ->scalarNode('page')
            ->defaultValue('start')
            ->end()
            ->scalarNode('itemPerPage')
            ->defaultValue('length')
            ->end()
            ->scalarNode('orderColumn')
            ->defaultValue('column')
            ->end()
            ->scalarNode('orderDirection')
            ->defaultValue('dir')
            ->end();
    }


}
