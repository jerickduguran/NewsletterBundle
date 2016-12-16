<?php

namespace Groupm\Mosaic\Bundle\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IndexController extends Controller
{
    const INVALID_SUBMISSION_SUFFIX = "_invalid_submission";
    const INVALID_SUBMISSION_MAX    = 1;
    
    public function subscribeAction(Request $request)
    {  
        if(!$request->isXmlHttpRequest() || !$request->isMethod(Request::METHOD_POST)){
            throw new AccessDeniedHttpException();
        }
        
        $instanceId     = $request->query->get('id');
        $widget_data    = $request->query->get('wd');    
        
        $session    = $request->getSession(); 
        $template   = $this->container->getParameter("mosaic_newsletter.widget.newsletter.template");  
         
        $subscriberManager  = $this->container->get("mosaic_newsletter.manager.subscriber");     
        $form               = $this->container->get("mosaic_newsletter.form.subscriber");   
        $subscriber         = $subscriberManager->create();  

        $subscriber->setsite($this->getCurrentSite());
        $form->setData($subscriber);
        $form->handleRequest($request);   
        
        if ($form->isSubmitted()) {    
            $isFormValid = $form->isValid();
            if ($isFormValid){  
				$currentEntry = $subscriberManager->findOneBy(array("email" => $subscriber->getEmail()));
				if(!$currentEntry){
					$subscriberManager->save($subscriber); 
				}
                $this->container->get('session')->getFlashBag()->set('newsletter_subscriber_form_success',true); 
                $this->clearInvalidSubmission($form->getName(),$session);           
				$form  = $this->container->get("mosaic_newsletter.form.subscriber");         
            }else{
                $this->addInvalidSubmission($form->getName(),$session);
            }  
        }  
        
        $widgetFormParams = array('form'       => $form->createView(), 
                                  "id"         => $instanceId,
                                  "attributes" => $widget_data);
         
        $returnData['html']   = $this->renderView($template,$widgetFormParams);

        return new JsonResponse($returnData); 
    }
    
    protected function clearInvalidSubmission($formName,$session)
    {
        $id = sprintf($formName."%s",self::INVALID_SUBMISSION_SUFFIX); 
        
        if($session->has($id)){
             $session->remove($id);
        }  
    }
    
    protected function addInvalidSubmission($formName,$session)
    {
        $id       = sprintf($formName."%s",self::INVALID_SUBMISSION_SUFFIX); 
        $attempts = 2;
        if($session->has($id)){
             $attempts = $session->get($id) + 1;
        }
        
        $session->set($id, $attempts);   
    }
    
    protected function getSubmissionAttempts($form,$session)
    {
        $attempts = 0;
        $id       = sprintf($form->getName()."%s",self::INVALID_SUBMISSION_SUFFIX);
        
        if($session->has($id)){
             $attempts = $session->get($id);
        }
        
        return $attempts;          
    }

    private function getCurrentSite()
    {
        return $this->container
                    ->get('mosaic.news_page.current')
                    ->getSite();
    }
}
