<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<div class="community usergroup-profile">
  
  <div class="header-wrapper" style="background-image:url('<?php echo $data->profile->header->generate_url(array('fill_height' => 620, 'fill_width' => 1600)); ?>')">
    <div class="header clearfix">
      <div class="contact-info">
        <a href="#" target="_blank"><span>https://mysite.com/group-page</span><i class="icon-globe"></i></a>
        <a href="mailto:<?php echo $data->profile->public_email; ?>"><span><?php echo $data->profile->public_email; ?></span><i class="icon-envelope-alt"></i></a>
        <a href="tel:<?php echo $data->profile->public_phonenumber; ?>" target="_blank"><span><?php echo $data->profile->public_phonenumber; ?></span><i class="icon-phone"></i></a>
        <a href="xmpp:<?php echo $data->profile->public_jid; ?>" target="_blank"><span><?php echo $data->profile->public_jid; ?></span><i class="icon-comment-alt"></i></a>
        <a href="xmpp:<?php echo $data->profile->public_muc; ?>" target="_blank"><span><?php echo $data->profile->public_muc; ?></span><i class="icon-comments-alt"></i></a>
      </div>
      <img class="logo" src="<?php echo $data->profile->logo->generate_url(array('fill_width' => 180,'fill_height' => 180)); ?>" />
      <h1 class="title"><?php echo $data->profile->info->title; ?></h1>
      <p class="subtitle"><?php echo $data->profile->title; ?></p>
    </div>
  </div>
  
  <?php if($data->profile->check_edit_permissions()){ ?>
    <div class="editor-bar">
      <a href="<?php echo url('edit=1'); ?>" class="button"><i class="icon-edit"></i> <?php __('Edit profile'); ?></a> - 
      <a href="#" class="button"><i class="icon-remove-sign"></i> <?php __('Delete profile'); ?></a>
    </div>
  <?php } ?>
  
  <?php echo $data->profile->description; ?>
  
  <hr>
  
  <?php echo $data->members; ?>
  
</div>