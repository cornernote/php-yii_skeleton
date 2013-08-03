<?php
/**
 * @var $this PageTrailController
 * @var $pageTrail PageTrail
 */

// index
if ($this->action->id == 'index') {
    $this->menu = Menu::getItemsFromMenu('System');
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
foreach ($pageTrail->getDropdownLinkItems(true) as $linkItem) {
    $this->menu[] = $linkItem;
}
