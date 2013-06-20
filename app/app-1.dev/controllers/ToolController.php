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
                'actions' => array('index', 'generateProperties', 'clearCache'),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
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
        cache()->flush();
        ModelCache::flush();
        user()->addFlash('Server cache has been cleared.', 'success');
        $this->redirect(ReturnUrl::getUrl());
    }

    /**
     * @return mixed
     */
    public function actionGenerateProperties()
    {
        $tableName = sf('tableName');
        if (!$tableName) {
            echo "<br> add a get attribute tableName to request url<br>";
            return;
        }
        $model = CActiveRecord::model($tableName);
        $output = '';
        if ($model) {
            $actualTableName = $model->tableName();
            echo "* This is the model class for table '$actualTableName'<br/>";
            echo '*<br/>';
            echo '*<br/>';
            //removing mixed as it was generating orange color in ide
            echo "* @method $tableName with() with()<br/>";
            echo "* @method $tableName find() find(\$condition, array \$params = array())<br/>";
            echo "* @method {$tableName}[] findAll() findAll(\$condition = '', array \$params = array())<br/>";
            echo "* @method $tableName findByPk() findByPk(\$pk, \$condition = '', array \$params = array())<br/>";
            echo "* @method {$tableName}[] findAllByPk() findAllByPk(\$pk, \$condition = '', array \$params = array())<br/>";
            echo "* @method $tableName findByAttributes() findByAttributes(array \$attributes, \$condition = '', array \$params = array())<br/>";
            echo "* @method {$tableName}[] findAllByAttributes() findAllByAttributes(array \$attributes, \$condition = '', array \$params = array())<br/>";
            echo "* @method $tableName findBySql() findBySql(string \$sql, array \$params = array())<br/>";
            echo "* @method {$tableName}[] findAllBySql() findAllBySql(string \$sql, array \$params = array())<br/>";
            echo "* <br/>";
            echo "* <br/>";
            $behaviors = $model->behaviors();
            $inheritedMethods = array();
            foreach (get_class_methods('CActiveRecordBehavior') as $methodName) {
                $inheritedMethods[$methodName] = $methodName;
            }
            $selfMethods = array();
            foreach (get_class_methods($tableName) as $methodName) {
                $selfMethods[$methodName] = $methodName;
            }
            foreach ($behaviors as $behavior) {

                $className = $behavior;
                if (is_array($behavior)) {
                    $className = $behavior['class'];
                }
                $className = explode('.', $className);
                $className = $className[count($className) - 1];
                //echo "<br> processing [$className] <br>";
                $methods = get_class_methods($className);
                $header = false;
                foreach ($methods as $methodName) {
                    if (isset($inheritedMethods[$methodName]) || isset($selfMethods[$methodName])) {
                        continue;
                    }
                    if (!$header) {
                        echo "*<br/>* Methods from behavior " . $className . '<br/>';
                        $header = true;
                    }

                    echo "* @method $methodName() $methodName(";
                    $r = new ReflectionMethod($className, $methodName);
                    $params = $r->getParameters();
                    $separator = '';
                    foreach ($params as $param) {
                        //$param is an instance of ReflectionParameter
                        /* @var $param ReflectionParameter */
                        echo $separator . '$' . $param->getName();
                        if ($param->isOptional()) {
                            echo ' = ';
                            var_export($param->getDefaultValue());
                        }
                        $separator = ', ';
                    }
                    echo ") <br/>";

                }
            }
            echo '*<br/>';
            echo '*<br/>';
            echo '* Properties from relation<br/>';
            $tableName = $model->tableName();
            $relations = $model->relations();
            foreach ($relations as $relationName => $relation) {
                if (in_array($relation[0], array('CBelongsToRelation', 'CHasOneRelation'))) {
                    echo '* @property ' . $relation[1] . ' $' . $relationName . "<br/>\n";
                }
                elseif (in_array($relation[0], array('CHasManyRelation', 'CManyManyRelation'))) {
                    echo '* @property ' . $relation[1] . '[] $' . $relationName . "<br/>\n";
                }
                elseif (in_array($relation[0], array('CStatRelation'))) {
                    echo '* @property integer $' . $relationName . "<br/>\n";
                }
                else {
                    echo '* @property unknown $' . $relationName . "<br/>\n";
                }

            }
            echo '*<br/>';
            echo '*<br/>';
            echo '* Properties from table fields<br/>';
        }
        $table = Yii::app()->db->getSchema()->getTable($tableName, true);
        if ($table) {
            //printr($table->columns);
            foreach ($table->columns as $column) {
                $type = $column->type;
                if (($column->dbType == 'datetime') || ($column->dbType == 'date')) {
                    $type = $column->dbType;
                }
                if (strpos($column->dbType, 'decimal') !== false) {
                    $type = 'number';
                }
                echo '* @property ' . $type . ' $' . $column->name . "<br/>\n";
            }
        }
        else {
            echo "<br> table not found [$tableName]<br>";
        }


    }

}
