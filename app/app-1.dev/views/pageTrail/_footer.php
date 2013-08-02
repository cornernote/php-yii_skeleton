<?php
$pageTrail = PageTrail::model()->findCurrent();
if (!$pageTrail) return;

echo '<div' . (YII_DEBUG ? '' : ' style="color: #fff;"') . '>';
echo '<!-- pt start -->pt-' . $pageTrail->id . '<!-- pt end -->'; // html comment is used for extracting page_trail_id
echo ' | ';
echo Setting::item('core', 'app_version');
echo ' | ';
echo number_format(microtime(true) - $pageTrail->start_time, 2) . 'sec';
echo ' | ';
echo round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb';
echo '</div>';
