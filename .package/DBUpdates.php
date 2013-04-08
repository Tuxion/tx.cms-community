<?php namespace components\community; if(!defined('TX')) die('No direct access.');

//Make sure we have the things we need for this class.
tx('Component')->check('update');
tx('Component')->load('update', 'classes\\BaseDBUpdates', false);

class DBUpdates extends \components\update\classes\BaseDBUpdates
{
  
  protected
    $component = 'community',
    $updates = array(
    );
  
  public function install_0_1($dummydata, $forced)
  {
    
    if($forced === true){
      tx('Sql')->query('DROP TABLE IF EXISTS `#__community_user_profile`');
      tx('Sql')->query('DROP TABLE IF EXISTS `#__community_user_group_profile`');
    }
    
    tx('Sql')->query('
      CREATE TABLE `#__community_user_profile` (
        `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
      CREATE TABLE `#__community_user_group_profile` (
        `user_group_id` int(10) unsigned NOT NULL,
        `owner_id` int(10) unsigned NOT NULL,
        `admission` ENUM(\'OPEN\', \'APPROVE\', \'INVITE\', \'CLOSED\'),
        `title` varchar(255) NULL,
        `bio` LONGTEXT NULL,
        `signature` TEXT NULL,
        `public_muc` varchar(255) NULL,
        `public_jid` varchar(255) NULL,
        `public_email` varchar(255) NULL,
        `public_phonenumber` varchar(255) NULL,
        `header_image_id` int(10) unsigned NULL,
        `is_public` bit(1) NOT NULL DEFAULT b\'0\',
        `is_listed` bit(1) NOT NULL DEFAULT b\'0\',
        PRIMARY KEY (`user_group_id`),
        KEY `owner_id` (`owner_id`),
        KEY `is_public` (`is_public`),
        KEY `is_listed` (`is_listed`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ');
    
    /*
    //Queue self-deployment with CMS component.
    $this->queue(array(
      'component' => 'cms',
      'min_version' => '1.2'
      ), function($version){
        
        //Ensures the mail component and mailing view.
        tx('Component')->helpers('cms')->_call('ensure_pagetypes', array(
          array(
            'name' => 'mail',
            'title' => 'Mailing component'
          ),
          array(
            'mailing' => true
          )
        ));
        
      }
    ); //END - Queue CMS 1.2+
    */
    
  }
  
}

