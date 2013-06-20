<?php

/**
 *
 * @package app.controller
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class AccountController extends WebController
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        // do not allow access by templogin users
        if (user()->checkAccess('templogin')) {
            return array(
                array('deny',
                    'users' => array('*'),
                )
            );
        }
        return array(
            array('allow',
                'actions' => array('index', 'update', 'password', 'settings'),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays current logged in user.
     */
    public function actionIndex()
    {
        $user = $this->loadModel(user()->id, 'User');
        $this->render('view', array(
            'user' => $user,
        ));
    }

    /**
     * Updates your own user details.
     */
    public function actionUpdate()
    {
        $user = $this->loadModel(user()->id, 'User');
        $user->scenario = 'account';

        $this->performAjaxValidation($user, 'account-form');

        if (isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            if ($user->save()) {
                user()->addFlash('Your account has been saved.', 'success');
                $this->redirect(ReturnUrl::getUrl());
            }
            else {
                user()->addFlash('Your account could not be saved.', 'warning');
            }
        }

        $this->render('update', array(
            'user' => $user,
        ));
    }

    /**
     * Updates your own user password.
     */
    public function actionPassword()
    {
        $user = $this->loadModel(user()->id, 'User');
        $userPassword = new UserPassword('password');
        $this->performAjaxValidation($userPassword, 'password-form');
        if (isset($_POST['UserPassword'])) {
            $userPassword->attributes = $_POST['UserPassword'];
            if ($userPassword->validate()) {

                $user->confirm_password = $userPassword->password;
                $user->password = $userPassword->password;
                $user->save();

                user()->addFlash('Your password has been saved.', 'success');
                $this->redirect(ReturnUrl::getUrl());
            }
            else {
                user()->addFlash('Your password could not be saved.', 'warning');
            }
        }
        $this->render('password', array('user' => $userPassword));

    }

    /**
     * Updates your own user settings.
     */
    public function actionSettings()
    {
        $user = $this->loadModel(user()->id, 'User');

        if (isset($_POST['UserEav'])) {
            if (isset($_POST['UserEav']['sales_monthly_budget'])) {
                $_POST['UserEav']['sales_monthly_budget'] = (int)$_POST['UserEav']['sales_monthly_budget'];
            }
            if ($user->setEavAttributes($_POST['UserEav'], true)) {
                user()->addFlash('Your settings have been saved.', 'success');
                $this->redirect(ReturnUrl::getUrl());
            }
            else {
                user()->addFlash('Your settings could not be saved.', 'warning');
            }
        }

        $this->render('settings', array(
            'user' => $user,
        ));
    }

}
