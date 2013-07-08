<?php

/**
 *
 * @package app.controller
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class SettingController extends WebController
{

    /**
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
     * Update settings
     */
    public function actionIndex()
    {
        if (isset($_POST['SettingEav'])) {
            foreach ($_POST['SettingEav'] as $setting_id => $settings) {
                $setting = Setting::model()->findByPk($setting_id);
                $setting->setEavAttributes($settings, true);
            }
            user()->addFlash('Settings have been saved.', 'success');
            $this->redirect(array('/setting/index'));
        }
        $this->render('index');
    }

}
