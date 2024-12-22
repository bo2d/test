jQuery(document).ready(function ($) {
    $('#upload_image_button').click(function (e) {
        e.preventDefault();

        var imageFrame;
        if (imageFrame) {
            imageFrame.open();
            return;
        }

        imageFrame = wp.media({
            title: 'Select Product Image',
            button: {
                text: 'Use This Image',
            },
            multiple: false,
        });

        imageFrame.on('select', function () {
            var attachment = imageFrame.state().get('selection').first().toJSON();
            $('#product_image').val(attachment.id);
            $('#product_image_preview').html('<img src="' + attachment.url + '" alt="" style="max-width:100%;"/>');
        });

        imageFrame.open();
    });
});
