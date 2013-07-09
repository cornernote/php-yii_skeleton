<?php
/**
 * @var $this WebController
 * @var $content
 */

echo '<div class="holder">';

echo '<div class="container-fluid">';

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

echo user()->multiFlash();
echo $content;
echo '</div>';

echo '<footer class="footer">';
echo '<div class="container-fluid">';
echo '<p class="pull-right">' . l(t('Back to Top') . ' &uarr;', '#') . '</p>';
echo '</div>';
echo '</footer>';

echo '</div>';
