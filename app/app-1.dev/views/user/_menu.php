<?php
$controller = $this->getId();
$action = $this->getAction()->getId();

if ($user->isNewRecord) {
    $this->menu[] = array(
        'label' => t('Create'),
        'url' => ru(),
        'active' => true,
    );
    return; // no more links
}


$this->menu[] = array(
    'label' => t('View'),
    'url' => array('/user/view', 'id' => $user->id),
);
$this->menu[] = array(
    'label' => t('Update'),
    'url' => array('/user/update', 'id' => $user->id, 'returnUrl' => ReturnUrl::getLinkValue(true)),
    'active' => $action == 'update' ? true : false,
);


// more
$more = array();

$more[] = array(
    'label' => t('Log'),
    'url' => array('/user/log', 'id' => $user->id),
);

if (user()->checkAccess('admin')) {
    $more[] = array(
        'label' => t('Login As'),
        'url' => array('/user/changeUser', 'id' => $user->id),
    );
}

if (!$user->deleted) {
    $more[] = array(
        'label' => t('Delete'),
        'url' => array('/user/delete', 'id' => $user->id, 'returnUrl' => ReturnUrl::getLinkValue(true)),
        'linkOptions' => array(
            'confirm' => t('Are you sure you want to delete this user?'),
        ),
    );
}

if (YII_DEBUG) {
    $more[] = array(
        'label' => t('PhpMyAdmin'),
        'url' => Helper::getPhpMyAdminUrl($user),
    );
}

$this->menu[] = array(
    'label' => t('More'),
    'items' => $more,
);



?>