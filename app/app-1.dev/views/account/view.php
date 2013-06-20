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
        array(
            'name' => 'id',
            'value' => 'user-' . $user->id,
            'type' => 'raw',
        ),
        'username',
        'first_name',
        'last_name',
    ),
)); ?>
</fieldset>

<fieldset>
    <legend><?php echo t('Contact Details') ?></legend>
    <?php $this->widget('DetailView', array(
    'data' => $user,
    'attributes' => array(
        'email',
        'phone',
        'fax',
    ),
)); ?>
</fieldset>
