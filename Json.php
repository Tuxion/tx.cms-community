<?php namespace components\community; if(!defined('TX')) die('No direct access.');

class Json extends \dependencies\BaseComponent
{
  
  protected
    $permissions = array(
      'update_user_profile' => 1,
      'create_usergroup' => 1
    );
  
  protected function update_user_profile($data, $params)
  {
    
    $uid = $data->user_id;
    
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
  protected function update_user_group_profile($data, $params)
  {
    
    $gid = $data->user_group_id;
    
    $group = tx('Sql')
      ->table('community', 'UserGroupProfiles')
      ->pk($gid)
      ->execute_single();
    
    //Check if we can actually do this. You can edit groups you own or use super admin powers.
    if(tx('Account')->user->id->get('int') !== $group->owner_id->get('int') && !tx('Account')->check_level(2))
      throw new \exception\Authorisation('You do not own this group.');
    
    return tx('Sql')
      ->model('community', 'UserGroupProfiles')
      ->set($data)
      ->validate_model(array(
        'nullify' => true
      ))
      ->save();
    
  }
  
  protected function create_usergroup($data, $params)
  {
    
    if(!tx('Account')->check_level(tx('Config')->user('community_allow_usergroup_creation')->get('boolean') ? 1 : 2))
      throw new \exception\Authorisation('You do not have permissions to create a new usergroup.');
    
    $group = tx('Sql')
      ->model('account', 'UserGroups')
      ->set(array(
        'title' => $data->title,
        'description' => transf($this->component, 'Created by community component on {0} at {1}.', date('d-m-Y'), date('H:i:s'))
      ))
      ->save();
    
    $membership = tx('Sql')
      ->model('account', 'AccountsToUserGroups')
      ->set(array(
        'user_group_id' => $group->id,
        'user_id' => tx('Account')->user->id
      ))
      ->save();
    
    $group_profile = tx('Sql')
      ->model('community', 'UserGroupProfiles')
      ->set(array(
        
        //Basic info.
        'user_group_id' => $group->id,
        'owner_id' => tx('Account')->user->id,
        
        //Don't open it up yet, but if someone knows about a brand new, non-public group at least let them apply.
        'admission' => 'APPROVE',
        
        //Since the group owner still needs to edit the profile, don't publish yet.
        'is_public' => false,
        'is_listed' => false
        
      ))
      ->save();
    
    return $group_profile
      ->info->back();
    
  }
  
}
