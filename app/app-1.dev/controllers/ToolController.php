<?php

/**
 *
 * @package app.controller
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class ToolController extends WebController
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'page', 'clearCache', 'clearCacheModel', 'clearAsset', 'generateProperties', 'generateRules'),
                'roles' => array('admin'),
            ),
            array('deny', 'users' => array('*')),
        );
    }

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'page' => array(
                'class' => 'CViewAction',
            ),
            'generateProperties' => array(
                'class' => 'actions.GeneratePropertiesAction',
            ),
        );
    }

    /**
     *
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     *
     */
    public function actionClearCache()
    {
        // yii cache
        cache()->flush();
        // model cache
        ModelCache::model()->flush();
        // assets
        Helper::removeDirectory(app()->getAssetManager()->basePath, false);
        // all done
        user()->addFlash(t('Server cache has been cleared.'), 'success');
        $this->redirect(ReturnUrl::getUrl());
    }

    /**
     * Clears cache for a single model
     *
     * @param $model
     * @param $id
     */
    public function actionClearCacheModel($model, $id)
    {
        /* @var $modelInstance ActiveRecord */
        $modelInstance = ActiveRecord::model($model)->findByPk($id);
        if ($modelInstance) {
            $modelInstance->clearCache();
            user()->addFlash(strtr(t('Cache cleared for :model :id.'), array(
                ':model' => $model,
                ':id' => $id,
            )), 'success');
        }
        else {
            user()->addFlash(strtr(t('Could not find :model with ID :id.'), array(
                ':model' => $model,
                ':id' => $id,
            )), 'success');
        }
        $this->redirect(ReturnUrl::getUrl());
    }

    /**
     *
     */
    public function actionClearAsset()
    {
        Helper::removeDirectory(Yii::app()->getAssetManager()->getBasePath(), false);
        user()->addFlash(t('Assets have been cleared'), 'success');
        $this->redirect(ReturnUrl::getUrl());
    }

    /**
     * @return mixed
     */
    public function actionGenerateRules()
    {
        $modelName = sf('modelName');
        if (!$modelName) {
            echo "<br> add a get attribute <b>modelName</b>=SomeModel to request url<br>";
            return;
        }
        $model = CActiveRecord::model($modelName);
        if (!$model) {
            echo "<br> model not found [$modelName]<br>";
            return;
        }

        $rules = array();
        $required = array();
        $integers = array();
        $numerical = array();
        $length = array();
        $safe = array();
        $search = array();
        $tableName = $model->tableName();
        $table = $model->getDbConnection()->getSchema()->getTable($tableName, true);
        foreach ($table->columns as $column) {
            if ($column->autoIncrement)
                continue;
            $search[] = $column->name;
            $r = !$column->allowNull && $column->defaultValue === null;
            if ($r)
                $required[] = $column->name;
            if ($column->type === 'integer')
                $integers[] = $column->name;
            else if ($column->type === 'double')
                $numerical[] = $column->name;
            else if ($column->type === 'string' && $column->size > 0)
                $length[$column->size][] = $column->name;
            else if (!$column->isPrimaryKey && !$r)
                $safe[] = $column->name;
        }
        if ($required !== array())
            $rules[] = "array('" . implode(', ', $required) . "', 'required')";
        if ($integers !== array())
            $rules[] = "array('" . implode(', ', $integers) . "', 'numerical', 'integerOnly'=>true)";
        if ($numerical !== array())
            $rules[] = "array('" . implode(', ', $numerical) . "', 'numerical')";
        if ($length !== array()) {
            foreach ($length as $len => $cols)
                $rules[] = "array('" . implode(', ', $cols) . "', 'length', 'max'=>$len)";
        }
        if ($safe !== array())
            $rules[] = "array('" . implode(', ', $safe) . "', 'safe')";

        echo '$rules = array();<br/>' . "\n";
        echo 'if ($this->scenario == \'search\') {' . "<br/>\n";
        echo "\t" . "\$rules[] = array('" . implode(', ', $search) . "', 'safe');" . "<br/>\n";
        echo '}' . "<br/>\n";
        echo 'if (in_array($this->scenario, array(\'create\', \'update\'))) {' . "<br/>\n";
        foreach ($rules as $rule) {
            echo "\t" . '$rules[] = ' . $rule . ';' . "<br/>\n";
        }
        echo '}' . "<br/>\n";
        echo 'return $rules;' . "<br/>\n";

    }

}

