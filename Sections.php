<?php namespace components\community; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{
  
  /*
    
    # The Sections.php file
    
    This is where you define sections.
    Sections are used to insert a part of a page that is for private use inside the component.
    This allows you to reuse pieces of HTML and allows you to reload by replacing the HTML.
    If your section is very autonomous and might be useful for other components,
    you probably should rewrite it as a Module.php function instead.
    
    Call a section from the client-side using:
      http://mysite.com/index.php?section=community/function_name
    
    Call a section from the server-side using:
      tx('Component')->sections('community')->get_html('function_name', Data($options));
    
    Read more about sections here:
      https://github.com/Tuxion/tuxion.cms/wiki/Sections.php
    
  */
  
  protected function user_listing($options)
  {
    
    //Gets all public profiles.
    return tx('Sql')
      ->table('community', 'UserProfiles')
      ->where('is_public', true)
      ->where('is_listed', true)
      ->execute();
    
  }
  
  protected function user_profile($account)
  {
    
    return tx('Sql')
      ->table('community', 'UserProfiles')
      ->pk($account->id)
      ->execute_single()
      ->is('empty', function()use($account){
        return tx('Sql')
          ->model('community', 'UserProfiles')->set(array(
            'user_id' => $account->id
          ));
      });
    
  }
  
  protected function user_group_listing($options)
  {
    
    //Gets all public profiles.
    return tx('Sql')
      ->table('community', 'UserGroupProfiles')
      ->where('is_public', true)
      ->where('is_listed', true)
      ->execute();
    
  }
  
  protected function user_group_profile($user_group)
  {
    
    return tx('Sql')
      ->table('community', 'UserGroupProfiles')
      ->pk($user_group->id)
      ->execute_single()
      ->is('empty', function()use($user_group){
        return tx('Sql')
          ->model('community', 'UserGroupProfiles')->set(array(
            'user_group_id' => $user_group->id
          ));
      });
    
  }
  
}
