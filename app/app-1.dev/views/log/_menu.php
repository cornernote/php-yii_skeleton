<?php
/**
 * @var $this AuditTrailController
 * @var $auditTrail AuditTrail
 */

// index
if ($this->action->id == 'index') {
    $this->menu = NavbarItems::systemMenuItems();
    return; // no more links
}

// create
//if ($auditTrail->isNewRecord) {
//    $this->menu[] = array(
//        'label' => t('Create'),
//        'url' => array('/auditTrail/create'),
//    );
//    return; // no more links
//}

// view
$this->menu[] = array(
    'label' => t('View'),
    'url' => $auditTrail->getUrl(),
);

// others
foreach ($auditTrail->getDropdownLinkItems() as $linkItem) {
    $this->menu[] = $linkItem;
}

// more
$more = array();
foreach ($auditTrail->getMoreDropdownLinkItems() as $linkItem) {
    $more[] = $linkItem;
}
if ($more) {
    $this->menu[] = array(
        'label' => t('More'),
        'items' => $more,
    );
}