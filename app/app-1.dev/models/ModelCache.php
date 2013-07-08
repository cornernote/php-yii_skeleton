<?php

/**
 * This is the model class for table 'model_cache'
 *
 *
 * @method ModelCache with() with()
 * @method ModelCache find() find($condition, array $params = array())
 * @method ModelCache[] findAll() findAll($condition = '', array $params = array())
 * @method ModelCache findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method ModelCache[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method ModelCache findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method ModelCache[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method ModelCache findBySql() findBySql(string $sql, array $params = array())
 * @method ModelCache[] findAllBySql() findAllBySql(string $sql, array $params = array())
 *
 *
 *
 *
 * Properties from relation
 *
 *
 * Properties from table fields
 * @property string $id
 * @property string $model
 * @property string $model_id
 * @property string $key
 * @property string $cache
 * @property string $created
 */
class ModelCache extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return ModelCache the static model class
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
        return 'model_cache';
    }

    /**
     * @static
     * @param ActiveRecord $model
     * @param $key
     * @return CActiveRecord
     */
    static function getCache($model, $key)
    {
        $cache = self::model()->findByAttributes(array(
            'model' => get_class($model),
            'model_id' => $model->getPrimaryKey(),
            'key' => $key,
        ));
        if (!$cache) {
            return false;
        }
        $cache = StringHelper::unserialize($cache->cache);
        // put cache back into memcache
        if ($cache) {
            Yii::app()->cache->set($key, $cache);
        }
        return $cache;
    }

    /**
     * @static
     * @param $model ActiveRecord
     * @param $key
     * @param $value
     */
    static function setCache($model, $key, $value)
    {
        if (!is_string($value))
            $value = serialize($value);
        $modelCache = self::model()->findByAttributes(array(
            'model' => get_class($model),
            'model_id' => $model->getPrimaryKey(),
            'key' => $key,
        ));
        if (!$modelCache) {
            $modelCache = new ModelCache();
            $modelCache->model = get_class($model);
            $modelCache->model_id = $model->getPrimaryKey();
            $modelCache->key = $key;
        }
        $modelCache->cache = $value;
        $modelCache->save();
    }

    /**
     * @static
     * @param $model ActiveRecord
     */
    static function deleteCache($model)
    {
        $sql = "
            DELETE FROM " . self::model()->tableName() . "
            WHERE model = '" . get_class($model) . "'
            AND model_id = '" . $model->getPrimaryKey() . "'
        ";
        Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     * @static
     *
     */
    static function flush()
    {
        $sql = "TRUNCATE TABLE `" . self::model()->tableName() . "`";
        Yii::app()->db->createCommand($sql)->execute();
    }

}
