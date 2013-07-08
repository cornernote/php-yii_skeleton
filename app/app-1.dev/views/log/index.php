<?php
/**
 * @var $this LogController
 * @var $log Log
 */
user()->setState('index.log', ru());
$this->pageTitle = t('Logs');
$this->pageHeading = t('Logs');
$this->breadcrumbs = array(
    t('Logs'),
);
$this->renderPartial('/site/_system_menu');

echo '<div class="spacer">';
$this->widget('bootstrap.widgets.BootButton', array(
    'label' => t('Create'),
    'url' => array('/log/create'),
    'type' => 'primary',
));
//echo ' ';
//$this->widget('bootstrap.widgets.BootButton', array(
//    'label' => t('Search'),
//    'htmlOptions' => array('class' => 'search-button'),
//    'toggle' => true,
//));
if (user()->getState('index.log') != url('/log/index')) {
    echo ' ';
    $this->widget('bootstrap.widgets.BootButton', array(
        'label' => t('Reset Filters'),
        'url' => array('/log/index'),
    ));
}
echo '</div>';

// search
//$this->renderPartial('/log/_search', array(
//    'log' => $log,
//));

// columns
$columns = array();

$columns[] = array(
    'name' => 'id',
    'type' => 'raw',
    'value' => 'l(h("pageTrail-".$data->id),$data->url)',
    'htmlOptions' => array('style' => 'width:110px'),
);
$columns[] = array(
    'name' => 'message',
);
$columns[] = array(
    'name' => 'details',
    'value' => 'print_r(unserialize($data->details),true)',
);
$columns[] = array(
    'name' => 'model',
    'header' => t('model'),
    'value' => '$data->model',
    'htmlOptions' => array('style' => 'width:70px'),
);
$columns[] = array(
    'name' => 'model_id',
    'header' => t('Model ID'),
    'value' => '$data->model_id',
    'htmlOptions' => array('style' => 'width:70px'),
);
$columns[] = array(
    'name' => 'user_id',
    'type' => 'raw',
    'value' => '$data->user?l(h($data->user->name),$data->user->url):null',
    'htmlOptions' => array('style' => 'width:105px'),
);
$columns[] = array(
    'name' => 'created',
    'value' => '$data->created',
    'type' => 'raw',
    'htmlOptions' => array('style' => 'width:150px'),
);
$columns[] = array(
    'name' => 'ip',
    'htmlOptions' => array('style' => 'width:100px'),
);

// grid
$this->widget('GridView', array(
    'id' => 'log-grid',
    'dataProvider' => $log->search(),
    'filter' => $log,
    'columns' => $columns,
));
?>