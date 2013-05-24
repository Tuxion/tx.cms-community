<?php namespace components\community; if(!defined('TX')) die('No direct access.'); ?>

<p class="settings-description"><?php __($names->component, 'SETTINGS_VIEW_DESCRIPTION'); ?></p>

<form id="edit_community_settings_form" class="form edit-community-settings-form" method="PUT" action="<?php echo url('rest=cms/settings', true); ?>">
  
  <div class="ctrlHolder">
    <label><?php __($names->component, 'Group management'); ?>
    <label>
      <input type="checkbox" name="community_allow_usergroup_creation[default]" value="0" disabled="disabled"<?php if($data->community_allow_usergroup_creation->get()) echo ' checked="checked"'; ?> />
      <?php echo __($names->component, 'Allow users to create their own usergroups', true).' ('.__($names->component, 'Not implemented', 'l', true).')'; ?>
    </label>
  </div>
  
  <div class="buttonHolder">
    <input type="submit" class="primaryAction button black" value="<?php __('Save'); ?>" />
  </div>
  
</form>

<script type="text/javascript">
jQuery(function($){
  $('#edit_community_settings_form').restForm();
});
</script>