<?php
/**
 * @var $this EmailTemplateController
 * @var $emailTemplate EmailTemplate
 */
user()->setState('index.emailTemplate', ru());
$this->pageTitle = t('Email Templates');
$this->pageHeading = t('Email Templates');
$this->breadcrumbs = array(t('Email Templates'));

echo '<div class="spacer">';
if (user()->getState('index.emailTemplate') != url('/emailTemplate/index')) {
    //echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Reset Filters'),
        'url' => array('/emailTemplate/index'),
    ));
}
echo '</div>';

// grid
$this->renderPartial('/emailTemplate/_grid', array(
    'emailTemplate' => $emailTemplate,
));
