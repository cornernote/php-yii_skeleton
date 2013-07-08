<?php
/**
 * @var $this UserController
 * @var $user User
 */
user()->setState('index.user.' . $user->id, ru());
$this->pageTitle = t('User') . ' ' . $user->name;
$this->pageHeading = $user->name;

$this->breadcrumbs = array();
$this->breadcrumbs[t('Users')] = user()->getState('index.user', array('/user/index'));
$this->breadcrumbs[] = $user->name;

$this->renderPartial('_menu', array(
    'user' => $user,
));

$attributes = array();
if (!in_array(Role::ROLE_LOCKSMITH, CHtml::listData($user->role, 'id', 'id'))) {
    if (user()->checkAccess('admin')) {
        $attributes[] = array(
            'name' => 'locksmith_id',
            'value' => $user->locksmith ? l($user->locksmith->getName(), $user->locksmith->getUrl()) : null,
            'type' => 'raw',
        );
    }
    if (!in_array(Role::ROLE_CUSTOMER, CHtml::listData($user->role, 'id', 'id'))) {
        if (user()->checkAccess('admin,locksmith')) {
            $attributes[] = array(
                'name' => 'customer_id',
                'value' => $user->customer ? l($user->customer->getName(), $user->customer->getUrl()) : null,
                'type' => 'raw',
            );
        }
    }
}
$attributes[] = 'name';
$attributes[] = array(
    'name' => 'email',
    'value' => l($user->email, 'mailto:' . $user->email),
    'type' => 'raw',
);
$attributes[] = 'phone';
$attributes[] = array(
    'label' => t('Roles'),
    'value' => implode(', ', CHtml::listData($user->role, 'id', 'name')),
);
$attributes[] = 'created';
$this->widget('DetailView', array(
    'data' => $user,
    'attributes' => $attributes,
));

