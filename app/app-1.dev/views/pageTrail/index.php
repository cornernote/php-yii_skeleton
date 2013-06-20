<?php
/**
 * @var $pageTrail PageTrail
 */
user()->setState('search.pageTrail', ru());

$this->pageTitle = t('Page Trails');
$this->pageHeading = t('Page Trails');
$this->breadcrumbs = array(
    t('Page Trails'),
);
$this->renderPartial('/site/_system_menu');
$this->renderPartial('/pageTrail/_grid', array('pageTrail' => $pageTrail));
?>
