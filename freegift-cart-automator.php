<?php
/*
Plugin Name: FreeGift Cart Automator
Description: Automatically adds free gift products to WooCommerce carts.
Version: 1.0
Author: DotInverse
Author URI: https://dotinverse.com/
*/

// Include the main plugin code
require_once(plugin_dir_path(__FILE__) . 'freegift-cart-functions.php');


// Add a menu item for your plugin settings
add_action('admin_menu', 'freegift_cart_automator_menu');
function freegift_cart_automator_menu() {
    add_menu_page('FreeGift Cart Automator Settings', 'FreeGift Automator', 'manage_options', 'freegift_cart_automator_settings', 'freegift_cart_automator_settings_page');
}

// Create the settings page content
function freegift_cart_automator_settings_page() {
    ?>
    <div class="wrap">
        <h2>FreeGift Cart Automator Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('freegift_cart_automator');
            do_settings_sections('freegift_cart_automator');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register and define fields for your settings
add_action('admin_init', 'freegift_cart_automator_settings');
function freegift_cart_automator_settings() {
    register_setting('freegift_cart_automator', 'specific_product_id');
    register_setting('freegift_cart_automator', 'free_product_id');
    register_setting('freegift_cart_automator', 'min_specific_product_qty');

    add_settings_section('freegift_cart_automator_section', 'Settings', 'freegift_cart_automator_section_callback', 'freegift_cart_automator');
    add_settings_field('specific_product_id', 'Specific Product ID', 'specific_product_id_callback', 'freegift_cart_automator', 'freegift_cart_automator_section');
    add_settings_field('free_product_id', 'Free Gift Product ID', 'free_product_id_callback', 'freegift_cart_automator', 'freegift_cart_automator_section');
    add_settings_field('min_specific_product_qty', 'Minimum Required Quantity', 'min_specific_product_qty_callback', 'freegift_cart_automator', 'freegift_cart_automator_section');
}

// Callback functions for fields
function specific_product_id_callback() {
    $specific_product_id = get_option('specific_product_id');
    echo '<input type="text" name="specific_product_id" value="' . esc_attr($specific_product_id) . '" />';
}

function free_product_id_callback() {
    $free_product_id = get_option('free_product_id');
    echo '<input type="text" name="free_product_id" value="' . esc_attr($free_product_id) . '" />';
}

function min_specific_product_qty_callback() {
    $min_specific_product_qty = get_option('min_specific_product_qty');
    echo '<input type="number" name="min_specific_product_qty" value="' . esc_attr($min_specific_product_qty) . '" />';
}

function freegift_cart_automator_section_callback() {
    // This function can be empty or contain additional content if needed.
}
