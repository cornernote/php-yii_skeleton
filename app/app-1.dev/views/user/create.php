<?php
$this->pageTitle = t('Create User');
$this->pageHeading = t('Create User');
$this->breadcrumbs = array(
    t('Users') => user()->getState('search.user', array('index')),
    t('Create'),
);
$this->renderPartial('_menu', array('user' => $user));
?>

<?php $this->renderPartial('_form', array('user' => $user, 'rolePreselect' => $rolePreselect)); ?>