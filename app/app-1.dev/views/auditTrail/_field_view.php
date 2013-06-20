<?php
echo '<tr class="even">';

echo '<td>';
echo isset($data->old_value) ? $data->old_value : '&nbsp;';
echo isset($data->old_value) && isset($data->new_value) ? ' &gt; ' : '';
echo isset($data->new_value) ? $data->new_value : '&nbsp;';
echo '</td>';

echo '<td>';
echo ($data->user_id && is_numeric($data->user_id) ? User::model()->findByPk($data->user_id)->username : $data->user_id);
echo '</td>';

echo '<td>';
echo Time::agoIcon($data->created, Setting::item('app', 'dateTimeFormat'));
echo '</td>';

echo '<td>';
echo $data->pageTrail ? $data->pageTrail->getLink() : '';
echo '</td>';

echo '</tr>';
?>