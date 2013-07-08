<?php
/**
 * @var $this UserController
 * @var $user User
 */
$this->pageTitle = t('User') . ' ' . $user->name . ' ' . t('Log');
$this->pageHeading = $user->name . ' ' . t('Log');

$this->breadcrumbs = array();
$this->breadcrumbs[t('Users')] = user()->getState('index.user', array('/user/index'));
$this->breadcrumbs[$user->name] = $user->getLink();
$this->breadcrumbs[] = t('Log');

$this->renderPartial('_menu', array(
    'user' => $user,
));
$this->renderPartial('/auditTrail/_log', array(
    'model' => $user,
));