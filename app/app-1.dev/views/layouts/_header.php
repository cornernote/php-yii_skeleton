<?php
/**
 * @var $this WebController
 */
?>

<div class="container-fluid">
    <?php
    if ($this->pageHeading) {
        echo '<h1 class="header">' . $this->pageHeading . '</h1>';
    }
    if ($this->menu) {
        $this->widget('bootstrap.widgets.TBMenu', array(
            'id' => 'menu',
            'type' => 'tabs',
            'items' => $this->menu,
        ));
    }
    if ($this->breadcrumbs) {
        $this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' => $this->breadcrumbs));
    }
    ?>
</div>