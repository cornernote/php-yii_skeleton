<?php
debug(Yii::app()->request->isAjaxRequest);
if (!Yii::app()->request->isAjaxRequest) {
    if ($this->breadcrumbs) {
        $this->widget('bootstrap.widgets.BootBreadcrumbs', array('links' => $this->breadcrumbs));
    }
    if ($this->pageHeading) {
        echo '<h1>' . $this->pageHeading . '</h1>';
    }
    if ($this->menu) {
        $this->widget('bootstrap.widgets.BootMenu', array(
            'type' => 'tabs',
            'items' => $this->menu,
            'htmlOptions' => array(
                'id' => 'menu',
            ),
        ));
    }
}
echo user()->multiFlash();
echo $content;
if (!Yii::app()->request->isAjaxRequest) {
    echo '<footer class="footer">';
    echo '<p class="pull-right"><a href="#">' . t('Back to Top') . ' &uarr;</a></p>';
    echo '</footer>';
}
?>