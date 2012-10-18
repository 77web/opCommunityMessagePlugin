<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');
include(dirname(__FILE__).'/../../bootstrap/database.php');

$t = new lime_test(null, new lime_output_color());
$form = new opCommunityMailPluginMailForm();

$t->diag('->configure()');
$t->ok(isset($form['title']), 'has "title" field');
$t->ok(isset($form['body']), 'has "body" field');
$t->ok(isset($form['_csrf_token']), 'check csrf');

$t->diag('->getName()');
$t->is($form->getName(), 'community_mail', 'returns "community_mail"');

$t->diag('->send()');
$community = Doctrine::getTable('Community')->find(1);
$member = Doctrine::getTable('Member')->find(1);
$t->ok(!$form->send($community, $member), 'could not send because the form is not valid.');
$values = array(
  'title' => 'test title',
  'body' => 'test body',
  '_csrf_token' => $form->getDefault('_csrf_token'),
);
$form->bind($values);
$t->ok($form->isValid(), 'the form is valid');
$t->is($form->send($community, $member), 2, 'sent a Message to 2 members');
$messageQuery = Doctrine::getTable('SendMessageData')->createQuery();
$t->is($messageQuery->count(), 1, 'inserted a Message');
$message = $messageQuery->fetchOne();
$t->is($message->getSubject(), 'test title', 'subject is set');
$t->is($message->getBody(), 'test body', 'body is set');
$t->is($message->getMemberId(), 1, 'the member id is 1');
$t->isa_ok($message->getMessageSendList(), 'Doctrine_Collection');
$t->is(count($message->getMessageSendList()), 2);