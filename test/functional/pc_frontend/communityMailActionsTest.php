<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';
$browser = new opTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
include dirname(__FILE__).'/../../bootstrap/database.php';

$browser
  ->get('/communityMail/1')
  ->with('request')->begin()
    ->isParameter('module', 'communityMail')
    ->isParameter('action', 'form')
  ->end()
  ->isForwardedTo('member', 'login')
;

$browser->login('sns@example.com', 'password');
$browser->setCulture('en');

$browser->get('/')->with('user')->isAuthenticated();

$browser
  ->get('/communityMail/1')
  ->with('request')->begin()
    ->isParameter('module', 'communityMail')
    ->isParameter('action', 'form')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#communityMailForm')
    ->checkElement('input#community_mail_title')
    ->checkElement('textarea#community_mail_body')
    ->checkElement('input#community_mail__csrf_token')
  ->end()
  
  ->info('CSRF')
  ->setField('community_mail[_csrf_token]', 'foo')
  ->click('Send')
  ->isForwardedTo('communityMail', 'form')
  ->with('response')->begin()
    ->checkElement('#communityMailForm')
    ->checkElement('.error_list li:contains("CSRF attack detected")')
  ->end()
  ->back()
  
  ->info('successful confirm-send')
  ->setField('community_mail[title]', 'test title')
  ->setField('community_mail[body]', 'test body')
  ->click('Send')
  ->isForwardedTo('communityMail', 'form')
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#communityMailConfirmForm')
    ->checkElement('table.confirm')
    ->checkElement('table.confirm tr:eq(0) td:contains("test title")')
    ->checkElement('table.confirm tr:eq(1) td:contains("test body")')
    ->checkElement('input[name=_csrf_token]', 2)
  ->end()

  ->get('/communityMail/2')
  ->with('request')->begin()
    ->isParameter('module', 'communityMail')
    ->isParameter('action', 'form')
  ->end()
  ->with('response')->isStatusCode(404)

  ->get('/communityMail/3')
  ->with('request')->begin()
    ->isParameter('module', 'communityMail')
    ->isParameter('action', 'form')
  ->end()
  ->with('response')->isStatusCode(404)
;
