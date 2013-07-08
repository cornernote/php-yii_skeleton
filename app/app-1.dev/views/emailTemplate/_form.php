<?php
/**
 * @var $this EmailTemplateController
 * @var $emailTemplate EmailTemplate
 */
?>

<?php
/* @var $form ActiveForm */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'emailTemplate-form',
    'enableAjaxValidation' => true,
    'type' => 'horizontal',
    'htmlOptions' => array(
        'class' => app()->request->isAjaxRequest ? 'modal-form' : '',
    ),
));
echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
if (app()->request->isAjaxRequest) echo '<div class="modal-body">';
echo $form->errorSummary($emailTemplate);
echo $form->textAreaRow($emailTemplate, 'description');
echo $form->textFieldRow($emailTemplate, 'message_subject');
echo $form->textAreaRow($emailTemplate, 'message_html');
echo $form->textAreaRow($emailTemplate, 'message_text');
?>

<?php if (app()->request->isAjaxRequest) echo '</div>'; ?>
<div class="<?php echo app()->request->isAjaxRequest ? 'modal-footer' : 'form-actions'; ?>">
    <?php
    $this->widget('bootstrap.widgets.BootButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => $emailTemplate->isNewRecord ? t('Create') : t('Save'),
        'htmlOptions' => array('class' => 'pull-right'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>