<?php
/**
 *
 */
class TestController extends WebController
{

    /**
     * Access Control
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index'),
                'roles' => array('admin'),
                //'users' => array('*','@','?'), // all, user, guest
            ),
            array('deny', 'users' => array('*')),
        );
    }

    /**
     * Filters
     */
    //public function filters()
    //{
    //    return array(
    //        'inlineFilterName',
    //        array(
    //            'class'=>'path.to.FilterClass',
    //            'propertyName'=>'propertyValue',
    //        ),
    //    );
    //}

    /**
     * Actions
     */
    //public function actions()
    //{
    //    return array(
    //        'action1' => 'path.to.ActionClass',
    //        'action2' => array(
    //            'class' => 'path.to.AnotherActionClass',
    //            'propertyName' => 'propertyValue',
    //        ),
    //    );
    //}

    /**
     * Index
     */
    public function actionIndex()
    {
        $this->render('index');
    }


}
