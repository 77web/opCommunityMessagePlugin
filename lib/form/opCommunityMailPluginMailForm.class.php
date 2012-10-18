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
  
  public function send(Community $community, Member $fromMember)
  {
    if (!$this->isValid())
    {
      return false;
    }
    
    $message = new SendMessageData();
    $message->setSubject($this->getValue('title'));
    $message->setBody($this->getValue('body'));
    $message->setMember($fromMember);
    $message->setIsSend(true);
    $message->save();
    
    $members = Doctrine::getTable('CommunityMember')->getCommunityMembers($community->getId());
    $count = 0;
    foreach ($members as $member)
    {
      $sendList = new MessageSendList();
      $sendList->setSendMessageData($message);
      $sendList->setMember($member);
      $sendList->save();
      $count++;
      
      $member->free(true);
    }
    
    return $count;
  }
}