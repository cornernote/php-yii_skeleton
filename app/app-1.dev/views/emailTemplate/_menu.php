<?php
/**
 * @var $this EmailTemplateController
 * @var $emailTemplate EmailTemplate
 */

if ($emailTemplate->isNewRecord) {
    $this->menu[] = array(
        'label' => t('Create'),
        'url' => array('/emailTemplate/create'),
    );
    return; // no more links
}

$this->menu[] = array(
    'label' => t('View'),
    'url' => $emailTemplate->getUrl(),
    'icon' => 'icon-file',
);

$this->menu[] = array(
    'label' => t('Update'),
    'url' => array('/emailTemplate/update', 'id' => $emailTemplate->id),
    'linkOptions' => array('data-toggle' => 'modal-remote'),
    'active' => $this->id == 'emailTemplate' && $this->action->id == 'update',
    'icon' => 'icon-pencil',
);
