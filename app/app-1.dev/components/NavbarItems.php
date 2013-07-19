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
    static function userMenu()
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
        app()->controller->widget('bootstrap.widgets.TbButtonGroup', array(
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
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => array(),
        );
        $menu['items'][] = array(
            'label' => t('Example'),
            'url' => array('/site/page', 'view' => 'example'),
        );
        $menu['items'][] = array(
            'label' => t('Documentation'),
            'url' => array('/tool/page', 'view' => 'documentation'),
        );
        if (user()->checkAccess('admin')) {
            $menu['items'][] = array(
                'label' => t('Users'),
                'url' => array('/user/index'),
                'active' => $controller == 'user',
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
            'class' => 'bootstrap.widgets.TbMenu',
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
        $controller = app()->controller->id;
        return array(
            'class' => 'bootstrap.widgets.TbMenu',
            'htmlOptions' => array(
                'class' => 'pull-right',
            ),
            'items' => array(
                array(
                    'label' => t('System'),
                    'active' => in_array($controller, array('tool', 'setting', 'emailTemplate', 'pageTrail', 'auditTrail', 'log')),
                    'items' => array(
                        array(
                            'label' => t('Clear Cache'),
                            'url' => array('/tool/clearCache', 'returnUrl' => ReturnUrl::getLinkValue(true)),
                        ),
                        '---',
                        array(
                            'label' => t('Settings'),
                            'url' => array('/setting/index'),
                        ),
                        array(
                            'label' => t('Tools'),
                            'url' => array('/tool/index'),
                            'active' => ($controller == 'tool'),
                        ),
                        array(
                            'label' => t('Email Templates'),
                            'url' => array('/emailTemplate/index'),
                            'active' => ($controller == 'emailTemplate'),
                        ),
                        '---',
                        array(
                            'label' => t('Page Trails'),
                            'url' => array('/pageTrail/index'),
                            'active' => ($controller == 'pageTrail'),
                        ),
                        array(
                            'label' => t('Audit Trails'),
                            'url' => array('/auditTrail/index'),
                            'active' => ($controller == 'auditTrail'),
                        ),
                        array(
                            'label' => t('Logs'),
                            'url' => array('/log/index'),
                            'active' => ($controller == 'log'),
                        ),
                    ),
                ),
            ),
        );
    }

}