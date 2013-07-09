<?php

/**
 * This is the shortcut to DIRECTORY_SEPARATOR
 */
/**
 *
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * This is the shortcut to Yii::app()
 * @return WebApplication
 */
function app()
{
    return Yii::app();
}

/**
 * This is the shortcut to Yii::app()->clientScript
 */
function cs()
{
    // You could also call the client script instance via Yii::app()->clientScript
    // But this is faster
    return app()->getClientScript();
}

/**
 * This is the shortcut to Yii::app()->user.
 * @return WebUser
 */
function user()
{
    if (!isset(app()->user)) {
        return false;
    }
    return app()->user;
}

/**
 * This is the shortcut to Yii::app()->createUrl()
url('package/view', array(
'id'=>$this->id,
);
 */
function url($route, $params = array(), $ampersand = '&')
{
    if (is_array($route)) {
        $params = CMap::mergeArray($route, $params);
        $route = $params[0];
        unset($params[0]);
    }
    return Yii::app()->createUrl($route, $params, $ampersand);
}

/**
 * This is the shortcut to Yii::app()->urlManager->parseUrl(Yii::app()->getRequest())
 */
function route()
{
    return parseUrl(request());
}

/**
 * This is the shortcut to Yii::app()->getRequest()
 */
function request()
{
    return Yii::app()->getRequest();
}

/**
 * This is the shortcut to Yii::app()->getRequest()->getRequestUri()
 */
function ru()
{
    return Yii::app()->getRequest()->getRequestUri();
}

/**
 * This is the shortcut to Yii::app()->urlManager->parseUrl()
 */
function parseUrl($url)
{
    return Yii::app()->urlManager->parseUrl($url);
}

/**
 * This is the shortcut to CHtml::encode
 */
function h($text)
{
    return htmlspecialchars($text, ENT_QUOTES, Yii::app()->charset);
}

/**
 * This is the shortcut to CHtml::link()
 * echo l('click here',array('package/view', 'id' => $this->id));
 */
function l($text, $url = '#', $htmlOptions = array())
{
    return CHtml::link($text, $url, $htmlOptions);
}

/**
 * This is the shortcut to CHtml::linkButton()
 */
function lb($image, $htmlOptions = array())
{
    return CHtml::linkButton($image, $htmlOptions);
}

/**
 * This is the shortcut to CHtml::image()
 */
function i($image, $alt = '', $htmlOptions = array())
{
    return CHtml::image($image, $alt, $htmlOptions);
}

/**
 * This is the shortcut to Yii::t()
 */
function t($message, $category = 'app', $params = array(), $source = null, $language = null)
{
    return Yii::t($category, $message, $params, $source, $language);
}

/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function bu($url = null)
{
    static $baseUrl;
    if ($baseUrl === null)
        $baseUrl = Yii::app()->getRequest()->getBaseUrl();
    return $url === null ? $baseUrl : $baseUrl . '/' . ltrim($url, '/');
}

/**
 * This is the shortcut to Yii::app()->request->basePath
 */
function bp()
{
    return Yii::app()->basePath;
}

/**
 * Asset Url
 * @return string
 */
function au()
{
    static $url;
    if ($url)
        return $url;
    return $url = app()->getAssetManager()->publish(ap(), false, 1, YII_DEBUG);
}

/**
 * Asset Path
 * @return string
 */
function ap()
{
    return bp() . DS . 'themes' . DS . 'assets' . DS;
}

/**
 * Vendors Path
 * @return string
 */
function vp()
{
    return dirname(dirname(dirname(__FILE__))) . DS . 'vendors';
}

/**
 * Returns the named application parameter.
 * This is the shortcut to Yii::app()->params[$name].
 * @param $name
 * @return bool
 */
function param($name)
{
    if (!isset(Yii::app()->params[$name])) {
        return false;
    }
    return Yii::app()->params[$name];
}

/**
 * This is the shortcut to Yii::app()->cache
 * @param string $cache mem|file
 * @return CCache
 */
function cache($cache = 'mem')
{
    if ($cache == 'file') {
        $cache = 'cacheFile';
    }
    else {
        $cache = 'cache';
    }
    return Yii::app()->$cache;
}

