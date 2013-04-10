<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<ul class="community user-group-listing">
  
  <?php $data->is('empty', function()use($names){
    
    echo '<li class="not-found">'.__($names->component, 'No groups were found', true).'.</li>';
    
  }); ?>
  
  <?php foreach($data as $profile): ?>
    
    <li class="group profile">
      <a class="profile-link clearfix" href="<?php echo url('group='.$profile->user_group_id); ?>" data-id="<?php echo $profile->user_group_id; ?>">
        
        <div class="avatar-wrapper <?php if($profile->avatar->is_set()) echo 'has-avatar'; ?>">
          <?php if($profile->avatar->is_set()): ?>
            <img class="avatar" src="<?php echo $profile->avatar->generate_url(array('fit_width'=>128)); ?>" />
          <?php endif; ?>
        </div>
        
        <span class="name"><?php echo $profile->info->title; ?></span>
        
        <span class="title"><?php echo $profile->title; ?></span>
        
      </a>
    </li>
    
  <?php endforeach; ?>
</ul>
