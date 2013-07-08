<?php

/**
 *
 */
class ConsoleCommand extends CConsoleCommand
{
    /**
     * @var array
     */
    private static $_instances = array();

    /**
     * @static
     * @param string $class
     * @return ConsoleCommand
     */
    public static function instance($class = __CLASS__)
    {
        if (isset(self::$_instances[$class]))
            return self::$_instances[$class];
        else
            return self::$_instances[$class] = new $class(null, null);
    }

    /**
     *
     */
    public function init()
    {
        PageTrail::model()->findCurrent();
        parent::init();
    }

}