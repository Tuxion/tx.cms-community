<?php namespace components\community; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{
  
  /*
    
    # The Views.php file
    
    This is where you define views.
    Views are used to provide you with an entire page worth of content.
    If you are using a view inside another view, you probably want to use
    a module or section instead. Views can only be loaded from the server-side.
    This discourages you from reloading them by replacing HTML, since they are
    intended to be entire pages.
    
    Call a view from the server-side using:
      tx('Component')->views('community')->get_html('function_name', Data($options));
    
    Read more about views here:
      https://github.com/Tuxion/tuxion.cms/wiki/Views.php
    
  */
  
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
  
}
