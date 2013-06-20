<?php Helper::searchToggle('user-grid'); ?>
<div class="search-form hide">
    <?php
    /** @var $form ActiveForm **/
    $form = $this->beginWidget('ActiveForm', array(
        'action' => url($this->route),
        'type' => 'horizontal',
        'method' => 'get',
    ));
    ?>
    <fieldset>
        <legend><?php echo t('User Search'); ?></legend>
        <?php
        echo $form->textFieldRow($user, 'id', array('class' => 'input-small', 'prepend' => 'user-'));
        echo $form->textFieldRow($user, 'company_name');
        echo $form->textFieldRow($user, 'first_name');
        echo $form->textFieldRow($user, 'last_name');
        echo $form->textFieldRow($user, 'email', array('size' => 60, 'maxlength' => 255));
        echo $form->textFieldRow($user, 'username', array('size' => 60, 'maxlength' => 255));
        echo $form->dropDownListRow($user, 'role', CHtml::listData(Role::model()->findAll(), 'id', 'name'), array('empty' => ''));
        echo $form->textFieldRow($user, 'phone');
        ?>
    </fieldset>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => t('Search')));
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>
</div>