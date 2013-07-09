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
    'htmlOptions' => array(
        'class' => app()->request->isAjaxRequest ? 'modal-form' : '',
    ),
));
if (app()->request->isAjaxRequest) echo '<div class="modal-body">';
echo CHtml::errorSummary($user);

echo $form->textFieldRow($user, 'email');
echo $form->passwordFieldRow($user, 'password');

$user->remember_me = Setting::item('app', 'rememberMe');
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
?>

<?php if (app()->request->isAjaxRequest) echo '</div>'; ?>
<div class="<?php echo app()->request->isAjaxRequest ? 'modal-footer' : 'form-actions'; ?>">
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
    ?>
</div>
<?php $this->endWidget(); ?>
