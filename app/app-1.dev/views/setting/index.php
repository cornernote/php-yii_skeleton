<?php
/**
 * @var $this SettingController
 * @var $form ActiveForm
 * @var $settings Setting[]
 */

$settings = array();
$_settings = Setting::model()->findAll();
foreach ($_settings as $setting) {
    $settings[$setting->id] = $setting;
}
$this->pageTitle = t('Settings');
$this->pageHeading = t('Settings');
$menu = NavbarItems::systemMenu();
$this->menu = $menu['items'][0]['items'];
?>


<?php
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'setting-form',
    //'enableAjaxValidation' => true,
    'inlineErrors' => false,
    'type' => 'horizontal',
));
$this->widget('AskToSaveWork', array('watchElement' => '#setting-form :input', 'message' => t('Please save before leaving the page')));
echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
echo $form->errorSummary($settings);
?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'icon' => 'ok white',
    'label' => t('Save'),
    'htmlOptions' => array('class' => 'pull-right'),
));
?>

    <h2><?php echo t('Core Settings'); ?></h2>
    <fieldset>
        <legend><?php echo t('Version Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('ID'), 'SettingEav_core_id', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][id]', $settings['core']->getEavAttribute('id'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('App Version'), 'SettingEav_core_app_version', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                $_versions = array();
                $p = dirname(bp());
                $d = dir($p);
                while (false !== ($entry = $d->read())) {
                    if (substr($entry, 0, 4) == 'app-') {
                        $time = filemtime($p . DS . $entry);
                        $_versions[$time] = array(
                            'entry' => $entry,
                            'display' => $entry . ' -- ' . date(Setting::item('app', 'dateTimeFormat'), $time) . ' -- (' . Time::ago($time) . ')',
                        );
                    }
                }
                $d->close();
                krsort($_versions);
                $versions = array();
                foreach ($_versions as $version) {
                    $versions[$version['entry']] = $version['display'];
                }
                echo CHtml::dropDownList('SettingEav[core][app_version]', $settings['core']->getEavAttribute('app_version'), $versions);
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Yii Version'), 'SettingEav_core_yii_version', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                $_versions = array();
                $p = dirname(dirname(bp())) . DS . 'vendors' . DS . 'yii';
                $d = dir($p);
                while (false !== ($entry = $d->read())) {
                    if (substr($entry, 0, 4) == 'yii-') {
                        $time = filemtime($p . DS . $entry);
                        $_versions[$time] = array(
                            'entry' => $entry,
                            'display' => $entry . ' -- ' . date(Setting::item('app', 'dateTimeFormat'), $time) . ' -- (' . Time::ago($time) . ')',
                        );
                    }
                }
                $d->close();
                krsort($_versions);
                $versions = array();
                foreach ($_versions as $version) {
                    $versions[$version['entry']] = $version['display'];
                }
                echo CHtml::dropDownList('SettingEav[core][yii_version]', $settings['core']->getEavAttribute('yii_version'), $versions);
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Debug Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Debug'), 'SettingEav_core_debug', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[core][debug]', 0);
                echo CHtml::checkBox('SettingEav[core][debug]', $settings['core']->getEavAttribute('debug'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Debug Levels'), 'SettingEav_core_debug_levels', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][debug_levels]', $settings['core']->getEavAttribute('debug_levels'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Debug Database'), 'SettingEav_core_debug_db', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[core][debug_db]', 0);
                echo CHtml::checkBox('SettingEav[core][debug_db]', $settings['core']->getEavAttribute('debug_db'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Error Email'), 'SettingEav_core_error_email', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][error_email]', $settings['core']->getEavAttribute('error_email'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('PHP Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Memory Limit'), 'SettingEav_core_memory_limit', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][memory_limit]', $settings['core']->getEavAttribute('memory_limit'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Time Limit'), 'SettingEav_core_time_limit', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][time_limit]', $settings['core']->getEavAttribute('time_limit'));
                ?>
            </div>
        </div>

    </fieldset>


    <fieldset>
        <legend><?php echo t('Path Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('htroot'), 'SettingEav_core_htroot', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][htroot]', $settings['core']->getEavAttribute('htroot'));
                ?>
            </div>
        </div>

    </fieldset>


    <h2><?php echo t('App Settings'); ?></h2>

    <fieldset>
        <legend><?php echo t('Application Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Theme'), 'SettingEav_app_theme', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][theme]', $settings['app']->getEavAttribute('theme'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Default Page Size'), 'SettingEav_app_defaultPageSize', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][defaultPageSize]', $settings['app']->getEavAttribute('defaultPageSize'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Login Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Allow Auto Login'), 'SettingEav_app_allowAutoLogin', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[app][allowAutoLogin]', 0);
                echo CHtml::checkBox('SettingEav[app][allowAutoLogin]', $settings['app']->getEavAttribute('allowAutoLogin'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Default Remember Me Value'), 'SettingEav_app_rememberMe', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[app][rememberMe]', 0);
                echo CHtml::checkBox('SettingEav[app][rememberMe]', $settings['app']->getEavAttribute('rememberMe'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Company Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Brand'), 'SettingEav_app_brand', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][brand]', $settings['app']->getEavAttribute('brand'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Name'), 'SettingEav_app_name', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][name]', $settings['app']->getEavAttribute('name'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Address'), 'SettingEav_app_address', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textArea('SettingEav[app][address]', $settings['app']->getEavAttribute('address'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Phone'), 'SettingEav_app_phone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][phone]', $settings['app']->getEavAttribute('phone'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Domain'), 'SettingEav_app_domain', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][domain]', $settings['app']->getEavAttribute('domain'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Website'), 'SettingEav_app_domain', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][website]', $settings['app']->getEavAttribute('website'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Email'), 'SettingEav_app_email', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][email]', $settings['app']->getEavAttribute('email'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Webmaster'), 'SettingEav_app_webmaster', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][webmaster]', $settings['app']->getEavAttribute('webmaster'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Recaptcha Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Recaptcha'), 'SettingEav_app_recaptcha', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][recaptcha]', $settings['app']->getEavAttribute('recaptcha'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Recaptcha Private Key'), 'SettingEav_app_recaptchaPrivate', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][recaptchaPrivate]', $settings['app']->getEavAttribute('recaptchaPrivate'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Recaptcha Public Key'), 'SettingEav_app_recaptchaPublic', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][recaptchaPublic]', $settings['app']->getEavAttribute('recaptchaPublic'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Date Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Timezone'), 'SettingEav_app_timezone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][timezone]', $settings['app']->getEavAttribute('timezone'));
                ?>
            </div>
        </div>

    </fieldset>

    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => t('Save'),
            'htmlOptions' => array('class' => 'pull-right'),
        ));
        ?>
    </div>

<?php $this->endWidget(); ?>