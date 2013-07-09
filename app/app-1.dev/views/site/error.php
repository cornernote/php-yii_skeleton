<?php
/**
 * @var $this SiteController
 */

$this->pageTitle = t('Error');
//$this->pageHeading = t('Error');
//$this->breadcrumbs = array(
//    t('Error'),
//);
?>

<?php
$this->beginWidget('bootstrap.widgets.TbHero', array(
    'heading' => t('Golly gosh!'),
));
?>

<p>
    <?php echo CHtml::encode($message); ?>
</p>

<p>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
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