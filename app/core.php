<?php
/**
 * Core Settings
 */
 
$_core = array(
	'path' => dirname(__FILE__),
	'db' => array(
		'host' => 'localhost',
		'user' => 'root',
		'pass' => '',
		'name' => 'test',
		'setting' => 'setting',
	), 
	'setting' => array(
		'id' => 'app',
		'name' => 'App',
		'brand' => 'App',
		'address' => "123 Fake St\nFakesville\nABC 1234",
		'phone' => '01 2345 6789',
		'email' => 'webmaster@localhost',
		'website' => 'localhost',
		'language' => 'en',
		'timezone' => 'GMT',
		'theme' => 'lite',
		'app_version' => 'app-1.dev',
		'yii_version' => 'yii-1.1.13.e9e4a0',
		'yii_lite' => false,
		'debug' => true,
		'debug_db' => false,
		'debug_levels' => 'error,warning',
		'error_email' => 'webmaster@localhost',
		'time_limit' => 60,
		'memory_limit' => '128M',
		'audit' => false,
	),
);
$_core_db = mysql_connect($_core['db']['host'], $_core['db']['user'], $_core['db']['pass']);
if ($_core_db && mysql_select_db($_core['db']['name'], $_core_db)) {
    mysql_set_charset('utf8', $_core_db);
	$_core['db']['setting'] = isset($_core['db']['setting']) ? $_core['db']['setting'] : 'setting'; // decide which table to use
	$q = mysql_query("SELECT * FROM {$_core['db']['setting']}", $_core_db);
	if ($q) while ($row = mysql_fetch_assoc($q))
		$_core['setting'][$row['key']] = $row['value'];
	mysql_close($_core_db);
	unset($_core_db);
}
return $_core;