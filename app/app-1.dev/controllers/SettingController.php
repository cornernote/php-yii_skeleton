<?php

/**
 *
 * @package app.controller
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class SettingController extends WebController
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
                'actions' => array('updateCore', 'updateApp'),
                'roles' => array('admin'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Updates core settings.
     */
    public function actionUpdateCore()
    {
        $setting = Setting::model()->findByPk('core');
        $this->performAjaxValidation($setting, 'setting-form');

        if (isset($_POST['SettingEav'])) {
            $_POST['SettingEav']['debug'] = isset($_POST['SettingEav']['debug']) ? (int)$_POST['SettingEav']['debug'] : 0;
            $_POST['SettingEav']['debug_db'] = isset($_POST['SettingEav']['debug_db']) ? (int)$_POST['SettingEav']['debug_db'] : 0;
            $_POST['SettingEav']['debug_toolbar'] = isset($_POST['SettingEav']['debug_toolbar']) ? (int)$_POST['SettingEav']['debug_toolbar'] : 0;
            $_POST['SettingEav']['yii_lite'] = isset($_POST['SettingEav']['yii_lite']) ? (int)$_POST['SettingEav']['yii_lite'] : 0;
            if ($setting->setEavAttributes($_POST['SettingEav'], true)) {
                user()->addFlash('Core settings have been saved.', 'success');
                $this->redirect(array('/setting/updateCore'));
            }
            else {
                user()->addFlash('Core settings could not be saved.', 'warning');
            }
        }

        $this->render('update_core', array(
            'setting' => $setting,
        ));
    }

    /**
     * Updates core settings.
     */
    public function actionUpdateApp()
    {
        $setting = Setting::model()->findByPk('app');
        $this->performAjaxValidation($setting, 'setting-form');

        if (isset($_POST['SettingEav'])) {
            $_POST['SettingEav']['allowAutoLogin'] = isset($_POST['SettingEav']['allowAutoLogin']) ? (int)$_POST['SettingEav']['allowAutoLogin'] : 0;
            $_POST['SettingEav']['rememberMe'] = isset($_POST['SettingEav']['rememberMe']) ? (int)$_POST['SettingEav']['rememberMe'] : 0;
            $_POST['SettingEav']['recaptcha'] = isset($_POST['SettingEav']['recaptcha']) ? (int)$_POST['SettingEav']['recaptcha'] : 0;
            if ($setting->setEavAttributes($_POST['SettingEav'], true)) {
                user()->addFlash('App settings have been saved.', 'success');
                $this->redirect(array('/setting/updateApp'));
            }
            else {
                user()->addFlash('App settings could not be saved.', 'warning');
            }
        }

        $this->render('update_app', array(
            'setting' => $setting,
        ));
    }

}
