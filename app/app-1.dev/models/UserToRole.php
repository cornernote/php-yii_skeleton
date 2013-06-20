<?php

class UserToRole extends ActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return \CActiveRecord|\UserToRole the static model class
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
        return 'user_to_role';
    }

    /**
     * @return array containing model behaviours
     */
    public function behaviors()
    {
        return array(
            'AuditBehavior' => 'AuditBehavior',
        );
    }

}