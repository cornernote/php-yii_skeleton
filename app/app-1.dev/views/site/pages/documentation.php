<?php
/**
 * @var $this WebController
 */
$this->pageTitle = $this->pageHeading = t('Documentation');

// menu
$this->menu = Menu::getItemsFromMenu('Help');

// breadcrumbs
$this->breadcrumbs = array(
    t('Help') => array('/site/page', 'view' => 'help'),
    t('Documentation'),
);

echo '<h2>' . t('Vendor Documentation') . '</h2>';
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills', // '', 'tabs', 'pills' (or 'list')
    'stacked' => true, // whether this is a stacked menu
    'items' => array(
        array('label' => t('Yii'), 'url' => 'http://www.yiiframework.com/doc/'),
        array('label' => t('Bootstrap'), 'url' => 'http://twitter.github.io/bootstrap/'),
        array('label' => t('Yii Bootstrap'), 'url' => 'http://www.cniska.net/yii-bootstrap'),
        array('label' => t('Kint'), 'url' => 'http://raveren.github.io/kint/'),
        array('label' => t('Swift Mailer'), 'url' => 'http://swiftmailer.org/docs/introduction.html'),
        array('label' => t('Highcharts'), 'url' => 'http://api.highcharts.com/highcharts'),
    ),
));

