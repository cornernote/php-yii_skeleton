<?php
/**
 * @var $this WebController
 * @var $content
 */
?>
<div class="container-fluid">
    <?php
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
    echo user()->multiFlash();
    echo $content;
    ?>
</div>