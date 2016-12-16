<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle\DependencyInjection\Compiler;
 
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference; 

class AddProviderCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->attachProviders($container);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function attachProviders(ContainerBuilder $container)
    {    
        //Subscriber Admin - Attach Site Manager
        $definition = $container->getDefinition('mosaic_newsletter.admin.subscriber');      
        $definition->addMethodCall('setSiteManager', array(new Reference('sonata.page.manager.site')));
        
    }
     
}
