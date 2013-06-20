<?php
$this->widget('Navbar', array(
    'id' => 'navbar',
    'fixed' => 'top',
    'fluid' => true,
    'collapse' => true,
    'items' => NavbarItems::mainMenu(),
    'constantItems' => array(
        NavbarItems::user(),
    ),
));
?>