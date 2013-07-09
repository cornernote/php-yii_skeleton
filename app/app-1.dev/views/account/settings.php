<?php
/**
 * @var $this AccountController
 * @var $form ActiveForm
 * @var $user User
 */
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

<div class="control-group">
    <?php echo CHtml::label(t('Theme'), 'UserEav_theme', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        echo CHtml::dropDownList('UserEav[theme]', $user->getEavAttribute('theme'), param('themes'));
        ?>
    </div>
</div>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => t('Save'))); ?>
</div>

<?php $this->endWidget(); ?>
