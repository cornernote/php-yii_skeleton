<?php
user()->setState('search.user', ru());

$this->pageTitle = t('Users');
$this->pageHeading = t('Users');
$this->breadcrumbs = array(
    t('Users'),
);
$this->renderPartial('/site/_users_menu');

echo '<div class="spacer">';
$this->widget('bootstrap.widgets.BootButton', array(
    'label' => t('Create User'),
    'url' => array('/user/create'),
    'type' => 'primary',
));
echo ' ';
$this->widget('bootstrap.widgets.BootButton', array(
    'label' => t('Search Users'),
    'htmlOptions' => array('class' => 'search-button'),
    'toggle' => true,
));
if (user()->getState('search.user') != url('/user/index')) {
    echo ' ';
    $this->widget('bootstrap.widgets.BootButton', array(
        'label' => t('Reset Filters'),
        'url' => array('/user/index'),
    ));
}
echo '</div>';

$this->renderPartial('/user/_search', array('user' => $user));
$this->renderPartial('/user/_grid', array('user' => $user));
?>