<?php
/**
 * @var $this AccountController
 * @var $user UserRegister
 */
$this->pageTitle = Setting::item('app', 'brand') . ' ' . t('Register');
$this->pageHeading = Setting::item('app', 'brand') . ' ' . t('Register');

/* @var $form ActiveForm */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'register-form',
    'enableAjaxValidation' => true,
    'type' => 'horizontal',
    'htmlOptions' => array(
        'class' => app()->request->isAjaxRequest ? 'modal-form' : '',
    ),
));
if (app()->request->isAjaxRequest) echo '<div class="modal-body">';
echo CHtml::errorSummary($user);

echo $form->textFieldRow($user, 'name');
echo $form->textFieldRow($user, 'email');
echo $form->passwordFieldRow($user, 'password');
echo $form->dropDownListRow($user, 'locksmith_plan', Locksmith::model()->getLocksmithPlans());
?>

<?php if (app()->request->isAjaxRequest) echo '</div>'; ?>
<div class="<?php echo app()->request->isAjaxRequest ? 'modal-footer' : 'form-actions'; ?>">
    <?php
    $this->widget('bootstrap.widgets.BootButton', array(
        'label' => t('Register'),
        'type' => 'primary',
        'buttonType' => 'submit',
    ));
    echo ' ';
    $this->widget('bootstrap.widgets.BootButton', array(
        'label' => t('Already have an account?'),
        'url' => array('/account/login'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
