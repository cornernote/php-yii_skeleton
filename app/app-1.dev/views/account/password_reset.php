<?php
/**
 * @var $this AccountController
 * @var $form ActiveForm
 * @var $user User
 */
?>
<?php
$this->pageTitle = t('Set Password');
$this->pageHeading = t('Set Password');
$this->breadcrumbs = array(
    t('Set Password'),
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
