<?php
/**
 * @var $this SiteController
 */
$this->pageTitle = t('Pricing');
$this->pageHeading = t('Pricing');
?>

<div class="row">
    <div class="span4">
        <div class="product-plan">
            <ul class="unstyled plans">
                <li>
                    <div class="name">
                        <h3>Small</h3>
                    </div>
                </li>
                <li>
                    <div class="price">
                        <h3>$<?php echo number_format(Locksmith::$locksmithPlans['small']['price'], 0)?>
                            <small>/ month</small>
                        </h3>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <h4><i class="icon-user"></i> Customers
                            <span class="pull-right"><?php echo number_format(Locksmith::$locksmithPlans['small']['customer'], 0); ?></span>
                        </h4>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <h4><i class="icon-lock"></i> Locks
                            <span class="pull-right"><?php echo number_format(Locksmith::$locksmithPlans['small']['lock'], 0); ?></span>
                        </h4>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <h4><i class="icon-key"></i> Keys
                            <span class="pull-right"><?php echo number_format(Locksmith::$locksmithPlans['small']['key'], 0); ?></span>
                        </h4>
                    </div>
                </li>
                <li>
                    <div class="action">
                        <?php
                        if (user()->isGuest) {
                            echo l(t('Signup Now'), array('/account/register', 'plan' => 'small'), array('class' => 'btn btn-primary btn-action', 'data-toggle' => 'modal-remote'));
                        }
                        elseif (user()->checkAccess('locksmith')) {
                            echo l(t('Plan Settings'), array('/checkout/plan'), array('class' => 'btn btn-primary btn-action'));
                        }
                        ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="span4">
        <div class="product-plan popular">
            <ul class="unstyled plans">
                <li>
                    <div class="name">
                        <h3>Medium</h3>
                    </div>
                </li>
                <li>
                    <div class="price">
                        <h3>$<?php echo number_format(Locksmith::$locksmithPlans['medium']['price'], 0)?>
                            <small>/ month</small>
                        </h3>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <h4><i class="icon-user"></i> Customers
                            <span class="pull-right"><?php echo number_format(Locksmith::$locksmithPlans['medium']['customer'], 0); ?></span>
                        </h4>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <h4><i class="icon-lock"></i> Locks
                            <span class="pull-right"><?php echo number_format(Locksmith::$locksmithPlans['medium']['lock'], 0); ?></span>
                        </h4>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <h4><i class="icon-key"></i> Keys
                            <span class="pull-right"><?php echo number_format(Locksmith::$locksmithPlans['medium']['key'], 0); ?></span>
                        </h4>
                    </div>
                </li>
                <li>
                    <div class="action">
                        <?php
                        if (user()->isGuest) {
                            echo l(t('Signup Now'), array('/account/register', 'plan' => 'medium'), array('class' => 'btn btn-primary btn-action', 'data-toggle' => 'modal-remote'));
                        }
                        elseif (user()->checkAccess('locksmith')) {
                            echo l(t('Plan Settings'), array('/checkout/plan'), array('class' => 'btn btn-primary btn-action'));
                        }
                        ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="span4">
        <div class="product-plan">
            <ul class="unstyled plans">
                <li>
                    <div class="name">
                        <h3>Large</h3>
                    </div>
                </li>
                <li>
                    <div class="price">
                        <h3>$<?php echo number_format(Locksmith::$locksmithPlans['large']['price'], 0)?>
                            <small>/ month</small>
                        </h3>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <h4><i class="icon-user"></i> Customers
                            <span class="pull-right"><?php echo number_format(Locksmith::$locksmithPlans['large']['customer'], 0); ?></span>
                        </h4>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <h4><i class="icon-lock"></i> Locks
                            <span class="pull-right"><?php echo number_format(Locksmith::$locksmithPlans['large']['lock'], 0); ?></span>
                        </h4>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <h4><i class="icon-key"></i> Keys
                            <span class="pull-right"><?php echo number_format(Locksmith::$locksmithPlans['large']['key'], 0); ?></span>
                        </h4>
                    </div>
                </li>
                <li>
                    <div class="action">
                        <?php
                        if (user()->isGuest) {
                            echo l(t('Signup Now'), array('/account/register', 'plan' => 'large'), array('class' => 'btn btn-primary btn-action', 'data-toggle' => 'modal-remote'));
                        }
                        elseif (user()->checkAccess('locksmith')) {
                            echo l(t('Plan Settings'), array('/checkout/plan'), array('class' => 'btn btn-primary btn-action'));
                        }
                        ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<hr class="dashed"/>
<p>Other Plans:</p>
<ul>
    <li>
        <?php
        if (user()->isGuest) {
            echo l(t('Free Plan'), array('/account/register', 'plan' => 'free'), array('data-toggle' => 'modal-remote'));
        }
        elseif (user()->checkAccess('locksmith')) {
            echo l(t('Free Plan'), array('/checkout/plan'));
        }
        ?>
        - <?php echo number_format(Locksmith::$locksmithPlans['free']['customer'], 0); ?>
        customer, <?php echo number_format(Locksmith::$locksmithPlans['free']['lock'], 0); ?>
        locks, <?php echo number_format(Locksmith::$locksmithPlans['free']['key'], 0); ?> keys.
    </li>
    <li>
        <?php
        if (user()->isGuest) {
            echo l(t('Unlimited Plan'), array('/account/register', 'plan' => 'unlimited'), array('data-toggle' => 'modal-remote'));
        }
        elseif (user()->checkAccess('locksmith')) {
            echo l(t('Unlimited Plan'), array('/checkout/plan'));
        }
        ?>
        - no limits on customers, locks or keys.
    </li>
</ul>
