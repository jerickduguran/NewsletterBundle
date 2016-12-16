<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MosaicNewsletterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('admin.xml');
        $loader->load('services.xml');
        
        $this->configureEntity($config,$container); 
        $this->configureAdminClass($config,$container);  
        $this->configureTranslationDomain($config,$container);   
        $this->configureWidgets($config,$container);  
        $this->configureForm($config, $container);
        $this->registerDoctrineMapping($config,$container);  
    }
    
    public function configureEntity($config, ContainerBuilder $container)
    { 
        //Subscriber
        $container->setParameter('mosaic_newsletter.entity.subscriber.class', $config['entity']['subscriber']['class']); 
        $container->setParameter('mosaic_newsletter.manager.subscriber', $config['entity']['subscriber']['manager']);  
    }
    
    public function configureAdminClass($config, ContainerBuilder $container)
    { 
        //Subscriber
        $container->setParameter('mosaic_newsletter.admin.subscriber.class', $config['admin']['subscriber']['class']);        
        $container->setParameter('mosaic_newsletter.admin.subscriber.controller', $config['admin']['subscriber']['controller']);  
    }      
    
    public function configureTranslationDomain($config, ContainerBuilder $container)
    {
        $container->setParameter('mosaic_newsletter.admin.subscriber.translation_domain', $config['admin']['subscriber']['translation']);
    }  
    
    public function configureWidgets($config, ContainerBuilder $container)
    {  
        $container->setParameter('mosaic_newsletter.widget.newsletter.class', $config['widget']['class']);  
        $container->setParameter('mosaic_newsletter.widget.newsletter.template', $config['widget']['template']);  
    }
    
     public function configureForm($config, ContainerBuilder $container)
    {
        //Newsletter Type
        $container->setParameter('mosaic_newsletter.form.subscriber.class', $config['form']['subscriber']['class']);
      
        //Validation Groups
        $container->setParameter('mosaic_newsletter.form.subscriber.validation_groups', $config['form']['subscriber']['validation_groups']); 
    
    }        
    
    public function registerDoctrineMapping(array $config)
    {   
        $collector = DoctrineCollector::getInstance();
        
        //Site 
        $collector->addAssociation($config['entity']['subscriber']['class'], 'mapManyToOne', array(
            'fieldName'     => 'site',
            'targetEntity'  => $config['entity']['site']['class'],
            'cascade'       => array(
                'persist',
            ),
            'mappedBy'      => null,
            'inversedBy'    => null,
            'joinColumns'   => array(
                array(
                    'name'                 => 'site_id',
                    'referencedColumnName' => 'id',
                ),
            ),
            'orphanRemoval' => false,
        ));  
    }
}
