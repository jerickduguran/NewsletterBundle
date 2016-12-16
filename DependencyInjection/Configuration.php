<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle\DependencyInjection;

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
        $rootNode    = $treeBuilder->root('mosaic_newsletter'); 
        $rootNode
            ->children() 
                 ->arrayNode('entity')
                    ->addDefaultsIfNotSet()
                    ->children() 
                        ->arrayNode('subscriber')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')
                                    ->cannotBeEmpty()->defaultValue('AppBundle\\Entity\\Newsletter\\Subscriber')->end()
                                ->scalarNode('manager')
                                    ->cannotBeEmpty()->defaultValue('Groupm\\Mosaic\\Bundle\\NewsletterBundle\\Entity\\Manager\\SubscriberManager')->end()
                            ->end()
                        ->end()
                        ->arrayNode('site')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')
                                    ->cannotBeEmpty()->defaultValue('AppBundle\\Entity\\Page\\Site')->end()
                                ->end()
                         ->end()
                    ->end()
                 ->end()
                 ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('subscriber')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Groupm\\Mosaic\\Bundle\\NewsletterBundle\\Admin\\SubscriberAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('MosaicAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('MosaicNewsletterBundle')->end()
                            ->end()
                        ->end()
                    ->end()
                 ->end()
                 ->arrayNode('form') 
                    ->addDefaultsIfNotSet()
                    ->children() 
                        ->arrayNode('subscriber') 
                            ->addDefaultsIfNotSet() 
                            ->children()
                                ->scalarNode('class')
                                    ->cannotBeEmpty()->defaultValue('Groupm\\Mosaic\\Bundle\\NewsletterBundle\\Form\\Type\\NewsletterType')->end() 
                                ->arrayNode('validation_groups')
                                    ->defaultValue(array('Default')) ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()  
                    ->end()
                 ->end()
                 ->arrayNode('widget')
                    ->addDefaultsIfNotSet()
                    ->children()  
                         ->scalarNode('class')->cannotBeEmpty()->defaultValue('Groupm\\Mosaic\\Bundle\\NewsletterBundle\\Widget\\NewsletterWidget')->end() 
                         ->scalarNode('template')->cannotBeEmpty()->defaultValue('MosaicNewsletterBundle:Widget:newsletter_subscribe.html.twig')->end()
                    ->end()
                 ->end()
             ->end();
                
        return $treeBuilder;
    }
}
