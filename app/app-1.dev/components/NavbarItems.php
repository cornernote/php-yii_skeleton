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
    static public function navMenu()
    {
        $menu = array();
        $menu[] = array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => Menu::getItemsFromMenu('Main'),
        );
        $menu[] = array(
            'class' => 'bootstrap.widgets.TbMenu',
            'htmlOptions' => array(
                'class' => 'pull-right',
            ),
            'items' => array(
                array(
                    'label' => t('Help'),
                    'icon' => 'icon-question-sign',
                    'items' => Menu::getItemsFromMenu('Help'),
                ),
            ),
        );
        if (user()->checkAccess('admin')) {
            $menu[] = array(
                'class' => 'bootstrap.widgets.TbMenu',
                'htmlOptions' => array(
                    'class' => 'pull-right',
                ),
                'items' => array(
                    array(
                        'label' => t('System'),
                        'items' => Menu::getItemsFromMenu('System'),
                    ),
                ),
            );
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
                    'label' => user()->isGuest ? t('Login or Signup') : user()->name,
                    'icon' => user()->isGuest ? 'icon-user' : 'icon-wrench',
                    'items' => Menu::getItemsFromMenu('User'),
                ),
            ),
        ));
        return ob_get_clean();
    }

}