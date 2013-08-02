<?php
/**
 *
 */
class ErrorEmailCommand extends ConsoleCommand
{
    /**
     * @return string
     */
    public function getHelp()
    {
        return <<<EOD
USAGE
  erroremail

DESCRIPTION
  This command will fetch all the errors and send them via email.

EOD;
    }


    /**
     * @param string $class
     * @return ErrorEmailCommand
     */
    public static function instance($class = __CLASS__)
    {
        return parent::instance($class);
    }

    /**
     *
     */
    public function actionIndex()
    {
        $archived = 0;
        $dir = app()->getRuntimePath() . '/errors';
        foreach (glob($dir . '/' . '*.html') as $error) {
            $archive = str_replace('runtime/errors/', 'runtime/errors/archive/', $error);
            if (!file_exists(dirname($archive))) mkdir(dirname($archive), 0777, true);
            rename($error, $archive);
            $archived++;
        }
        if ($archived) {
            email()->sendError($archived);
        }
    }

}
