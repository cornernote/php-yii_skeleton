<?php
$this->pageTitle = t('Error');
//$this->pageHeading = t('Error');
//$this->breadcrumbs = array(
//    t('Error'),
//);
?>

<?php
$this->beginWidget('bootstrap.widgets.BootHero', array(
    'heading' => t('Holy shit!'),
));
?>

<p>
    <?php echo CHtml::encode($message); ?>
</p>

<p>
    <?php
    $this->widget('bootstrap.widgets.BootButton', array(
        'type' => 'primary',
        'size' => 'large',
        'label' => t('OK, whatever! Just take me back home.'),
        'url' => app()->homeUrl,
    ));
    ?>
</p>

<?php
$this->endWidget();
?>