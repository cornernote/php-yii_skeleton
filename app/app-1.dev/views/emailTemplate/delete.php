<?php
/**
 * @var $this EmailTemplateController
 * @var $id int
 */

$this->pageTitle = $this->pageHeading = $this->getName() . ' ' . t('Delete');
$this->breadcrumbs = array();
$this->breadcrumbs[$this->getName() . ' ' . t('List')] = user()->getState('index.emailTemplate', array('/emailTemplate/index'));
$this->breadcrumbs[] = t('Delete');

$emailTemplate = $id ? EmailTemplate::model()->findByPk($id) : new EmailTemplate('search');
/** @var ActiveForm $form */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'emailTemplate-delete-form',
    'type' => 'horizontal',
    'action' => array('/emailTemplate/delete', 'id' => $id),
));
echo sfGridHidden($id);
echo CHtml::hiddenField('confirm', 1);
echo $form->beginModalWrap();
echo $form->errorSummary($emailTemplate);

echo '<fieldset>';
echo '<legend>' . t('Selected Records') . '</legend>';
$emailTemplates = EmailTemplate::model()->findAll('t.id IN (' . implode(',', sfGrid($id)) . ')');
if ($emailTemplates) {
	echo '<ul>';
	foreach ($emailTemplates as $emailTemplate) {
		echo '<li>';
		echo $emailTemplate->getName();
		echo '</li>';
	}
	echo '</ul>';
}
echo '</fieldset>';

echo $form->endModalWrap();
echo '<div class="' . $form->getSubmitRowClass() . '">';
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => t('Confirm Delete'),
    'htmlOptions' => array('class' => 'pull-right'),
));
echo '</div>';
$this->endWidget();
