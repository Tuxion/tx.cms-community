<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<h1><?php __('community', 'Groups'); ?></h1>

<ul class="community user-group-listing">
  
  <?php $data->allow_create->is('true', function()use($names){ ?>
    
    <a href="#" class="create_group btn"><i class="icon-group"></i> <?php __($names->component, 'Create group'); ?></a>
    <form id="create_community_usergroup" class="form create-community-usergroup-form" method="POST" action="<?php echo url('rest=community/usergroup', true); ?>">
      
      <label for="l_title"><?php __($names->component, 'Group title'); ?></label>
      <input type="text" id="l_title" name="title" value="" placeholder="<?php __($names->component, 'Group title'); ?>" />
      <input type="submit" class="primaryAction button black" value="<?php __('CREATE_VERB'); ?>" />
      
    </form>
    
    <?php echo load_plugin('jquery_rest'); ?>
    <?php echo load_plugin('jquery_tmpl'); ?>
    <?php echo load_plugin('plupload'); ?>
    <script type="text/javascript" src="<?php echo url('?section=media/image_upload_js',1); ?>"></script>
    
    <script type="text/javascript">
    jQuery(function($){
      
      $('.user-group-listing .create_group').on('click', function(e){
        e.preventDefault();
        $('#create_community_usergroup').slideToggle('fast');
      });
      
      $('#create_community_usergroup').restForm({
        
        beforeSubmit: function(){
          $('#create_community_usergroup :input').attr('disabled', 'disabled');
        },
        
        success: function(usergroup){
          
          $.ajax('?section=community/user_group_profile&options[id]='+usergroup.user_group_id)
            .done(function(html){
              $('.community.user-group-listing').html(html);
            });
          
        }
        
      });
      
    });
    </script>
    
  <?php }); ?>
  
  <?php $data->groups->is('empty', function()use($names){
    
    echo '<li class="not-found">'.__($names->component, 'No groups were found', true).'.</li>';
    
  }); ?>
  
  <?php foreach($data->groups as $profile): ?>
    
    <li class="group profile<?php echo $profile->is_public->is_true() ? '' : ' private'; echo $profile->is_listed->is_true() ? '' : ' unlisted'; ?>">
      <a class="profile-link clearfix" href="<?php echo url('group='.$profile->user_group_id); ?>" data-id="<?php echo $profile->user_group_id; ?>">
        
        <div class="logo-wrapper <?php if($profile->logo->is_set()) echo 'has-logo'; ?>">
          <?php if($profile->logo->is_set()): ?>
            <img class="logo" src="<?php echo $profile->logo->generate_url(array('fill_width'=>180, 'fill_height'=>180)); ?>" />
          <?php endif; ?>
        </div>
        
        <span class="name">
          <?php echo $profile->info->title; ?>
          <?php if(!$profile->is_public->is_true()){ ?><small class="icon-lock"></small><?php } ?>
          <?php if(!$profile->is_listed->is_true()){ ?><small class="icon-eye-close"></small><?php } ?>
        </span>
        
        <span class="title"><?php echo $profile->title; ?></span>
        
      </a>
    </li>
    
  <?php endforeach; ?>
</ul>
