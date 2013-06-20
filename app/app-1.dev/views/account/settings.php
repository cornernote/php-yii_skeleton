<?php
/* @var $this AccountController */
?>
<?php
$this->pageTitle = t('Account Settings');
$this->pageHeading = t('Account Settings');
$this->breadcrumbs = array(
    t('My Account') => array('index'),
    t('Settings'),
);
$this->renderPartial('_menu', array('user' => $user));
?>

<?php
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'account-form',
    'enableAjaxValidation' => true,
    'type' => 'horizontal',
));
echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
echo $form->errorSummary($user);
?>

<fieldset>
    <legend><?php echo t('Interface Settings') ?></legend>

    <div class="control-group">
        <?php echo CHtml::label(t('Theme'), 'UserEav_theme', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::dropDownList('UserEav[theme]', $user->getEavAttribute('theme'), param('themes'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Hints'), 'UserEav_hide_hints', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::dropDownList('UserEav[hide_hints]', $user->getEavAttribute('hide_hints'), array('show hints', 'hide hints'));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo CHtml::label(t('Translate'), 'UserEav_translate', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::checkBox('UserEav[translate]', $user->getEavAttribute('translate'));
            ?>
        </div>
    </div>

    <?php /* ?>
    <div class="control-group">
        <?php echo CHtml::label(t('Language'), 'UserEav_language', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::dropDownList('UserEav[language]', $user->getEavAttribute('language'), param('languages'));
            ?>
        </div>
    </div>
    <?php */ ?>

</fieldset>


<fieldset>
    <legend><?php echo t('Tour Settings') ?></legend>

    <div class="control-group">
        <?php echo CHtml::label(t('Home Tour'), 'UserEav_hide_tour_home', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::dropDownList('UserEav[hide_tour_home]', $user->getEavAttribute('hide_tour_home'), array('show tour', 'hide tour'));
            ?>
        </div>
    </div>

</fieldset>

<?php if (user()->checkAccess('sales')) { ?>
<fieldset>
    <legend><?php echo t('Sales Rep Settings') ?></legend>

    <div class="control-group">
        <?php echo CHtml::label(t('Monthly Budget'), 'UserEav_sales_monthly_budget', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo '<div class="input-prepend input-append">';
            echo '<span class="add-on">$</span>';
            echo CHtml::textField('UserEav[sales_monthly_budget]', $user->getEavAttribute('sales_monthly_budget'));
            echo '<span class="add-on">.00</span>';
            echo '</div>';
            ?>
        </div>
    </div>

</fieldset>
<?php } ?>


<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => t('Save'))); ?>
</div>

<?php $this->endWidget(); ?>
