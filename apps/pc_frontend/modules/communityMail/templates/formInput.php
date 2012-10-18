<?php

$options = array();
$options['url'] = url_for('@community_mail?id='.$community->getId());

op_include_form('communityMailForm', $form, $options);