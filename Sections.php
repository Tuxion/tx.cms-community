<?php namespace components\community; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{
  
  protected
    $permissions = array(
      'user_listing' => 0,
      'user_profile' => 0,
      'user_group_listing' => 0,
      'user_group_profile' => 0,
      'edit_user_group_profile' => 0
    );
  
  protected function user_listing($options)
  {
    
    //Gets all public profiles.
    return tx('Sql')
      ->table('community', 'UserProfiles')
      ->where('is_public', true)
      ->where('is_listed', true)
      ->is($options->user_group->is_set(), function($t)use($options){
        $t->join('Accounts')
          ->join('AccountsToUserGroups', $ATU)
          ->where("$ATU.user_group_id", $options->user_group);
      })
      ->execute();
    
  }
  
  protected function user_profile($account)
  {
    
    #TODO: Check if public.
    
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
    return array(
      'groups' => tx('Sql')
        ->table('community', 'UserGroupProfiles')
        ->is(!tx('Account')->check_level(2), function($t){
          $t->where(tx('Sql')->conditions()
            ->add('1', array('is_public', true))
            ->add('2', array('is_listed', true))
            ->add('3', array('owner_id', tx('Account')->user->id))
            ->combine('4', array('1', '2'), 'AND')
            ->combine('5', array('4', '3'), 'OR')
            ->utilize('5')
          );
        })
        ->execute(),
      'allow_create' => tx('Account')->check_level(
        tx('Config')->user('community_allow_usergroup_creation')->get('boolean') ? 1 : 2
      )
    );
    
  }
  
  protected function user_group_profile($user_group)
  {
    
    #TODO: Check if public.
    
    $profile = tx('Sql')
      ->table('community', 'UserGroupProfiles')
      ->pk($user_group->id)
      ->execute_single()
      ->is('set', function($profile){
        $profile->is_editable;
      })
      ->is('empty', function()use($user_group){
        return tx('Sql')
          ->model('community', 'UserGroupProfiles')->set(array(
            'user_group_id' => $user_group->id
          ));
      });
    
    return array(
      'profile' => $profile,
      'members' => $this->section('user_listing', array('user_group'=>$profile->id))
    );
    
  }
  
  protected function edit_user_group_profile($user_group)
  {
    return $this->user_group_profile($user_group);
  }
  
}
