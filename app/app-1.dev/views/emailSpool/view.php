<?php
/**
 * @var $this EmailSpoolController
 * @var $emailSpool EmailSpool
 */
$this->pageTitle = $this->pageHeading = $emailSpool->getName() . ' - ' . $this->getName() . ' ' . t('View');

$this->breadcrumbs = array();
$this->breadcrumbs[$this->getName() . ' ' . t('List')] = user()->getState('index.emailSpool', array('/emailSpool/index'));
$this->breadcrumbs[] = $emailSpool->getName();

$this->renderPartial('_menu', array(
    'emailSpool' => $emailSpool,
));

$attributes = array();
$attributes[] = array(
    'name' => 'id',
    'value' => ' emailSpool-' . $emailSpool->id,
);
$attributes[] = 'message_subject';
$attributes[] = 'to_email';
$attributes[] = 'to_name';
$attributes[] = 'status';
$attributes[] = 'model';
$attributes[] = 'model_id';
$attributes[] = 'from_email';
$attributes[] = 'from_name';
$modelName = explode('.', $emailSpool->model);
$modelName = $modelName ? current($modelName) : false;
if ($modelName != 'Error' && class_exists($modelName)) {
    $model = new $modelName;
    if (is_subclass_of($model, 'ActiveRecord')) {
        /** @var $model ActiveRecord */
        $model = ActiveRecord::model($modelName)->findByPk($emailSpool->model_id);
        if ($model) {
            $attributes[] = array(
                'label' => 'Model Link',
                'value' => $model->getLink(),
                'type' => 'raw',
            );
        }
    }
}
$attributes[] = array(
    'name' => 'message_html',
    'value' => $emailSpool->message_html,
    'type' => 'raw',
);
$attributes[] = array(
    'name' => 'message_text',
    'value' => format()->formatNtext($emailSpool->message_text),
    'type' => 'raw',
);
$attributes[] = 'sent';
$this->widget('DetailView', array(
    'data' => $emailSpool,
    'attributes' => $attributes,
));
