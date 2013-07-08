<?php

/**
 *
 */
class NavbarItems
{
    /**
     * @static
     * @return array
     */
    static function mainMenu()
    {
        $menu = array();
        $menu[] = self::topMenu();
        $menu[] = self::helpMenu();
        if (user()->checkAccess('admin')) {
            $menu[] = self::systemMenu();
        }
        return $menu;
    }

    /**
     * @static
     * @return array
     */
    static function user()
    {
        $items = array();
        if (!user()->isGuest) {
            $items[] = array(
                'label' => t('Account'),
                'url' => array('/account/index'),
            );
            //$items[] = array(
            //    'label' => t('Settings'),
            //    'url' => array('/account/settings'),
            //);
            $items[] = array(
                'label' => t('Password'),
                'url' => array('/account/password'),
            );
            if (user()->checkAccess('locksmith')) {
                $items[] = '---';
                $items[] = array(
                    'label' => t('Locksmith Plan'),
                    'url' => array('/checkout/plan'),
                );
                $items[] = array(
                    'label' => t('Transactions'),
                    'url' => array('/transaction/index'),
                );
            }
            $items[] = '---';
            $items[] = array(
                'label' => t('Logout'),
                'url' => array('/account/logout'),
            );
        }
        ob_start();
        Yii::app()->controller->widget('bootstrap.widgets.BootButtonGroup', array(
            'type' => '', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'htmlOptions' => array(
                'id' => 'navmenu-header-account',
                'class' => 'pull-right navbutton',
            ),
            'buttons' => array(
                array(
                    'label' => user()->isGuest ? t('Login') : user()->name,
                    'icon' => user()->isGuest ? 'icon-user' : 'icon-wrench',
                    'items' => $items,
                    'url' => user()->isGuest ? array('/account/login') : null,
                    //'htmlOptions' => user()->isGuest ? array('data-toggle' => 'modal-remote') : null,
                ),
            ),
        ));
        return ob_get_clean();
    }


    /**
     * @static
     * @return array
     */
    static function topMenu()
    {
        $controller = app()->controller->id;
        $menu = array(
            'class' => 'bootstrap.widgets.BootMenu',
            'items' => array(),
        );
        if (user()->checkAccess('admin')) {
            $menu['items'][] = array(
                'label' => t('Locksmiths'),
                'url' => array('/locksmith/index'),
                'active' => $controller == 'locksmith',
            );
            $menu['items'][] = array(
                'label' => t('Customers'),
                'url' => array('/customer/index'),
                'active' => $controller == 'customer',
            );
            $menu['items'][] = array(
                'label' => t('Systems'),
                'url' => array('/system/index'),
                'active' => $controller == 'system',
            );
            $menu['items'][] = array(
                'label' => t('Orders'),
                'url' => array('/order/index'),
                'active' => $controller == 'order',
            );
        }
        elseif (user()->checkAccess('locksmith')) {
            $menu['items'][] = array(
                'label' => t('Customers'),
                'url' => array('/customer/index'),
                'icon' => 'icon-group',
                'active' => in_array($controller, array('customer', 'keyHolder', 'system', 'key', 'lock', 'keyIssue')),
            );
            $menu['items'][] = array(
                'label' => t('Orders'),
                'url' => array('/order/index'),
                'icon' => 'icon-shopping-cart',
                'active' => $controller == 'order',
            );
        }
        elseif (user()->checkAccess('customer,key_holder')) {
            $menu['items'][] = array(
                'label' => t('Systems'),
                'url' => array('/system/index'),
                'icon' => 'icon-briefcase',
                'active' => in_array($controller, array('system', 'lock', 'key', 'keyIssue')),
            );
            $menu['items'][] = array(
                'label' => t('Key Holders'),
                'url' => array('/keyHolder/index'),
                'icon' => 'icon-user',
                'active' => $controller == 'keyHolder',
            );
            $menu['items'][] = array(
                'label' => t('Orders'),
                'url' => array('/order/index'),
                'icon' => 'icon-shopping-cart',
                'active' => in_array($controller, array('order', 'orderKey')),
            );
        }
        elseif (user()->isGuest) {
            $menu['items'][] = array(
                'label' => t('Features'),
                'url' => array('/site/page', 'view' => 'features'),
            );
            $menu['items'][] = array(
                'label' => t('Screencasts'),
                'url' => array('/site/page', 'view' => 'screencasts'),
            );
            $menu['items'][] = array(
                'label' => t('Pricing'),
                'url' => array('/site/page', 'view' => 'pricing'),
            );
            $menu['items'][] = array(
                'label' => t('About'),
                'url' => array('/site/page', 'view' => 'about'),
            );
        }
        return $menu;
    }

    /**
     * @static
     * @return array
     */
    static function helpMenu()
    {
        return array(
            'class' => 'bootstrap.widgets.BootMenu',
            'htmlOptions' => array(
                'class' => 'pull-right',
            ),
            'items' => array(
                array(
                    'label' => t('Help'),
                    'icon' => 'icon-question-sign',
                    'url' => 'javascript:showUserVoicePopupWidget();',
                ),
            ),
        );
    }

    /**
     * @static
     * @return array
     */
    static function systemMenu()
    {
        return array(
            'class' => 'bootstrap.widgets.BootMenu',
            'htmlOptions' => array(
                'class' => 'pull-right',
            ),
            'items' => array(
                array(
                    'label' => t('System'),
                    'items' => array(
                        array(
                            'label' => t('Clear Cache'),
                            'url' => array('/tool/clearCache', 'returnUrl' => ReturnUrl::getLinkValue(true)),
                        ),
                        '---',
                        array(
                            'label' => t('Core Settings'),
                            'url' => array('/setting/updateCore'),
                        ),
                        array(
                            'label' => t('App Settings'),
                            'url' => array('/setting/updateApp'),
                        ),
                        '---',
                        array(
                            'label' => t('Email Templates'),
                            'url' => array('/emailTemplate/index'),
                        ),
                        array(
                            'label' => t('Page Trails'),
                            'url' => array('/pageTrail/index'),
                        ),
                        array(
                            'label' => t('Audit Trails'),
                            'url' => array('/auditTrail/index'),
                        ),
                        array(
                            'label' => t('Logs'),
                            'url' => array('/log/index'),
                        ),
                    ),
                ),
            ),
        );
    }

}