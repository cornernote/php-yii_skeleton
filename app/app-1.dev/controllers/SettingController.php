<?php

/**
 * SettingController
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
            array('deny', 'users' => array('*')),
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
        /** @var Setting[] $settings */
        $settings = array();
        $_settings = Setting::model()->findAll();
        foreach ($_settings as $setting) {
            $settings[$setting->key] = $setting;
        }
        foreach (Setting::items() as $key => $value) {
            if (!isset($settings[$key])) {
                $settings[$key] = new Setting();
                $settings[$key]->key = $key;
                $settings[$key]->value = $value;
                $settings[$key]->save(false);
            }
        }
        foreach (Yii::app()->params as $key => $value) {
            if (is_scalar($value) && !isset($settings[$key])) {
                $settings[$key] = new Setting();
                $settings[$key]->key = $key;
                $settings[$key]->value = $value;
                $settings[$key]->save(false);
            }
        }
        $this->render('index', array(
            'settings' => $settings,
        ));
    }

}
