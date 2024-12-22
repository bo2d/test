<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

echo "= " . esc_html( $email_heading ) . " =\n\n";

echo __( 'A new product has been submitted:', 'wp-my-product-webspark' ) . "\n\n";

echo __( 'Product Name:', 'wp-my-product-webspark' ) . ' ' . esc_html( $product_name ) . "\n";
echo __( 'Author Profile:', 'wp-my-product-webspark' ) . ' ' . esc_url( $author_url ) . "\n";
echo __( 'Edit Product:', 'wp-my-product-webspark' ) . ' ' . esc_url( $edit_url ) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

do_action( 'woocommerce_email_footer', $email );
