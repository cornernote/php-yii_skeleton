<?php
$config = require(dirname(__FILE__) . '/main.php');
$web = array();

// web only preloads
//$web['preload'][] = 'kint'; // cannot be in main.php  due to issue in commands
$web['preload'][] = 'bootstrap';

// -- LOG ROUTES --
// CDbLogRoute: saves messages in a database table.
// CEmailLogRoute: sends messages to specified email addresses.
// CFileLogRoute: saves messages in a file under the application runtime directory.
// CWebLogRoute: displays messages at the end of the current Web page.
// CProfileLogRoute: displays profiling messages at the end of the current Web page.

$web['components']['log']['routes'] = array();
if ($_ENV['_core']['setting']['debug']) {

    $web['components']['log']['routes'][] = array(
        'class' => 'CWebLogRoute',
        'levels' => $_ENV['_core']['setting']['debug_levels'],
        //'levels' => 'trace, info, error, warning, profile',
    );
    if ($_ENV['_core']['setting']['debug_db']) {
        $web['components']['log']['routes'][] = array(
            'class' => 'ProfileLogRoute',
            'levels' => 'profile',
        );
    }

}
else {

    // no debug, file log route
    $web['components']['log']['routes'][] = array(
        'class' => 'CFileLogRoute',
        'levels' => $_ENV['_core']['setting']['debug_levels'],
    );

}

// asset paths
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptName == '/') {
    $scriptName = '';
}
$web['components']['assetManager'] = array(
    'class' => 'AssetManager',
    'basePath' => dirname($_SERVER['SCRIPT_FILENAME']) . '/assets',
    'baseUrl' => $scriptName . '/assets',
);

// default theme
$web['theme'] = $_ENV['_core']['setting']['theme'] ? $_ENV['_core']['setting']['theme'] : 'lite';
$web['params']['themes'] = array(
    '' => 'Bootstrap',
    'lite' => 'Lite',
    'admingrey' => 'Admin Grey',
    'bounce' => 'Bounce',
    'reboot' => 'Reboot',
);

// local web config overrides
$local = array();
if (file_exists(dirname(__FILE__) . '/web.local.php')) {
    $local = require(dirname(__FILE__) . '/web.local.php');
}
return CMap::mergeArray($config, $web, $local);