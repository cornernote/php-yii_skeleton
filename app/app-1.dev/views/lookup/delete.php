<?php
/**
 * @var $this LookupController
 * @var $id int
 */

$this->pageTitle = $this->pageHeading = $this->getName() . ' ' . t('Delete');
$this->breadcrumbs = array();
$this->breadcrumbs[$this->getName() . ' ' . t('List')] = user()->getState('index.lookup', array('/lookup/index'));
$this->breadcrumbs[] = t('Delete');

$lookup = $id ? Lookup::model()->findByPk($id) : new Lookup('search');
/** @var ActiveForm $form */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'lookup-delete-form',
    'type' => 'horizontal',
    'action' => array('/lookup/delete', 'id' => $id),
));
echo sfGridHidden($id);
echo CHtml::hiddenField('confirm', 1);
echo $form->beginModalWrap();
echo $form->errorSummary($lookup);

echo '<fieldset>';
echo '<legend>' . t('Selected Records') . '</legend>';
$lookups = Lookup::model()->findAll('t.id IN (' . implode(',', sfGrid($id)) . ')');
if ($lookups) {
	echo '<ul>';
	foreach ($lookups as $lookup) {
		echo '<li>';
		echo $lookup->getName();
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
