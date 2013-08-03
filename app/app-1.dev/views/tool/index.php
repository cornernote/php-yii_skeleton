<?php
/**
 * @var $this ToolController
 */

$this->pageTitle = t('Tools');
$this->pageHeading = t('Tools');
$this->breadcrumbs = array(
    t('Tools'),
);

// menu
$this->menu = Menu::getItemsFromMenu('System');


$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills', // '', 'tabs', 'pills' (or 'list')
    'stacked' => true, // whether this is a stacked menu
    'items' => array(
        array('label' => t('Documentation'), 'url' => array('/tool/page', 'view' => 'documentation')),
        array('label' => t('Generate Model PHPDocs'), 'url' => array('/tool/generateProperties')),
        array('label' => t('Generate Model Rules'), 'url' => array('/tool/generateRules')),
    ),
));