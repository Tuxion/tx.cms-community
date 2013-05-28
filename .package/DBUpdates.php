<?php namespace components\community; if(!defined('TX')) die('No direct access.');

//Make sure we have the things we need for this class.
tx('Component')->check('update');
tx('Component')->load('update', 'classes\\BaseDBUpdates', false);

class DBUpdates extends \components\update\classes\BaseDBUpdates
{
  
  protected
    $component = 'community',
    $updates = array(
      '0.1' => '0.2',
      '0.2' => '0.3'
    );
  
  public function update_to_0_3($current_version, $forced)
  {
    
    if($forced === true){
      tx('Sql')->query('DROP TABLE IF EXISTS `#__community_user_group_applications`');
    }
    
    tx('Sql')->query('
      CREATE TABLE `#__community_user_group_applications` (
        `user_id` int(10) unsigned NOT NULL,
        `user_group_id` int(10) unsigned NOT NULL,
        PRIMARY KEY (`user_id`, `user_group_id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ');
    
    try{
      
      tx('Sql')->query('
        ALTER TABLE `#__community_user_group_profiles`
          ADD `description` longtext NULL DEFAULT NULL after `title`
      ');
      
    }catch(\exception\Sql $ex){
      //When it's not forced, this is a problem.
      //But when forcing, ignore this.
      if(!$forced) throw $ex;
    }
    
  }
  
  public function update_to_0_2($current_version, $forced)
  {
    
    //Queue self-deployment with CMS component.
    $this->queue(array(
      'component' => 'cms',
      'min_version' => '3.0'
      ), function($version){
        
        //Ensures the mail component and mailing view.
        tx('Component')->helpers('cms')->_call('ensure_pagetypes', array(
          array(
            'name' => 'community',
            'title' => 'A community component that allows extended profile and group pages'
          ),
          array(
            'settings' => 'SETTINGS'
          )
        ));
        
      }
    ); //END - Queue CMS
    
  }
  
  public function install_0_1($dummydata, $forced)
  {
    
    if($forced === true){
      tx('Sql')->query('DROP TABLE IF EXISTS `#__community_user_profiles`');
      tx('Sql')->query('DROP TABLE IF EXISTS `#__community_user_group_profiles`');
    }
    
    tx('Sql')->query('
      CREATE TABLE `#__community_user_profiles` (
        `user_id` int(10) unsigned NOT NULL,
        `title` varchar(255) NULL,
        `bio` LONGTEXT NULL,
        `signature` TEXT NULL,
        `public_jid` varchar(255) NULL,
        `public_email` varchar(255) NULL,
        `public_phonenumber` varchar(255) NULL,
        `header_image_id` int(10) unsigned NULL,
        `is_public` bit(1) NOT NULL DEFAULT b\'0\',
        `is_listed` bit(1) NOT NULL DEFAULT b\'0\',
        PRIMARY KEY (`user_id`),
        KEY `is_public` (`is_public`),
        KEY `is_listed` (`is_listed`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ');
    tx('Sql')->query('
      CREATE TABLE `#__community_user_group_profiles` (
        `user_group_id` int(10) unsigned NOT NULL,
        `owner_id` int(10) unsigned NOT NULL,
        `admission` ENUM(\'OPEN\', \'APPROVE\', \'INVITE\', \'CLOSED\') NOT NULL DEFAULT \'OPEN\',
        `title` varchar(255) NULL,
        `signature` TEXT NULL,
        `public_muc` varchar(255) NULL,
        `public_jid` varchar(255) NULL,
        `public_email` varchar(255) NULL,
        `public_phonenumber` varchar(255) NULL,
        `logo_image_id` int(10) unsigned NULL,
        `header_image_id` int(10) unsigned NULL,
        `is_public` bit(1) NOT NULL DEFAULT b\'0\',
        `is_listed` bit(1) NOT NULL DEFAULT b\'0\',
        PRIMARY KEY (`user_group_id`),
        KEY `owner_id` (`owner_id`),
        KEY `is_public` (`is_public`),
        KEY `is_listed` (`is_listed`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ');
    
    //Queue self-deployment with CMS component.
    $this->queue(array(
      'component' => 'cms',
      'min_version' => '1.2'
      ), function($version){
        
        //Ensures the mail component and mailing view.
        tx('Component')->helpers('cms')->_call('ensure_pagetypes', array(
          array(
            'name' => 'community',
            'title' => 'A community component that allows extended profile and group pages'
          ),
          array(
            'users' => false,
            'usergroups' => false
          )
        ));
        
      }
    ); //END - Queue CMS 1.2+
    
  }
  
}

