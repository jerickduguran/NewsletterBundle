<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Model\ManagerInterface;

class SubscriberAdmin extends Admin
{
    public function getNewInstance()
    {
        $instance = parent::getNewInstance(); 

        if ($site = $this->getSite()) {
             $instance->setSite($site);
        }

        return $instance;
    } 
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('site', null, array('show_filter' => false))
            ->add('email')
            ->add('optIn')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('email')
            ->add('optIn')
            ->add('createdAt') 
            ->add('id')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('email')
            ->add('optIn') 
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('email')
            ->add('optIn')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id')
        ;
    }
    
     /**
     * {@inheritdoc}
     */
    public function getPersistentParameters()
    {
        $site = $this->getSite();

        $parameters = array('site' => $site ? $site : '');

        if ($this->getSubject()) {
             $parameters['site']   = $this->getSubject()->getSite() ? $this->getSubject()->getSite()->getId() : '';
            return $parameters;
        }

        if ($this->hasRequest()) { 
            $parameters['site'] = $this->getRequest()->get('site');
            return $parameters;
        }

        return $parameters;
    } 
    
    /**
     * @return SiteInterface|bool
     *
     * @throws \RuntimeException
     */
    public function getSite()
    {
        if (!$this->hasRequest()) {
            return false;
        }

        $siteId = null;

        if ($this->getRequest()->getMethod() == 'POST') {
            $values = $this->getRequest()->get($this->getUniqid());
            $siteId = isset($values['site']) ? $values['site'] : null;
        }

        $siteId = (null !== $siteId) ? $siteId : $this->getRequest()->get('site');

        if ($siteId) {
            $site = $this->siteManager->findOneBy(array('id' => $siteId));

            if (!$site) {
                throw new \RuntimeException('Unable to find the site with id='.$this->getRequest()->get('site'));
            }

            return $site;
        } else {
            return $this->siteManager->findOneBy(array('host'=>'localhost','isDefault' => true));
        }
    } 
    
    /**
     * @return mixed
     */
    public function getSiteManager()
    {
        return $this->siteManager;
    }

    /**
     * @param mixed $siteManager
     */
    public function setSiteManager(ManagerInterface $siteManager)
    {  
        $this->siteManager = $siteManager;
    }
}
