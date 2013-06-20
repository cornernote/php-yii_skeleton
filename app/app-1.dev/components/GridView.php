<?php

Yii::import('bootstrap.widgets.BootGridView');
/**
 *
 */
class GridView extends BootGridView
{

    /**
     * @var string
     */
    public $template = "{summary}{pager}{pageSelect}{items}{multiActions}{submitButton}";

    /**
     * @var string
     */
    public $type = 'striped condensed bordered';

    /**
     * @var array
     */
    public $multiActions = array();

    /**
     * @var string
     */
    public $submitButton;

    /**
     * @var int
     */
    public $selectableRows = 1000;

    /**
     *
     */
    public function init()
    {
        // userPageSize drop down changed
        if (isset($_GET['userPageSize'])) {
            foreach ($_GET['userPageSize'] as $type => $size) {
                user()->setState('userPageSize.' . $type, (int)$size);
            }
            unset($_GET['userPageSize']);
        }

        // set data provider pageSize
        $key = 'userPageSize.' . str_replace('-', '_', $this->id);
        $size = user()->getState($key, param('defaultPageSize'));
        if (!$size) {
            $size = param('defaultPageSize');
        }
        $pagination = new CPagination();
        $pagination->pageSize = $size;
        $this->dataProvider->setPagination($pagination);

        // add checkbox when we have multiactions
        if ($this->multiActions) {
            $this->columns = CMap::mergeArray(array(array(
                'class' => 'CCheckBoxColumn',
            )), $this->columns);
        }

        parent::init();
    }

    /**
     *
     */
    public function registerClientScript()
    {
        parent::registerClientScript();

        if ($this->multiActions || $this->submitButton) {
            Yii::app()->clientScript->registerScriptFile(au() . '/js/jquery.form.js');
            // put the url from the button into the form action
            // capture the response into fancybox
            Yii::app()->clientScript->registerScript("{$this->id}-multiForm", "
                $('#{$this->id}-form .multiAction').click(function(){
                    var checked = false;
                    $('.select-on-check').each(function(){
                        if ($(this).attr('checked')) {
                            checked = true;
                        }
                    });
                    if (checked) {
                        $('#{$this->id}-form').attr('action',$(this).attr('value'));
                    }
                    else {
                        alert('No rows selected');
                        return false;
                    }
                });
                $('#{$this->id}-form').ajaxForm({
                    beforeSubmit: function(response){
                        $.fancybox.showActivity();
                    },
                    success: function(response){
                        $.fancybox({'content':response});
                    },
                    error: function(response){
                        $.fancybox({'content':'<h1>Error</h1>  The server did not provide a valid response'});
                    }
                });
            ", CClientScript::POS_READY);
        }
    }

    /**
     *
     */
    public function run()
    {
        if ($this->multiActions || $this->submitButton) {
            echo CHtml::openTag('div', array(
                'id' => $this->id . '-multi-checkbox',
                'class' => 'multi-checkbox-table',
            )) . "\n";
            echo CHtml::beginForm(ru(), 'POST', array(
                'id' => $this->id . '-form',
            ));
            echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue(true));
        }

        parent::run();

        if ($this->multiActions || $this->submitButton) {
            echo CHtml::endForm();
            echo CHtml::closeTag('div');
        }
    }

    /**
     *
     */
    public function renderToggleFilters()
    {
        $js = "jQuery(document).on('click','.toggle-filters',function(){ jQuery(this).closest('.grid-view').find('.filters').toggle(); });";
        cs()->registerScript(__CLASS__ . '_toggle-filters', $js);
        echo '<i class="icon-search toggle-filters" title="' . t('Show Filters') . '"></i>';
    }

    /**
     *
     */
    public function renderPageSelect()
    {
        $size = user()->getState('userPageSize.' . str_replace('-', '_', $this->id), param('defaultPageSize'));
        $label = t('per page');
        $options = array(
            5 => '5 ' . $label,
            10 => '10 ' . $label,
            20 => '20 ' . $label,
            50 => '50 ' . $label,
            100 => '100 ' . $label,
            200 => '200 ' . $label,
            500 => '500 ' . $label,
            1000 => '1000 ' . $label,
        );
        echo CHtml::dropDownList("userPageSize[{$this->id}]", $size, $options, array(
            'onchange' => "$.fn.yiiGridView.update('{$this->id}',{data:{userPageSize:{" . str_replace('-', '_', $this->id) . ":$(this).val()}}})",
            'class' => 'page-size',
        ));
    }

    /**
     *
     */
    public function renderMultiActions()
    {
        if ($this->multiActions) {
            echo '<div class="form-multi-actions">';
            foreach ($this->multiActions as $multiAction) {
                echo '<button class="btn multiAction" value="' . $multiAction['url'] . '">' . $multiAction['name'] . '</button> ';
            }
            echo '</div>';
        }
    }

    /**
     *
     */
    public function renderSubmitButton()
    {
        if ($this->submitButton) {
            echo '<div class="form-actions">';
            echo $this->submitButton;
            echo '</div>';
        }
    }

}