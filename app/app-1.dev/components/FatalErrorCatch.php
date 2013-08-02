<?php
/**
 * Extension for catching FATAL errors
 * In configuration file main.php add this lines of code:
 *
 * 'preload'=>array('fatalerrorcatch',...),
 *  ...
 * 'components'=>array(
 *   ...
 *   'fatalerrorcatch'=>array(
 *     'class'=>'ext.error.FatalErrorCatch',
 *   ),
 *
 * @author Rustam Gumerov <psrustik@yandex.ru>
 * @link https://github.com/psrustik/yii-fatal-error-catch
 */
class FatalErrorCatch extends CApplicationComponent
{

    /**
     * Yii-action for error displaying.
     * Better to use handlers from Yii because self-written handlers can have errors too :)
     * @var mixed
     */
    public $errorAction = null;

    /**
     * Errors types that we want to catch
     * @var array
     */
    public $errorTypes = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING);

    /**
     * @return mixed
     */
    public function init()
    {
        register_shutdown_function(array($this, 'shutdownHandler'));
        return parent::init();
    }

    /**
     * Error handler
     */
    public function shutdownHandler()
    {
        $e = error_get_last();
        if ($e !== null && in_array($e['type'], $this->errorTypes)) {
            $msg = 'Fatal error: ' . $e['message'];
            Yii::app()->errorHandler->errorAction = $this->errorAction;
            Yii::app()->handleError($e['type'], $msg, $e['file'], $e['line']);
        }
    }
}
