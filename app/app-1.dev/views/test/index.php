<?php
/**
 * @var $this TestController
 * @var $test Test
 */

user()->setState('index.test', ru());

$this->pageTitle = $this->pageHeading = $this->getName() . ' ' . t('List');

$this->breadcrumbs = array($this->getName() . ' ' . t('List'));

d(123);

d($this);