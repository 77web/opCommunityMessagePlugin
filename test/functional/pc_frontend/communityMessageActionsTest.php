<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';
$browser = new opTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
include dirname(__FILE__).'/../../bootstrap/database.php';

$browser
  ->get('/communityMessage/1')
  ->with('request')->begin()
    ->isParameter('module', 'communityMessage')
    ->isParameter('action', 'form')
  ->end()
  ->isForwardedTo('member', 'login')
;

$browser->login('sns@example.com', 'password');
$browser->setCulture('en');

$browser->get('/')->with('user')->isAuthenticated();

$browser
  ->get('/communityMessage/1')
  ->with('request')->begin()
    ->isParameter('module', 'communityMessage')
    ->isParameter('action', 'form')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#communityMessageForm')
    ->checkElement('input#community_message_subject')
    ->checkElement('textarea#community_message_body')
    ->checkElement('input#community_message__csrf_token')
  ->end()
  
  ->info('CSRF')
  ->setField('community_message[_csrf_token]', 'foo')
  ->click('Send')
  ->isForwardedTo('communityMessage', 'form')
  ->with('response')->begin()
    ->checkElement('#communityMessageForm')
    ->checkElement('.error_list li:contains("CSRF attack detected")')
  ->end()
  ->back()
  
  ->info('successful confirm-send')
  ->setField('community_message[subject]', 'test title')
  ->setField('community_message[body]', 'test body')
  ->click('Send')
  ->isForwardedTo('communityMessage', 'form')
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#communityMessageConfirmForm')
    ->checkElement('table.confirm')
    ->checkElement('table.confirm tr:eq(0) td:contains("test title")')
    ->checkElement('table.confirm tr:eq(1) td:contains("test body")')
    ->checkElement('input[name=_csrf_token]', 2)
  ->end()
;
$savedData = $browser->getUser()->getAttribute('community_message.1');
$browser->test()->isa_ok($savedData, 'array', 'saved data is array');
$browser->test()->ok(isset($savedData['subject']), 'subject is saved');
$browser->test()->is($savedData['subject'], 'test title', 'the saved subject is "test title"');
$browser->test()->ok(isset($savedData['body']), 'body is saved');
$browser->test()->is($savedData['body'], 'test body', 'the saved body is "test body"');

$browser
  ->click('No')
  ->isForwardedTo('communityMessage', 'form')
  ->back()
  
  ->click('Yes')
  ->with('request')->begin()
    ->isParameter('module', 'communityMessage')
    ->isParameter('action', 'send')
    ->isParameter('id', 1)
  ->end()
  ->with('response')->isStatusCode(302)
  ->followRedirect()
  ->isForwardedTo('community', 'home')

  ->get('/communityMessage/2')
  ->with('request')->begin()
    ->isParameter('module', 'communityMessage')
    ->isParameter('action', 'form')
  ->end()
  ->with('response')->isStatusCode(404)

  ->get('/communityMessage/3')
  ->with('request')->begin()
    ->isParameter('module', 'communityMessage')
    ->isParameter('action', 'form')
  ->end()
  ->with('response')->isStatusCode(404)
;
