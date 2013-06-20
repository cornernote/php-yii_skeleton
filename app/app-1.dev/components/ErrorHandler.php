<?php
/**
 *
 */
class ErrorHandler extends CErrorHandler
{

    var $data = array();

    /**
     * @param $event CErrorEvent
     */
    public function LogError($event)
    {
        ob_start();
        self::handleError($event);
        $output = ob_get_clean();
        $dir = app()->getRuntimePath() . '/ErrorList';
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $path = $dir . '/' . static_id('page_trail_id') . '-error.html';
        file_put_contents($path, $output);
    }

    /**
     * @param $event CErrorEvent
     */

    public function handleError($event)
    {
        $trace = debug_backtrace();
        // skip the first 3 stacks as they do not tell the error position
        if (count($trace) > 6)
            $trace = array_slice($trace, 6);
        $traceString = '';
        foreach ($trace as $i => $t)
        {
            if (!isset($t['file']))
                $trace[$i]['file'] = 'unknown';

            if (!isset($t['line']))
                $trace[$i]['line'] = 0;

            if (!isset($t['function']))
                $trace[$i]['function'] = 'unknown';

            $traceString .= "#$i {$trace[$i]['file']}({$trace[$i]['line']}): ";
            if (isset($t['object']) && is_object($t['object']))
                $traceString .= get_class($t['object']) . '->';
            $traceString .= "{$trace[$i]['function']}()\n";

            unset($trace[$i]['object']);
        }

        $app = Yii::app();
        if ($app instanceof CWebApplication) {
            switch ($event->code)
            {
                case E_WARNING:
                    $type = 'PHP warning';
                    break;
                case E_NOTICE:
                    $type = 'PHP notice';
                    break;
                case E_USER_ERROR:
                    $type = 'User error';
                    break;
                case E_USER_WARNING:
                    $type = 'User warning';
                    break;
                case E_USER_NOTICE:
                    $type = 'User notice';
                    break;
                case E_RECOVERABLE_ERROR:
                    $type = 'Recoverable error';
                    break;
                default:
                    $type = 'PHP error';
            }
            $data = array(
                'code' => 500,
                'type' => $type,
                'message' => $event->message,
                'file' => $event->file,
                'line' => $event->line,
                'trace' => $traceString,
                'traces' => $trace,
            );
            //            $this->render('print', array(
            //                'job' => $job,
            //            ));
            $data['version'] = $this->getVersionInfo();
            $data['time'] = time();
            $data['admin'] = $this->adminInfo;
            app()->controller->renderPartial('application.views.site.exception', array(
                'data' => $data,
                'errorHandler' => $this,
            ));
            $this->data = $data;
        }
        else
            $app->displayError($event->code, $event->message, $event->file, $event->line);
    }


    public function renderSourceCode($file, $errorLine, $maxLines)
    {
        return parent::renderSourceCode($file, $errorLine, $maxLines);
    }

    /**
     * Returns a value indicating whether the call stack is from application code.
     * @param array $trace the trace data
     * @return boolean whether the call stack is from application code.
     */
    public function isCoreCode($trace)
    {
        return parent::isCoreCode($trace);
    }

    /**
     * Converts arguments array to its string representation
     *
     * @param array $args arguments array to be converted
     * @return string string representation of the arguments array
     */
    public function argumentsToString($args)
    {
        return parent::argumentsToString($args);
    }

}