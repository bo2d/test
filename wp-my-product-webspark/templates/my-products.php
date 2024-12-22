<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! is_user_logged_in() ) {
    echo '<p>' . esc_html__( 'You must be logged in to view your products.', 'wp-my-product-webspark' ) . '</p>';
    return;
}

$current_user = get_current_user_id();
$paged = get_query_var( 'paged', 1 );

$args = [
    'post_type'      => 'product',
    'post_status'    => [ 'publish', 'pending' ],
    'posts_per_page' => 10,
    'paged'          => $paged,
    'author'         => $current_user,
];

$query = new WP_Query( $args );

if ( $query->have_posts() ) {
    echo '<table>';
    echo '<thead><tr>';
    echo '<th>' . esc_html__( 'Name', 'wp-my-product-webspark' ) . '</th>';
    echo '<th>' . esc_html__( 'Quantity', 'wp-my-product-webspark' ) . '</th>';
    echo '<th>' . esc_html__( 'Price', 'wp-my-product-webspark' ) . '</th>';
    echo '<th>' . esc_html__( 'Status', 'wp-my-product-webspark' ) . '</th>';
    echo '<th>' . esc_html__( 'Edit', 'wp-my-product-webspark' ) . '</th>';
    echo '<th>' . esc_html__( 'Delete', 'wp-my-product-webspark' ) . '</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    while ( $query->have_posts() ) {
        $query->the_post();
        $product_id = get_the_ID();
        $price = get_post_meta( $product_id, '_price', true );
        $quantity = get_post_meta( $product_id, '_stock', true );

        echo '<tr>';
        echo '<td>' . get_the_title() . '</td>';
        echo '<td>' . esc_html( $quantity ) . '</td>';
        echo '<td>' . wc_price( $price ) . '</td>'; 
        echo '<td>' . esc_html( ucfirst( get_post_status() ) ) . '</td>';
        
        echo '<td><a href="' . esc_url( get_edit_post_link( $product_id ) ) . '" target="_blank">' . esc_html__( 'Edit', 'wp-my-product-webspark' ) . '</a></td>';
        echo '<td><a href="' . esc_url( add_query_arg( 'delete_product_id', $product_id, wc_get_account_endpoint_url( 'my-products' ) ) ) . '">' . esc_html__( 'Delete', 'wp-my-product-webspark' ) . '</a></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    echo paginate_links( [
        'total' => $query->max_num_pages,
        'current' => $paged,
        'format' => '?paged=%#%',
    ] );
} else {
    echo '<p>' . esc_html__( 'No products found.', 'wp-my-product-webspark' ) . '</p>';
}

wp_reset_postdata();

if ( isset( $_GET['delete_product_id'] ) ) {
    $product_id_to_delete = (int) $_GET['delete_product_id'];
    if ( $product_id_to_delete ) {
        wp_delete_post( $product_id_to_delete, true ); 
        wp_redirect( wc_get_account_endpoint_url( 'my-products' ) ); 
        exit;
    }
}
?>
