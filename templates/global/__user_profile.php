<?php namespace components\community; if(!defined('TX')) die('No direct access.');

$data->render_form($id, '?rest=community/user_profile', array(
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
