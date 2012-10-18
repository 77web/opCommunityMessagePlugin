<?php

class opCommunityMailPluginCommunityMailActions extends sfActions
{
  public function preExecute()
  {
    $this->community = $this->getRoute()->getObject();
    $this->forward404Unless($this->community->isAdmin($this->getUser()->getMemberId()));
  }
  
  public function executeForm(sfWebRequest $request)
  {
  }
  
  public function executeSend(sfWebRequest $request)
  {
  }
}