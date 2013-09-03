<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<div class="community user-group-profile">
  
  <?php echo load_plugin('jquery_rest'); ?>
  
  <div class="header-wrapper"
      <?php if($data->profile->header->is_set()){ ?>
        style="background-image:url('<?php echo $data->profile->header->generate_url(array('fill_height' => 620, 'fill_width' => 1600)); ?>')"
      <?php } ?>
    >
    <div class="header clearfix">
      <div class="contact-info">
        <?php if(!$data->profile->public_website->is_empty()){ ?>
          <a href="#" target="_blank"><span>https://mysite.com/group-page</span><i class="icon-globe"></i></a>
        <?php } ?>
        <?php if(!$data->profile->public_email->is_empty()){ ?>
          <a href="mailto:<?php echo $data->profile->public_email; ?>"><span><?php echo $data->profile->public_email; ?></span><i class="icon-envelope-alt"></i></a>
        <?php } ?>
        <?php if(!$data->profile->public_phonenumber->is_empty()){ ?>
          <a href="tel:<?php echo $data->profile->public_phonenumber; ?>" target="_blank"><span><?php echo $data->profile->public_phonenumber; ?></span><i class="icon-phone"></i></a>
        <?php } ?>
        <?php if(!$data->profile->public_jid->is_empty()){ ?>
          <a href="xmpp:<?php echo $data->profile->public_jid; ?>" target="_blank"><span><?php echo $data->profile->public_jid; ?></span><i class="icon-comment-alt"></i></a>
        <?php } ?>
        <?php if(!$data->profile->public_muc->is_empty()){ ?>
          <a href="xmpp:<?php echo $data->profile->public_muc; ?>" target="_blank"><span><?php echo $data->profile->public_muc; ?></span><i class="icon-comments-alt"></i></a>
        <?php } ?>
      </div>
      <?php if($data->profile->logo->is_set()){ ?>
        <img class="logo" src="<?php echo $data->profile->logo->generate_url(array('fill_width' => 180,'fill_height' => 180)); ?>" />
      <?php } ?>
      <h1 class="title"><?php echo $data->profile->info->title; ?></h1>
      <p class="subtitle"><?php echo $data->profile->title; ?></p>
    </div>
  </div>
  
  <?php if($data->profile->can_apply->get('boolean')){ ?>
    <div class="membership-bar">
      <a href="#" class="button join-user-group-profile" data-id="<?php echo $data->profile->user_group_id; ?>">
        <i class="icon-signin"></i>
        <?php __($names->component, $data->profile->admission->get() === 'OPEN' ? 'Join group' : 'Apply for membership'); ?>
      </a>
    </div>
  <?php } else if($data->profile->can_leave->get('boolean')) { ?>
    <div class="membership-bar">
      <a href="#" class="button leave-user-group-profile" data-id="<?php echo $data->profile->user_group_id; ?>">
        <i class="icon-signout"></i> <?php __($names->component, 'Leave group'); ?>
      </a>
    </div>
  <?php } else if($data->profile->is_currently_applying->get('boolean')){ ?>
    <div class="membership-bar">
      <span class="user-group-application-pending">
        <i class="icon-time"></i> <?php __($names->component, 'Application pending'); ?>
      </span> - <a href="#" class="button leave-user-group-profile" data-id="<?php echo $data->profile->user_group_id; ?>">
        <i class="icon-signout"></i> <?php __($names->component, 'Withdraw application'); ?>
      </a>
    </div>
  <?php } ?>
  
  <?php if($data->profile->check_edit_permissions()){ ?>
    <div class="editor-bar">
      <a href="<?php echo url('edit=1'); ?>" class="button"><i class="icon-edit"></i> <?php __($names->component, 'Edit profile'); ?></a> - 
      <a href="#" class="button delete-user-group-profile"
        data-id="<?php echo $data->profile->user_group_id; ?>"
        data-title="<?php echo $data->profile->info->title; ?>"><i class="icon-remove-sign"></i> <?php __($names->component, 'Delete profile'); ?></a>
    </div>
  <?php } ?>
  
  <?php echo $data->profile->description; ?>
  
  <?php if($data->applying->is_set()){ ?>
    
    <h2><?php __($names->component, 'Pending applications'); ?></h2>
    <ul class="community user-listing user-group-applications">
      
      <?php foreach($data->applying as $application): $profile = $application->user_profile; ?>
        
        <li class="user profile">
          <a class="profile-link clearfix" href="<?php echo url('user='.$profile->user_id); ?>" data-id="<?php echo $profile->user_id; ?>">
            
            <div class="avatar-wrapper <?php if($profile->info->avatar->is_set()) echo 'has-avatar'; ?>">
              <?php if($profile->info->avatar->is_set()): ?>
                <img class="avatar" src="<?php echo $profile->info->avatar->generate_url(array('fit_width'=>128)); ?>" />
              <?php endif; ?>
            </div>
            
            <span class="name"><?php echo $profile->info->full_name; ?></span>
            
            <span class="title"><?php echo $profile->title; ?></span>
            
          </a>
          <a href="#" class="user-group-application-button approve-user-group-application"
            data-response="approve" data-uid="<?php echo $profile->user_id; ?>"
            data-gid="<?php echo $data->profile->user_group_id; ?>"><i class="icon-ok-circle"></i></a>
          <a href="#" class="user-group-application-button reject-user-group-application"
            data-response="reject" data-uid="<?php echo $profile->user_id; ?>"
            data-gid="<?php echo $data->profile->user_group_id; ?>"><i class="icon-remove-circle"></i></a>
        </li>
        
      <?php endforeach; ?>
      
    </ul>
    
  <?php } ?>
  
  <h2><?php __($names->component, 'Members'); ?></h2>
  <?php echo $data->members; ?>
  
</div>