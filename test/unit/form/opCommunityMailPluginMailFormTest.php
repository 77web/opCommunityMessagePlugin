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