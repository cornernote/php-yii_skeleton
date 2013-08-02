<?php
/**
 * @var $this AccountController
 * @var $user User
 */
$this->pageTitle = $this->pageHeading = t('Change Password');
$this->breadcrumbs = array(
    t('My Account') => array('/account/index'),
    t('Change Password'),
);
$this->renderPartial('_menu', array('user' => $user));

/** @var ActiveForm $form */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'password-form',
    //'enableAjaxValidation' => true,
    'type' => 'horizontal',
));
echo $form->beginModalWrap();
echo $form->errorSummary($user);

echo $form->passwordFieldRow($user, 'current_password');
echo $form->passwordFieldRow($user, 'password');
echo $form->passwordFieldRow($user, 'confirm_password');

echo $form->endModalWrap();
echo '<div class="' . $form->getSubmitRowClass() . '">';
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'icon' => 'ok white',
    'label' => t('Save'),
    'htmlOptions' => array('class' => 'pull-right'),
));
echo '</div>';
$this->endWidget();