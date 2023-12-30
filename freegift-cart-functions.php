<?php
add_action( 'woocommerce_before_calculate_totals', 'add_free_gift_to_cart' );
function add_free_gift_to_cart( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // Retrieve settings from options
    $specific_product_id = get_option('specific_product_id');
    $min_specific_product_qty = get_option('min_specific_product_qty');
    $free_product_id = get_option('free_product_id');

    $has_specific_product = $free_key = false;
    $free_qty = 0;

    // Loop through cart items
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        // Check if the specific product is in the cart with at least the minimum required quantity
        if ( $specific_product_id == $cart_item['product_id'] && $cart_item['quantity'] >= $min_specific_product_qty ) {
            $has_specific_product = true;
        }

        // Check if the free product is already in the cart
        if ( $free_product_id == $cart_item['product_id'] ) {
            $free_key = $cart_item_key;
            $free_qty = $cart_item['quantity'];
            $cart_item['data']->set_price(0); // Set free product price to zero
        }
    }

    // Add the free gift product if the specific product has at least the minimum required quantity
    if ( $has_specific_product && $free_key === false ) {
        $cart->add_to_cart( $free_product_id, 1 );
    }
    // Remove the free gift product if the specific product is below the minimum required quantity
    elseif ( !$has_specific_product && $free_key !== false ) {
        $cart->remove_cart_item( $free_key );
    }
    // Adjust the free gift product quantity to 1 if more than 1 is added
    elseif ( $free_key !== false && $free_qty > 1 ) {
        $cart->set_quantity( $free_key, 1 );
    }
}

// Optional: Display the free gift product price as zero on the minicart
add_filter( 'woocommerce_cart_item_price', 'change_minicart_free_gifted_item_price', 10, 3 );
function change_minicart_free_gifted_item_price( $price_html, $cart_item, $cart_item_key ) {
    $free_product_id = get_option('free_product_id');

    if( $cart_item['product_id'] == $free_product_id ) {
        return wc_price( 0 );
    }
    return $price_html;
}
