<?php namespace components\community\models; if(!defined('TX')) die('No direct access.');

class UserProfile extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'community_user_profile',
    
    $relations = array(
      'Accounts' => array('user_id' => 'accounts.Accounts.id'),
      'Images' => array('header_image_id' => 'media.Images.id')
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
      'title' => array('string', 'no_html', 'between'=>array(1,255)),
      'bio' => array('string', 'not_empty'),
      'signature' => array('string', 'not_empty', 'between'=>array(1, 65535)),
      'public_jid' => array('string', 'jid'=>'bare', 'between'=>array(1,255)),
      'public_email' => array('string', 'email', 'between'=>array(1,255)),
      'public_phonenumber' => array('string', 'phonenumber'=>'+31', 'between'=>array(1,255)),
      'header_image_id' => array('number'=>'integer', 'gt'=>0),
      'is_public' => array('boolean'),
      'is_listed' => array('boolean')
    );
  
}
