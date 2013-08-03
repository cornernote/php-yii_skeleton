<?php
/**
 * @var $this MenuController
 * @var $id int
 */

$this->pageTitle = $this->pageHeading = $this->getName() . ' ' . t('Delete');
$this->breadcrumbs = array();
$this->breadcrumbs[$this->getName() . ' ' . t('List')] = user()->getState('index.menu', array('/menu/index'));
$this->breadcrumbs[] = t('Delete');

$menu = $id ? Menu::model()->findByPk($id) : new Menu('search');
/** @var ActiveForm $form */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'menu-delete-form',
    'type' => 'horizontal',
    'action' => array('/menu/delete', 'id' => $id),
));
echo sfGridHidden($id);
echo CHtml::hiddenField('confirm', 1);
echo $form->beginModalWrap();
echo $form->errorSummary($menu);

echo '<fieldset>';
echo '<legend>' . t('Selected Records') . '</legend>';
$menus = Menu::model()->findAll('t.id IN (' . implode(',', sfGrid($id)) . ')');
if ($menus) {
	echo '<ul>';
	foreach ($menus as $menu) {
		echo '<li>';
		echo $menu->getName();
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
