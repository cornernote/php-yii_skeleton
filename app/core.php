<?php

/**
 * @return array
 */
function _core_settings()
{
    // load config file
    $file = dirname(__FILE__) . '/db.php';
    if (!file_exists($file)) die('no db file: ' . $file);
    $config = require($file);

    // connect to core db
    $db = mysql_connect($config['host'], $config['user'], $config['pass']) or die('no core connection');
    mysql_select_db($config['name'], $db) or die('no core database');

    // get core settings
    $q = mysql_query("SELECT * FROM {$config['table']} WHERE entity='core'", $db) or die('no core data');
    $core = array();
    while ($row = mysql_fetch_assoc($q)) {
        $core[$row['attribute']] = $row['value'];
    }

    // get app settings
    $q = mysql_query("SELECT * FROM {$config['table']} WHERE entity='app'", $db) or die('no core data');
    $app = array();
    while ($row = mysql_fetch_assoc($q)) {
        $app[$row['attribute']] = $row['value'];
    }

    // get user default settings
    $q = mysql_query("SELECT * FROM {$config['table']} WHERE entity='user'", $db) or die('no core data');
    $user = array();
    while ($row = mysql_fetch_assoc($q)) {
        $user[$row['attribute']] = $row['value'];
    }

    // disconnect from mysql
    mysql_close($db);

    return array(
        'db' => array(
            'host' => $config['host'],
            'name' => $config['name'],
            'user' => $config['user'],
            'pass' => $config['pass'],
            'table' => $config['table'],
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
