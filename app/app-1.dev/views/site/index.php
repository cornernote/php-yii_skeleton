<?php
$this->pageTitle = app()->name;
//$this->pageHeading = app()->name;
?>

<?php
$this->beginWidget('bootstrap.widgets.BootHero', array(
    'heading' => t('Welcome to') . ' ' . app()->name,
));
?>
<br/>
<p>You may change the content of this page by modifying the following file:</p>
<p><tt><?php echo __FILE__; ?></tt></p>

<?php
//$this->widget('bootstrap.widgets.BootButton', array(
//    'type' => 'primary',
//    'size' => 'large',
//    'label' => 'Learn more',
//));
?>

<?php
$this->endWidget();
?>


<!--
<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <tt><?php echo __FILE__; ?></tt></li>
	<li>Layout file: <tt><?php echo $this->getLayoutFile('main'); ?></tt></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>

-->
<?php

?>
