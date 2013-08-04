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
class Setting extends ActiveRecord
{

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
        return 'setting';
    }

    /**
     * @return array containing model behaviors
     */
    public function behaviors()
    {
        global $settings;
        return array(
            'AuditBehavior' => 'behaviors.AuditBehavior',
            'EavBehavior' => array(
                'class' => 'behaviors.EavBehavior',
                'tableName' => $_ENV['_settings']['db']['table'],
            ),
        );
    }

    /**
     * @static
     * @param string $group
     * @param string $name
     * @return string
     */
    public static function item($group, $name)
    {
        static $items;
        if (isset($items[$group][$name])) {
            return $items[$group][$name];
        }
        $setting = self::items($group, array());
        if (!isset($setting[$name])) {
            return false;
        }
        return $item[$group][$name] = $setting[$name];
    }

    /**
     * @static
     * @param string $group
     * @param array $names
     * @return mixed
     */
    public static function items($group, $names = array())
    {
        static $items;
        $_names = md5(serialize($names));
        if (isset($items[$group][$_names])) {
            return $items[$group][$_names];
        }
        /* @var $settings Setting[] */
        static $settings;
        if (!isset($settings[$group])) {
            $settings[$group] = self::model()->findByPk($group);
            if (!$settings[$group]) {
                return array();
            }
        }
        return $item[$group][$_names] = $settings[$group]->getEavAttributes($names);
    }

}