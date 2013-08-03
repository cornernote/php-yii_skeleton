<?php
/**
 * Override ActiveRecord
 *
 *
 * @method ActiveRecord static model() model()
 * @method ActiveRecord with() with()
 * @method ActiveRecord find() find($condition, array $params = array())
 * @method ActiveRecord[] findAll() findAll($condition = '', array $params = array())
 * @method ActiveRecord findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method ActiveRecord[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method ActiveRecord findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method ActiveRecord[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method ActiveRecord findBySql() findBySql(string $sql, array $params = array())
 * @method ActiveRecord[] findAllBySql() findAllBySql(string $sql, array $params = array())
 *
 *
 * @property string $errorString
 * @property boolean isNewRecord
 * @property string $scenario
 * @property string $controllerName
 *
 */
class ActiveRecord extends CActiveRecord
{
    /**
     * @var array
     */
    public $dbAttributes = array();

    /**
     * @var bool
     */
    public $ignoreClearCache = false;

    /**
     * @var array
     */
    public $cacheRelations = array();

    /**
     * @var string
     */
    protected $_controllerName;

    /**
     * @var array
     */
    private $_joins = array();

    /**
     * Returns the data from the object prepared for a search
     * @param string $type the type of search
     * @param string $attribute the model attribute to search
     * @return mixed the search string
     */
    public function getSearchField($type, $attribute)
    {
        $field = $this->$attribute;

        // Strips the contents before the dash to get a clean id
        if ($type == 'id') {
            if (strpos($field, '-')) {
                $field = explode('-', $field);
                $field = $field[count($field) - 1];
            }
            return $field;
        }

        // Converts the date for a search
        if ($type == 'date') {
            $operator = null;
            if ($field) {
                $operators = array('<=', '>=', '<', '>', '=');
                foreach ($operators as $operator) {
                    if (strpos($field, $operator) === 0) {
                        $field = trim(substr($field, strlen($operator)));
                        break;
                    }
                }
                $field = explode('-', $field);
                if (count($field) == 3 && strlen($field[2]) == 4) {
                    $field = $field[2] . '-' . $field[1] . '-' . $field[0];
                }
                else {
                    $operator = null;
                    $field = null;
                }
            }
            return $operator . $field;
        }

        // Converts the between for a search
        if ($type == 'between') {
            $operator = null;
            $values = explode(' ', $field);
            foreach ($values as $k => $value) {
                $value = explode('-', $value);
                if (count($value) == 3 && strlen($value[2]) == 4) {
                    $value = $value[2] . '-' . $value[1] . '-' . $value[0];
                }
                $values[$k] = $value;
            }
            return $values;
        }

        return false;
    }

    /**
     * Converts getErrors array into string
     *
     * @return string
     */
    public function getErrorString()
    {
        return self::getStaticErrorString($this);
    }

    /**
     * If you want to find errors of CActiveRecord like in AuditTrail use this function
     *
     * @static
     * @param $model ActiveRecord
     * @return string
     */
    public static function getStaticErrorString($model)
    {
        $output = array();
        foreach ($model->getErrors() as $attribute => $errors) {
            $output[] = $attribute . ': ' . implode(' ', $errors);
        }
        return implode(' | ', $output);
    }

    /**
     * Override getActiveRelation()
     *
     * @param string $name of the relation
     * @return CActiveRelation
     */
    public function getActiveRelation($name)
    {
        if ($this->getJoinRelation($name)) {
            return $this->_joins[$name];
        }
        return parent::getActiveRelation($name);
    }

    /**
     * Allows you to use dynamic relation called "join_yourRelationName"
     *
     * The $relation->on in the dynamic relation will be populated from
     * the $relation->conditions in relation named yourRelationName
     *
     * This results in the conditions being used in the ON clause instead of the WHERE clause
     *
     * To use this, simply define a relation in your model called "yourRelationName",
     * and then use model()->with("join_yourRelationName")
     *
     * @param string $name of the relation
     * @return bool|CActiveRelation
     */
    private function getJoinRelation($name)
    {
        if (isset($this->_joins[$name]))
            return $this->_joins[$name];

        if (substr($name, 0, 5) == 'join_') {
            $relationName = substr($name, 5);
            $relation = $this->getActiveRelation($relationName);
            if (!$relation)
                return false;

            $joinRelation = clone $relation;
            $joinRelation->name = $name;
            $joinRelation->on = $joinRelation->condition;
            $joinRelation->on = preg_replace('/\b' . $relationName . '\./i', $name . '.', $joinRelation->on);
            $joinRelation->condition = '';

            $this->_joins[$name] = $joinRelation;
            return $relation;
        }
        return false;
    }

