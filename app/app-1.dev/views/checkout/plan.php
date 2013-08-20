<?php
/**
 * @var $this CheckoutController
 * @var $locksmith Locksmith
 */
$this->pageTitle = t('Locksmith Plan');
$this->pageHeading = t('Locksmith Plan');
$this->breadcrumbs = array(
    t('My Account') => array('/account/index'),
    t('Locksmith Plan'),
);
$this->renderPartial('/account/_menu', array('user' => $locksmith));
$plan = $locksmith->getPlan();
?>

<?php
$planForm = null;
if ($plan['subscription'] != 'active') {
    ob_start();
    /**@var $form ActiveForm **/
    $form = $this->beginWidget('ActiveForm', array(
        'id' => 'locksmithPlan-form',
        'htmlOptions' => array(
            'class' => 'form-inline',
        ),
    ));
    echo CHtml::hiddenField('returnUrl', ReturnUrl::getFormValue());
    echo $form->errorSummary($locksmith);
    echo CHtml::dropDownList('LocksmithEav[locksmith_plan]', $locksmith->getEavAttribute('locksmith_plan'), $locksmith->getLocksmithPlans()) . ' ';
    $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => t('Change')));
    echo ' ' . l(t('Plan Info'), array('/site/page', 'view' => 'pricing'), array('class' => 'btn'));
    $this->endWidget();
    $planForm = ob_get_clean();
}
?>

<?php
$attributes[] = array(
    'name' => 'name',
    'label' => t('Locksmith Plan'),
    'value' => $planForm,
    'type' => 'raw',
);
if ($plan['name'] != 'free') {
    $attributes[] = array(
        'name' => 'expires',
        'label' => t('Expires'),
        'value' => $plan['expires'] . ' <small>' . Time::ago($plan['expires']) . '</small>',
        'type' => 'raw',
    );
    $subscription = $plan['subscription'] . ' - ' . l('cancel subscription', PayPalHelper::getUnsubscribeLink());
    if ($plan['subscription'] != 'active') {
        $subscription = PayPalHelper::getButton(array(
            'item_name' => 'Locksmith Plan ' . $plan['label'],
            'item_number' => 'locksmith_plan_' . $plan['name'],
            'custom' => user()->id,
            'a3' => $plan['price'],
        ));
    }
    $attributes[] = array(
        'name' => 'subscription',
        'label' => t('Subscription'),
        'value' => $subscription,
        'type' => 'raw',
    );
}
$this->widget('DetailView', array(
    'data' => $plan,
    'attributes' => $attributes,
));
?>

