<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<div class="community user-profile-edit">
  
  <h1 class="full-name"><?php echo $data->profile->info->full_name; ?></h1>
  
  <div class="editor-bar">
    <a href="<?php echo url('edit=NULL'); ?>" class="button"><i class="icon-eye-open"></i> <?php __($names->component, 'View profile'); ?></a>
  </div>
  
  <?php 
  
  $data->profile->render_form($id, '?rest=community/user_profile', array(
    'method' => 'put',
    'relations' => array('Images'),
    'fields' => array(
      'user_id' => array('type' => '\\dependencies\\forms\\HiddenField')
    )
  ));
  
  echo load_plugin('jquery_rest');
  echo load_plugin('jquery_tmpl');
  
  ?>
  
  <script type="text/javascript">
  jQuery(function($){
    
    $('#<?php echo $id; ?>').restForm({});
    
  });
  </script>

</div>