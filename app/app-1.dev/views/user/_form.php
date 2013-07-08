<?php
/**
 * @var $this UserController
 * @var $user User
 * @var $role Role
 */
?>

<?php
/* @var $form ActiveForm */
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => true,
    'type' => 'horizontal',
    'htmlOptions' => array(
        'class' => app()->request->isAjaxRequest ? 'modal-form' : '',
    ),
));
echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
if (app()->request->isAjaxRequest) echo '<div class="modal-body">';
echo $form->errorSummary($user);

if ($user->isNewRecord) {
    if (user()->checkAccess('admin')) {
        if (in_array($role->id, array(Role::ROLE_CUSTOMER, Role::ROLE_KEY_HOLDER))) {
            //echo $form->textFieldRow($user, 'locksmith_id');
            echo '<div class="control-group ">';
            echo $form->labelEx($user, 'locksmith_id', array('class' => 'control-label'));
            echo '<div class="controls">';
            $this->widget('CAutoComplete', array(
                'id' => 'User_locksmith_id',
                'name' => 'User[locksmith_id]',
                'value' => $user->locksmith ? $user->locksmith->getName() : $user->locksmith_id,
                'url' => array('/user/autoCompleteLookup', 'role' => Role::ROLE_LOCKSMITH),
                'max' => 10,
                'minChars' => 2,
                'delay' => 500,
                'matchCase' => false,
                'htmlOptions' => array(
                    'class' => $user->hasErrors('locksmith_id') ? 'error' : '',
                ),
                'methodChain' => '.result(function(event,item){ $("#User_locksmith_id").val(item[1]); });',
            ));
            echo $form->error($user, 'locksmith_id');
            echo '</div></div>';
        }
    }
    if (user()->checkAccess('admin,locksmith')) {
        if (in_array($role->id, array(Role::ROLE_KEYHOLDER))) {
            //echo $form->textFieldRow($user, 'customer_id');
            echo '<div class="control-group ">';
            echo $form->labelEx($user, 'customer_id', array('class' => 'control-label'));
            echo '<div class="controls">';
            $this->widget('CAutoComplete', array(
                'id' => 'User_customer_id',
                'name' => 'User[customer_id]',
                'value' => $user->customer ? $user->customer->getName() : $user->customer_id,
                'url' => array('/user/autoCompleteLookup', 'role' => Role::ROLE_CUSTOMER),
                'max' => 10,
                'minChars' => 2,
                'delay' => 500,
                'matchCase' => false,
                'htmlOptions' => array(
                    'class' => $user->hasErrors('customer_id') ? 'error' : '',
                ),
                'methodChain' => '.result(function(event,item){ $("#User_customer_id").val(item[1]); });',
            ));
            echo $form->error($user, 'customer_id');
            echo '</div></div>';
        }
    }
}
//echo $form->textFieldRow($user, 'username');
echo $form->textFieldRow($user, 'name');
echo $form->textFieldRow($user, 'email');
echo $form->textFieldRow($user, 'phone');
?>


<?php if (app()->request->isAjaxRequest) echo '</div>'; ?>
<div class="<?php echo app()->request->isAjaxRequest ? 'modal-footer' : 'form-actions'; ?>">
    <?php
    $this->widget('bootstrap.widgets.BootButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => $user->isNewRecord ? t('Create') : t('Save'),
        'htmlOptions' => array('class' => 'pull-right'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>