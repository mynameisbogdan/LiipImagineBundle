<?php

namespace Avalanche\Bundle\ImagineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('avalanche_imagine', 'array');

        $rootNode
            ->fixXmlConfig('format', 'formats')
            ->fixXmlConfig('filter', 'filters')
            ->children()
                ->scalarNode('driver')->defaultValue('gd')
                    ->validate()
                        ->ifTrue(function($v) { return in_array($v['driver'], array('gd', 'imagick')); })
                        ->thenInvalid('Invalid imagine driver specified')
                    ->end()
                ->end()
                ->scalarNode('web_root')->defaultValue('%kernel.root_dir%/../web')->end()
                ->scalarNode('cache_prefix')->defaultValue('/media/cache')->end()
                ->scalarNode('cache')->defaultTrue()->end()
                ->arrayNode('formats')
                    ->defaultValue(array())
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('filters')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->fixXmlConfig('option', 'options')
                        ->children()
                            ->scalarNode('type')->end()
                            ->scalarNode('path')->end()
                            ->scalarNode('quality')->defaultValue(100)->end()
                            ->variableNode('options')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}