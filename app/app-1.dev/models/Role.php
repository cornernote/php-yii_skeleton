<?php

/**
 * This is the model class for table 'role'
 *
 *
 * @method Role with() with()
 * @method Role find() find($condition, array $params = array())
 * @method Role[] findAll() findAll($condition = '', array $params = array())
 * @method Role findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method Role[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method Role findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Role[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Role findBySql() findBySql(string $sql, array $params = array())
 * @method Role[] findAllBySql() findAllBySql(string $sql, array $params = array())
 *
 *
 *
 *
 * Properties from relation
 *
 *
 * Properties from table fields
 * @property string $id
 * @property string $name
 */
class Role extends ActiveRecord
{

    /**
     * Admin - super user
     */
    const ROLE_ADMIN = 1;
    /**
     * Reseller - manager of locksmith accounts
     */
    const ROLE_RESELLER = 2;
    /**
     * Locksmith - manager of customer and key holder accounts
     */
    const ROLE_LOCKSMITH = 3;
    /**
     * Customer - manager of key holder accounts
     */
    const ROLE_CUSTOMER = 4;
    /**
     * KeyHolder
     */
    const ROLE_KEY_HOLDER = 5;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return Role the static model class
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