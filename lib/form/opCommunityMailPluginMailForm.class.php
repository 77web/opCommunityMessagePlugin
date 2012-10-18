<?php

class opCommunityMailPluginMailForm extends BaseForm
{
  public function configure()
  {
    $this->setWidget('title', new sfWidgetFormInput());
    $this->setValidator('title', new sfValidatorString());
    
    $this->setWidget('body', new sfWidgetFormTextarea());
    $this->setValidator('body', new sfValidatorString());
    
    $this->getWidgetSchema()->setNameFormat('community_mail[%s]');
    $this->getWidgetSchema()->getFormFormatter()->setTranslationCatalogue('form_communityMail');
  }
  
  public function getName()
  {
    return 'community_mail';
  }
}