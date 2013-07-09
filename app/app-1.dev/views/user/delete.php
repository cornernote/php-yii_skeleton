<?php
/**
 * @var $this UserController
 * @var $id int
 * @var $form ActiveForm
 */
$this->pageTitle = t('Delete Users');
$this->pageHeading = t('Delete');
?>

<?php
$user = $id ? User::model()->findByPk($id) : new User('search');
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'user-delete-form',
    'type' => 'horizontal',
    'action' => array('/user/delete', 'id' => $id),
    'htmlOptions' => array(
        'class' => app()->request->isAjaxRequest ? 'modal-form' : '',
    ),
));
echo sfGridHidden($id);
echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
echo CHtml::hiddenField('confirm', 1);
if (app()->request->isAjaxRequest) echo '<div class="modal-body">';
?>

<fieldset>
    <legend><?php echo t('Selected Users'); ?></legend>
    <?php
    $users = User::model()->findAll('t.id IN (' . implode(',', sfGrid($id)) . ')');
    if ($users) {
        echo '<ul class="bullet">';
        foreach ($users as $user) {
            echo '<li>';
            echo $user->name;
            echo '</li>';
        }
        echo '</ul>';
    }
    ?>
</fieldset>

<?php if (app()->request->isAjaxRequest) echo '</div>'; ?>
<div class="<?php echo app()->request->isAjaxRequest ? 'modal-footer' : 'form-actions'; ?>">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => t('Confirm Delete'))); ?>
</div>
<?php $this->endWidget(); ?>