    /**
     * Repopulates this active record with the latest data.
     * @return boolean whether the row still exists in the database. If true, the latest data will be populated to this active record.
     */
    public function refresh()
    {
        if (!parent::refresh()) {
            return false;
        }
        $this->clearCache();
        return true;
    }

    /**
     * Actions to be performed before the model is saved
     * @return bool
     */
    protected function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }
        $this->setDefaultAttributes();
        return true;
    }

    /**
     * Actions to be performed before the model is saved
     * @return bool
     */
    protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }
        $this->clearCache();
        $this->setDefaultAttributes();
        return true;
    }

    /**
     * Actions to be performed after the model is saved
     */
    protected function afterSave()
    {
        parent::afterSave();
        $this->clearCache();
        $this->isNewRecord = false;
        $this->dbAttributes = $this->attributes;
    }

    /**
     * Actions to be performed before the model is deleted
     * @return bool
     */
    protected function beforeDelete()
    {
        $this->clearCache();

        // soft delete needs parent::beforeDelete() to run last
        if (!parent::beforeDelete()) {
            return false;
        }
        return true;
    }

    /**
     * Actions to be performed after the model is deleted
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        $this->clearCache();
    }

    /**
     * Actions to be performed after the model is loaded
     */
    protected function afterFind()
    {
        parent::afterFind();
        // preserve an array containing the current database values
        $this->dbAttributes = $this->attributes;
    }

    /**
     * clear model cache
     */
    public function clearCache()
    {
        if ($this->ignoreClearCache) {
            return;
        }
        $this->getCacheKeyPrefix($removeOldKey = true);
        ModelCache::deleteCache($this);
        foreach ($this->cacheRelations as $dependentRelationName) {
            //i.e. to clearCache of job for given item
            if (is_array($this->$dependentRelationName)) {
                foreach ($this->$dependentRelationName as $singleModel) {
                    /* @var ActiveRecord $singleModel */
                    $singleModel->clearCache();
                }
            }
            else {
                // if the related record exists, clear cache
                if ($this->$dependentRelationName) {
                    $this->$dependentRelationName->clearCache();
                }
            }
        }
        $this->getCacheKeyPrefix($removeOldKey = true);
    }

    /**
     * tweak attributes before saving
     */
    public function setDefaultAttributes()
    {
    }

    /**
     * Check if any fields have changed
     *
     * @param string $field
     * @param bool $details return the field name that was changed
     * @return bool|string|array
     */
    public function changed($field = '*', $details = false)
    {
        if ($this->isNewRecord)
            return false;
        $changed = array();
        if ($field != '*') {
            if ($details) {
                if ($this->dbAttributes[$field] == $this->{$field}) {
                    $changed[$field] = array($this->dbAttributes[$field], $this->{$field});
                }
                return !empty($changed) ? $changed : false;
            }
            return $this->dbAttributes[$field] != $this->{$field};
        }
        $changed = array();
        foreach ($this->attributes as $k => $v) {
            $dbAttribute = isset($this->dbAttributes[$k]) ? $this->dbAttributes[$k] : null;
            if ($dbAttribute != $v) {
                if (!$details)
                    return true;
                else {
                    $changed[$k] = array('old' => $this->dbAttributes[$k], 'new' => $v);
                }
            }
        }
        return !empty($changed) && $details ? $changed : false;
    }


    /**
     * @return array|mixed|null
     */
    public function rules()
    {
        $rules = array();
        if ($this->hasEventHandler('onRules')) {
            $event = new CModelEvent($this);
            $this->onRules($event);
            $rules = $event->params;
        }
        return $rules;
    }

    /**
     * @return array|mixed|null
     */
    public function attributeLabels()
    {
        $attributeLabels = array();
        if ($this->hasEventHandler('onAttributeLabels')) {
            $event = new CModelEvent($this);
            $this->onAttributeLabels($event);
            $attributeLabels = $event->params;
        }
        return $attributeLabels;
    }

    /**
     * @param $event
     */
    public function onRules($event)
    {
        $this->raiseEvent('onRules', $event);
    }

    /**
     * @param $event
     */
    public function onAttributeLabels($event)
    {
        $this->raiseEvent('onAttributeLabels', $event);
    }

    /**
     * @param bool $removeOldKey
     * @return bool|string
     */
    public function getCacheKeyPrefix($removeOldKey = false)
    {
        $key = 'getCacheKeyPrefix.' . get_class($this) . '.' . $this->getPrimaryKey();
        $prefix = false;
        if (!$removeOldKey) {
            $prefix = Yii::app()->cache->get($key);
        }
        if (!$prefix) {
            $prefix = uniqid();
            Yii::app()->cache->set($key, $prefix);
        }
        return $prefix . '.';
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getCache($key)
    {
        $fullKey = $this->getCacheKeyPrefix() . $key;
        $return = Yii::app()->cache->get($fullKey);
        //attempt to get it from database keyvalue
        if (!$return) {
            $return = ModelCache::getCache($this, $fullKey);
        }
        return $return;
    }

    /**
     * @param $key
     * @param $value
     * @param bool $insertModelCache
     */
    public function setCache($key, $value, $insertModelCache = true)
    {
        $fullKey = $this->getCacheKeyPrefix() . $key;
        Yii::app()->cache->set($fullKey, $value);
        if ($insertModelCache) {
            ModelCache::setCache($this, $fullKey, $value);
        }

    }

    /**
     *
     * @param array $ids
     * @param array $attributes
     * @param null $scenario
     * @return array|int|string
     */
    function updateAll($ids = array(), $attributes = array(), $scenario = null)
    {
        $messages = array();
        $message = array();
        $class = get_class($this);
        foreach ($ids as $id) {
            /* @var ActiveRecord $model */
            $model = ActiveRecord::model($class)->findByPk($id);
            if (!$model) {
                $message['error'][] = $class . ' ' . $id . ' ' . t('could not be found.');
                continue;
            }
            if ($scenario) {
                $model->scenario = $scenario;
            }
            $model->attributes = $attributes;
            if ($model->save()) {
                $message['success'][] = $model->getLink('inline');
            }
            else {
                $message['error'][] = $model->getLink('inline') . ' ' . $model->errorString;
            }
        }
        if (isset($message['success'])) {
            $messages['success'] = t('The following records have been updated:');
            $messages['success'] .= '<ul class="bullet">';
            foreach ($message['success'] as $_message) {
                $messages['success'] .= "<li>{$_message}</li>";
            }
            $messages['success'] .= '</ul>';
        }
        if (isset($message['error'])) {
            $messages['error'] = t('The following records could not be updated:');
            $messages['error'] .= '<ul class="bullet">';
            foreach ($message['error'] as $_message) {
                $messages['error'] .= "<li>{$_message}</li>";
            }
            $messages['error'] .= '</ul>';
        }
        return $messages;
    }

    /**
     * @param array $tables
     */
    public function lockTables($tables = array())
    {
        // $table['table_name table_alias'] = 'lock_type'
        $tables[$this->tableName()] = 'WRITE';
        $tables[$this->tableName() . ' t'] = 'WRITE';

        $tableSql = array();
        foreach ($tables as $table => $lockType) {
            $tableSql[] = $table . ' ' . $lockType;
        }
        $sql = 'LOCK TABLES ' . implode(', ', $tableSql);
        $this->getDbConnection()->createCommand($sql)->execute();
    }

    /**
     *
     */
    public function unlockTables()
    {
        $sql = 'UNLOCK TABLES';
        $this->getDbConnection()->createCommand($sql)->execute();
    }

    /**
     * @return CDbTransaction
     */
    public function beginTransaction()
    {
        return $this->getDbConnection()->beginTransaction();
    }

    /**
     * @param $attribute
     * @return null
     */
    public function getDbAttribute($attribute)
    {
        if (isset($this->dbAttributes[$attribute])) {
            return $this->dbAttributes[$attribute];
        }
        else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function getAuditModel()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getControllerName() . '-' . $this->getPrimaryKeyString();
    }

    /**
     * @param string $action
     * @param array $params
     * @return string
     */
    public function getUrl($action = 'view', $params = array())
    {
        return array_merge(array(
            '/' . $this->controllerName . '/' . $action,
            $this->getPrimaryKeySchemaString() => $this->getPrimaryKeyString(),
        ), (array)$params);
    }

    /**
     * @param string $title
     * @param string $urlAction
     * @param array $urlParams
     * @return string
     */
    public function getLink($title = null, $urlAction = 'view', $urlParams = array())
    {
        $title = $title ? $title : $this->getName();
        return l($title, $this->getUrl($urlAction, $urlParams));
    }

    /**
     * @return array
     */
    public function getDropdownLinks()
    {
        $links = array(
            array('label' => $this->getName(), 'url' => $this->getUrl()),
        );
        $items = $this->getDropdownLinkItems();
        if ($items) {
            $links[] = array('items' => $items);
        }
        return $links;
    }

    /**
     * @return array
     */
    public function getDropdownLinkItems()
    {
        return array();
    }

    /**
     * @return array
     */
    public function getMoreDropdownLinkItems()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getPrimaryKeySchemaString()
    {
        if (is_array($this->tableSchema->primaryKey))
            return implode('-', $this->tableSchema->primaryKey);
        return $this->tableSchema->primaryKey;
    }

    /**
     * @return string
     */
    public function getPrimaryKeyString()
    {
        if (is_array($this->getPrimaryKey()))
            return implode('-', $this->getPrimaryKey());
        return $this->getPrimaryKey();
    }


    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->_controllerName ? $this->_controllerName : lcfirst(get_class($this));
    }

}
