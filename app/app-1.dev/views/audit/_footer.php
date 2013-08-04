<?php
$audit = Audit::model()->findCurrent();
if (!$audit) return;

echo '<div' . (YII_DEBUG ? '' : ' style="color: #fff;"') . '>';
echo '<!-- pt start -->pt-' . $audit->id . '<!-- pt end -->'; // html comment is used for extracting audit_id
echo ' | ';
echo Setting::item('app_version');
echo ' | ';
echo number_format(microtime(true) - $audit->start_time, 2) . 'sec';
echo ' | ';
echo round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb';
echo '</div>';
