<?php

/**
 * @return array core settings
 */
function _core()
{
	$core = array(
		'db' => require(dirname(__FILE__) . '/db.php'), 
		'path' => dirname(__FILE__),
		'setting' => array(),
	);
    $db = mysql_connect($core['db']['host'], $core['db']['user'], $core['db']['pass']) or die('no core connection');
    mysql_select_db($core['db']['name'], $db) or die('no core database');
	$core['db']['setting'] = isset($core['db']['setting']) ? $core['db']['setting'] : 'setting'; // decide which table to use
    $q = mysql_query("SELECT * FROM {$core['db']['setting']}", $db) or die('no core data');
    while ($row = mysql_fetch_assoc($q))
        $core['setting'][$row['key']] = $row['value'];
    mysql_close($db);
    return $core;
}