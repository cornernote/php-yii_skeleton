<?php
/**
 * @var $user User
 * @var $form ActiveForm
 */
?>

<?php
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => true,
));
$this->widget('AskToSaveWork', array('watchElement' => '#user-form :input', 'message' => t('Please save before leaving the page')));

echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
echo $form->errorSummary($user);
?>

<div class="row-fluid">
    <div class="span4">

        <fieldset>
            <legend><?php echo t('User Details') ?></legend>

            <?php
            echo $form->textFieldRow($user, 'first_name');
            echo $form->textFieldRow($user, 'last_name');
            echo $form->textFieldRow($user, 'initials', array('size' => 3));
            ?>

        </fieldset>

        <?php if ($user->isNewRecord) { ?>
        <fieldset>
            <legend><?php echo t('Company Details') ?></legend>

            <div class="control-group">
                <?php echo $form->labelEx($user, 'company_id', array('class' => 'control-label')); ?>
                <div class="controls">
                    <?php
                    $this->widget('CAutoComplete', array(
                        'name' => 'company_name',
                        'value' => $user->company_id ? Company::model()->findByPk($user->company_id)->name : '',
                        'model' => $user,
                        'url' => array('/company/autoCompleteLookup'),
                        'max' => 10,
                        'minChars' => 2,
                        'delay' => 500,
                        'matchCase' => false,
                        'htmlOptions' => array(
                            'onChange' => '
                            $("#company_name").val("");
                            $("#User_company_id").val("");
                        ',
                        ),
                        'methodChain' => '
                        .result(function(event,item){
                            $("#User_company_id").val(item[1]);
                        })
                    ',
                    ));
                    echo $form->hiddenField($user, 'company_id');
                    echo $form->error($user, 'company_id');
                    ?>
                </div>
            </div>

        </fieldset>
        <?php } ?>

    </div>
    <div class="span4">

        <fieldset>
            <legend><?php echo t('Contact Details') ?></legend>

            <?php
            echo $form->textFieldRow($user, 'email');
            echo $form->textFieldRow($user, 'phone');
            echo $form->textFieldRow($user, 'fax');
            ?>

        </fieldset>

        <fieldset>
            <legend><?php echo t('Login Details') ?></legend>

            <?php
            echo $form->checkboxRow($user, 'login_enabled');
            echo $form->textFieldRow($user, 'username', array('autocomplete' => 'off'));
            if (user()->checkAccess('admin')) {
                echo $form->passwordFieldRow($user, 'password', array('autocomplete' => 'off', 'hint' => t('Leave blank if you do not  wish to change the password.')));
                echo $form->passwordFieldRow($user, 'confirm_password', array('autocomplete' => 'off'));
            }
            echo $form->checkboxRow($user, 'api_enabled');
            ?>

        </fieldset>


    </div>
    <div class="span4">

        <fieldset>
            <legend><?php echo t('Roles') ?></legend>

            <?php
            if (user()->checkAccess('admin')) {
                $allowedIds = true;
            }
            else {
                $allowedIds = $user->staffAllowedRoles;
            }
            $this->widget('application.components.Relation', array(
                'model' => $user,
                'relation' => 'role',
                'fields' => 'name',
                'style' => 'checkbox',
                'showAddButton' => false,
                'allowedIds' => $allowedIds,
                'preselect' => isset($rolePreselect) ? $rolePreselect : false,
            ));
            ?>
            <?php echo $form->error($user, 'role_id'); ?>
            <div class="hint"><?php echo t('Select the Roles that this User belongs to.'); ?></div>
        </fieldset>
        <?php
        $sql = 'select id from ' . Role::model()->tableName() . ' order by name ';
        $roles = app()->db->createCommand($sql)->queryColumn();
        $staffRole = Role::model()->findByAttributes(array('name' => 'staff'));
        $staffRoleOrder = array_search($staffRole->id, $roles);
        $script = "
            function toggleTeamFieldSet(){
                if ($('#User_Role_$staffRoleOrder').attr('checked')){
                    $('#teamsFieldSet').show();
                }
                else{
                    $('#teamsFieldSet').hide();
                }
            }
            $('#User_Role_$staffRoleOrder').click(toggleTeamFieldSet);
            toggleTeamFieldSet();
        ";
        cs()->registerScript('toggle_teams', $script, CClientScript::POS_READY);
        ?>

        <fieldset id="teamsFieldSet" class="hide">
            <legend><?php echo t('Teams') ?></legend>

            <?php
            $this->widget('application.components.Relation', array(
                'model' => $user,
                'relation' => 'team',
                'fields' => 'name',
                'style' => 'checkbox',
                'showAddButton' => false,
                'allowedIds' => CHtml::listData(Team::model()->findAll('t.deleted IS NULL'), 'id', 'id'),
            ));
            ?>
            <?php echo $form->error($user, 'team_id'); ?>
            <div class="hint"><?php echo t('Select the Teams that this User belongs to.'); ?></div>

        </fieldset>

    </div>
</div>

<div class="form-actions">
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