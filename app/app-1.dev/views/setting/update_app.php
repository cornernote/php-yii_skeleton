<?php
/**
 * @var $this SettingController
 * @var $form ActiveForm
 * @var $setting Setting
 */
?>

<?php
$this->pageTitle = 'setting-' . $setting->id . ' ' . t('Update');
$this->pageHeading = 'setting-' . $setting->id . ' ' . t('Update');
$this->renderPartial('/site/_system_menu', array('setting' => $setting));
?>


<?php
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'setting-form',
    'enableAjaxValidation' => true,
    'inlineErrors' => false,
    'type' => 'horizontal',
));
$this->widget('AskToSaveWork', array('watchElement' => '#setting-form :input', 'message' => t('Please save before leaving the page')));
echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
echo $form->errorSummary($setting);
?>

<?php
$this->widget('bootstrap.widgets.BootButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'icon' => 'ok white',
    'label' => t('Save'),
    'htmlOptions' => array('class' => 'pull-right', 'style' => 'margin-top:-50px'),
));
?>

<fieldset>
    <legend><?php echo t('Company Settings') ?></legend>

    <div class="control-group">
        <?php echo CHtml::label(t('Name'), 'SettingEav_name', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[name]', $setting->getEavAttribute('name'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Phone'), 'SettingEav_phone', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[phone]', $setting->getEavAttribute('phone'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Domain'), 'SettingEav_domain', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[domain]', $setting->getEavAttribute('domain'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Website'), 'SettingEav_domain', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[website]', $setting->getEavAttribute('website'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Email'), 'SettingEav_email', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[email]', $setting->getEavAttribute('email'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Webmaster'), 'SettingEav_webmaster', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[webmaster]', $setting->getEavAttribute('webmaster'));
            ?>
        </div>
    </div>

</fieldset>

<fieldset>
    <legend><?php echo t('Login Settings') ?></legend>

    <div class="control-group">
        <?php echo CHtml::label(t('Allow Auto Login'), 'SettingEav_allowAutoLogin', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::checkBox('SettingEav[allowAutoLogin]', $setting->getEavAttribute('allowAutoLogin'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Default Remember Me Value'), 'SettingEav_rememberMe', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::checkBox('SettingEav[rememberMe]', $setting->getEavAttribute('rememberMe'));
            ?>
        </div>
    </div>

</fieldset>

<fieldset>
    <legend><?php echo t('Application Settings') ?></legend>

    <div class="control-group">
        <?php echo CHtml::label(t('Default Page Size'), 'SettingEav_defaultPageSize', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[defaultPageSize]', $setting->getEavAttribute('defaultPageSize'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Theme'), 'SettingEav_theme', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[theme]', $setting->getEavAttribute('theme'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Recaptcha'), 'SettingEav_recaptcha', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::checkBox('SettingEav[recaptcha]', $setting->getEavAttribute('recaptcha'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Recaptcha Private Key'), 'SettingEav_recaptchaPrivate', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[recaptchaPrivate]', $setting->getEavAttribute('recaptchaPrivate'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Recaptcha Public Key'), 'SettingEav_recaptchaPublic', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[recaptchaPublic]', $setting->getEavAttribute('recaptchaPublic'));
            ?>
        </div>
    </div>

</fieldset>


<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.BootButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => t('Save'),
        'htmlOptions' => array('class' => 'pull-right'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>