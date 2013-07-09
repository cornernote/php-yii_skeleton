<?php
$config = array(

    // yii settings
    'id' => $_ENV['_settings']['core']['id'],
    'name' => $_ENV['_settings']['app']['name'],
    'language' => $_ENV['_settings']['app']['language'],

    // paths
    'basePath' => dirname(dirname(__FILE__)),
    'runtimePath' => dirname(dirname(dirname(dirname(__FILE__)))) . '/runtime',

    // preload classes and autoload paths
    'preload' => array('log'),
    'import' => array(
        'application.extensions.*',
        'application.commands.*',
        'application.models.*',
        'application.components.*',
        'application.components.behaviors.*',
        'application.components.validators.*',
    ),

    // modules
    'modules' => array(),

    // components
    'components' => array(
        'user' => array(
            'class' => 'application.components.WebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/account/login'),
        ),
        'urlManager' => array(
            'urlFormat' => isset($_GET['r']) ? 'get' : 'path', // allow filters in pageTrail/index work
            'showScriptName' => false,
        ),
        'themeManager' => array(
            'basePath' => dirname(dirname(__FILE__)) . '/themes',
        ),
        'db' => array(
            'connectionString' => "mysql:host={$_ENV['_settings']['db']['host']};dbname={$_ENV['_settings']['db']['name']}",
            'emulatePrepare' => true,
            'username' => $_ENV['_settings']['db']['user'],
            'password' => $_ENV['_settings']['db']['pass'],
            'charset' => 'utf8',
            'schemaCachingDuration' => 3600,
            'enableProfiling' => $_ENV['_settings']['core']['debug_db'],
            'enableParamLogging' => $_ENV['_settings']['core']['debug_db'],
        ),
        'errorHandler' => array(
            'class' => 'ErrorHandler',
            'errorAction' => 'site/error',
        ),
        'cacheFile' => array(
            'class' => 'CFileCache',
        ),
        'cache' => array(
            'class' => 'CMemCache',
            'keyPrefix' => 'keyvault.',
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
            //'responsiveCss' => true,
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