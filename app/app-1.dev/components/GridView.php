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
    public $template = "{summary}{pager}{pageSelect}{items}{multiActions}";

    /**
     * @var string
     */
    public $type = 'striped condensed bordered';

    /**
     * @var array
     */
    public $multiActions = array();

    /**
     * @var int
     */
    public $selectableRows = 1000;

    /**
     *
     */
    public function init()
    {
        // pager labels
        if (!isset($this->pager['firstPageLabel']))
            $this->pager['firstPageLabel'] = '<i class="icon-fast-backward"></i>';
        if (!isset($this->pager['lastPageLabel']))
            $this->pager['lastPageLabel'] = '<i class="icon-fast-forward"></i>';
        if (!isset($this->pager['nextPageLabel']))
            $this->pager['nextPageLabel'] = '<i class="icon-forward"></i>';
        if (!isset($this->pager['prevPageLabel']))
            $this->pager['prevPageLabel'] = '<i class="icon-backward"></i>';
        if (!isset($this->pager['maxButtonCount']))
            $this->pager['maxButtonCount'] = 5;
        if (!isset($this->pager['displayFirstAndLast']))
            $this->pager['displayFirstAndLast'] = true;


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

        if ($this->multiActions) {
            Yii::app()->clientScript->registerScriptFile(au() . '/js/jquery.form.js');
            // put the url from the button into the form action
            // handle submit form to capture the response into a modal
            Yii::app()->controller->beginWidget('JavaScriptWidget', array('position' => CClientScript::POS_END));
            ?>
            <script type="text/javascript">
                var modalRemote = $('#modal-remote');
                $('#<?php echo $this->id; ?>-form').on('change', '.multi-actions', function () {
                    var checked = false;
                    var action = $('#<?php echo $this->id; ?>-form').attr('action');
                    var url = $(this).val();
                    $(this).val('');
                    if (url) {
                        $('.select-on-check').each(function () {
                            if ($(this).attr('checked'))
                                checked = true;
                        });
                        if (checked)
                            $('#<?php echo $this->id; ?>-form').attr('action', url).submit();
                        else
                            alert('No rows selected');
                    }
                });
                $('#<?php echo $this->id; ?>-form').ajaxForm({
                    beforeSubmit:function (response) {
                        if (!modalRemote.length) modalRemote = $('<div class="modal hide fade" id="modal-remote"></div>');
                        modalRemote.modalResponsiveFix();
                        modalRemote.touchScroll();
                        modalRemote.html('<div class="modal-header"><h3><?php echo t('Loading...'); ?></h3></div><div class="modal-body"><div class="modal-remote-indicator"></div>').modal();
                    },
                    success:function (response) {
                        modalRemote.html(response);
                        $(window).resize();
                        $('#modal-remote input:text:visible:first').focus();
                    },
                    error:function (response) {
                        modalRemote.children('.modal-header').html('<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button><h3><?php echo t('Error!'); ?></h3>');
                        modalRemote.children('.modal-body').html(response);
                    }
                });
            </script>
            <?php
            Yii::app()->controller->endWidget();
        }
    }

    /**
     *
     */
    public function run()
    {
        if ($this->multiActions) {
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

        if ($this->multiActions) {
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
            10 => '10 ' . $label,
            100 => '100 ' . $label,
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
        if ($this->dataProvider->getItemCount() > 0 && $this->multiActions) {
            echo '<div class="form-multi-actions">';
//            foreach ($this->multiActions as $multiAction) {
//                echo '<button class="btn multiAction" value="' . $multiAction['url'] . '">' . $multiAction['name'] . '</button> ';
//            }
            echo CHtml::dropDownList("multiAction[{$this->id}]", '', CHtml::listData($this->multiActions, 'url', 'name'), array(
                'empty' => t('with selected...'),
                'class' => 'multi-actions',
            ));
            echo '</div>';
        }
    }

}