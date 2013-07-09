<?php
/**
 * @var $this AccountController
 */
?>
<?php
$this->pageTitle = t('Update Account');
$this->pageHeading = t('Update Account');
$this->breadcrumbs = array(
    t('My Account') => array('index'),
    t('Update'),
);
$this->renderPartial('_menu', array('user' => $user));
?>


<?php
/* @var $form ActiveForm */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'account-form',
    'enableAjaxValidation' => true,
    'type' => 'horizontal',
));
echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
echo $form->errorSummary($user);
?>

<fieldset>
    <legend><?php echo t('User Details') ?></legend>

    <?php
    echo $form->textFieldRow($user, 'username', array('size' => 60, 'maxlength' => 255));
    echo $form->textFieldRow($user, 'name', array('size' => 60, 'maxlength' => 255));
    echo $form->textFieldRow($user, 'email', array('size' => 60, 'maxlength' => 255));
    echo $form->textFieldRow($user, 'phone', array('size' => 60, 'maxlength' => 255));
    ?>

</fieldset>


<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => t('Save'))); ?>
</div>

<?php $this->endWidget(); ?>
