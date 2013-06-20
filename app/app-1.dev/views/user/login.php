<?php
$this->pageTitle = Setting::item('app', 'brand') . ' ' . t('Login');
$this->pageHeading = Setting::item('app', 'brand') . ' ' . t('Login');
?>


<?php
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'login-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
));
echo CHtml::errorSummary($user);
?>

<div class="well">
    <?php
    echo $form->textFieldRow($user, 'username');
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
</div>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.BootButton', array(
        'label' => t('Login'),
        'type' => 'primary',
        'buttonType' => 'submit',
    ));
    echo ' ';
    $this->widget('bootstrap.widgets.BootButton', array(
        'label' => t('Lost Password'),
        'url' => array('/user/recover'),
    ));
    ?>
</div>

<?php
$this->endWidget();
?>
