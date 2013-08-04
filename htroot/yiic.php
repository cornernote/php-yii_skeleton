<?php

// ensure cli is being called
if (substr($sapi_type, 0, 3) != 'cli') {
    echo 'ERROR - need to run from cli';
	exit;
}

// start the timer
$_ENV['_start'] = microtime(true);

// include core functions
$core = dirname(__FILE__) . '/../core.php';
if (!file_exists($core)) {
    echo 'ERROR - cannot load core';
    exit;
}
require_once($core);

// load core settings
$_ENV['_settings'] = _core();

// set default php settings
date_default_timezone_set($_ENV['_settings']['core']['timezone']);
set_time_limit(0);
ini_set('memory_limit', $_ENV['_settings']['core']['memory_limit']);
ini_set('xdebug.max_nesting_level', 200);

// set debug levels
if ($_ENV['_settings']['core']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $_ENV['_settings']['core']['debug']);
}
else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 0);
    defined('YII_DEBUG') or define('YII_DEBUG', false);
}

// include global functions
$globals = dirname(__FILE__) . '/globals.php';
if (!file_exists($globals)) {
    echo 'ERROR - cannot load globals';
    exit;
}
require_once($globals);

// define path to congig
$config = dirname(__FILE__) . '/config/console.php';
if (!file_exists($config)) {
    echo 'ERROR - cannot load config';
    exit;
}

// include Yiic
$yiic = dirname(__FILE__) . '/../../vendors/yii/' . $_ENV['_settings']['core']['yii_version'] . '/framework/yiic.php';
if (!file_exists($yiic)) {
    echo 'ERROR - cannot load framework';
    exit;
}

// run the Yii app (Yii-Haw!)
require_once($yiic);