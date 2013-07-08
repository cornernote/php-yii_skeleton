<?php
/**
 * @var $this SiteController
 */
$this->pageTitle = t('Example');
$this->pageHeading = t('Example');

// menu
$menu = NavbarItems::systemMenu();
$this->menu = $menu['items'];

// breadcrumbs
$this->breadcrumbs = array(
    t('Account') => array('/account/index'),
    t('Example'),
);

?>


<p><?php echo __FILE__; ?></p>