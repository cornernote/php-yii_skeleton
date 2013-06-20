<?php

class Role extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return \CActiveRecord|\Role the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'role';
    }

}