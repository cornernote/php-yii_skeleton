<?php
$this->pageTitle = t('Tools');
$this->pageHeading = t('Tools');
//ReturnUrl::setCurrentUrlAsReturnUrl();
$this->breadcrumbs = array(
    t('Tools'),
);
$this->renderPartial('_menu');
echo '<br/> '.CHtml::link('generate properties for IDE',array('tool/generateProperties','tableName'=>'OCOrder'));
//echo '<br/> '.CHtml::link('Execute Command update Approval Date',array('tool/','id'=>'updateApprovalDate','returnUrl'=>ReturnUrl::getLinkValue(true)));
//echo '<br/> '.CHtml::link('Execute Command fix Item Status',array('tool/executeCommand','id'=>'fixItemStatus','returnUrl'=>ReturnUrl::getLinkValue(true)));
//echo '<br/> '.CHtml::link('List Errors',array('tool/executeCommand','id'=>'listErrors'));
?>