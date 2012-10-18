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
  }
}