<?php

/**
 * @return array
 */
function _core_settings()
{
    // load config file
    $file = dirname(__FILE__) . '/../../config/database.php';
    if (!file_exists($file)) die('no core file: ' . $file);
    $_core_config = require($file);
    if (!isset($_core_config['table'])) $_core_config['table'] = 'ff_setting_eav';

    // connect to core db
    $db = mysql_connect($_core_config['host'], $_core_config['user'], $_core_config['pass']) or die('no core connection');
    mysql_select_db($_core_config['name'], $db) or die('no core database');

    // get core settings
    $q = mysql_query("SELECT * FROM {$_core_config['table']} WHERE entity='core'", $db) or die('no core data');
    $core = array();
    while ($row = mysql_fetch_assoc($q)) {
        $core[$row['attribute']] = $row['value'];
    }

    // get app settings
    $q = mysql_query("SELECT * FROM {$_core_config['table']} WHERE entity='app'", $db) or die('no core data');
    $app = array();
    while ($row = mysql_fetch_assoc($q)) {
        $app[$row['attribute']] = $row['value'];
    }

    // get user default settings
    $q = mysql_query("SELECT * FROM {$_core_config['table']} WHERE entity='user'", $db) or die('no core data');
    $user = array();
    while ($row = mysql_fetch_assoc($q)) {
        $user[$row['attribute']] = $row['value'];
    }

    // disconnect from mysql
    mysql_close($db);

    return array(
        'db' => array(
            'host' => $_core_config['host'],
            'name' => $_core_config['name'],
            'user' => $_core_config['user'],
            'pass' => $_core_config['pass'],
            'table' => $_core_config['table'],
        ),
        'core' => $core,
        'app' => $app,
        'user' => $user,
    );
}



//    // load override settings from cookie
//    $cookieSettings = isset($_COOKIE['settings']) ? unserialize(stripslashes($_COOKIE['settings'])) : array('settings' => array());
//    if (isset($cookieSettings['key']) && isset($cookieSettings['ttl'])) {
//        if ($cookieSettings['key'] == md5(serialize($cookieSettings['settings']) . $cookieSettings['ttl'] . 'pSh%3w3fs#')) {
//            foreach ($settings as $k => $v) {
//                if (isset($cookieSettings['settings'][$k])) {
//                    $settings[$k] = array_merge($settings[$k], $cookieSettings['settings'][$k]);
//                }
//            }
//        }
//    }
