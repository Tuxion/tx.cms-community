<?php namespace components\community\models; if(!defined('TX')) die('No direct access.');

class UserGroupProfiles extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'community_user_group_profiles',
    
    $relations = array(
      'Accounts' => array('owner_id' => 'account.Accounts.id'),
      'Images' => array('header_image_id' => 'media.Images.id'),
      'SecondaryImages' => array('logo_image_id' => 'SecondaryImages.id'),
      'AccountsToUserGroups' => array('user_group_id' => 'account.AccountsToUserGroups.user_group_id')
    ),
    
    $labels = array(
      'owner_id' => 'Owner',
      'logo_image_id' => 'Logo (180 x 180 pixels)',
      'header_image_id' => 'Header image',
      'title' => 'Group subtitle',
      'description' => 'Group description',
      'admission' => 'Admission type',
      'public_muc' => 'Public XMPP multi-user chat',
      'public_jid' => 'Public XMPP address',
      'public_email' => 'Public e-mail address',
      'public_phonenumber' => 'Public phone number',
      'header_image_id' => 'Header image',
      'is_public' => 'Make profile public',
      'is_listed' => 'Include profile in public listing'
    ),
    
    $validate = array(
      'user_group_id' => array('required', 'number'=>'integer', 'gt'=>0),
      'title' => array('string', 'no_html', 'between'=>array(0,255)),
      'description' => array('string'),
      'logo_image_id' => array('number'=>'integer', 'gt'=>0),
      'header_image_id' => array('number'=>'integer', 'gt'=>0),
      'is_public' => array('boolean'),
      'is_listed' => array('boolean'),
      'admission' => array('required', 'string', 'in'=>array('OPEN', 'APPROVE', 'CLOSED')),
      'public_muc' => array('string', 'jid'=>'bare', 'between'=>array(0,255)),
      'public_jid' => array('string', 'jid'=>'bare', 'between'=>array(0,255)),
      'public_email' => array('string', 'email', 'between'=>array(0,255)),
      'public_phonenumber' => array('string', 'phonenumber'=>'+31'),
      'signature' => array('string', 'not_empty', 'between'=>array(0, 65535)),
      'owner_id' => array('required', 'number'=>'integer', 'gt'=>0)
    );
  
  public function get_info()
  {
    return tx('Sql')
      ->table('account', 'UserGroups')
      ->pk($this->user_group_id)
      ->execute_single();
  }
  
  public function get_logo()
  {
    return tx('Sql')
      ->table('media', 'Images')
      ->pk($this->logo_image_id)
      ->execute_single();
  }
  
  public function get_header()
  {
    return tx('Sql')
      ->table('media', 'Images')
      ->pk($this->header_image_id)
      ->execute_single();
  }
  
  public function check_edit_permissions()
  {
    
    if(tx('Account')->check_level(2))
      return true;
    
    if(tx('Account')->check_level(1) && $this->owner_id->get() === tx('Account')->user->id->get())
      return true;
    
    return false;
    
  }
  
  public function get_members()
  {
    return tx('Sql')
      ->table('account', 'AccountsToUserGroups')
      ->where('user_group_id', $this->user_group_id)
      ->join('Accounts', $A)
      ->execute($A);
  }
  
  public function get_applications()
  {
    return tx('Sql')
      ->table('community', 'UserGroupApplications')
      ->where('user_group_id', $this->user_group_id)
      ->execute();
  }
  
  public function get_is_currently_member()
  {
    
    if(!tx('Account')->check_level(1))
      return false;
    
    return tx('Sql')
      ->table('account', 'AccountsToUserGroups')
      ->where('user_id', tx('Account')->user->id)
      ->where('user_group_id', $this->user_group_id)
      ->count()->get() > 0;
    
  }
  
  public function get_is_currently_applying()
  {
    
    if(!tx('Account')->check_level(1))
      return false;
    
    return tx('Sql')
      ->table('community', 'UserGroupApplications')
      ->where('user_id', tx('Account')->user->id)
      ->where('user_group_id', $this->user_group_id)
      ->count()->get() > 0;
    
  }
  
  public function get_can_leave()
  {
    
    if(!$this->is_currently_member->get('boolean'))
      return false;
    
    //Owners can't leave. Only transfer ownership or delete the group.
    if($this->owner_id->get() === tx('Account')->user->id->get())
      return false;
    
    return true;
    
  }
  
  public function get_can_apply()
  {
    
    if(!tx('Account')->check_level(1))
      return false;
    
    if($this->is_currently_applying->get('boolean'))
      return false;
    
    if($this->is_currently_member->get('boolean'))
      return false;
    
    return in_array($this->admission->get('string'), array('OPEN', 'APPROVE'));
    
  }
  
  public function get_can_approve()
  {
    return $this->check_edit_permissions();
  }
  
  public function get_can_delete()
  {
    return $this->check_edit_permissions();
  }
  
}
