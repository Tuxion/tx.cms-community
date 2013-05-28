<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<div class="community usergroup-profile-edit">
  
  <h1 class="title"><?php echo $data->profile->info->title; ?></h1>
  
  <div class="editor-bar">
    <a href="<?php echo url('edit=NULL'); ?>" class="button"><i class="icon-eye-open"></i> <?php __('View profile'); ?></a> - 
    <a href="#" class="button"><i class="icon-remove-sign"></i> <?php __('Delete profile'); ?></a>
  </div>

  <?php
  
  $data->profile->render_form($id, '?rest=community/user_group_profile', array(
    'method' => 'put',
    'class' => 'edit-community-usergroup-form',
    'relations' => array('Images', 'SecondaryImages'),
    'fields' => array(
      'user_group_id' => array('type' => '\\dependencies\\forms\\HiddenField'),
      'owner_id' => array('type' => '\\dependencies\\forms\\HiddenField') #TODO: Add transfer option.
    ),
    'image_preview_filters' => array(
      'logo_image_id' => array(
        'fill_width' => 180,
        'fill_height' => 180
      )
    )
  ));
  
  echo load_plugin('jquery_rest');
  echo load_plugin('jquery_tmpl');
  
  ?>
  <script type="text/javascript">
  jQuery(function($){
    
    $('#<?php echo $id; ?>').restForm({
      
      beforeSubmit: function(){
        $('#<?php echo $id; ?> :input').attr('disabled', 'disabled');
      },
      
      success: function(e){
        $('#<?php echo $id; ?> input[type=submit]')
          .closest('.ctrlHolder, .buttonHolder')
          .append($('<span class="saved"><?php __("Saved"); ?></span>')
            .hide()
            .fadeToggle('fast', function(){
              $('#<?php echo $id; ?> :input').removeAttr('disabled');
            })
            .delay(2000)
            .fadeToggle('fast', function(){
              $(this).remove();
            })
          );
      },
      
      error: function(e){
        $('#<?php echo $id; ?> :input').removeAttr('disabled');
      }
      
    });
    
  });
  </script>

</div>