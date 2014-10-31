<?php

namespace Jb\ApiYahooWeatherBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jb_api_yahoo_weather');
                
        $rootNode
            ->children()
               ->scalarNode('woeid')->defaultNull()->end()
               ->scalarNode('unit')->defaultValue('f')->end()
               ->integerNode('ttl_memcached_s')->min(0)->defaultValue(1800)->end()
            ->end()
        ;
         
        return $treeBuilder;
    }
}
