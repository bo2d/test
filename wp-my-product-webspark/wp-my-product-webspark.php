<?php
/**
 * Plugin Name: Woo My Product Submit (Webspark)
 * Description: Test
 * Version: 1.1
 * Author: Bohdan
 * Text Domain: wp-my-product-webspark
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    add_action( 'admin_notices', function() {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'WooCommerce must be active for WP My Product Webspark to work.', 'wp-my-product-webspark' ) . '</p></div>';
    } );
    return;
}

/**
 * Define constants
 */
define( 'WP_MY_PRODUCT_WEBSPARK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Include required files
 */
require_once WP_MY_PRODUCT_WEBSPARK_PLUGIN_DIR . 'includes/functions-product-management.php';

/**
 * Load scripts and styles
 */
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_script( 'wp-my-product-webspark-scripts', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', [ 'jquery' ], '1.0', true );
    wp_enqueue_media();
});

/**
 * Add menu items to My Account
 */
add_filter( 'woocommerce_account_menu_items', function( $items ) {
    $items['add-product'] = __( 'Add Product', 'wp-my-product-webspark' );
    $items['my-products'] = __( 'My Products', 'wp-my-product-webspark' );
    return $items;
});

/**
 * Add endpoint for Add Product
 */
add_action( 'init', function() {
    add_rewrite_endpoint( 'add-product', EP_PAGES );
    add_rewrite_endpoint( 'my-products', EP_PAGES );
});

/**
 * Render Add Product page
 */
add_action( 'woocommerce_account_add-product_endpoint', function() {
    wc_get_template( 'add-product.php', [], '', WP_MY_PRODUCT_WEBSPARK_PLUGIN_DIR . 'templates/' );
});

/**
 * Render My Products page
 */
add_action( 'woocommerce_account_my-products_endpoint', function() {
    wc_get_template( 'my-products.php', [], '', WP_MY_PRODUCT_WEBSPARK_PLUGIN_DIR . 'templates/' );
});

/**
 * Register email classes via WooCommerce hook
 */
add_filter( 'woocommerce_email_classes', 'filter_woocommerce_email_classes' );
function filter_woocommerce_email_classes( $email_classes ) {
    if ( class_exists( 'WC_Emails' ) ) {
        require_once WP_MY_PRODUCT_WEBSPARK_PLUGIN_DIR . 'includes/class-wc-email-product-submitted.php';

        $email_classes['WC_Email_Product_Submitted'] = new WC_Email_Product_Submitted();
    }
    return $email_classes;
}

/**
 * Add actions for sending email
 */
add_action( 'woocommerce_product_submit_notification', function( $product_id ) {
    if ( class_exists( 'WC_Emails' ) ) {
        $emails = WC()->mailer()->get_emails();
        if ( isset( $emails['WC_Email_Product_Submitted'] ) ) {
            $emails['WC_Email_Product_Submitted']->trigger( $product_id );
        }
    }
});

/**
 * Plugin activation hook
 */
register_activation_hook( __FILE__, function() {
    flush_rewrite_rules();
    
    update_option( 'woocommerce_manage_stock', 'yes' ); 
    update_option( 'woocommerce_stock_threshold', 1 ); 
    update_option( 'woocommerce_stock_display', 'yes' ); 
    update_option( 'woocommerce_out_of_stock_visibility', 'no' ); 
});

/**
 * Plugin deactivation hook
 */
register_deactivation_hook( __FILE__, function() {
    flush_rewrite_rules();
});

