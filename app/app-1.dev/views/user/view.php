<?php
$this->pageTitle = 'user-' . $user->id . ' ' . $user->name;
$this->pageHeading = 'user-' . $user->id . ' <small>' . $user->name . '</small>';
$this->breadcrumbs = array(
    t('Users') => user()->getState('search.user', array('index')),
    'user-' . $user->id,
);
$this->renderPartial('_menu', array('user' => $user));

$companySeparator = "";
$companyNames = "";
foreach ($user->company as $singleCompany) {
    $companyNames .= $companySeparator . $singleCompany->name;
    $companySeparator = "+";
}
?>

<div class="row-fluid">
    <div class="span4">

        <fieldset>
            <legend><?php echo t('Contact Details') ?></legend>
            <?php $this->widget('DetailView', array(
            'data' => $user,
            'attributes' => array(
                array(
                    'name' => 'id',
                    'value' =>
                    l(i(au() . '/icons/update.png'), array('/user/update', 'id' => $user->id))
                        . ' user-' . $user->id,
                    'type' => 'raw',
                ),
                'first_name',
                'last_name',
                'email',
                'phone',
                'fax',
            ),

            //$this->getApiKey()
        )); ?>
        </fieldset>

        <fieldset>
            <legend><?php echo t('Login Details') ?></legend>
            <?php $this->widget('DetailView', array(
            'data' => $user,
            'attributes' => array(
                array(
                    'name' => 'id',
                    'value' =>
                    l(i(au() . '/icons/update.png'), array('/user/update', 'id' => $user->id))
                        . ' user-' . $user->id,
                    'type' => 'raw',
                ),
                'username',
                array(
                    'name' => 'login_enabled',
                    'value' => $user->getLoginEnabledString(),
                    'type' => 'raw',
                ),
                array(
                    'name' => 'api_enabled',
                    'value' => $user->getApiEnabledString(),
                    'type' => 'raw',
                ),
                array(
                    'name' => 'api_key',
                    'value' => $user->getApiKey(),
                    'type' => 'raw',
                ),
            ),

            //$this->getApiKey()
        )); ?>
        </fieldset>

        <fieldset>
            <legend><?php echo t('Companies') ?></legend>
            <?php
            // actions
            $this->widget('bootstrap.widgets.BootButton', array(
                'label' => t('Assign Company'),
                'url' => 'javascript:void(0)',
                'type' => 'primary',
                'size' => 'mini',
                'htmlOptions' => array(
                    'onclick' => 'jQuery("#Company_select").show("fast");',
                    'class' => 'pull-right',
                ),
            ));
            ?>
            <div id="Company_select" class="hide">
                <?php
                $form = $this->beginWidget('ActiveForm', array(
                    'id' => 'user-form',
                    'action' => array('/user/company', 'id' => $user->id),
                    'type' => 'horizontal',
                ));
                echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue(true));
                ?>
                <div class="control-group">
                    <?php
                    echo $form->labelEx($user, 'company_name', array('class' => 'control-label'));
                    echo '<div class="controls">';
                    $this->widget('CAutoComplete', array(
                        'name' => 'User[company_name]',
                        'model' => $user,
                        'url' => array('/company/autoCompleteLookup'),
                        'max' => 10,
                        'minChars' => 0,
                        'delay' => 500,
                        'matchCase' => false,
                        'htmlOptions' => array(
                            'class' => isset($user->errors['company_id']) ? 'error' : null,
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
                    echo $form->error($user, 'company_name');
                    echo '</div>';
                    ?>
                </div>

                <div class="form-actions">
                    <?php $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => t('Assign'))); ?>
                </div>

                <?php $this->endWidget(); ?>
            </div>
            <?php
            // company data
            $attributes = array();
            foreach ($user->company as $company) {
                $attributes[] = array(
                    'label' => l(i(au() . '/icons/group_blue_remove.png'), array('/user/company', 'id' => $user->id, 'company_id' => $company->id, 'action' => 'remove'), array('confirm' => t('Are you sure?'), 'title' => t('Unassign Company'))),
                    'value' => l(h($company->name), $company->getUrl()),
                    'type' => 'raw',
                );
            }
            $this->widget('DetailView', array(
                'data' => $user,
                'attributes' => $attributes,
            ));
            ?>
        </fieldset>

    </div>

    <div class="span4">

        <fieldset>
            <legend><?php echo t('Roles') ?></legend>
            <?php echo implode(', ', CHtml::listData($user->role, 'id', 'name')); ?>
        </fieldset>
        <fieldset>
            <legend><?php echo t('Teams') ?></legend>
            <?php echo implode(', ', CHtml::listData($user->team, 'id', 'name')); ?>
        </fieldset>

    </div>
    <div class="span4">

        <fieldset>
            <legend><?php echo t('Notes') ?></legend>
            <?php $this->renderPartial('/note/_index', array('model' => $user)); ?>
        </fieldset>

        <fieldset>
            <legend><?php echo t('Attachments') ?></legend>
            <?php $this->renderPartial('/attachment/_index', array('model' => $user)); ?>
        </fieldset>

    </div>
</div>


<div class="accordion" id="user-accordion">

    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#user-accordion"
               href="#toggle_JobsRep">
                <?php echo t('Jobs (as a sales rep)'); ?>
            </a>
        </div>
        <div id="toggle_JobsRep" class="accordion-body collapse">
            <div class="accordion-inner">
                <?php
                // grid data
                $job = new Job('search');
                $job->sales_rep_id = $user->id;
                $this->renderPartial('/job/_grid', array('job' => $job));
                ?>
            </div>
        </div>
    </div>

    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#user-accordion"
               href="#toggle_JobsCustomer">
                <?php echo t('Jobs (as a customer contact)'); ?>
            </a>
        </div>
        <div id="toggle_JobsCustomer" class="accordion-body collapse">
            <div class="accordion-inner">
                <?php
                // grid data
                $job = new Job('search');
                $job->contact_id = $user->id;
                $this->renderPartial('/job/_grid', array('job' => $job));
                ?>
            </div>
        </div>
    </div>

    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#user-accordion"
               href="#toggle_ItemsSupplier">
                <?php echo t('Items (as a supplier contact)'); ?>
            </a>
        </div>
        <div id="toggle_ItemsSupplier" class="accordion-body collapse">
            <div class="accordion-inner">
                <?php
                // grid data
                $item = new Item('search');
                $item->supplier_contact_id = $user->id;
                $this->renderPartial('/item/_grid', array('item' => $item));
                ?>
            </div>
        </div>
    </div>

</div>
