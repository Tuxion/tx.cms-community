<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<div class="community user-profile">
  
  <div class="header-wrapper"
      <?php if($data->profile->header->is_set()){ ?>
        style="background-image:url('<?php echo $data->profile->header->generate_url(array('fill_height' => 620, 'fill_width' => 1600)); ?>')"
      <?php } ?>
    >
    <div class="header clearfix">
      <div class="contact-info">
        <?php if(!$data->profile->public_email->is_empty()){ ?>
          <a href="mailto:<?php echo $data->profile->public_email; ?>"><span><?php echo $data->profile->public_email; ?></span><i class="icon-envelope-alt"></i></a>
        <?php } ?>
        <?php if(!$data->profile->public_phonenumber->is_empty()){ ?>
          <a href="tel:<?php echo $data->profile->public_phonenumber; ?>" target="_blank"><span><?php echo $data->profile->public_phonenumber; ?></span><i class="icon-phone"></i></a>
        <?php } ?>
        <?php if(!$data->profile->public_jid->is_empty()){ ?>
          <a href="xmpp:<?php echo $data->profile->public_jid; ?>" target="_blank"><span><?php echo $data->profile->public_jid; ?></span><i class="icon-comment-alt"></i></a>
        <?php } ?>
      </div>
      <?php if($data->profile->avatar->is_set()){ ?>
        <img class="avatar" src="<?php echo $data->profile->avatar->generate_url(array('fill_width' => 180,'fill_height' => 180)); ?>" />
      <?php } ?>
      <h1 class="full-name"><?php echo $data->profile->info->full_name; ?></h1>
      <p class="subtitle"><?php echo $data->profile->title; ?></p>
    </div>
  </div>
  
  <?php if($data->profile->check_edit_permissions()){ ?>
    <div class="editor-bar">
      <a href="<?php echo url('edit=1'); ?>" class="button"><i class="icon-edit"></i> <?php __($names->component, 'Edit profile'); ?></a>
    </div>
  <?php } ?>
  
  <?php echo $data->profile->bio; ?>
  
  <h2><?php __($names->component, 'Groups'); ?></h2>
  <?php echo $data->groups; ?>
  
</div>