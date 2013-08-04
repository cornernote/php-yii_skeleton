<?php

/**
 * --- BEGIN GenerateProperties ---
 *
 * This is the model class for table 'setting'
 *
 * @method Setting with() with()
 * @method Setting find() find($condition, array $params = array())
 * @method Setting[] findAll() findAll($condition = '', array $params = array())
 * @method Setting findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method Setting[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method Setting findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Setting[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Setting findBySql() findBySql($sql, array $params = array())
 * @method Setting[] findAllBySql() findAllBySql($sql, array $params = array())
 *
 * Methods from behavior EavBehavior
 * @method CActiveRecord loadEavAttributes() loadEavAttributes(array $attributes)
 * @method setModelTableFk() setModelTableFk($modelTableFk)
 * @method setSafeAttributes() setSafeAttributes($safeAttributes)
 * @method CActiveRecord saveEavAttributes() saveEavAttributes($attributes)
 * @method CActiveRecord deleteEavAttributes() deleteEavAttributes($attributes = array(), $save = false)
 * @method CActiveRecord setEavAttributes() setEavAttributes($attributes, $save = false)
 * @method CActiveRecord setEavAttribute() setEavAttribute($attribute, $value, $save = false)
 * @method array getEavAttributes() getEavAttributes($attributes = array())
 * @method getEavAttribute() getEavAttribute($attribute)
 * @method CActiveRecord withEavAttributes() withEavAttributes($attributes = array())
 *
 * Properties from table fields
 * @property string $id
 *
 * --- END GenerateProperties ---
 */
class Setting extends CActiveRecord
{

    static private $_items = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return Setting the static model class
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
        return $_ENV['_core']['db']['setting'];
    }

    /**
     * @return array containing model behaviors
     */
    public function behaviors()
    {
        return array(
            'AuditBehavior' => 'behaviors.AuditBehavior',
        );
    }

    /**
     * @static
     * @param string $name
     * @return string
     */
    public static function item($name)
    {
        if (isset(self::$_items[$name])) {
            return self::$_items[$name];
        }
        $setting = self::items();
        if (isset($setting[$name])) {
            return $setting[$name];
        }
        return false;
    }

    /**
     * @static
     * @return array
     */
    public static function items()
    {
        if (isset(self::$_items)) {
            return self::$_items;
        }
        return self::$_items = CHtml::listData(self::model()->findAll(), 'key', 'value');
    }

}