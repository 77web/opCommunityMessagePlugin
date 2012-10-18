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
    ->checkElement('form')
    ->checkElement('input#community_mail_title')
    ->checkElement('textarea#community_mail_body')
    ->checkElement('input#community_mail__csrf_token')
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
