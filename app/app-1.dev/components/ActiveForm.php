<?php

Yii::import('bootstrap.widgets.TbActiveForm');
/**
 * Class ActiveForm
 */
class ActiveForm extends TbActiveForm
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