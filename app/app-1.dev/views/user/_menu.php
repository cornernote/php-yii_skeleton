<?php
/**
 * @var $this UserController
 * @var $user User
 */
$controller = $this->id;
$action = $this->action->id;

if ($user->isNewRecord) {
    $this->menu[] = array(
        'label' => t('Create'),
        'url' => array('/user/create'),
    );
    return; // no more links
}

$this->menu[] = array(
    'label' => t('View'),
    'url' => $user->getUrl(),
);

$this->menu[] = array(
    'label' => t('Update'),
    'url' => array('/user/update', 'id' => $user->id),
    'active' => $this->id == 'user' && $this->action->id == 'update',
);

$more = array();

$more[] = array(
    'label' => t('Log'),
    'url' => array('/user/log', 'id' => $user->id),
);

$more[] = array(
    'label' => t('Delete'),
    'url' => array('/user/delete', 'id' => $user->id),
    'linkOptions' => array('data-toggle' => 'modal-remote'),
    'active' => $this->id == 'user' && $this->action->id == 'delete',
);

$more[] = '---';
$more[] = array(
    'label' => t('Create Admin'),
    'url' => array('/user/create', 'role_id' => Role::ROLE_ADMIN),
);
$more[] = array(
    'label' => t('Create Reseller'),
    'url' => array('/user/create', 'role_id' => Role::ROLE_RESELLER),
);
$more[] = array(
    'label' => t('Create Locksmith'),
    'url' => array('/user/create', 'role_id' => Role::ROLE_LOCKSMITH),
);
$more[] = array(
    'label' => t('Create Customer'),
    'url' => array('/user/create', 'role_id' => Role::ROLE_CUSTOMER),
);
$more[] = array(
    'label' => t('Create Key Holder'),
    'url' => array('/user/create', 'role_id' => Role::ROLE_KEY_HOLDER),
);


$this->menu[] = array(
    'label' => t('More'),
    'items' => $more,
);
