<?php
$this->pageTitle = 'user-' . $user->id . ' ' . t('Update');
$this->pageHeading = 'user-' . $user->id . ' ' . t('Update') . ' <small>' . $user->name . '</small>';
$this->breadcrumbs = array(
    t('Users') => user()->getState('search.user', array('index')),
    'user-' . $user->id => array('view', 'id' => $user->id),
    t('Update'),
);
$this->renderPartial('_menu', array('user' => $user));
?>

<?php $this->renderPartial('_form', array('user' => $user)); ?>