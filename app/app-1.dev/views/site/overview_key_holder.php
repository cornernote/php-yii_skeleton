<?php
/**
 * @var $this SiteController
 * @var $count array
 * @var $keyHolder KeyHolder
 */
$this->pageTitle = t('Overview');
$this->pageHeading = t('Overview');

// messages
if (OrderKey::model()->countByAttributes(array('customer_id' => $keyHolder->customer_id, 'order_id' => 0, 'deleted' => null))) {
    user()->addFlash(strtr(t('You have an order with pending keys - :link.'), array(':link' => l(t('click here to complete your order'), array('/order/create')))), 'info');
}

$criteria = new CDbCriteria();
$criteria->compare('t.customer_id', $keyHolder->customer_id);
$criteria->addInCondition('t.status', Order::completeStatusList());
$criteria->addCondition('eav_keys_issued.value = 0');
$order = Order::model()->withEavAttributes(array('keys_issued'))->find($criteria);
if ($order) {
    user()->addFlash(strtr(t('You have an order awaiting keys to be issued - :link.'), array(':link' => l(t('click here to complete your order'), $order->getUrl()))), 'info');
}
?>

<?php
$this->renderPartial('/locksmith/_contact_details', array('locksmith' => $keyHolder->customer->locksmith));
?>

<div class="row-fluid">
    <div class="span9">
        <h2><?php echo '<i class="icon-briefcase"></i> ' . t('Systems'); ?></h2>
        <?php
        $criteria = new CDbCriteria();
        $criteria->addInCondition('t.id', CHtml::listData($keyHolder->keyHolderAccessPermView, 'system_id', 'system_id'));
        $criteria->compare('t.customer_id', $keyHolder->customer_id);
        $criteria->addCondition('t.deleted IS NULL');
        $criteria->order = 'eav_last_view.value DESC';
        $systems = System::model()->withEavAttributes(array('last_view'))->findAll($criteria);
        foreach ($systems as $system) {
            ?>
            <hr class="dashed">
            <div class="pull-right"><?php echo $system->getCountHtml(); ?></div>
            <h3><i class="icon-briefcase"></i> <?php echo $system->getLink(); ?></h3>
        <?php
        }
        ?>
    </div>
    <div class="span3">
        <h3><?php echo l('<i class="icon-user"></i> ' . t('Key Holders'), array('/keyHolder/index')); ?></h3>

        <div class="well">
            <?php
            foreach ($keyHolder->customer->keyHolder as $_keyHolder) {
                echo $_keyHolder->getLink(array('update', 'delete')) . '<br/>';
            }
            ?>
        </div>
    </div>
</div>

