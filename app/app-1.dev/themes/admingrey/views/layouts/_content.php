<?php
echo '<div id="header">';
if ($this->pageHeading) {
    echo '<h1>' . $this->pageHeading . '</h1>';
}
echo '</div>';
if (!Yii::app()->request->isAjaxRequest) {
    echo '<div id="content">';
    if ($this->menu) {
        $this->widget('bootstrap.widgets.BootMenu', array(
            'type' => 'tabs',
            'items' => $this->menu,
            'htmlOptions' => array(
                'id' => 'menu',
            ),
        ));
    }
    echo '<div id="inner">';
    if ($this->breadcrumbs) {
        $this->widget('Breadcrumbs', array('links' => $this->breadcrumbs, 'separator' => ' '));
    }
}
echo user()->multiFlash();
echo $content;

if (!Yii::app()->request->isAjaxRequest) {
    echo '</div></div>';

    echo '<footer class="footer">';
    echo '<p class="pull-right"><a href="#">' . t('Back to Top') . ' &uarr;</a></p>';
    echo '</footer>';
}
?>