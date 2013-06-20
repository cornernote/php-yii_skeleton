<?php
/**
 *@var $this AuditTrailController
 *@var $auditTrail AuditTrail
 */

?>
<?php
$this->pageTitle = t('Audit Trails');
$this->pageHeading = t('Audit Trails');
$this->breadcrumbs = array(
    t('Audit Trails'),
);
$this->renderPartial('/site/_system_menu');
$this->renderPartial('/auditTrail/_grid', array('auditTrail' => $auditTrail));
?>