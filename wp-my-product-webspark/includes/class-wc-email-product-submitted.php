<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Email_Product_Submitted extends WC_Email {

    public function __construct() {
        $this->id          = 'product_submitted';
        $this->title       = __( 'Product Submitted', 'wp-my-product-webspark' );
        $this->description = __( 'Email sent to admin when a product is submitted.', 'wp-my-product-webspark' );

        $this->template_html  = 'emails/product-submitted.php';
        $this->template_plain = 'emails/plain/product-submitted.php';

        parent::__construct();

        $this->recipient = get_option( 'admin_email' );
    }
    
    public function trigger( $product_id ) {
        $this->object = get_post( $product_id );

        error_log('Triggering email for product ID: ' . $product_id);

        if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
            error_log('Email not sent: Disabled or no recipient');
            return;
        }

        error_log('Sending email to: ' . $this->get_recipient());

        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }

    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            [
                'email_heading' => __( 'New Product Submitted', 'wp-my-product-webspark' ),
                'product_name'  => $this->object->post_title,
                'author_url'    => admin_url( 'user-edit.php?user_id=' . $this->object->post_author ),
                'edit_url'      => admin_url( 'post.php?post=' . $this->object->ID . '&action=edit' ),
            ]
        );
    }

    public function get_content_plain() {
        return wc_get_template_html(
            $this->template_plain, 
            [
                'product_name'  => $this->object->post_title,
                'author_url'    => admin_url( 'user-edit.php?user_id=' . $this->object->post_author ),
                'edit_url'      => admin_url( 'post.php?post=' . $this->object->ID . '&action=edit' ),
            ]
        );
    }

}

