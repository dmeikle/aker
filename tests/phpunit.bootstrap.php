<?php

date_default_timezone_set('America/Vancouver');

$site_path =  realpath(dirname(__FILE__));// strip the /web from it

define ('__SITE_PATH', $site_path);
define ('__CACHE_DIRECTORY', $site_path . '/app/cache');
//include_once('phpunit.configuration.php');
require_once(__SITE_PATH . '/../vendor/composer/ClassLoader.php');

$loader = new Composer\Autoload\ClassLoader();
// register classes with namespaces
$loader->add('Gossamer\\Aker', __SITE_PATH .'/../src');
$loader->add('Gossamer\\Horus', __SITE_PATH .'/../vendor/gossamer/horus/src');
$loader->add('Gossamer\\Pesedget', __SITE_PATH .'/../vendor/gossamer/pesedget/src');
$loader->add('Gossamer\\Caching', __SITE_PATH .'/../vendor/gossamer/caching/src');
$loader->add('Monolog', __SITE_PATH.'/../vendor/monolog/monolog/src');

// activate the autoloader
$loader->register();

// to enable searching the include path (eg. for PEAR packages)
$loader->setUseIncludePath(true);


$_SERVER['DOCUMENT_ROOT'] = getenv('DOCUMENT_ROOT');
$_SERVER['REMOTE_ADDR'] = '192.168.2.120';