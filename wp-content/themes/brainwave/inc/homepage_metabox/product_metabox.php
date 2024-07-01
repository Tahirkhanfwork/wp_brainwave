<?php
// Step 1: Add Meta Box
function product_meta_box() {
    global $post;
    
    if (is_object($post) && $post->post_type == 'page') {
        $template_file = get_post_meta($post->ID, '_wp_page_template', true);
        
        if ($template_file == 'pages/homepage.php') {
            add_meta_box(
                'product-meta-box',
                __('Product Section', 'textdomain'),
                'product_meta_box_callback',
                'page',
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'product_meta_box');

// Step 2: Meta Box Callback Function
// Step 2: Meta Box Callback Function
function product_meta_box_callback($post) {
    wp_nonce_field(basename(__FILE__), 'product-meta-box-nonce');

    $product_heading = get_post_meta($post->ID, 'product_heading', true);
    $product_description = get_post_meta($post->ID, 'product_description', true);
    $products = get_post_meta($post->ID, 'product_items', true);
    $pages = get_pages();
    $links = array();

    foreach ($pages as $page) {
        $links[get_permalink($page->ID)] = $page->post_title;
    }

    ?>
    <div id="custom-repeater-wrapper">
        <div id="custom-repeater-fields">

            <p><label for="product_heading">Heading</label></p>
            <input type="text" id="product_heading" name="product_heading" value="<?= esc_attr($product_heading) ?>" size="50" />
            <p><label for="product_description">Description</label></p>
            <textarea id="product_description" name="product_description" rows="5" cols="50"><?= esc_textarea($product_description) ?></textarea>
            <p><label for="shop_btn_link">Shop Button link</label></p>
            <select id="shop_btn_link" name="shop_btn_link" style="margin-bottom: 10px;">
                <option disabled>Shop Button link</option>
                <?php foreach ($links as $url => $title) { ?>
                    <option value="<?php echo esc_attr($url); ?>" <?php selected($url, get_post_meta($post->ID, 'shop_btn_link', true), false); ?>>
                        <?php echo esc_html($title); ?>
                    </option>
                <?php } ?>
            </select>


            <?php
            if ($products) {
                foreach ($products as $product) {
                    ?>
                    <div class="repeater-item">
                        <button type="button" class="remove-repeater-item">Remove</button>
                        <p><label>Image:</label></p>
                        <input type="hidden" class="product-image-input" name="product_items[image][]" value="<?php echo esc_attr($product['image']); ?>" />
                        <button type="button" class="button product-image-button">Select Image</button>
                        <div class="product-image-container">
                            <?php if (!empty($product['image'])) : ?>
                                <img src="<?php echo esc_url(wp_get_attachment_image_url($product['image'], 'thumbnail')); ?>" style="margin: 5px; max-width: 100px;" />
                            <?php endif; ?>
                        </div>

                        <p><label>Title:</label></p>
                        <input type="text" name="product_items[title][]" value="<?php echo esc_attr($product['title']); ?>" />

                        <p><label>Price:</label></p>
                        <input type="text" name="product_items[price][]" value="<?php echo esc_attr($product['price']); ?>" />
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <button type="button" id="add-repeater-item">Add Product</button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        function initMediaUploader(button, input, container) {
            var mediaUploader;

            button.on('click', function(e) {
                e.preventDefault();

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Select Images',
                    button: {
                        text: 'Select Images'
                    },
                    multiple: true
                });

                mediaUploader.on('select', function() {
                    var attachments = mediaUploader.state().get('selection').toJSON();
                    var galleryIDs = [];
                    var galleryHTML = '';
                    attachments.forEach(function(attachment) {
                        galleryIDs.push(attachment.id);
                        var thumbnailUrl = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
                        galleryHTML += '<img src="' + thumbnailUrl + '" style="margin: 5px; max-width: 100px;" />';
                    });
                    input.val(galleryIDs.join(','));
                    container.html(galleryHTML);
                });

                mediaUploader.open();
            });
        }

        $('#add-repeater-item').on('click', function() {
            var item = '<div class="repeater-item">' +
                '<button type="button" class="remove-repeater-item">Remove</button>' +
                '<p><label>Image:</label></p>' +
                '<input type="hidden" class="product-image-input" name="product_items[image][]" value="" />' +
                '<button type="button" class="button product-image-button">Select Image</button>' +
                '<div class="product-image-container"></div>' +
                '<p><label>Title:</label></p>' +
                '<input type="text" name="product_items[title][]" value="" />' +
                '<p><label>Price:</label></p>' +
                '<input type="text" name="product_items[price][]" value="" />' +
                '</div>';
            $('#custom-repeater-fields').append(item);
        });

        $(document).on('click', '.remove-repeater-item', function() {
            $(this).parent('.repeater-item').remove();
        });

        $('#custom-repeater-fields .repeater-item').each(function() {
            var item = $(this);
            var button = item.find('.product-image-button');
            var input = item.find('.product-image-input');
            var container = item.find('.product-image-container');
            initMediaUploader(button, input, container);
        });

    });
    </script>
    <?php
}


// Step 3: Save Meta Box Data
function save_product_meta_data($post_id) {
    if (!isset($_POST['product-meta-box-nonce']) || !wp_verify_nonce($_POST['product-meta-box-nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if ('page' !== $_POST['post_type']) {
        return $post_id;
    }

    if (!current_user_can('edit_page', $post_id)) {
        return $post_id;
    }

    $fields = array('product_heading', 'product_description', 'shop_btn_link');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    if (isset($_POST['product_items'])) {
        $product_items = [];
        foreach ($_POST['product_items']['image'] as $index => $image) {
            $product_items[] = [
                'image' => intval($image),
                'title' => sanitize_text_field($_POST['product_items']['title'][$index]),
                'price' => sanitize_text_field($_POST['product_items']['price'][$index]),
            ];
        }
        update_post_meta($post_id, 'product_items', $product_items);
    }
}
add_action('save_post', 'save_product_meta_data');

// Step 4: Enqueue JavaScript and CSS using the footer hook (already provided in your code)
