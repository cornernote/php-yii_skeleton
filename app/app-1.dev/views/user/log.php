<?php
$this->pageTitle = 'user-' . $user->id . ' ' . t('Log');
$this->pageHeading = $user->name . ' <small>user-' . $user->id . ' ' . t('Log') . '</small>';
$this->breadcrumbs = array(
    t('Users') => user()->getState('search.user', array('index')),
    'user-' . $user->id => array('view', 'id' => $user->id),
    t('Log'),
);
$this->renderPartial('_menu', array('user' => $user));
$this->renderPartial('//auditTrail/log_common', array('model' => $user));