<?php
/**
 * @var $this PageTrailController
 * @var $pageTrail PageTrail
 */
$this->pageTitle = $this->pageHeading = $pageTrail->getName() . ' - ' . $this->getName() . ' ' . t('View');

$this->breadcrumbs = array();
$this->breadcrumbs[$this->getName() . ' ' . t('List')] = user()->getState('index.pageTrail', array('/pageTrail/index'));
$this->breadcrumbs[] = $pageTrail->getName();

$this->renderPartial('_menu', array(
    'pageTrail' => $pageTrail,
));

?>

<div>

    <fieldset>
        <legend><?php echo $this->getName() . ' ' . t('Details') ?></legend>
        <?php

        $attributes = array();
        $attributes[] = array(
            'name' => 'id',
            'value' => ' pageTrail-' . $pageTrail->id,
        );
        $attributes[] = array(
            'name' => 'link',
            'value' => CHtml::link(urldecode($pageTrail->link), urldecode($pageTrail->link)),
            'type' => 'raw',
        );
        $attributes[] = array(
            'name' => 'referrer',
            'value' => CHtml::link(urldecode($pageTrail->referrer), urldecode($pageTrail->referrer)),
            'type' => 'raw',
        );
        $attributes[] = array(
            'name' => 'redirect',
            'value' => CHtml::link(urldecode($pageTrail->redirect), urldecode($pageTrail->redirect)),
            'type' => 'raw',
        );
        $attributes[] = array(
            'name' => 'created',
            'value' => $pageTrail->created,
            'type' => 'raw',
        );
        $attributes[] = array(
            'name' => 'total_time',
        );
        $attributes[] = array(
            'name' => 'memory_usage',
            'value' => number_format($pageTrail->memory_usage, 0),
        );
        $attributes[] = array(
            'name' => 'memory_peak',
            'value' => number_format($pageTrail->memory_peak, 0),
        );
        $attributes[] = array(
            'name' => 'ip',
        );
        $attributes[] = array(
            'name' => 'user_id',
            'label' => 'user',
            'type' => 'raw',
            'value' => $pageTrail->user ? ('user-' . $pageTrail->user->id . '  ' . l(h($pageTrail->user->name), $pageTrail->user->url)) : null,
        );
        $attributes[] = array(
            'name' => 'preserve',
            'value' => $pageTrail->preserve ? t('This PageTrail is Preserved.') . ' - ' . l('Remove Preserve', array('/pageTrail/preserve', 'id' => $pageTrail->id, 'status' => 0))
                : l('Preserve Values', array('/pageTrail/preserve', 'id' => $pageTrail->id, 'status' => 1)),
            'type' => 'raw',
        );


        $this->widget('widgets.DetailView', array(
            'data' => $pageTrail,
            'attributes' => $attributes,
        ));
        ?>
    </fieldset>

    <fieldset>
        <legend><?php echo t('Audit Trail') ?></legend>
        <?php
        $auditTrail = new AuditTrail('search');
        if (isset($_GET['AuditTrail'])) {
            $auditTrail->attributes = $_GET['AuditTrail'];
        }
        $auditTrail->page_trail_id = $pageTrail->id;
        $this->renderPartial('/auditTrail/_grid', array(
            'auditTrail' => $auditTrail,
        ));
        ?>
    </fieldset>

    <fieldset>
        <legend><?php echo t('Version Settings') ?></legend>
        <?php
        $this->widget('widgets.DetailView', array(
            'data' => $pageTrail,
            'attributes' => array(
                array(
                    'name' => 'app_version',
                ),
                array(
                    'name' => 'yii_version',
                ),
            ),
        ));
        ?>
    </fieldset>

    <fieldset>
        <legend><?php echo t('Page Variables') ?></legend>
        <?php
        $this->widget('widgets.DetailView', array(
            'data' => $pageTrail,
            'attributes' => array(
                array(
                    'label' => '$_GET',
                    'value' => '<pre>' . print_r($pageTrail->unpack('get'), true) . '</pre>',
                    'type' => 'raw',
                ),
                array(
                    'label' => '$_POST',
                    'value' => '<pre>' . print_r($pageTrail->unpack('post'), true) . '</pre>',
                    'type' => 'raw',
                ),
                array(
                    'label' => '$_FILES',
                    'value' => '<pre>' . print_r($pageTrail->unpack('files'), true) . '</pre>',
                    'type' => 'raw',
                ),
            ),
        ));
        ?>
    </fieldset>

    <fieldset>
        <legend><?php echo t('Session and Cookies') ?></legend>
        <a href='javascript:void(0)' onclick="$('#show_session_detail').show('slow');$('#show_session').hide();"
           id='show_session'>Show</a>

        <div id='show_session_detail' style="display: none;">
            <a href='javascript:void(0)'
               onclick="$('#show_session_detail').hide('hide');$('#show_session').show();">Hide</a>
            <?php
            $this->widget('widgets.DetailView', array(
                'data' => $pageTrail,
                'attributes' => array(
                    array(
                        'label' => '$_SESSION',
                        'value' => '<pre>' . print_r($pageTrail->unpack('session'), true) . '</pre>',
                        'type' => 'raw',
                    ),
                    array(
                        'label' => '$_COOKIE',
                        'value' => '<pre>' . print_r($pageTrail->unpack('cookie'), true) . '</pre>',
                        'type' => 'raw',
                    ),
                ),
            ));
            ?>
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo t('Server Data') ?></legend>
        <a href='javascript:void(0)' onclick="$('#show_server_detail').show('slow');$('#show_server').hide();"
           id='show_server'>Show</a>

        <div id='show_server_detail' style="display: none;">
            <a href='javascript:void(0)'
               onclick="$('#show_server_detail').hide('hide');$('#show_server').show();">Hide</a>
            <?php
            $this->widget('widgets.DetailView', array(
                'data' => $pageTrail,
                'attributes' => array(
                    array(
                        'label' => '$_SERVER',
                        'value' => '<pre>' . print_r($pageTrail->unpack('server'), true) . '</pre>',
                        'type' => 'raw',
                    ),
                ),
            )); ?>
        </div>
    </fieldset>

</div>
