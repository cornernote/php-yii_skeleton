<?php

class DbMigration extends CDbMigration
{

    /**
     * @param $sql
     * @return integer number of rows affected by the execution.
     */
    protected function q($sql)
    {
        $r = $this->dbConnection->createCommand($sql)->execute();
        if (isset(Yii::app()->cache)) {
            Yii::app()->cache->flush();
        }
        return $r;
    }

    /**
     * @param $sql
     * @param string $separator
     * @return array of numbers of rows affected by the executions.
     */
    protected function qs($sql, $separator = ';')
    {
        $rs = array();
        $qs = explode($separator, $sql);
        foreach ($qs as $q) {
            if (trim($q)) {
                $rs[] = $this->q($q);
            }
        }
        return $rs;
    }

}