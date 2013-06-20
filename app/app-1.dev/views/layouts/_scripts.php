<?php
if (!isset($this->showNavBar) || !$this->showNavBar) {
    cs()->registerCSS('reset', 'body{padding-top:20px;}', array('order' => -6));
}
cs()->registerCSSFile(au() . '/css/style.css', '', array('order' => 1));

// auto-fancybox for class="fancybox"
$this->widget('FancyBox', array(
    'target' => '.fancybox',
    'config' => array(
        'centerOnScroll' => true,
    ),
));

$this->renderPartial('/layouts/_theme_scripts');
?>