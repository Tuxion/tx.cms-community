<?php namespace components\community; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{
  
  protected
    $permissions = array(
      'users' => 0,
      'usergroups' => 0
    );
  
  protected function users($options)
  {
    
    $user = tx('Sql')
      ->table('account', 'Accounts')
      ->pk(tx('Data')->get->user)
      ->execute_single();
    
    return $this->section(
      $user->is_empty() ? 'user_listing' : 'user_profile',
      $user
    );
    
  }
  
  protected function usergroups($options)
  {
    
    $user_group = tx('Sql')
      ->table('account', 'UserGroups')
      ->pk(tx('Data')->get->group)
      ->execute_single();
    
    return $this->section(
      $user_group->is_empty() ? 'user_group_listing' : 'user_group_profile',
      $user_group
    );
    
  }
  
  protected function settings($options)
  {
    
    return tx('Config')->user()->having(
      'community_allow_usergroup_creation'
    );
    
  }
  
}
