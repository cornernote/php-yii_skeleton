<?php
/* @var $this AccountController */
?>
<?php
$this->pageTitle = $this->pageHeading = t('My Account');
$this->breadcrumbs = array(
    t('My Account'),
);
$this->menu = NavbarItems::userMenuItems();
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
