<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle\Entity;
 
use Groupm\Mosaic\Bundle\NewsletterBundle\Model\Subscriber as ModelSubscriber;

/**
 * Subscriber
 */
abstract class Subscriber extends ModelSubscriber  
{
    /**
     * Pre Persist method
     */
    public function prePersist()
    { 
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }  
    
    /**
     * Pre Update method
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
     
}
