<?php namespace components\community; if(!defined('TX')) die('No direct access.');

class Json extends \dependencies\BaseComponent
{
  
  /*
    
    # The Json.php file
    
    This is where you define REST calls.
    They are mostly used for asynchronous operations, such as jQuery.restForm.
    If you need the operation to cause a pageload, you probably need the Actions.php file.
    
    REST calls are prefixed based on the request type.
    For example, calling ?rest=component_name/function_name using an HTTP GET request
    calls get_function_name in the corresponding Json.php file.
    
    The prefixes:
      HTTP GET     = get_function_name
      HTTP PUT     = update_function_name
      HTTP POST    = create_function_name
      HTTP DELETE  = delete_function_name
    
    Read more about actions here:
      https://github.com/Tuxion/tuxion.cms/wiki/Json.php
    
  */
  
  protected function update_user_profile($data, $params)
  {
    
    $uid = $data->user_id;
    
    //See if we are logged in at all.
    if(!tx('Account')->check_level(1))
      throw new \exception\Authorisation('You are not logged in.');
    
    //Check if we can actually do this. You can edit yourself or use super admin powers.
    if(tx('Account')->user->id->get('int') !== $uid->get('int') && !tx('Account')->check_level(2))
      throw new \exception\Authorisation('This is not your profile.');
    
    return tx('Sql')
      ->model('community', 'UserProfiles')
      ->set($data)
      ->validate_model(array(
        'nullify' => true
      ))
      ->save();
    
  }
  
}
