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
));
echo $form->beginModalWrap();
echo $form->errorSummary($user);
echo $form->textFieldRow($user, 'email');
echo $form->passwordFieldRow($user, 'password');
echo $form->endModalWrap();
?>

<div class="<?php echo $form->getSubmitRowClass(); ?>">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Register'),
        'type' => 'primary',
        'buttonType' => 'submit',
    ));
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Already have an account?'),
        'url' => array('/account/login'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
