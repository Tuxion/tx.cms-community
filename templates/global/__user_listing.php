<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<h1><?php __('community', 'Users'); ?></h1>

<ul class="community user-listing">
  
  <?php $data->is('empty', function()use($names){
    
    echo '<li class="not-found">'.__($names->component, 'No users were found', true).'.</li>';
    
  }); ?>
  
  <?php foreach($data as $profile): ?>
    
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
    </li>
    
  <?php endforeach; ?>
</ul>
