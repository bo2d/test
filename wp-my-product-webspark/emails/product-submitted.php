<?php
/**
 * Email HTML Template for Product Submitted
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$product_name = isset( $args['product_name'] ) ? $args['product_name'] : __( 'Unknown Product', 'wp-my-product-webspark' );
$author_url   = isset( $args['author_url'] ) ? $args['author_url'] : '';
$edit_url     = isset( $args['edit_url'] ) ? $args['edit_url'] : '';

?>

<h2 style="text-align:center;width:100%;"><?php echo esc_html( $product_name ); ?> <?php _e( 'has been submitted.', 'wp-my-product-webspark' ); ?></h2>
<p>
    <?php _e( 'A new product has been submitted. Here are the details:', 'wp-my-product-webspark' ); ?>
</p>
<ul>
    <li><strong><?php _e( 'Product Name:', 'wp-my-product-webspark' ); ?></strong> <?php echo esc_html( $product_name ); ?></li>
    <li><strong><?php _e( 'Author Page:', 'wp-my-product-webspark' ); ?></strong> <a href="<?php echo esc_url( $author_url ); ?>" target="_blank"><?php _e( 'View Author', 'wp-my-product-webspark' ); ?></a></li>
    <li><strong><?php _e( 'Edit Product:', 'wp-my-product-webspark' ); ?></strong> <a href="<?php echo esc_url( $edit_url ); ?>" target="_blank"><?php _e( 'Edit Product', 'wp-my-product-webspark' ); ?></a></li>
</ul>
