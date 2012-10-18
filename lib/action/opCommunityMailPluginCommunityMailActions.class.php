<?php

class opCommunityMailPluginCommunityMailActions extends sfActions
{
  public function preExecute()
  {
    $this->community = $this->getRoute()->getObject();
    $this->forward404Unless($this->community->isAdmin($this->getUser()->getMemberId()));
    
    $this->form = new opCommunityMailPluginMailForm();
  }
  
  public function executeForm(sfWebRequest $request)
  {
    if ($request->isMethod(sfRequest::POST) && $request->getParameter($this->form->getName()))
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->getUser()->setAttribute('community_mail.'.$this->community->getId(), $this->form->getValues());
        
        $this->csrfForm = new BaseForm();
        
        return sfView::SUCCESS;
      }
    }
    
    return sfView::INPUT;
  }
  
  public function executeSend(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $values = $this->getUser()->getAttribute('community_mail.'.$this->community->getId());
    $this->forward404Unless($values);
    
    $values['_csrf_token'] = $this->form->getDefault('_csrf_token');
    $this->form->bind($values);
    
    if ($this->form->isValid())
    {
      $communityId = $this->community->getId();
      
      $this->form->send($this->community, $this->getUser()->getMember());
      $this->getUser()->setAttribute('community_mail.'.$communityId, null);
      
      $this->getUser()->setFlash('notice', 'Message sent.');
      
      $this->redirect('@community_home?id='.$communityId);
    }
    
    $this->setTemplate('form');
    
    return sfView::INPUT;
  }
}