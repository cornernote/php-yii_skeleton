<?php
/**
 * @var $this UserController
 * @var $user User
 * @var $role Role
 */
$this->pageTitle = ucwords($role->name) . ' ' . t('Create');
$this->pageHeading = ucwords($role->name) . ' ' . t('Create');
$this->breadcrumbs = array(
    t('Users') => user()->getState('index.user', array('/user/index')),
    ucwords($role->name) . ' ' . t('Create'),
);
$this->renderPartial('_menu', array(
    'user' => $user,
));
$this->renderPartial('_form', array(
    'user' => $user,
    'role' => $role,
));
?>