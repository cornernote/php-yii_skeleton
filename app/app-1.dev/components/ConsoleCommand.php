<?php

/**
 *
 */
class ConsoleCommand extends CConsoleCommand
{
    /**
     * @var array
     */
    private static $_models = array();

    /**
     * @static
     * @param string $className
     * @return ConsoleCommand
     */
    public static function model($className = __CLASS__)
    {
        if (isset(self::$_models[$className]))
            return self::$_models[$className];
        else
            return self::$_models[$className] = new $className(null, null);
    }


}