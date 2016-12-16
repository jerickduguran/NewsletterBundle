<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle\Widget;

use Groupm\Mosaic\Bundle\WidgetBundle\Widget\AbstractWidget;
  
class NewsletterWidget extends AbstractWidget
{      
    public function getTemplate()
    { 
        $template = $this->container->getParameter("mosaic_newsletter.widget.newsletter.template");

        return  $template;
    } 

    public function execute()
    {   
        $id              = uniqid(); 
        $form            = $this->container->get("mosaic_newsletter.form.subscriber");
        return $this->environment->render($this->getTemplate(),array('form'         => $form->createView(), 
                                                                     "id"           => $id,
                                                                     'attributes'   => $this->attributes));
    }

    public function configureSettings()
    {
        return array();
    }

    public function getName()
    {
        return 'Newsletter';
    } 
    
}