/**
 * This is the shortcut to Yii::app()->format
 * @return mixed
 */
function format()
{
    return app()->format;
}

/**
 * This is the shortcut to Yii::app()->mailManager.
 * @return EmailManager
 */
function email()
{
    return Yii::app()->mailManager;
}

/**
 * Dumps the target with syntax highlighting on by default.
 */
function dump($target)
{
    CVarDumper::dump($target, 10, true);
}

/**
 * Debug the target with syntax highlighting on by default.
 */
function debug($var = null, $name = null)
{
    $bt = debug_backtrace();
    if ($name !== false) {
        $file = str_replace(bp(), '', $bt[0]['file']);
        print '<div style="font-family: arial; background: #FFFBD6; margin: 10px 0;  padding: 5px; border:1px solid #666;">';
        if ($name) $name = '<b>' . $name . '</b><br/>';
        print '<span style="font-size:14px;">' . $name . '</span>';
        print '<div style="border:1px solid #ccc; border-width: 1px 0;">';
    }
    print '<pre style="margin:0;padding:10px;">';
    print_r($var);
    print '</pre>';
    if ($name !== false) {
        print '</div>';
        print '<span style="font-family: helvetica; font-size:10px;">' . $file . ' on line ' . $bt[0]['line'] . '</span>';
        print '</div>';
    }
}

/**
 * @param $object
 * @param string $name
 */
function printr($object, $name = '')
{
    // echo "<hr/>";

    $bt = debug_backtrace();
    $file = str_replace(bp(), '', $bt[0]['file']);
    print '<div style="background: #FFFBD6">';
    $nameLine = '';
    if ($name) $nameLine = '<b> <span style="font-size:18px;">' . $name . '</span></b> printr:<br/>';
    print '<span style="font-size:12px;">' . $nameLine . ' ' . $file . ' on line ' . $bt[0]['line'] . '</span>';
    print '<div style="border:1px solid #000;">';
    // if ($name) 
    // print ( 'printr of \'' . $name . '\' : ' ) ;
    print ('<pre>');
    if (is_array($object)) {
        print_r($object);
    }
    else {
        var_dump($object);
    }
    print ('</pre>');


    echo "</div></div><hr/>";
}

/**
 * Gets a submitted field
 * used to be named getSubmittedField()
 */
function sf($field, $model = null)
{
    $return = null;
    if ($model && isset($_GET[$model][$field])) {
        $return = $_GET[$model][$field];
    }
    elseif ($model && isset($_POST[$model][$field])) {
        $return = $_POST[$model][$field];
    }
    elseif (isset($_GET[$field])) {
        $return = $_GET[$field];
    }
    elseif (isset($_POST[$field])) {
        $return = $_POST[$field];
    }
    return $return;
}

/**
 * @param null $key
 * @param null $id
 * @return bool
 */
function static_id($key = null, $id = null)
{
    static $ids = array();
    if (!$key) return false;
    if ($id !== null) {
        $ids[$key] = $id;
    }
    return isset($ids[$key]) ? $ids[$key] : false;
}

/**
 * @param $id
 * @return array
 */
function sfGrid($id)
{
    $ids = array();
    $gridData = array();
    if (!empty($_REQUEST)) {
        foreach ($_REQUEST as $k => $v) {
            if (strpos($k, '-grid_c0') !== false) {
                if (is_array($v)) {
                    $gridData = $v;
                }

            }
        }
    }
    if (!empty($gridData)) {
        foreach ($gridData as $k => $id) {
            $ids[] = $id;
        }
    }
    else {
        if ($id) {
            $ids[] = $id;
        }
    }
    return $ids;
}

/**
 * @param null $id
 * @return string
 */
function sfGridHidden($id = null)
{
    $inputs = array();
    $ids = sfGrid($id);
    foreach ($ids as $id) {
        $inputs[] = CHtml::hiddenField('hidden-sf-grid_c0[]', $id);
    }
    return implode("\n", $inputs);
}

/**
 * @return bool
 */
function isAjax()
{
    return Yii::app()->request->isAjaxRequest;
}
