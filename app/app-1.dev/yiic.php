<?php

// start the timer
$GLOBALS['start'] = microtime(true);

// include core functions
$core = dirname(__FILE__) . '/core.php';
if (!file_exists($core)) {
    echo 'ERROR - cannot load core';
    exit;
}
require_once($core);

// load core settings
$GLOBALS['settings'] = _core_settings();

// set default php settings
date_default_timezone_set($GLOBALS['settings']['core']['timezone']);
ini_set('memory_limit', $GLOBALS['settings']['core']['memory_limit']);
ini_set('xdebug.max_nesting_level', 200);

// set debug levels
if ($GLOBALS['settings']['core']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $GLOBALS['settings']['core']['debug']);
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
$yiic = dirname(__FILE__) . '/../../vendors/yii/' . $GLOBALS['settings']['core']['yii_version'] . '/framework/yiic.php';
if (!file_exists($yiic)) {
    echo 'ERROR - cannot load framework';
    exit;
}

// run the Yii app (Yii-Haw!)
require_once($yiic);