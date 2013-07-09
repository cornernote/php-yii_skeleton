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
            'class' => 'bootstrap.widgets.TBMenu',
            'items' => array(),
        );
        if (user()->checkAccess('admin')) {
            $menu['items'][] = array(
                'label' => t('Users'),
                'url' => array('/user/index'),
                'active' => $controller == 'user',
            );
        }
        elseif (user()->isGuest) {
            $menu['items'][] = array(
                'label' => t('Example'),
                'url' => array('/site/page', 'view' => 'example'),
            );
            $menu['items'][] = array(
                'label' => t('Documentation'),
                'url' => array('/site/page', 'view' => 'documentation'),
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
            'class' => 'bootstrap.widgets.TBMenu',
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
            'class' => 'bootstrap.widgets.TBMenu',
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
                            'label' => t('Settings'),
                            'url' => array('/setting/index'),
                        ),
                        array(
                            'label' => t('Email Templates'),
                            'url' => array('/emailTemplate/index'),
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

}