<?php
/**
 * @var $this AttachmentController
 * @var $attachment Attachment
 */

/** @var $form ActiveForm */
$form = $this->beginWidget('widgets.ActiveForm', array(
    'id' => 'attachment-form',
    'type' => 'horizontal',
    //'enableAjaxValidation' => true,
));
echo $form->beginModalWrap();
echo $form->errorSummary($attachment);

echo $form->textFieldRow($attachment, 'model');
echo $form->textFieldRow($attachment, 'model_id');
echo $form->textFieldRow($attachment, 'filename');
echo $form->textFieldRow($attachment, 'extension');
echo $form->textFieldRow($attachment, 'filetype');
echo $form->textFieldRow($attachment, 'filesize');
echo $form->textFieldRow($attachment, 'notes');
echo $form->textFieldRow($attachment, 'sort_order');
echo $form->textFieldRow($attachment, 'created');
echo $form->textFieldRow($attachment, 'deleted');

echo $form->endModalWrap();
echo '<div class="' . $form->getSubmitRowClass() . '">';
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'icon' => 'ok white',
    'label' => $attachment->isNewRecord ? t('Create') : t('Save'),
    'htmlOptions' => array('class' => 'pull-right'),
));
echo '</div>';
$this->endWidget();
