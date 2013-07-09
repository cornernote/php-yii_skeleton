<?php
/**
 * @var $this WebController
 */
?>
<div class="container-fluid">
    <?php
    if (!app()->request->isAjaxRequest) {
        echo '<div id="content">';
    }
    if ($this->menu) {
        $this->widget('bootstrap.widgets.TBMenu', array(
            'id' => 'menu',
            'type' => 'tabs',
            'items' => $this->menu,
        ));
    }
    if (!app()->request->isAjaxRequest) {
        echo '<div id="inner">';
    }
    if ($this->breadcrumbs) {
        $this->widget('Breadcrumbs', array('links' => $this->breadcrumbs, 'separator' => ' '));
    }
    echo user()->multiFlash();
    echo $content;
    if (!app()->request->isAjaxRequest) {
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>