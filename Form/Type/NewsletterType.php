<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle\Form\Type;
  
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType; 
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;  
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class NewsletterType extends AbstractType
{ 
    const INVALID_SUBMISSION_SUFFIX = "_invalid_submission";
    const INVALID_SUBMISSION_MAX    = 3; 
    const RECAPTCHA_FIELD           = 'g-recaptcha-response'; 
    
    protected $showSecurityField;
    protected $session;
    protected $requestStack;
    
    protected $validationGroups = array('Default');
    
    public function __construct(Session  $session,RequestStack $requestStack)
    {
       $this->showSecurityField = false;
       $this->session           = $session;
       $this->requestStack      = $requestStack;
       $this->checkInvalidSubmissions();
    }
    
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $builder
            ->add('email')
        ; 
        
        $formModifier = function (FormInterface $form,$groups){  
             $form->add('recaptcha', EWZRecaptchaType::class,array( 'mapped'      => false,
                                                                    'validation_groups' => $groups,
                                                                    'constraints' => array(
                                                                    new RecaptchaTrue())));
        };

        $builder->get('email')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) { 
                $form = $event->getForm(); 
                $formProperties = $form->all();
                if($this->showSecurityField){ 
                    $groups        = $this->validationGroups;
                    $requestParams = $this->requestStack->getCurrentRequest()->request->all(); 
                    if(!isset($requestParams[self::RECAPTCHA_FIELD])){
                        $groups = array('NA');  
                    }
                    $formModifier($form->getParent(),$groups);
                }
            }
        ); 
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Groupm\Mosaic\Bundle\NewsletterBundle\Entity\Subscriber',
            'validation_grooups' => $this->validationGroups
        ));
    } 

    /**
     * @return string
     */
    public function getName()
    {
        return 'mosaic_newsletter';
    } 
    
    protected function checkInvalidSubmissions()
    {
        $id       = sprintf($this->getName()."%s", self::INVALID_SUBMISSION_SUFFIX);  
        if($this->session->has($id)){
             $attempts = $this->session->get($id);
             if($attempts >= self::INVALID_SUBMISSION_MAX){
                 $this->showSecurityField = true;
             }
        } 
    }
}