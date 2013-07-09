<?php
/**
 * @var $this UserController
 * @var $user User
 */
user()->setState('index.user', ru());
$this->pageTitle = t('Users');
$this->pageHeading = t('Users');
$this->breadcrumbs = array(t('Users'));

$this->renderPartial('_menu', array(
    'user' => $user,
));

echo '<div class="spacer">';
if (user()->checkAccess('admin')) {
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Create Admin'),
        'url' => array('/user/create', 'role_id' => Role::ROLE_ADMIN),
        'type' => 'primary',
        'htmlOptions' => array('data-toggle' => 'modal-remote'),
    ));
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Create Reseller'),
        'url' => array('/user/create', 'role_id' => Role::ROLE_RESELLER),
        'type' => 'primary',
        'htmlOptions' => array('data-toggle' => 'modal-remote'),
    ));
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Create Locksmith'),
        'url' => array('/user/create', 'role_id' => Role::ROLE_LOCKSMITH),
        'type' => 'primary',
        'htmlOptions' => array('data-toggle' => 'modal-remote'),
    ));
}
if (user()->checkAccess('admin,locksmith')) {
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Create Customer'),
        'url' => array('/user/create', 'role_id' => Role::ROLE_CUSTOMER),
        'type' => 'primary',
        'htmlOptions' => array('data-toggle' => 'modal-remote'),
    ));
}
if (user()->checkAccess('admin,locksmith,customer')) {
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Create Key Holder'),
        'url' => array('/user/create', 'role_id' => Role::ROLE_KEYHOLDER),
        'type' => 'primary',
        'htmlOptions' => array('data-toggle' => 'modal-remote'),
    ));
}
echo ' ';
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => t('Search'),
    'htmlOptions' => array('class' => 'search-button'),
    'toggle' => true,
));
if (user()->getState('index.user') != url('/user/index')) {
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Reset Filters'),
        'url' => array('/user/index'),
    ));
}
echo '</div>';

// search
$this->renderPartial('/user/_search', array(
    'user' => $user,
));

// grid
$this->renderPartial('/user/_grid', array(
    'user' => $user,
));
