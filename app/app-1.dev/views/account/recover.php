<?php
/**
 * @var $this AccountController
 * @var $user UserRecover
 * @var $recaptcha string
 */

$this->pageTitle = t('Recover Password');
$this->pageHeading = t('Recover Password');

/* @var $form ActiveForm */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'recover-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => array(
        'class' => app()->request->isAjaxRequest ? 'modal-form' : '',
    ),
));
if (app()->request->isAjaxRequest) echo '<div class="modal-body">';
echo CHtml::errorSummary($user);

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

<?php if (app()->request->isAjaxRequest) echo '</div>'; ?>
<div class="<?php echo app()->request->isAjaxRequest ? 'modal-footer' : 'form-actions'; ?>">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Recover'),
        'type' => 'primary',
        'buttonType' => 'submit',
    ));
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Back to Login'),
        'url' => array('/account/login'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>


