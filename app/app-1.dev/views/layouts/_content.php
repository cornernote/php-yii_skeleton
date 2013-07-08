<?php
/**
 * @var $this WebController
 * @var $content
 */
?>
<div class="container-fluid">
    <?php
    echo user()->multiFlash();
    echo $content;
    ?>
</div>