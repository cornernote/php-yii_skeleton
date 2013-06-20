<?php
/* @var $pageTrail PageTrail */
$this->pageTitle = 'pageTrail-' . $pageTrail->id;
$this->pageHeading = 'pageTrail-' . $pageTrail->id;
$this->breadcrumbs = array(
    t('Page Trails') => user()->getState('search.pageTrail', array('index')),
    'pageTrail-' . $pageTrail->id,
);
$this->renderPartial('/site/_system_menu');
?>

<div>

    <fieldset>
        <legend><?php echo t('Page Details') ?></legend>
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


        $this->widget('DetailView', array(
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
        )); ?>
    </fieldset>

    <fieldset>
        <legend><?php echo t('Version Settings') ?></legend>
        <?php $this->widget('DetailView', array(
        'data' => $pageTrail,
        'attributes' => array(
            array(
                'name' => 'app_version',
            ),
            array(
                'name' => 'yii_version',
            ),
        ),
    )); ?>
    </fieldset>

    <fieldset>
        <legend><?php echo t('Page Variables') ?></legend>
        <?php
        $this->widget('DetailView', array(
            'data' => $pageTrail,
            'attributes' => array(
                array(
                    'name' => 'get',
                    'value' => '<pre>' . print_r($pageTrail->get, true) . '</pre>',
                    'type' => 'raw',
                ),
                array(
                    'name' => 'post',
                    'value' => '<pre>' . print_r($pageTrail->post, true) . '</pre>',
                    'type' => 'raw',
                ),
                array(
                    'name' => 'files',
                    'value' => '<pre>' . print_r($pageTrail->files, true) . '</pre>',
                    'type' => 'raw',
                ),
            ),
        )); ?>
    </fieldset>

    <fieldset>
        <legend><?php echo t('Session and Cookies') ?></legend>
        <a href='javascript:void(0)' onclick="$('#show_session_detail').show('slow');$('#show_session').hide();"
           id='show_session'>Show</a>

        <div id='show_session_detail' style="display: none;">
            <a href='javascript:void(0)'
               onclick="$('#show_session_detail').hide('hide');$('#show_session').show();">Hide</a>
            <?php
            $this->widget('DetailView', array(
                'data' => $pageTrail,
                'attributes' => array(
                    array(
                        'name' => 'session',
                        'value' => '<pre>' . print_r($pageTrail->session, true) . '</pre>',
                        'type' => 'raw',
                    ),
                    array(
                        'name' => 'cookie',
                        'value' => '<pre>' . print_r($pageTrail->cookie, true) . '</pre>',
                        'type' => 'raw',
                    ),
                ),
            )); ?>
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
            $this->widget('DetailView', array(
                'data' => $pageTrail,
                'attributes' => array(
                    array(
                        'name' => 'server',
                        'value' => '<pre>' . print_r($pageTrail->server, true) . '</pre>',
                        'type' => 'raw',
                    ),
                ),
            )); ?>
        </div>
    </fieldset>

</div>
