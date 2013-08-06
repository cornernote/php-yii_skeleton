<?php
/**
 * In configuration file main.php add this lines of code:
 * 'preload'=>array('globalInit',...),
 *  ...
 * 'components'=>array(
 *   ...
 *   'globalInit'=>array(
 *     'class'=>'GlobalInit',
 *   ),
 */
class GlobalInit extends CApplicationComponent
{
    /**
     *
     */
    public function init()
    {
        parent::init();

        // set an alias to components
        Yii::setPathOfAlias('actions', Yii::app()->getBasePath() . '/components/actions');
        Yii::setPathOfAlias('behaviors', Yii::app()->getBasePath() . '/components/behaviors');
        Yii::setPathOfAlias('validators', Yii::app()->getBasePath() . '/components/validators');
        Yii::setPathOfAlias('widgets', Yii::app()->getBasePath() . '/components/widgets');
        Yii::setPathOfAlias('core', $_ENV['_core']['path']);

        // set default php settings
        date_default_timezone_set(Setting::item('timezone'));
        set_time_limit((substr(php_sapi_name(), 0, 3) == 'cgi') ? 0 : Setting::item('time_limit'));
        ini_set('memory_limit', Setting::item('memory_limit'));
        ini_set('xdebug.max_nesting_level', 200);

        // start the audit
        Audit::findCurrent();
    }
}
