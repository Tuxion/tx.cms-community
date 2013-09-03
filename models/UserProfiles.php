<?php namespace components\community\models; if(!defined('TX')) die('No direct access.');

class UserProfiles extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'community_user_profiles',
    
    $relations = array(
      'Accounts' => array('user_id' => 'account.Accounts.id'),
      'Images' => array('header_image_id' => 'media.Images.id'),
      'AccountsToUserGroups' => array('user_id' => 'account.AccountsToUserGroups.user_id'),
      'UserGroupApplications' => array('user_id' => 'UserGroupApplications.user_id'),
    ),
    
    $labels = array(
      'title' => 'Personal title',
      'bio' => 'Biography',
      'public_jid' => 'Public XMPP address',
      'public_email' => 'Public e-mail address',
      'public_phonenumber' => 'Public phone number',
      'header_image_id' => 'Header image',
      'is_public' => 'Make profile public',
      'is_listed' => 'Include profile in public listing'
    ),
    
    $validate = array(
      'user_id' => array('required', 'number'=>'integer', 'gt'=>0),
      'header_image_id' => array('number'=>'integer', 'gt'=>0),
      'title' => array('string', 'no_html', 'between'=>array(0,255)),
      'bio' => array('string', 'not_empty'),
      'signature' => array('string', 'not_empty', 'between'=>array(0, 65535)),
      'public_jid' => array('string', 'jid'=>'bare', 'between'=>array(0,255)),
      'public_email' => array('string', 'email', 'between'=>array(0,255)),
      'public_phonenumber' => array('string', 'phonenumber'=>'+31', 'between'=>array(0,255)),
      'is_public' => array('boolean'),
      'is_listed' => array('boolean')
    );
  
  //Alias for user_id
  public function get_id()
  {
    return $this->user_id;
  }
  
  public function get_account()
  {
    return tx('Sql')
      ->table('account', 'Accounts')
      ->pk($this->user_id)
      ->execute_single();
  }
  
  public function get_info()
  {
    return $this->account->user_info;
  }
  
  public function check_edit_permissions()
  {
    return tx('Account')->check_level(2) || tx('Account')->user->id->get('int') === $this->user_id->get('int');
  }
  
  public function get_avatar()
  {
    return tx('Sql')
      ->table('media', 'Images')
      ->pk($this->info->avatar_image_id)
      ->execute_single();
  }
  
  public function get_header()
  {
    return tx('Sql')
      ->table('media', 'Images')
      ->pk($this->header_image_id)
      ->execute_single();
  }
  
}
