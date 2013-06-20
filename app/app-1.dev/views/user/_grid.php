<?php
$controller_params = Yii::app()->controller->getActionParams();
if (isset($controller_params['id'])) {
    $company_id = $controller_params['id'];
}
else {
    $company_id = null;
}

if ($company_id) {
    $company = Company::model()->findByPk((int)$company_id);
    $default_user_id = $company->default_user_id;
}
else {
    $default_user_id = null;
}

if (Yii::app()->controller->id == 'company') {
    $default_output = '."&nbsp;".l(i(bu().$data->getDefaultUserIcon(' . $default_user_id . '),"set as default user"),array("company/ChangeDefaultUser","user_id"=>$data->id,"company_id"=>' . $company_id . '))';
}
else {
    $default_output = '';
}

$columns = array();
$columns[] = array(
    'name' => 'id',
    'type' => 'raw',
    'value' => '$data->getLink("update")' . $default_output,
);
$columns[] = 'username';
$columns[] = 'first_name';
$columns[] = 'last_name';
$columns[] = 'email';
$columns[] = 'phone';

if (!$user->company_id) {
    $columns[] = array(
        'name' => 'company_name',
        'type' => 'raw',
        'value' => 'implode(", ",CHtml::listData($data->company,"id","name"))',
    );
}
$columns[] = array(
    'name' => 'role_id',
    'value' => 'implode(", ",CHtml::listData($data->role,"id","name"))',
    'filter' => CHtml::listData(Role::model()->findAll(), 'id', 'name'),
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'login_enabled',
    'value' => '$data->getLoginEnabledString()',
    'filter' => array('0' => 'disabled', '1' => 'enabled'),
    'type' => 'raw',
);
//$columns[] = array(
//    'header' => t('Viewed'),
//    'value' => '$data->last_viewed?date("Y-m-d H:i:s",$data->last_viewed):null',
//    'filter' => false,
//);

// grid
$this->widget('GridView', array(
    'id' => 'user-grid',
    'dataProvider' => $user->search(),
    'filter' => $user,
    'columns' => $columns,
));

?>
