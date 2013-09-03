<?php namespace components\community\models; if(!defined('TX')) die('No direct access.');

tx('Sql')->model('media', 'Images');

class SecondaryImages extends \components\media\models\Images{}