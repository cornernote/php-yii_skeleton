<?php
$config = require(dirname(__FILE__) . '/main.php');
$web = array();

// cannot be in main.php  due to issue in commands
$web['preload'][] = 'kint';
$web['preload'][] = 'bootstrap';

// -- LOG ROUTES --
// CDbLogRoute: saves messages in a database table.
// CEmailLogRoute: sends messages to specified email addresses.
// CFileLogRoute: saves messages in a file under the application runtime directory.
// CWebLogRoute: displays messages at the end of the current Web page.
// CProfileLogRoute: displays profiling messages at the end of the current Web page.

$web['components']['log']['routes'] = array();
if ($_ENV['_settings']['core']['debug']) {

    // enable the debug toolbar
    if ($_ENV['_settings']['core']['debug_toolbar']) {
        $web['components']['log']['routes'][] = array(
            'class' => 'XWebDebugRouter',
            'config' => 'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
            'levels' => $_ENV['_settings']['core']['debug_levels'] ? $_ENV['_settings']['core']['debug_levels'] : 'none',
            'allowedIPs' => array('.*'),
        );
    } // web log route
    else {
        $web['components']['log']['routes'][] = array(
            'class' => 'CWebLogRoute',
            'levels' => $_ENV['_settings']['core']['debug_levels'] ? $_ENV['_settings']['core']['debug_levels'] : 'none',
            //'levels' => 'trace, info, error, warning, profile',
        );
        if ($_ENV['_settings']['core']['debug_db']) {
            $web['components']['log']['routes'][] = array(
                'class' => 'ProfileLogRoute',
                'levels' => 'profile',
            );
        }
    }

}
else {

    // no debug, file log route
    $web['components']['log']['routes'][] = array(
        'class' => 'CFileLogRoute',
        'levels' => $_ENV['_settings']['core']['debug_levels'] ? $_ENV['_settings']['core']['debug_levels'] : 'none',
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
$web['theme'] = $_ENV['_settings']['app']['theme'];
$web['params']['themes'] = array(
    '' => 'Bootstrap',
    'admingrey' => 'Admin Grey',
    'bounce' => 'Bounce',
);

// local web config overrides
$local = array();
if (file_exists(dirname(__FILE__) . '/web.local.php')) {
    $local = require(dirname(__FILE__) . '/web.local.php');
}
return CMap::mergeArray($config, $web, $local);