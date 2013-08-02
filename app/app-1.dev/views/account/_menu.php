<?php
/* @var $this AccountController */
?>
<?php
$this->menu[] = array(
    'label' => t('Account'),
    'url' => array('/account/index'),
);
$this->menu[] = array(
    'label' => t('Update'),
    'url' => array('/account/update'),
    'active' => ($this->id == 'account' && $this->action->id == 'update'),
);
$this->menu[] = array(
    'label' => t('Password'),
    'url' => array('/account/password'),
    'active' => ($this->id == 'account' && $this->action->id == 'password'),
);
//$this->menu[] = array(
//    'label' => t('Settings'),
//    'url' => array('/account/settings'),
//    'active' => ($this->id == 'account' && $this->action->id == 'settings'),
//);
