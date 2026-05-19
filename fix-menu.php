<?php
require_once('/Users/stanoje/Local Sites/ac-taverne/app/public/wp-load.php');
$menu_locations = get_nav_menu_locations();
if (isset($menu_locations['menu-1'])) {
    $menu = wp_get_nav_menu_object($menu_locations['menu-1']);
    if ($menu) {
        $items = wp_get_nav_menu_items($menu->term_id);
        foreach($items as $item) {
            echo "Item: {$item->title} (Order: {$item->menu_order}) ID: {$item->ID}\n";
        }
    } else {
        echo "Menu non trovato.\n";
    }
} else {
    echo "Nessun menu assegnato a menu-1.\n";
}
