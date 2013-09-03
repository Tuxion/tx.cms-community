<?php namespace components\community\models; if(!defined('TX')) die('No direct access.');

class UserGroupApplications extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'community_user_group_applications',
    
    $relations = array(
      'Accounts' => array('owner_id' => 'account.Accounts.id'),
    );
  
  public function get_user_profile()
  {
    return tx('Sql')
      ->table('community', 'UserProfiles')
      ->pk($this->user_id)
      ->execute_single();
  }
  
}
