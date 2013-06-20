<?php
$columns = array();
$columns[] = array(
    'name' => 'user_id',
    'value' => '$data->user?l($data->user->name,$data->user->url):null',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'page_trail_id',
    'value' => '$data->pageTrail?l("pageTrail-".$data->pageTrail->id,$data->pageTrail->getUrl()):null',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'action',
);
if (in_array($this->id, array('auditTrail', 'pageTrail'))) {
    $columns[] = array(
        'name' => 'model',
    );
    $columns[] = array(
        'name' => 'model_id',
        'header' => t('Id'),
    );
}
$columns[] = array(
    'name' => 'field',
);
$columns[] = array(
    'name' => 'old_value',
    'value' => '$data->oldValueString',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'new_value',
    'value' => '$data->newValueString',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'created',
    'value' => '$data->created',
    'type' => 'raw',
    'htmlOptions' => array('style' => 'width:150px'),
);

// grid
$this->widget('GridView', array(
    'id' => 'auditTrail-grid',
    'dataProvider' => $auditTrail->search(),
    'filter' => $auditTrail,
    'columns' => $columns,
));
?>