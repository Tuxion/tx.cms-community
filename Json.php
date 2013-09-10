<?php namespace components\community; if(!defined('TX')) die('No direct access.');

class Json extends \dependencies\BaseComponent
{
  
  protected
    $permissions = array(
      'update_user_profile' => 1,
      'create_user_group' => 1,
      'delete_user_group_profile' => 1,
      'create_user_group_profile_application' => 1,
      'delete_user_group_profile_application' => 1,
      'update_user_group_profile_application_response' => 1
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
      ->save()
      ->is(true, function($profile)use($data){
        
        $profile->account->merge($data->account->having('username'))->save();
        $profile->info->merge($data->info->having('avatar_image_id'))->save();
        
        //Prevent sensitive data from being sent back.
        $profile->info->set($profile->info->having('avatar_image_id')->as_array());
        $profile->account->set($profile->account->having('username')->as_array());
        
      });
    
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
  
  protected function create_user_group($data, $params)
  {
    
    if(!tx('Account')->check_level(tx('Config')->user('community_allow_user_group_creation')->get('boolean') ? 1 : 2))
      throw new \exception\Authorisation('You do not have permissions to create a new user group.');
    
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
  
  protected function create_user_group_profile_application($data, $params)
  {
    
    $params->{0}->validate('User group ID', array('required', 'number'=>'integer', 'gt'=>0));
    
    $group_profile = tx('Sql')
      ->table('community', 'UserGroupProfiles')
      ->pk($params->{0})
      ->execute_single()
      ->is('empty', function(){
        throw new \exception\NotFound('No user group with this ID found.');
      });
    
    if($group_profile->is_currently_member->get('boolean'))
      throw new \exception\User('You are already a member of this user group.');
    
    if($group_profile->is_currently_applying->get('boolean'))
      throw new \exception\User('You are submitted an application for this group which is still pending.');
    
    if(!$group_profile->can_apply->get('boolean'))
      throw new \exception\User('The user group does not currently allow applications.');
    
    $model = null;
    
    switch($group_profile->admission->get()){
      
      case 'OPEN':
        $model = tx('Sql')->model('account', 'AccountsToUserGroups');
        break;
      
      case 'APPROVE':
        $model = tx('Sql')->model('community', 'UserGroupApplications');
        break;
      
      default:
        throw new \exception\User('The user group does not currently allow applications.');
      
    }
    
    return $model
      ->set(array(
        'user_id' => tx('Account')->user->id,
        'user_group_id' => $group_profile->user_group_id
      ))
      ->save();
    
  }
  
  //This is both withdrawing your application and leaving.
  protected function delete_user_group_profile_application($data, $params)
  {
    
    $params->{0}->validate('User group ID', array('required', 'number'=>'integer', 'gt'=>0));
    
    $group_profile = tx('Sql')
      ->table('community', 'UserGroupProfiles')
      ->pk($params->{0})
      ->execute_single()
      ->is('empty', function(){
        throw new \exception\NotFound('No user group with this ID found.');
      });
    
    if($group_profile->is_currently_member->get('boolean')){
      
      //Delete membership
      tx('Sql')
        ->table('account', 'AccountsToUserGroups')
        ->where('user_id', tx('Account')->user->id)
        ->where('user_group_id', $group_profile->user_group_id)
        ->execute_single()
        ->delete();
      
    } else if($group_profile->is_currently_applying->get('boolean')){
      
      //Delete application
      tx('Sql')
        ->table('community', 'UserGroupApplications')
        ->where('user_id', tx('Account')->user->id)
        ->where('user_group_id', $group_profile->user_group_id)
        ->execute_single()
        ->delete();
      
    }
    
    return array(
      'success' => true
    );
    
  }
  
  protected function update_user_group_profile_application_response($data, $params)
  {
    
    $data
      ->user_group_id->validate('User group ID', array('required', 'number'=>'integer', 'gt'=>0))->back()
      ->user_id->validate('User ID', array('required', 'number'=>'integer', 'gt'=>0))->back()
      ->response->validate('Response', array('required', 'string', 'not_empty', 'in'=>array('approve', 'reject')))->back()
    ;
    
    $group_profile = tx('Sql')
      ->table('community', 'UserGroupProfiles')
      ->pk($data->user_group_id)
      ->execute_single();
    
    //Note: not checking for existence of the group profile causes this to throw the exception when it doesn't exist.
    if(!$group_profile->can_approve->get('boolean'))
      throw new \exception\Authorisation('You do not have permissions to approve or reject applications for this group.');
    
    $application = tx('Sql')
      ->table('community', 'UserGroupApplications')
      ->where('user_id', $data->user_id)
      ->where('user_group_id', $data->user_group_id)
      ->execute_single()
      ->is('empty', function(){
        throw new \exception\NotFound('There is no application from this user in this group.');
      });
    
    switch($data->response->get()){
      
      case 'approve':
        $membership = tx('Sql')
          ->model('account', 'AccountsToUserGroups')
          ->set($application)
          ->save();
        
        $application->delete();
        
        return $membership;
        
      case 'reject':
        $application->delete();
        return;
      
      default:
        throw new \exception\Programmer('Unsupported response: '.$data->response);
    }
    
  }
  
  protected function delete_user_group_profile($data, $params)
  {
    
    if(!tx('Account')->check_level(tx('Config')->user('community_allow_user_group_creation')->get('boolean') ? 1 : 2))
      throw new \exception\Authorisation('You do not have permissions to delete a user group.');
    
    $params->{0}->validate('User group ID', array('required', 'number'=>'integer', 'gt'=>0));
    
    $allowed = false;
    
    $group_profile = tx('Sql')
      ->table('community', 'UserGroupProfiles')
      ->pk($params->{0})
      ->execute_single()
      ->is('set', function($group_profile)use(&$allowed){
        
        if(!$group_profile->can_delete->get('boolean'))
          throw new \exception\Authorisation('You do not have permissions to delete this user group.');
        
        $allowed = true;
        $group_profile->delete();
        
      });
    
    //In case there is no profile for this user group, but the user group exists, don't allow normal users to delete the underlaying group.
    if(!$allowed) throw new \exception\Authorisation('You do not have permissions to delete this user group.');
    
    $group = tx('Sql')
      ->table('account', 'UserGroups')
      ->pk($params->{0})
      ->execute_single()
      ->is('set', function($group){
        $group->delete();
      });
    
    return array(
      'success' => true
    );
      
  }
  
}
