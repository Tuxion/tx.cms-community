<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<div class="community user-profile-edit">
  
  <!-- 
  <h1 class="full-name"><?php echo $data->profile->info->full_name; ?></h1>
  
  <div class="editor-bar">
    <a href="<?php echo url('edit=NULL'); ?>" class="button"><i class="icon-eye-open"></i> <?php __($names->component, 'View profile'); ?></a>
  </div>
   -->
  
  <?php 
  
  $data->profile->render_form($id, '?rest=community/user_profile', array(
    
    'method' => 'put',
    'relations' => array('Images'),
    
    'fields' => array(
      
      'user_id' => array('type' => 'HiddenField'),
      
      //Account fields.
      'account[username]' => array(
        'title' => 'Username',
        'type' => 'TextField'
      ),
      'info[avatar_image_id]' => array(
        'title' => 'Avatar',
        'type' => '\\components\\media\\classes\\ImageUploadField'
      )
      
    ),
    
    'fieldsets' => array(
      
      'Images' => array(
        'info[avatar_image_id]',
        'header_image_id'
      ),
      
      'Identity' => array(
        'account[username]',
        'title',
        'bio',
        'signature'
      ),
      
      'Contact info' => array(
        'public_jid',
        'public_email',
        'public_phonenumber'
      ),
      
      'Settings' => array(
        'is_public',
        'is_listed'
      )
      
    )
    
  ));
  
  echo load_plugin('jquery_rest');
  echo load_plugin('jquery_tmpl');
  
  ?>
  
  <script type="text/javascript">
  jQuery(function($){
    
    $('#<?php echo $id; ?>').restForm({
      success: function(result, form){
        $message = $('<div>', {text: "Saved!"}).fadeIn().delay(1500).fadeOut(function(){ $(this).remove(); });
        $(form).find('input[type=submit]').closest('.buttonHolder').append($message);
      }
    });
    
  });
  </script>

</div>