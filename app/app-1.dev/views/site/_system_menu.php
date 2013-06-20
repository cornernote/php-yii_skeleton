<?php
$menu = NavbarItems::systemMenu();
foreach ($menu['items'][0]['items'] as $item) {
    if (is_array($item)) {
        $this->menu[] = $item;
    }
}
?>