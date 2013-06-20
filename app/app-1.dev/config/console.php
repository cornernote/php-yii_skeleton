<?php
$config = require(dirname(__FILE__) . '/main.php');

$config['components']['db']['enableProfiling'] = false;
$config['components']['db']['enableParamLogging'] = false;

$console = array(
    'commandMap' => array(
        'migrate' => array(
            'class' => 'system.cli.commands.MigrateCommand',
            'migrationPath' => 'application.migrations',
            'migrationTable' => 'migration',
            'connectionID' => 'db',
            'templateFile' => 'application.migrations.templates.migrate_template',
        ),
    ),
);
// local console config overrides
$local = array();
if (file_exists(dirname(__FILE__) . '/console.local.php')) {
    $local = require(dirname(__FILE__) . '/console.local.php');
}
return CMap::mergeArray($config, $console, $local);