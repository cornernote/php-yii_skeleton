<?php

/**
 * This is the model class for table 'audit_trail'
 *
 *
 * @method AuditTrail with() with()
 * @method AuditTrail find() find($condition, array $params = array())
 * @method AuditTrail[] findAll() findAll($condition = '', array $params = array())
 * @method AuditTrail findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method AuditTrail[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method AuditTrail findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method AuditTrail[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method AuditTrail findBySql() findBySql(string $sql, array $params = array())
 * @method AuditTrail[] findAllBySql() findAllBySql(string $sql, array $params = array())
 *
 *
 *
 *
 * Properties from relation
 * @property User $user
 * @property PageTrail $pageTrail
 *
 *
 * Properties from table fields
 * @property string $id
 * @property string $old_value
 * @property string $new_value
 * @property string $action
 * @property string $model
 * @property string $field
 * @property datetime $created
 * @property integer $user_id
 * @property string $model_id
 * @property integer $page_trail_id
 */
class AuditTrail extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return AuditTrail the static model class
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
        return 'audit_trail';
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(
                self::BELONGS_TO,
                'User',
                'user_id',
            ),
            'pageTrail' => array(
                self::BELONGS_TO,
                'PageTrail',
                'page_trail_id',
            ),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            // search fields
            array('id, new_value, old_value, action, model, field, created, user_id, model_id, page_trail_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => t('ID'),
            'old_value' => t('Old Value'),
            'new_value' => t('New Value'),
            'action' => t('Action'),
            'model' => t('Model'),
            'field' => t('Field'),
            'created' => t('Created'),
            'user_id' => t('User'),
            'model_id' => t('Model'),
            'page_trail_id' => t('Page Trail'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @param array $options
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($options = array())
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('old_value', $this->old_value);
        $criteria->compare('new_value', $this->new_value);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('model', $this->model);
        $criteria->compare('field', $this->field);
        $criteria->compare('created', $this->created);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('page_trail_id', $this->page_trail_id);
        $criteria->mergeWith($this->getDbCriteria());

        return new ActiveDataProvider(get_class($this), CMap::mergeArray(array(
            'criteria' => $criteria,
        ), $options));
    }

    /**
     * @return null|string
     */
    public function getOldValueString()
    {
        return $this->old_value;
    }

    /**
     * @return null|string
     */
    public function getNewValueString()
    {
        return $this->new_value;
    }

}