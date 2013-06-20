<?php
$criteria = new CDbCriteria();
$criteria->condition = 'model=:model AND foreign_key=:foreign_key';
$criteria->params = array(
    ':model' => $model,
    ':foreign_key' => $foreign_key,
);
$dataProvider = new CActiveDataProvider('Log', array(
    'criteria' => $criteria,
    'sort' => array(
        'defaultOrder' => 'created DESC, id DESC',
    ),
));

echo '<div class="grid-view">';
$this->widget('ListView', array(
    'id' => "log-list-$model-$foreign_key",
    'dataProvider' => $dataProvider,
    'itemView' => '/log/_history_view',
    'itemsTagName' => 'table',
    'itemsCssClass' => 'items',
));
echo '</div>';
?>