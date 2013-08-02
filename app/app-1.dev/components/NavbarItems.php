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
    static public function mainMenu()
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
    static public function userMenu()
    {
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
                    'items' => self::userMenuItems(),
                    'url' => user()->isGuest ? array('/account/login') : null,
                    //'htmlOptions' => user()->isGuest ? array('data-toggle' => 'modal-remote') : null,
                ),
            ),
        ));
        return ob_get_clean();
    }


    /**
     * @return array
     */
    static public function userMenuItems()
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
                'label' => t('Update'),
                'url' => array('/account/update'),
            );
            $items[] = array(
                'label' => t('Password'),
                'url' => array('/account/password'),
            );
            $items[] = '---';
            $items[] = array(
                'label' => t('Logout'),
                'url' => array('/account/logout'),
            );
        }
        return $items;
    }

    /**
     * @return array
     */
    static public function topMenu()
    {
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
            'url' => array('/site/page', 'view' => 'documentation'),
        );
        return $menu;
    }

    /**
     * @static
     * @return array
     */
    static public function helpMenu()
    {
        $menu = array(
            'class' => 'bootstrap.widgets.TbMenu',
            'htmlOptions' => array(
                'class' => 'pull-right',
            ),
            'items' => array(),
        );
        $menu['items'][] = array(
            'label' => t('Help'),
            'icon' => 'icon-question-sign',
            'items' => self::helpMenuItems(),
        );
        return $menu;
    }

    /**
     * @static
     * @return array
     */
    static public function helpMenuItems()
    {
        $items = array();
        $items[] = array(
            'label' => t('Help'),
            'url' => array('/site/page', 'view' => 'help'),
        );
        $items[] = array(
            'label' => t('Documentation'),
            'url' => array('/site/page', 'view' => 'documentation'),
        );
        return $items;
    }

    /**
     * @static
     * @return array
     */
    static public function systemMenu()
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
                    'items' => self::systemMenuItems(),
                ),
            ),
        );
    }

    /**
     * @return array
     */
    static public function systemMenuItems()
    {
        $controller = app()->controller->id;

        $items = array();
        $items[] = array(
            'label' => t('Clear Cache'),
            'url' => array('/tool/clearCache', 'returnUrl' => ReturnUrl::getLinkValue(true)),
        );
        $items[] = '---';
        $items[] = array(
            'label' => t('Users'),
            'url' => array('/user/index'),
            'active' => ($controller == 'user'),
        );
        $items[] = array(
            'label' => t('Settings'),
            'url' => array('/setting/index'),
            'active' => ($controller == 'setting'),
        );
        $items[] = array(
            'label' => t('Tools'),
            'url' => array('/tool/index'),
            'active' => ($controller == 'tool'),
        );
        $items[] = '---';
        $items[] = array(
            'label' => t('Email Spool'),
            'url' => array('/emailSpool/index'),
            'active' => ($controller == 'emailSpool'),
        );
        $items[] = array(
            'label' => t('Email Templates'),
            'url' => array('/emailTemplate/index'),
            'active' => ($controller == 'emailTemplate'),
        );
        $items[] = '---';
        $items[] = array(
            'label' => t('Page Trails'),
            'url' => array('/pageTrail/index'),
            'active' => ($controller == 'pageTrail'),
        );
        $items[] = array(
            'label' => t('Audit Trails'),
            'url' => array('/auditTrail/index'),
            'active' => ($controller == 'auditTrail'),
        );
        $items[] = array(
            'label' => t('Logs'),
            'url' => array('/log/index'),
            'active' => ($controller == 'log'),
        );
        return $items;
    }

}