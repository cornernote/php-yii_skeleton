<?php
/**
 * @var $this EmailTemplateController
 * @var $emailTemplate EmailTemplate
 */
$this->pageTitle = t('Email Template') . ' ' . $emailTemplate->name . ' ' . t('Update');
$this->pageHeading = $emailTemplate->name . ' ' . t('Update');

$this->breadcrumbs = array();
$this->breadcrumbs[t('Email Templates')] = user()->getState('index.emailTemplate', array('/emailTemplate/index'));
$this->breadcrumbs[$emailTemplate->name] = $emailTemplate->getLink();
$this->breadcrumbs[] = t('Update');

$this->renderPartial('_menu', array(
    'emailTemplate' => $emailTemplate,
));
$this->renderPartial('_form', array(
    'emailTemplate' => $emailTemplate,
));
