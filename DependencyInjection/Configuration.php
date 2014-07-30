<?php

namespace Jb\YahooWeatherApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jb_yahoo_weather_api');

        $rootNode
            ->children()
               ->scalarNode('city_woeid')->isRequired()->cannotBeEmpty()->end()
               ->scalarNode('unit')->defaultValue('f')->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
