<?php
$pageTrail = PageTrail::model()->findCurrent();
?>
<div<?php echo YII_DEBUG ? '' : ' style="color: #fff;"'; ?>>
    <?php
    //html comment needed for extracting page_trail_id
    echo '<!-- pt start -->pt-' . $pageTrail->id;
    echo '<!-- pt end --> | ';
    //echo $settings['version']['yii'];
    //echo ' | '; 
    echo Setting::item('core', 'app_version');
    echo ' | ';
    echo number_format(microtime(true) - $pageTrail->start_time, 2) . 'sec';
    echo ' | ';
    //echo round(memory_get_usage() / 1024 / 1024, 2) . 'memuse';
    //echo ' | ';
    echo round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb';
    ?>
</div>