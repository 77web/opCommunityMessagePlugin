<?php slot('confirm'); ?>
<table class="confirm">
  <tr>
    <th><?php echo $form['title']->renderLabel(); ?></th>
    <td><?php echo $form->getValue('title'); ?></td>
  </tr>
  <tr>
    <th><?php echo $form['body']->renderLabel(); ?></th>
    <td><?php echo $form->getValue('body'); ?></td>
  </tr>
</table>
<?php
end_slot();

$options = array();
$options['yes_url'] = url_for('@community_mail_send?id='.$community->getId());
$options['no_url'] = url_for('@community_mail?id='.$community->getId());
$options['body'] = get_slot('confirm');

op_include_yesno('communityMailConfirmForm', $csrfForm, $csrfForm, $options);
