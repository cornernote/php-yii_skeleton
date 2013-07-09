<?php
/**
 * @var $this WebController
 */
$this->pageTitle = t('Documentation');
$this->pageHeading = t('Documentation');

// menu
$menu = NavbarItems::systemMenu();
$this->menu = $menu['items'];

//// breadcrumbs
//$this->breadcrumbs = array(
//    t('Account') => array('/account/index'),
//    t('Example'),
//);

$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills', // '', 'tabs', 'pills' (or 'list')
    'stacked' => true, // whether this is a stacked menu
    'items' => array(
        array('label' => t('Yii'), 'url' => 'http://www.yiiframework.com/doc/'),
        array('label' => t('Bootstrap'), 'url' => 'http://twitter.github.io/bootstrap/'),
        array('label' => t('Yii Bootstrap'), 'url' => 'http://www.cniska.net/yii-bootstrap'),
        array('label' => t('Font-Awesome'), 'url' => 'http://fortawesome.github.io/Font-Awesome/'),
    ),
));

