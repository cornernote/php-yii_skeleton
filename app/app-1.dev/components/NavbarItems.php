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
        if (!user()->isGuest) {

            // left
            $menu[] = self::keys();
            $menu[] = self::customers();
            $menu[] = self::orders();

            // right
            $menu[] = self::systemMenu();

        }
        return $menu;
    }

    /**
     * @static
     * @return array
     */
    static function keys()
    {
        return array(
            'class' => 'bootstrap.widgets.BootMenu',
            'items' => array(
                array(
                    'label' => t('Keys'),
                    'items' => array(
                        array(
                            'label' => t('Keys'),
                            'url' => array('/key/index'),
                        ),
                        array(
                            'label' => t('Doors'),
                            'url' => array('/door/index'),
                        ),
                    ),
                ),
            ),
        );
    }

    /**
     * @static
     * @return array
     */
    static function orders()
    {
        return array(
            'class' => 'bootstrap.widgets.BootMenu',
            'items' => array(
                array(
                    'label' => t('Orders'),
                    'items' => array(
                        array(
                            'label' => t('Orders'),
                            'url' => array('/order/index'),
                        ),
                    ),
                ),
            ),
        );
    }

    /**
     * @static
     * @return array
     */
    static function customers()
    {
        return array(
            'class' => 'bootstrap.widgets.BootMenu',
            'items' => array(
                array(
                    'label' => t('Customers'),
                    'items' => array(
                        array(
                            'label' => t('Customers'),
                            'url' => array('/customer/index'),
                        ),
                    ),
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

    /**
     * @static
     * @return array
     */
    static function user()
    {
        $items = array();
        if (user()->isGuest) {
            $items[] = array(
                'label' => t('Login'),
                'url' => array('/user/login'),
            );
        }
        else {
            $items[] = array(
                'label' => t('Account'),
                'url' => array('/account/index'),
            );
            $items[] = array(
                'label' => t('Settings'),
                'url' => array('/account/settings'),
            );
            $items[] = array(
                'label' => t('Logout'),
                'url' => array('/site/logout'),
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
                    'label' => user()->name,
                    'icon' => 'icon-user',
                    'items' => $items,
                ),
            ),
        ));
        $userMenu = ob_get_clean();
        return $userMenu;
//        return array(
//            'class' => 'bootstrap.widgets.BootMenu',
//            'htmlOptions' => array('class' => 'pull-right'),
//            'items' => ,
//        );
    }


}