<?php
/**
 * @var $this WebController
 * @var $content
 */
?>
<?php
$this->widget('Navbar', array(
    'id' => 'navbar',
    'fixed' => 'top',
    'fluid' => true,
    'collapse' => true,
    'items' => NavbarItems::mainMenu(),
    'constantItems' => array(
        NavbarItems::userMenu(),
    ),
));
?>
<div class="holder">
    <div id="body" class="container-fluid">
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
            $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                'htmlOptions' => array(
                    'id' => 'breadcrumbs',
                ),
                'separator' => '',
                'links' => $this->breadcrumbs,
            ));
        }
        echo user()->multiFlash();
        echo $content;
        ?>
    </div>
    <footer class="footer">
        <div class="container-fluid">
            <?php
            echo '<p class="pull-right">' . l(t('Back to Top') . ' &uarr;', '#') . '</p>';
            ?>
        </div>
    </footer>
</div>