<?php
/* @var $this AccountController */
?>
<?php
$this->pageTitle = t('My Account');
$this->pageHeading = t('My Account');
$this->breadcrumbs = array(
    t('My Account'),
);
$this->renderPartial('_menu', array('user' => $user));
?>

<fieldset>
    <legend><?php echo t('User Details') ?></legend>
    <?php $this->widget('DetailView', array(
    'data' => $user,
    'attributes' => array(
        'username',
        'name',
        'email',
        'phone',
    ),
)); ?>
</fieldset>
