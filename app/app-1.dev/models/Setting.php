<?php

class Setting extends ActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return \CActiveRecord|\Setting the static model class
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
     * @return array containing model behaviours
     */
    public function behaviors()
    {
        global $settings;
        return array(
            'AuditBehavior' => 'AuditBehavior',
            'EavBehavior' => array(
                'class' => 'EavBehavior',
                'tableName' => $settings['db']['table'],
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
                return false;
            }
        }
        return $item[$group][$_names] = $settings[$group]->getEavAttributes($names);
    }

}