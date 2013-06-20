<?php
$config = array(

    // yii settings
    'id' => $GLOBALS['settings']['core']['id'],
    'name' => $GLOBALS['settings']['app']['name'],
    'language' => $GLOBALS['settings']['app']['language'],

    // paths
    'basePath' => dirname(dirname(__FILE__)),
    'runtimePath' => dirname(dirname(dirname(dirname(__FILE__)))) . '/runtime',

    // preload classes and autoload paths
    'preload' => array('log'),
    'import' => array(
        'application.commands.*',
        'application.models.*',
        'application.components.*',
        'application.components.behaviors.*',
        'application.components.validators.*',
    ),

    // modules
    'modules' => array(
    ),

    // components
    'components' => array(
        'user' => array(
            'class' => 'application.components.WebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/user/login'),
        ),
        'urlManager' => array(
            'urlFormat' => isset($_GET['r']) ? 'get' : 'path', // allow filters in pageTrail/index work
            'showScriptName' => false,
        ),
        'themeManager' => array(
            'basePath' => dirname(dirname(__FILE__)) . '/themes',
        ),
        'db' => array(
            'connectionString' => "mysql:host={$GLOBALS['settings']['db']['host']};dbname={$GLOBALS['settings']['db']['name']}",
            'emulatePrepare' => true,
            'username' => $GLOBALS['settings']['db']['user'],
            'password' => $GLOBALS['settings']['db']['pass'],
            'charset' => 'utf8',
            'schemaCachingDuration' => 3600,
            'enableProfiling' => $GLOBALS['settings']['core']['debug_db'],
            'enableParamLogging' => $GLOBALS['settings']['core']['debug_db'],
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'cacheFile' => array(
            'class' => 'CFileCache',
        ),
        'cache' => array(
            'class' => 'CMemCache',
            'keyPrefix' => 'kv.',
            'servers' => array(
                array(
                    'host' => '127.0.0.1',
                    'port' => 11211,
                    'weight' => 10,
                ),
            ),
        ),
        'log' => array(
            'class' => 'CLogRouter',
        ),
        'clientScript' => array(
            'class' => 'ClientScript',
        ),
        'swiftMailer' => array(
            'class' => 'application.extensions.swiftMailer.SwiftMailer',
        ),
        'mailManager' => array(
            'class' => 'EMailManager',
        ),
        'kint' => array(
            'class' => 'ext.kint.Kint',
        ),
        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap',
            'responsiveCss' => true,
        ),
    ),

    // application-level parameters - accessed using
    // Yii::app()->params['paramName'] or param('paramName')
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@brett.local',
        'defaultPageSize' => 10,
    ),
);
// local main config overrides
$local = array();
if (file_exists(dirname(__FILE__) . '/main.local.php')) {
    $local = require(dirname(__FILE__) . '/main.local.php');
}
return CMap::mergeArray($config, $local);