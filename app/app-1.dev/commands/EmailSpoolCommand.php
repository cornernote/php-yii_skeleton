<?php
/**
 *
 */
class EmailSpoolCommand extends ConsoleCommand
{
    /**
     * @return string
     */
    public function getHelp()
    {
        return <<<EOD
USAGE
  spoolEmail

DESCRIPTION
  This command will fetch all the emails from the spool table and send them via swiftMailer.

EXAMPLES
 * Spool all emails in the queue
        spoolEmail

EOD;
    }

    /**
     * @param string $class
     * @return EmailSpoolCommand
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
        // long loop
        set_time_limit(60 * 60 * 24);
        for ($i = 0; $i < 60 * 60; $i++) {
            sleep(1);
            EmailSpool::spool();
        }
    }

}
