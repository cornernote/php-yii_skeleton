<?php
/**
 * @var $this UserController
 * @var $user User
 */
$this->pageTitle = t('User') . ' ' . $user->name . ' ' . t('Update');
$this->pageHeading = $user->name . ' ' . t('Update');

$this->breadcrumbs = array();
$this->breadcrumbs[t('Users')] = user()->getState('index.user', array('/user/index'));
$this->breadcrumbs[$user->name] = $user->getLink();
$this->breadcrumbs[] = t('Update');

$this->renderPartial('_menu', array(
    'user' => $user,
));
$this->renderPartial('_form', array(
    'user' => $user,
));
