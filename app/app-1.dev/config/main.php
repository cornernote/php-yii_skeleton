<?php
$config = array(

    // yii settings
    'id' => $_ENV['_core']['setting']['id'],
    'name' => $_ENV['_core']['setting']['name'],
    'language' => $_ENV['_core']['setting']['language'],

    // paths
    'basePath' => dirname(dirname(__FILE__)),
    'runtimePath' => dirname(dirname(dirname(dirname(__FILE__)))) . '/runtime',

    // preload classes
    'preload' => array(
        'log',
        'fatalErrorCatch',
        'globalInit',
    ),

    // import paths
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
        ),
    ),

    // default settings, access using: Setting::item('paramName')
    // add field to views.setting.index to allow override
    'params' => array(
        'dateFormat' => 'Y-m-d',
        'dateFormatLong' => 'Y-m-d',
        'timeFormat' => 'H:i:s',
        'timeFormatLong' => 'H:i:s',
        'dateTimeFormat' => 'Y-m-d H:i:s',
        'dateTimeFormatLong' => 'Y-m-d H:i:s',

        'allowAutoLogin' => true,
        'rememberMe' => true,
        'defaultPageSize' => '10',
        'website' => 'localhost',
        'email' => 'webmaster@localhost',
        'error_email' => 'webmaster@localhost',

        'recaptcha' => false,
        'recaptchaPrivate' => '6LeBItQSAAAAALA4_G05e_-fG5yH_-xqQIN8AfTD',
        'recaptchaPublic' => '6LeBItQSAAAAAG_umhiD0vyxXbDFbVMPA0kxZUF6',
    ),

);
// local main config overrides
$local = array();
if (file_exists(dirname(__FILE__) . '/main.local.php')) {
    $local = require(dirname(__FILE__) . '/main.local.php');
}
return CMap::mergeArray($config, $local);