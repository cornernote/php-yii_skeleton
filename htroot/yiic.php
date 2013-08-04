<?php

// ensure cli is being called
if (substr(php_sapi_name(), 0, 3) != 'cli') {
    trigger_error('This script needs to be run from a CLI.', E_USER_ERROR);
}

// start the timer
$_ENV['_start'] = microtime(true);

// include core functions
$core = dirname(__FILE__) . '/../core.php';
if (!file_exists($core)) {
    trigger_error('cannot find core file at "' . $core . '"', E_USER_ERROR);
}
require_once($core);

// load core settings
$_ENV['_core'] = _core();

// set default php settings
date_default_timezone_set($_ENV['_core']['setting']['timezone']);
set_time_limit(0);
ini_set('memory_limit', $_ENV['_core']['setting']['memory_limit']);
ini_set('xdebug.max_nesting_level', 200);

// set debug levels
if ($_ENV['_core']['setting']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $_ENV['_core']['setting']['debug']);
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
    trigger_error('cannot find globals file at "' . $globals . '"', E_USER_ERROR);
}
require_once($globals);

// define path to congig
$config = dirname(__FILE__) . '/config/console.php';
if (!file_exists($config)) {
    trigger_error('cannot find config file at "' . $config . '"', E_USER_ERROR);
}

// include Yiic
$yiic = dirname(__FILE__) . '/../../vendors/yii/' . $_ENV['_core']['setting']['yii_version'] . '/framework/yiic.php';
if (!file_exists($yiic)) {
    trigger_error('cannot find framework file at "' . $yii . '"', E_USER_ERROR);
}

// run the Yii CLI app (Yii-Haw!)
require_once($yiic);