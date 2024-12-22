<?php

$product_id = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;

if ($product_id) {
    $product = wc_get_product($product_id);
    $product_name = $product->get_name();
    $product_price = $product->get_price();
    $product_quantity = $product->get_stock_quantity();
    $product_description = $product->get_description();
} else {
    $product_name = '';
    $product_price = '';
    $product_quantity = '';
    $product_description = '';
}

?>

<form method="post" enctype="multipart/form-data">
    <?php wp_nonce_field('add_product_action', 'wp_my_product_nonce'); ?>
    <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">

    <p>
        <label for="product_name"><?php esc_html_e('Product Name', 'wp-my-product-webspark'); ?></label>
        <input type="text" id="product_name" name="product_name" value="<?php echo esc_attr($product_name); ?>" required>
    </p>

    <p>
        <label for="product_price"><?php esc_html_e('Price', 'wp-my-product-webspark'); ?></label>
        <input type="number" id="product_price" name="product_price" step="0.01" value="<?php echo esc_attr($product_price); ?>" required>
    </p>

    <p>
        <label for="product_quantity"><?php esc_html_e('Quantity', 'wp-my-product-webspark'); ?></label>
        <input type="number" id="product_quantity" name="product_quantity" value="<?php echo esc_attr($product_quantity); ?>" required>
    </p>

    <p>
        <label for="product_description"><?php esc_html_e('Description', 'wp-my-product-webspark'); ?></label>
        <?php wp_editor($product_description, 'product_description', ['textarea_name' => 'product_description']); ?>
    </p>

    <p>
        <label for="product_image"><?php esc_html_e('Product Image', 'wp-my-product-webspark'); ?></label>
        <input type="hidden" id="product_image" name="product_image">
        <button type="button" id="upload_image_button"><?php esc_html_e('Upload Image', 'wp-my-product-webspark'); ?></button>
        <div id="product_image_preview"></div>
    </p>

    <p>
        <button type="submit" name="wp_my_product_submit"><?php esc_html_e('Add Product', 'wp-my-product-webspark'); ?></button>
    </p>
</form>
