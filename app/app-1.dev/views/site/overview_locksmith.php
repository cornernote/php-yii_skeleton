<?php
/**
 * @var $this SiteController
 * @var $count array
 * @var $customers Customer[]
 */
$this->pageTitle = t('Overview');
$this->pageHeading = t('Overview');

$locksmith = Locksmith::model()->findByPk(user()->id);
$plan = $locksmith->getPlan();

// messages
$count = Order::model()->countByAttributes(array('locksmith_id' => user()->id, 'status' => 'pending'));
if ($count) {
    user()->addFlash(strtr(t('You have :count :link.'), array(
        ':count' => $count,
        ':link' => l(t('pending orders'), array('/order/index', 'Order[status]' => 'pending')),
    )), 'info');
}


// get customers list
$criteria = new CDbCriteria();
$criteria->addCondition('t.deleted IS NULL');
$criteria->compare('locksmith_id', $locksmith->id);
$criteria->join = "LEFT JOIN user_to_role u2r ON u2r.user_id=t.id AND u2r.role_id='" . Role::ROLE_CUSTOMER . "'";
$criteria->compare('u2r.role_id', Role::ROLE_CUSTOMER);
$criteria->order = 'eav_last_view.value DESC';
$criteria->limit = 10;
$customers = Customer::model()->withEavAttributes(array('last_view'))->findAll($criteria);

?>

<div class="row-fluid">
    <div class="span9">
        <h2>
            <?php echo l('<i class="icon-group"></i> ' . t('Customers'), array('/customer/index')); ?>
            <div class="btn-toolbar pull-right">
                <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'encodeLabel' => false,
                'buttons' => array(
                    array('label' => '<i class="icon-plus"></i>', 'url' => array('/customer/create'), 'htmlOptions' => array('data-toggle' => 'modal-remote')),
                    array('items' => array(
                        array('label' => t('Create Customer'), 'url' => array('/customer/create'), 'linkOptions' => array('data-toggle' => 'modal-remote')),
                    )),
                ),
            )); ?>
            </div>
        </h2>
        <?php
        foreach ($customers as $customer) {
            echo '<hr class="dashed">';
            echo '<div class="pull-right">' . $customer->getCountHtml() . '</div>';
            echo '<h3>' . l('<i class="icon-group"></i> ' . $customer->name, $customer->getUrl()) . '</h3>';
            foreach ($customer->system as $system) {
                echo '<div class="well well-small">';
                echo '<div class="pull-right small">' . $system->getCountHtml() . '</div>';
                echo '<h4>' . l('<i class="icon-briefcase"></i> ' . $system->name, $system->getUrl()) . '</h4>';
                echo '</div>';
            }
        }
        ?>
        <?php if (!$customers) { ?>
        <h3 class="txt-middle"><?php echo t('No customers here, would you like to') . ' ' . l(t('create a customer'), array('/customer/create'), array('data-toggle' => 'modal-remote')) . '?'; ?></h3>
        <hr class="dashed">
        <?php } ?>
    </div>
    <div class="span3">

        <h3><?php echo l('<i class="icon-shopping-cart"></i> ' . t('Orders'), array('/order/index')); ?></h3>

        <div class="well"><?php echo $locksmith->getCountOrderHtml(); ?></div>

        <h3><?php echo l('<i class="icon-dashboard"></i> ' . t('Plan'), array('/checkout/plan')); ?></h3>

        <div class="well">
            <h4><?php echo t('Usage'); ?></h4>
            <?php echo $locksmith->getCountHtml(); ?>
            <h4><?php echo t($plan['label']) . ' ' . t('Plan'); ?></h4>
            <?php echo $locksmith->getPlanHtml(); ?>
        </div>

    </div>
</div>