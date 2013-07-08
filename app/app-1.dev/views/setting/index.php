<?php
/**
 * @var $this SettingController
 * @var $form ActiveForm
 * @var $settings Setting[]
 */

$settings = array();
$_settings = Setting::model()->findAll();
foreach ($_settings as $setting) {
    $settings[$setting->id] = $setting;
}
$this->pageTitle = t('Settings');
$this->pageHeading = t('Settings');
$this->renderPartial('/site/_system_menu');
?>


<?php
$form = $this->beginWidget('ActiveForm', array(
    'id' => 'setting-form',
    //'enableAjaxValidation' => true,
    'inlineErrors' => false,
    'type' => 'horizontal',
));
$this->widget('AskToSaveWork', array('watchElement' => '#setting-form :input', 'message' => t('Please save before leaving the page')));
echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
echo $form->errorSummary($settings);
?>

<?php
$this->widget('bootstrap.widgets.BootButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'icon' => 'ok white',
    'label' => t('Save'),
    'htmlOptions' => array('class' => 'pull-right'),
));
?>

    <h2><?php echo t('Core Settings'); ?></h2>
    <fieldset>
        <legend><?php echo t('Version Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('ID'), 'SettingEav_core_id', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][id]', $settings['core']->getEavAttribute('id'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('App Version'), 'SettingEav_core_app_version', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                $_versions = array();
                $p = dirname(bp());
                $d = dir($p);
                while (false !== ($entry = $d->read())) {
                    if (substr($entry, 0, 6) == 'app-3.') {
                        $time = filemtime($p . DS . $entry);
                        $_versions[$time] = array(
                            'entry' => $entry,
                            'display' => $entry . ' -- ' . date(Setting::item('app', 'dateTimeFormat'), $time) . ' -- (' . Time::ago($time) . ')',
                        );
                    }
                }
                $d->close();
                krsort($_versions);
                $versions = array();
                foreach ($_versions as $version) {
                    $versions[$version['entry']] = $version['display'];
                }
                echo CHtml::dropDownList('SettingEav[core][app_version]', $settings['core']->getEavAttribute('app_version'), $versions);
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Yii Version'), 'SettingEav_core_yii_version', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                $_versions = array();
                $p = dirname(dirname(bp())) . DS . 'vendors' . DS . 'yii';
                $d = dir($p);
                while (false !== ($entry = $d->read())) {
                    if (substr($entry, 0, 4) == 'yii-') {
                        $time = filemtime($p . DS . $entry);
                        $_versions[$time] = array(
                            'entry' => $entry,
                            'display' => $entry . ' -- ' . date(Setting::item('app', 'dateTimeFormat'), $time) . ' -- (' . Time::ago($time) . ')',
                        );
                    }
                }
                $d->close();
                krsort($_versions);
                $versions = array();
                foreach ($_versions as $version) {
                    $versions[$version['entry']] = $version['display'];
                }
                echo CHtml::dropDownList('SettingEav[core][yii_version]', $settings['core']->getEavAttribute('yii_version'), $versions);
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Debug Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Debug'), 'SettingEav_core_debug', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[core][debug]', 0);
                echo CHtml::checkBox('SettingEav[core][debug]', $settings['core']->getEavAttribute('debug'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Debug Levels'), 'SettingEav_core_debug_levels', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][debug_levels]', $settings['core']->getEavAttribute('debug_levels'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Debug Database'), 'SettingEav_core_debug_db', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[core][debug_db]', 0);
                echo CHtml::checkBox('SettingEav[core][debug_db]', $settings['core']->getEavAttribute('debug_db'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Error Email'), 'SettingEav_core_error_email', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][error_email]', $settings['core']->getEavAttribute('error_email'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('PHP Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Memory Limit'), 'SettingEav_core_memory_limit', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][memory_limit]', $settings['core']->getEavAttribute('memory_limit'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Time Limit'), 'SettingEav_core_time_limit', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][time_limit]', $settings['core']->getEavAttribute('time_limit'));
                ?>
            </div>
        </div>

    </fieldset>


    <fieldset>
        <legend><?php echo t('Path Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('htroot'), 'SettingEav_core_htroot', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[core][htroot]', $settings['core']->getEavAttribute('htroot'));
                ?>
            </div>
        </div>

    </fieldset>



    <h2><?php echo t('App Settings'); ?></h2>

    <fieldset>
        <legend><?php echo t('Company Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Brand'), 'SettingEav_app_brand', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][brand]', $settings['app']->getEavAttribute('brand'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Name'), 'SettingEav_app_name', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][name]', $settings['app']->getEavAttribute('name'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Address'), 'SettingEav_app_address', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textArea('SettingEav[app][address]', $settings['app']->getEavAttribute('address'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Phone'), 'SettingEav_app_phone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][phone]', $settings['app']->getEavAttribute('phone'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Domain'), 'SettingEav_app_domain', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][domain]', $settings['app']->getEavAttribute('domain'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Website'), 'SettingEav_app_domain', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][website]', $settings['app']->getEavAttribute('website'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Email'), 'SettingEav_app_email', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][email]', $settings['app']->getEavAttribute('email'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Webmaster'), 'SettingEav_app_webmaster', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][webmaster]', $settings['app']->getEavAttribute('webmaster'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Login Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Allow Auto Login'), 'SettingEav_app_allowAutoLogin', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[app][allowAutoLogin]', 0);
                echo CHtml::checkBox('SettingEav[app][allowAutoLogin]', $settings['app']->getEavAttribute('allowAutoLogin'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Default Remember Me Value'), 'SettingEav_app_rememberMe', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[app][rememberMe]', 0);
                echo CHtml::checkBox('SettingEav[app][rememberMe]', $settings['app']->getEavAttribute('rememberMe'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Print') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Print Logo Source'), 'SettingEav_app_print_logo_src', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][print_logo_src]', $settings['app']->getEavAttribute('print_logo_src'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Print Logo Width'), 'SettingEav_app_print_logo_width', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][print_logo_width]', $settings['app']->getEavAttribute('print_logo_width'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Print Logo Height'), 'SettingEav_app_print_logo_height', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][print_logo_height]', $settings['app']->getEavAttribute('print_logo_height'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Print Signature on PDF Footer'), 'SettingEav_app_job_print_show_signature', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[app][job_print_show_signature]', 0);
                echo CHtml::checkBox('SettingEav[app][job_print_show_signature]', $settings['app']->getEavAttribute('job_print_show_signature'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Application Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Default Page Size'), 'SettingEav_app_defaultPageSize', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][defaultPageSize]', $settings['app']->getEavAttribute('defaultPageSize'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Theme'), 'SettingEav_app_theme', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][theme]', $settings['app']->getEavAttribute('theme'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Recaptcha'), 'SettingEav_app_recaptcha', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][recaptcha]', $settings['app']->getEavAttribute('recaptcha'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Recaptcha Private Key'), 'SettingEav_app_recaptchaPrivate', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][recaptchaPrivate]', $settings['app']->getEavAttribute('recaptchaPrivate'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Recaptcha Public Key'), 'SettingEav_app_recaptchaPublic', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][recaptchaPublic]', $settings['app']->getEavAttribute('recaptchaPublic'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Input Unit'), 'SettingEav_app_input_unit', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][input_unit]', $settings['app']->getEavAttribute('input_unit'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Output Unit'), 'SettingEav_app_output_unit', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][output_unit]', $settings['app']->getEavAttribute('output_unit'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Output Unit Area'), 'SettingEav_app_output_unit_area', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][output_unit_area]', $settings['app']->getEavAttribute('output_unit_area'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Date Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Timezone'), 'SettingEav_app_timezone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][timezone]', $settings['app']->getEavAttribute('timezone'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Date Format'), 'SettingEav_app_dateFormat', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][dateFormat]', $settings['app']->getEavAttribute('dateFormat'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Date Format Long'), 'SettingEav_app_dateFormatLong', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][dateFormatLong]', $settings['app']->getEavAttribute('dateFormatLong'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Date Format Short'), 'SettingEav_app_dateFormatShort', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][dateFormatShort]', $settings['app']->getEavAttribute('dateFormatShort'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Date Format Short Word'), 'SettingEav_app_dateFormatShortWord', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][dateFormatShortWord]', $settings['app']->getEavAttribute('dateFormatShortWord'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Date Format Word'), 'SettingEav_app_dateFormatWord', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][dateFormatWord]', $settings['app']->getEavAttribute('dateFormatWord'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Date Format Word Without Day'), 'SettingEav_app_dateFormatWordWithoutDay', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][dateFormatWordWithoutDay]', $settings['app']->getEavAttribute('dateFormatWordWithoutDay'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Date Time Format'), 'SettingEav_app_dateTimeFormat', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][dateTimeFormat]', $settings['app']->getEavAttribute('dateTimeFormat'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Date Time Format Short'), 'SettingEav_app_dateTimeFormatShort', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][dateTimeFormatShort]', $settings['app']->getEavAttribute('dateTimeFormatShort'));
                ?>
            </div>
        </div>

    </fieldset>


    <fieldset>
        <legend><?php echo t('Path Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Attachment Spool Path'), 'SettingEav_app_attachmentSpoolPath', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][attachmentSpoolPath]', $settings['app']->getEavAttribute('attachmentSpoolPath'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Print Spool Path'), 'SettingEav_app_printSpoolPath', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][printSpoolPath]', $settings['app']->getEavAttribute('printSpoolPath'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Email Settings') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('New Job Email BCC'), 'SettingEav_app_new_job_email_bcc', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][new_job_email_bcc]', $settings['app']->getEavAttribute('new_job_email_bcc'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Approval Email BCC'), 'SettingEav_app_approval_email_bcc', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][approval_email_bcc]', $settings['app']->getEavAttribute('approval_email_bcc'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Approval Email BCC'), 'SettingEav_app_approval_email_team', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][approval_email_team]', $settings['app']->getEavAttribute('approval_email_team'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Approval Email Customer To'), 'SettingEav_app_approval_email_customer_to', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::dropDownList('SettingEav[app][approval_email_customer_to]', $settings['app']->getEavAttribute('approval_email_customer_to'), array(
                    'customer' => t('Customer'),
                    'customer_sales_rep' => t('Customer Sales Rep'),
                ));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Approval Email Supplier To'), 'SettingEav_app_approval_email_supplier_to', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::dropDownList('SettingEav[app][approval_email_supplier_to]', $settings['app']->getEavAttribute('approval_email_supplier_to'), array(
                    'supplier' => t('Supplier'),
                    'customer_sales_rep' => t('Customer Sales Rep'),
                ));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Is Customer Sales Rep required for Jobs?'), 'SettingEav_app_job_customer_sales_rep_required', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][job_customer_sales_rep_required]', $settings['app']->getEavAttribute('job_customer_sales_rep_required'));
                ?>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo t('Production Time') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Jobs with COD Payments Require Manager Approval'), 'SettingEav_app_job_cod_requires_manager_approval', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::hiddenField('SettingEav[app][job_cod_requires_manager_approval]', 0);
                echo CHtml::checkBox('SettingEav[app][job_cod_requires_manager_approval]', $settings['app']->getEavAttribute('job_cod_requires_manager_approval'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Working Days to Produce an Item'), 'SettingEav_app_item_production_days', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][item_production_days]', $settings['app']->getEavAttribute('item_production_days'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Days before Due that Customer can Approve Items'), 'SettingEav_app_item_max_approval_days', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textField('SettingEav[app][item_max_approval_days]', $settings['app']->getEavAttribute('item_max_approval_days'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Holiday Dates'), 'SettingEav_app_holiday_dates', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::textArea('SettingEav[app][holiday_dates]', $settings['app']->getEavAttribute('holiday_dates'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Work on Weekend'), 'SettingEav_app_work_on_weekend', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][work_on_weekend]', $settings['app']->getEavAttribute('work_on_weekend'));
                ?>
            </div>
        </div>

    </fieldset>


    <fieldset>
        <legend><?php echo t('Item Print Fields') ?></legend>

        <div class="control-group">
            <?php echo CHtml::label(t('Show Finishing'), 'SettingEav_app_item_print_show_finishing', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][item_print_show_finishing]', $settings['app']->getEavAttribute('item_print_show_finishing'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Show Sleeve'), 'SettingEav_app_item_print_show_sleeve', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][item_print_show_sleeve]', $settings['app']->getEavAttribute('item_print_show_sleeve'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Show Corner'), 'SettingEav_app_item_print_show_corner', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][item_print_show_corner]', $settings['app']->getEavAttribute('item_print_show_corner'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Show Rip Number'), 'SettingEav_app_item_print_show_rip_number', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][item_print_show_rip_number]', $settings['app']->getEavAttribute('item_print_show_rip_number'));
                ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo CHtml::label(t('Show Edge'), 'SettingEav_app_item_print_show_edge', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::checkBox('SettingEav[app][item_print_show_edge]', $settings['app']->getEavAttribute('item_print_show_edge'));
                ?>
            </div>
        </div>


    </fieldset>

    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.BootButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => t('Save'),
            'htmlOptions' => array('class' => 'pull-right'),
        ));
        ?>
    </div>

<?php $this->endWidget(); ?>