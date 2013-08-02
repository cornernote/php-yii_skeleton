<?php
/**
 * @var $this PageTrailController
 * @var $pageTrail PageTrail
 */

// index
if ($this->action->id == 'index') {
    $this->menu = NavbarItems::systemMenuItems();
    return; // no more links
}

// create
//if ($pageTrail->isNewRecord) {
//    $this->menu[] = array(
//        'label' => t('Create'),
//        'url' => array('/pageTrail/create'),
//    );
//    return; // no more links
//}

// view
$this->menu[] = array(
    'label' => t('View'),
    'url' => $pageTrail->getUrl(),
);

// others
foreach ($pageTrail->getDropdownLinkItems() as $linkItem) {
    $this->menu[] = $linkItem;
}

// more
$more = array();
foreach ($pageTrail->getMoreDropdownLinkItems() as $linkItem) {
    $more[] = $linkItem;
}
if ($more) {
    $this->menu[] = array(
        'label' => t('More'),
        'items' => $more,
    );
}