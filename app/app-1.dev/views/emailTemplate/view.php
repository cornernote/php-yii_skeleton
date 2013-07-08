<?php
/**
 * @var $this EmailTemplateController
 * @var $emailTemplate EmailTemplate
 */
user()->setState('index.emailTemplate.' . $emailTemplate->id, ru());
$this->pageTitle = t('Email Template') . ' ' . $emailTemplate->name;
$this->pageHeading = $emailTemplate->name;

$this->breadcrumbs = array();
$this->breadcrumbs[t('Email Templates')] = user()->getState('index.emailTemplate', array('/emailTemplate/index'));
$this->breadcrumbs[] = $emailTemplate->name;

$this->renderPartial('_menu', array(
    'emailTemplate' => $emailTemplate,
));

$attributes = array();
$attributes[] = 'name';
$attributes[] = 'description';
$attributes[] = 'message_subject';
$attributes[] = array('name' => 'message_html', 'type' => 'raw');
$attributes[] = 'message_text';
$attributes[] = 'created';
$this->widget('DetailView', array(
    'data' => $emailTemplate,
    'attributes' => $attributes,
));
