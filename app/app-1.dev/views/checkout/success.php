<?php
/**
 * @var $this CheckoutController
 */
?>
<?php
$this->pageTitle = t('Payment Successful');
$this->pageHeading = t('Payment Successful');
$this->breadcrumbs = array(
    t('My Account') => array('/account/index'),
    t('Locksmith Plan') => array('/checkout/plan'),
    t('Payment Successful'),
);
?>

<p><?php echo t('Your payment was successful.'); ?></p>
<p><?php echo t('Your payment was successful. You may still see the subscribe button on the plans page for up to 30 seconds.  This is because we are waiting for PayPal to notify us of your payment. If you still see the subscribe button after 30 seconds please contact us.'); ?></p>
<p><?php echo l(t('View your Plan'), array('/checkout/plan'), array('class' => 'btn')); ?></p>

