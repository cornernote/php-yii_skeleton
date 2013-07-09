<?php
$this->pageTitle = t('Change Password');
$this->pageHeading = t('Change Password');
$this->breadcrumbs = array(
    //t('Account') => array('/user/index'),
    t('Change Password'),
);
?>

<?php
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'password-form',
    'enableAjaxValidation' => true,
    'type' => 'horizontal',
));
echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
echo CHtml::errorSummary($user);
echo $form->passwordFieldRow($user, 'password');
echo $form->passwordFieldRow($user, 'confirm_password');
?>

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
