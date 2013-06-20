<?php

/**
 *
 */
class ModelCache extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return \CActiveRecord|\ModelCache the static model class
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
        $cache = @unserialize($cache->cache);
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
        $value = serialize($value);
        if (strlen($value) > 10240000) {
            return;
        }
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
