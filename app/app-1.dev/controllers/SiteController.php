<?php

class SiteController extends WebController
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
                'actions' => array('overview'),
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('index', 'error', 'page'),
                'users' => array('*'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    /**
     * Declares class-based actions.
     * @return array
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        if (user()->id) {
            $this->redirect(array('/site/overview'));
        }
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionOverview()
    {
        if (user()->checkAccess('admin')) {
            $this->render('overview_admin');
        }
        elseif (user()->checkAccess('locksmith')) {
            $this->render('overview_locksmith');
        }
        elseif (user()->checkAccess('customer')) {
            $customer = Customer::model()->findByPk(user()->id);
            $this->render('overview_customer', array(
                'customer' => $customer,
            ));
        }
        elseif (user()->checkAccess('key_holder')) {
            $keyHolder = KeyHolder::model()->findByPk(user()->id);
            $this->render('overview_key_holder', array(
                'keyHolder' => $keyHolder,
            ));
        }
    }


}