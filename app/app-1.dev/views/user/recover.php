<?php
$this->pageTitle = t('Recover Password');
$this->pageHeading = t('Recover Password');
?>

<?php
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'recover-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
));
echo CHtml::errorSummary($user);
?>
<div class="well">

    <?php
    echo $form->textFieldRow($user, 'username_or_email');
    if ($recaptcha) {
        echo CHtml::activeLabel($user, 'recaptcha');
        $this->widget('EReCaptcha', array(
            'model' => $user,
            'attribute' => 'recaptcha',
            'theme' => 'red',
            'language' => 'en_EN',
            'publicKey' => Setting::item('app', 'recaptchaPublic'),
        ));
        echo CHtml::error($user, 'recaptcha');
    }
    ?>
</div>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.BootButton', array(
        'label' => t('Recover'),
        'type' => 'primary',
        'buttonType' => 'submit',
    ));
    echo ' ';
    $this->widget('bootstrap.widgets.BootButton', array(
        'label' => t('Back to Login'),
        'url' => array('/user/login'),
    ));
    ?>
</div>
<?php
$this->endWidget();
?>


