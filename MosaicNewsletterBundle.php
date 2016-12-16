<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Groupm\Mosaic\Bundle\NewsletterBundle\DependencyInjection\Compiler\AddProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MosaicNewsletterBundle extends Bundle
{    
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    { 
        $container->addCompilerPass(new AddProviderCompilerPass());
    }
}
