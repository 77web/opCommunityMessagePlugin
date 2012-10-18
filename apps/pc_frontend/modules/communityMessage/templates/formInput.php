<?php

$options = array();
$options['url'] = url_for('@community_message?id='.$community->getId());

op_include_form('communityMessageForm', $form, $options);