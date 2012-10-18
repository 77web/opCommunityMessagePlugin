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
    return sfView::INPUT;
  }
  
  public function executeSend(sfWebRequest $request)
  {
  }
}