<?php

// start the timer
$_ENV['_start'] = microtime(true);

// include core functions
$core = dirname(__FILE__) . '/../app/core.php';
if (!file_exists($core)) {
    echo '<b>ERROR</b> - cannot load core';
    exit;
}
require_once($core);

// load core settings
$_ENV['_settings'] = _core();

// set default php settings
date_default_timezone_set($_ENV['_settings']['core']['timezone']);
set_time_limit($_ENV['_settings']['core']['time_limit']);
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
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 0);
    defined('YII_DEBUG') or define('YII_DEBUG', false);
}

// include global functions
$globals = dirname(__FILE__) . '/../app/' . $_ENV['_settings']['core']['app_version'] . '/globals.php';
if (!file_exists($globals)) {
    echo '<b>ERROR</b> - cannot load globals';
    exit;
}
require_once($globals);

// define path to congig
$config = dirname(__FILE__) . '/../app/' . $_ENV['_settings']['core']['app_version'] . '/config/web.php';
if (!file_exists($config)) {
    echo '<b>ERROR</b> - cannot load config';
    exit;
}

// include Yii, or custom Yii
if ($_ENV['_settings']['core']['yii_lite']) {
    $yii = dirname(__FILE__) . '/../vendors/yii/' . $_ENV['_settings']['core']['yii_version'] . '/framework/yiilite.php';
}
else {
    $yii = dirname(__FILE__) . '/../vendors/yii/' . $_ENV['_settings']['core']['yii_version'] . '/framework/yii.php';
}
if (!file_exists($yii)) {
    echo '<b>ERROR</b> - cannot load framework';
    exit;
}

// run the Yii app (Yii-Haw!)
require_once($yii);
$webApp = Yii::createWebApplication($config);
$webApp->run();