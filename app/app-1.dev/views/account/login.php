<?php
/**
 * @var $this AccountController
 * @var $user UserLogin
 * @var $recaptcha string
 */

$this->pageTitle = Setting::item('app', 'brand') . ' ' . t('Login');
$this->pageHeading = Setting::item('app', 'brand') . ' ' . t('Login');

/* @var $form ActiveForm */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'login-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
));
echo $form->beginModalWrap();
echo $form->errorSummary($user);
echo $form->textFieldRow($user, 'email');
echo $form->passwordFieldRow($user, 'password');
echo $form->checkBoxRow($user, 'remember_me');

if ($recaptcha) {
    echo CHtml::activeLabel($user, 'recaptcha');
    $this->widget('EReCaptcha', array(
        'model' => $user, 'attribute' => 'recaptcha',
        'theme' => 'red', 'language' => 'en_EN',
        'publicKey' => Setting::item('app', 'recaptchaPublic'),
    ));
    echo CHtml::error($user, 'recaptcha');
}
echo $form->endModalWrap();
?>

<div class="<?php echo $form->getSubmitRowClass(); ?>">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Login'),
        'type' => 'primary',
        'buttonType' => 'submit',
    ));
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Lost Password'),
        'url' => array('/account/recover'),
    ));
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Register New Account'),
        'url' => array('/account/register'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
