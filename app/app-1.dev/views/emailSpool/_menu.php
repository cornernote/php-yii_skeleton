<?php
/**
 * @var $this EmailSpoolController
 * @var $emailSpool EmailSpool
 */

// index
if ($this->action->id == 'index') {
    $this->menu = NavbarItems::systemMenuItems();
    return; // no more links
}

// create
//if ($emailSpool->isNewRecord) {
//    $this->menu[] = array(
//        'label' => t('Create'),
//        'url' => array('/emailSpool/create'),
//    );
//    return; // no more links
//}

// view
$this->menu[] = array(
    'label' => t('View'),
    'url' => $emailSpool->getUrl(),
);

// others
foreach ($emailSpool->getDropdownLinkItems() as $linkItem) {
    $this->menu[] = $linkItem;
}

// more
$more = array();
foreach ($emailSpool->getMoreDropdownLinkItems() as $linkItem) {
    $more[] = $linkItem;
}
if ($more) {
    $this->menu[] = array(
        'label' => t('More'),
        'items' => $more,
    );
}