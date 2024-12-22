<?php

add_action( 'template_redirect', function() {
	
    if ( isset( $_POST['wp_my_product_submit'] ) && isset( $_POST['wp_my_product_nonce'] ) && wp_verify_nonce( $_POST['wp_my_product_nonce'], 'add_product_action' ) ) {
        $product_name        = sanitize_text_field( $_POST['product_name'] );
        $product_price       = floatval( $_POST['product_price'] );
        $product_quantity    = intval( $_POST['product_quantity'] );
        $product_description = wp_kses_post( $_POST['product_description'] );
        $product_image_id    = intval( $_POST['product_image'] );

        $product = new WC_Product_Simple();
        $product->set_name( $product_name );
        $product->set_regular_price( $product_price );
        $product->set_description( $product_description );
        
        $product->set_manage_stock( true ); 
        $product->set_stock_quantity( $product_quantity ); 
        
        if ( $product_image_id ) {
            $product->set_image_id( $product_image_id );
        }

        $product->set_status( 'pending' );

        $product->save();

        do_action( 'woocommerce_product_submit_notification', $product->get_id() );

        wp_redirect( wc_get_account_endpoint_url( 'my-products' ) );
        exit;
    }
});

add_action('template_redirect', function() {
    if ( isset($_POST['wp_my_product_submit']) && isset($_POST['wp_my_product_nonce']) && wp_verify_nonce($_POST['wp_my_product_nonce'], 'add_product_action') ) {
        $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $product_name = sanitize_text_field($_POST['product_name']);
        $product_price = floatval($_POST['product_price']);
        $product_quantity = intval($_POST['product_quantity']);
        $product_description = wp_kses_post($_POST['product_description']);
        $product_image = isset($_POST['product_image']) ? (int) $_POST['product_image'] : 0;

        if ($product_id) {
            $product = wc_get_product($product_id);
            $product->set_name($product_name);
            $product->set_regular_price($product_price);
            $product->set_description($product_description);
            
            $product->set_manage_stock( true );
            $product->set_stock_quantity($product_quantity);
            
            if ($product_image) {
                $product->set_image_id($product_image);
            }
            $product->save();
        } else {
            $product = wc_create_product('simple');
            $product->set_name($product_name);
            $product->set_regular_price($product_price);
            $product->set_description($product_description);
            
            $product->set_manage_stock( true );
            $product->set_stock_quantity($product_quantity);
            
            if ($product_image) {
                $product->set_image_id($product_image);
            }
            $product->set_status('pending'); 
            $product->save();
        }
    }
});

add_action( 'save_post', function( $post_id, $post, $update ) {
    if ( 'product' !== $post->post_type ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    $product = wc_get_product( $post_id );

    if ( $update && $product ) {
        $author_id = $post->post_author;
        $author_url = get_edit_user_link( $author_id ); 

        $edit_product_url = get_edit_post_link( $post_id ); 

        $to = get_option( 'admin_email' ); 
        $subject = 'Продукт оновлено: ' . $product->get_name();
        $message = 'Продукт був оновлений: ' . $product->get_name() . "\n";
        $message .= 'Посилання на сторінку автора товару: ' . $author_url . "\n";
        $message .= 'Посилання на сторінку редагування товару: ' . $edit_product_url . "\n";
        $message .= 'Ціна: ' . $product->get_regular_price() . "\n";
        $message .= 'Кількість на складі: ' . $product->get_stock_quantity() . "\n";
        $message .= 'Опис: ' . $product->get_description() . "\n";

        wp_mail( $to, $subject, $message );
    }
}, 10, 3 );
