<?php
/**
 * @var $this UserController
 * @var $user User
 */

$columns = array();
if (user()->checkAccess('admin')) {
    $columns[] = array(
        'name' => 'id',
        'htmlOptions' => array('width' => '80'),
    );
}

$columns[] = array(
    'name' => 'name',
    'value' => '$data->getLink(array("update","delete"))',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'email',
    'value' => '$data->email ? l($data->email, "mailto:" . $data->email) : null',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'phone',
    'filter' => false,
);

if (user()->checkAccess('admin')) {
    $columns[] = array(
        'name' => 'locksmith_id',
        'value' => '$data->locksmith?$data->locksmith->getLink():null',
        'type' => 'raw',
        'filter' => false,
    );
}
if (user()->checkAccess('admin,locksmith')) {
    $columns[] = array(
        'name' => 'customer_id',
        'value' => '$data->customer?$data->customer->getLink():null',
        'type' => 'raw',
        'filter' => false,
    );
}
if (!$user->role_id) {
    $columns[] = array(
        'name' => 'role_id',
        'value' => 'implode(", ",CHtml::listData($data->role,"id","name"))',
        'filter' => CHtml::listData(Role::model()->findAll(), 'id', 'name'),
        'type' => 'raw',
    );
}


// multi actions
$multiActions = array();
$multiActions[] = array(
    'name' => t('Delete'),
    'url' => url('/user/delete'),
);

// grid
$this->widget('GridView', array(
    'id' => 'user-grid',
    'dataProvider' => $user->search(),
    'filter' => $user,
    'columns' => $columns,
    'multiActions' => $multiActions,
));

