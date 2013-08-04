<?php
/**
 * @var $this SettingController
 * @var $settings Setting[]
 */

$this->pageTitle = $this->pageHeading = t('Settings');
$this->menu = Menu::getItemsFromMenu('System');
?>


<?php
/** @var ActiveForm $form */
$form = $this->beginWidget('widgets.ActiveForm', array(
    'id' => 'setting-form',
    //'enableAjaxValidation' => true,
    'inlineErrors' => false,
    'type' => 'horizontal',
));
$this->widget('widgets.AskToSaveWork', array('watchElement' => '#setting-form :input', 'message' => t('Please save before leaving the page')));
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

        <?php
        echo $form->textFieldRow($settings['id'], 'value');
        echo $form->dropDownListRow($settings['app_version'], 'value', Setting::appVersions());
        echo $form->dropDownListRow($settings['yii_version'], 'value', Setting::yiiVersions());
        echo $form->checkBoxRow($settings['yii_lite'], 'value');
        ?>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Debug Settings') ?></legend>

        <?php
        echo $form->checkBoxRow($settings['debug'], 'value');
        echo $form->textFieldRow($settings['debug_levels'], 'value');
        echo $form->checkBoxRow($settings['debug_db'], 'value');
        echo $form->textFieldRow($settings['error_email'], 'value');
        ?>

    </fieldset>

    <fieldset>
        <legend><?php echo t('PHP Settings') ?></legend>

        <?php
        echo $form->textFieldRow($settings['memory_limit'], 'value');
        echo $form->textFieldRow($settings['time_limit'], 'value');
        ?>

    </fieldset>

    <h2><?php echo t('App Settings'); ?></h2>

    <fieldset>
        <legend><?php echo t('Application Settings') ?></legend>

        <?php
        echo $form->dropDownListRow($settings['theme'], 'value', param('themes'));
        echo $form->textFieldRow($settings['defaultPageSize'], 'value');
        ?>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Login Settings') ?></legend>

        <?php
        echo $form->checkBoxRow($settings['allowAutoLogin'], 'value');
        echo $form->checkBoxRow($settings['rememberMe'], 'value');
        ?>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Company Settings') ?></legend>

        <?php
        echo $form->textFieldRow($settings['brand'], 'value');
        echo $form->textFieldRow($settings['name'], 'value');
        echo $form->textAreaRow($settings['address'], 'value');
        echo $form->textFieldRow($settings['phone'], 'value');
        echo $form->textFieldRow($settings['website'], 'value');
        echo $form->textFieldRow($settings['email'], 'value');
        ?>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Recaptcha Settings') ?></legend>

        <?php
        echo $form->checkBoxRow($settings['recaptcha'], 'value');
        echo $form->textFieldRow($settings['recaptchaPrivate'], 'value');
        echo $form->textFieldRow($settings['recaptchaPublic'], 'value');
        ?>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Date Settings') ?></legend>

        <?php
        echo $form->textFieldRow($settings['timezone'], 'value');
        echo $form->textFieldRow($settings['dateFormat'], 'value');
        echo $form->textFieldRow($settings['dateFormatLong'], 'value');
        echo $form->textFieldRow($settings['timeFormat'], 'value');
        echo $form->textFieldRow($settings['timeFormatLong'], 'value');
        echo $form->textFieldRow($settings['dateTimeFormat'], 'value');
        echo $form->textFieldRow($settings['dateTimeFormatLong'], 'value');
        ?>

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