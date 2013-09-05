<?php namespace components\community; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{
  
  protected
    $permissions = array(
      'profile' => 0,
      'user_groups' => 0
    );
  
  protected function profile($options)
  {
    
    $exists = true;
    
    //See if a profile entry exists for this user.
    $profile = tx('Sql')
      ->table('community', 'UserProfiles')
      ->pk(tx('Account')->user->id)
      ->execute_single()
      ->is('empty', function()use(&$exists){
        $exists = false;
        return tx('Sql')
          ->model('community', 'UserProfiles')->set(array(
            'user_id' => tx('Account')->user->id
          ));
      });
    
    //Either show edit or view section if the profile is found.
    if($exists)
      return $this->section(
        $options->edit->otherwise(tx('Data')->get->edit)->is_set() ? 'edit_user_profile' : 'user_profile',
        $profile
      );
    
    //If the profile does not exist, always show edit mode.
    return $this->section('edit_user_profile', $profile);
    
  }
  
  protected function user_groups($options)
  {
    
    //Since we start from a group-list perspective, the user argument is dominant.
    $user = tx('Sql')
      ->table('account', 'Accounts')
      ->pk(tx('Data')->get->user)
      ->execute_single();
    
    //Either show edit or view section if the user is found.
    if($user->is_set())
      return $this->section(
        tx('Data')->get->edit->is_set() ? 'edit_user_profile' : 'user_profile',
        $user
      );
    
    //Fallback is the group argument.
    $user_group = tx('Sql')
      ->table('account', 'UserGroups')
      ->pk(tx('Data')->get->group)
      ->execute_single();
    
    //Either show edit or view section if the group is found.
    if($user_group->is_set())
      return $this->section(
        tx('Data')->get->edit->is_set() ? 'edit_user_group_profile' : 'user_group_profile',
        $user_group
      );
    
    //Otherwise the fallback fallback: the group listing.
    return $this->section('user_group_listing');
    
  }
  
  protected function settings($options)
  {
    
    return tx('Config')->user()->having(
      'community_allow_user_group_creation'
    );
    
  }
  
}
