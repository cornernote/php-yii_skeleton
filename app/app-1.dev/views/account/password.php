<?php
/* @var $this AccountController */
?>
<?php
$this->pageTitle = t('Change Password');
$this->pageHeading = t('Change Password');
$this->breadcrumbs = array(
    t('My Account') => array('index'),
    t('Password'),
);
$this->renderPartial('_menu', array('user' => $user));
?>

<?php
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'password-form',
    'enableAjaxValidation' => true,
    'type' => 'horizontal',
));
?>

<?php echo CHtml::errorSummary($user); ?>

<?php
echo $form->passwordFieldRow($user, 'current_password');
echo $form->passwordFieldRow($user, 'password');
echo $form->passwordFieldRow($user, 'confirm_password');
?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => t('Save'))); ?>
</div>

<?php $this->endWidget(); ?>
