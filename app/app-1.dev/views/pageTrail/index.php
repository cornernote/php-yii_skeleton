<?php
/**
 * @var $this PageTrailController
 * @var $pageTrail PageTrail
 */
user()->setState('search.pageTrail', ru());
$this->pageTitle = $this->pageHeading = $this->getName() . ' ' . t('List');
$this->breadcrumbs = array($this->getName() . ' ' . t('List'));
$this->renderPartial('_menu');
$this->renderPartial('/pageTrail/_grid', array('pageTrail' => $pageTrail));