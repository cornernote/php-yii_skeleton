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
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'icon' => 'ok white',
    'label' => t('Save'),
    'htmlOptions' => array('class' => 'pull-right', 'style' => 'margin-top:-50px'),
));
?>

<fieldset>
    <legend><?php echo t('Version Settings') ?></legend>

    <div class="control-group">
        <?php echo CHtml::label(t('ID'), 'SettingEav_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[id]', $setting->getEavAttribute('id'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('App Version'), 'SettingEav_app_version', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $_versions = array();
            $p = dirname(bp());
            $d = dir($p);
            while (false !== ($entry = $d->read())) {
                if (substr($entry, 0, 6) == 'app-1.') {
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
            echo CHtml::dropDownList('SettingEav[app_version]', $setting->getEavAttribute('app_version'), $versions);
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Yii Version'), 'SettingEav_yii_version', array('class' => 'control-label')); ?>
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
            echo CHtml::dropDownList('SettingEav[yii_version]', $setting->getEavAttribute('yii_version'), $versions);
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Yii Lite'), 'SettingEav_yii_lite', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::checkBox('SettingEav[yii_lite]', $setting->getEavAttribute('yii_lite'));
            ?>
        </div>
    </div>

</fieldset>

<fieldset>
    <legend><?php echo t('Debug Settings') ?></legend>

    <div class="control-group">
        <?php echo CHtml::label(t('Debug'), 'SettingEav_debug', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::checkBox('SettingEav[debug]', $setting->getEavAttribute('debug'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Debug Levels'), 'SettingEav_debug_levels', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[debug_levels]', $setting->getEavAttribute('debug_levels'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Debug Database'), 'SettingEav_debug_db', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::checkBox('SettingEav[debug_db]', $setting->getEavAttribute('debug_db'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Debug Email'), 'SettingEav_debug_email', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::checkBox('SettingEav[debug_email]', $setting->getEavAttribute('debug_email'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Debug Toolbar'), 'SettingEav_debug_toolbar', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::checkBox('SettingEav[debug_toolbar]', $setting->getEavAttribute('debug_toolbar'));
            ?>
        </div>
    </div>

</fieldset>

<fieldset>
    <legend><?php echo t('PHP Settings') ?></legend>

    <div class="control-group">
        <?php echo CHtml::label(t('Memory Limit'), 'SettingEav_memory_limit', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[memory_limit]', $setting->getEavAttribute('memory_limit'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Time Limit'), 'SettingEav_time_limit', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::textField('SettingEav[time_limit]', $setting->getEavAttribute('time_limit'));
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