<?php

/**
 *
 * @package app.controller
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class LogController extends WebController
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
                'actions' => array('index'),
                'roles' => array('admin'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Lists all logs.
     */
    public function actionIndex()
    {
        $log = new Log('search');
        $this->render('index', array(
            'log' => $log,
        ));
    }

}
