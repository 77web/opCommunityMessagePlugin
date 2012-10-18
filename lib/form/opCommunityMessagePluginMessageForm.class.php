<?php

class opCommunityMessagePluginMessageForm extends SendMessageDataForm
{
  public function configure()
  {
    parent::configure();
    $this->useFields(array('subject', 'body'));
    
    $this->getWidgetSchema()->setNameFormat('community_message[%s]');
    $this->getWidgetSchema()->getFormFormatter()->setTranslationCatalogue('form_communityMessage');
  }
  
  public function getName()
  {
    return 'community_message';
  }
  
  public function send(Community $community, Member $fromMember)
  {
    if (!$this->isValid())
    {
      return false;
    }
    
    $message = new SendMessageData();
    $message->setSubject($this->getValue('subject'));
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