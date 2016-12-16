<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle\Model;

class Subscriber implements  SubscriberInterface
{ 
    /**
     * @var string
     */
    protected $email;

    /**
     * @var boolean
     */
    protected $optIn;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;
 
    protected $site;
    
    public function __construct()
    {
        $this->optIn = true;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Subscriber
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set optIn
     *
     * @param boolean $optIn
     * @return Subscriber
     */
    public function setOptIn($optIn)
    {
        $this->optIn = $optIn;

        return $this;
    }

    /**
     * Get optIn
     *
     * @return boolean 
     */
    public function getOptIn()
    {
        return $this->optIn;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Subscriber
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Subscriber
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
        
    /**
     * Set site
     *
     * @param \Sonata\PageBundle\Model\SiteInterface $site
     * @return BaseRecipeIngredient
     */
    public function setSite(\Sonata\PageBundle\Model\SiteInterface $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return \Sonata\PageBundle\Model\SiteInterface 
     */
    public function getSite()
    {
        return $this->site;
    }

	public function __toString() {
		return $this->id ? $this->getEmail() : 'New';
	}
}