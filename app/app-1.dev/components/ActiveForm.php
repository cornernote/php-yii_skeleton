<?php

Yii::import('bootstrap.widgets.BootActiveForm');
/**
 * Class ActiveForm
 */
class ActiveForm extends BootActiveForm
{
    /**
     * @var ActiveFormModel
     */
    public $model;

    /**
     * Initializes the widget.
     * This renders the form open tag.
     */
    public function init()
    {
        // get a model we can use for this form
        $this->model = new ActiveFormModel();
        parent::init();
    }

}