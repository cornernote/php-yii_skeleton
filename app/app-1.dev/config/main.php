<?php
$config = array(

    // yii settings
    'id' => $_ENV['_core']['setting']['id'],
    'name' => $_ENV['_core']['setting']['name'],
    'language' => $_ENV['_core']['setting']['language'],
    'params' => $_ENV['_core']['setting'],

    // paths
    'basePath' => dirname(dirname(__FILE__)),
    'runtimePath' => dirname(dirname(dirname(dirname(__FILE__)))) . '/runtime',

    // preload classes and autoload paths
    'preload' => array('log', 'fatalErrorCatch', 'globalInit'),
    'import' => array(
        'application.commands.*',
        'application.models.*',
        'application.components.*',
    ),

    // modules
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            'generatorPaths' => array(
                'application.modules.gii.generators', // a path alias
            ),
            // 'ipFilters'=>array(...a list of IPs...),
            // 'newFileMode'=>0666,
            // 'newDirMode'=>0777,
        ),
    ),

    // components
    'components' => array(
        'user' => array(
            'class' => 'application.components.WebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/account/login'),
        ),
        'urlManager' => array(
            'urlFormat' => isset($_GET['r']) ? 'get' : 'path', // allow filters in audit/index work
            'showScriptName' => false,
        ),
        'themeManager' => array(
            'basePath' => dirname(dirname(__FILE__)) . '/themes',
        ),
        'db' => array(
            'connectionString' => "mysql:host={$_ENV['_core']['db']['host']};dbname={$_ENV['_core']['db']['name']}",
            'emulatePrepare' => true,
            'username' => $_ENV['_core']['db']['user'],
            'password' => $_ENV['_core']['db']['pass'],
            'charset' => 'utf8',
            'schemaCachingDuration' => 3600,
            'enableProfiling' => $_ENV['_core']['setting']['debug_db'],
            'enableParamLogging' => $_ENV['_core']['setting']['debug_db'],
        ),
        'errorHandler' => array(
            'class' => 'ErrorHandler',
            'errorAction' => 'site/error',
        ),
        'fatalErrorCatch' => array(
            'class' => 'FatalErrorCatch',
        ),
        'globalInit' => array(
            'class' => 'GlobalInit',
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
        'eMailManager' => array(
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

);
// local main config overrides
$local = array();
if (file_exists(dirname(__FILE__) . '/main.local.php')) {
    $local = require(dirname(__FILE__) . '/main.local.php');
}
return CMap::mergeArray($config, $local);