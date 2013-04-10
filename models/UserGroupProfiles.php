<?php namespace components\community\models; if(!defined('TX')) die('No direct access.');

class UserGroupProfiles extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'community_user_group_profiles',
    
    $relations = array(
      'Accounts' => array('owner_id' => 'account.Accounts.id'),
      'Images' => array('header_image_id' => 'media.Images.id')
    ),
    
    $labels = array(
      'owner_id' => 'Owner',
      'title' => 'Group subtitle',
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
      'logo_image_id' => array('number'=>'integer', 'gt'=>0),
      'header_image_id' => array('number'=>'integer', 'gt'=>0),
      'title' => array('string', 'no_html', 'between'=>array(1,255)),
      'signature' => array('string', 'not_empty', 'between'=>array(1, 65535)),
      'public_muc' => array('string', 'jid'=>'bare', 'between'=>array(1,255)),
      'public_jid' => array('string', 'jid'=>'bare', 'between'=>array(1,255)),
      'public_email' => array('string', 'email', 'between'=>array(1,255)),
      'public_phonenumber' => array('string', 'phonenumber'=>'+31'),
      'owner_id' => array('required', 'number'=>'integer', 'gt'=>0),
      'admission' => array('required', 'string', 'in'=>array('OPEN', 'APPROVE', 'INVITE', 'CLOSED')),
      'is_public' => array('boolean'),
      'is_listed' => array('boolean')
    );
  
  public function get_info()
  {
    return tx('Sql')
      ->table('account', 'UserGroups')
      ->pk($this->user_group_id)
      ->execute_single();
  }
  
}
