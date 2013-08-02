<?php
/**
 * @var $this WebController
 */
$this->pageTitle = $this->pageHeading = t('Help');

// menu
$this->menu = NavbarItems::helpMenuItems();

// breadcrumbs
$this->breadcrumbs = array(
    t('Help'),
);
