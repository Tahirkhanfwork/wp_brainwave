<?php
// Step 1: Add Meta Box
function custom_repeater_meta_box() {
    global $post;
    
    if (is_object($post) && $post->post_type == 'page') {
        $template_file = get_post_meta($post->ID, '_wp_page_template', true);
        
        if ($template_file == 'pages/homepasge.php') { // Replace 'pages/homepage.php' with your template file name
            add_meta_box(
                'custom-repeater-meta-box',
                __('Custom Repeater', 'textdomain'),
                'custom_repeater_meta_box_callback',
                'page',
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'custom_repeater_meta_box');

// Step 2: Meta Box Callback Function
function custom_repeater_meta_box_callback($post) {
    wp_nonce_field(basename(__FILE__), 'custom-repeater-meta-box-nonce');

    // Retrieve existing data
    $repeater_data = get_post_meta($post->ID, 'custom_repeater_data', true);

    // Output repeater fields
    echo '<div id="custom-repeater-wrapper">';
    if ($repeater_data) {
        foreach ($repeater_data as $index => $item) {
            echo '<div class="repeater-item">';
            echo '<input type="text" name="custom_repeater_data[' . $index . '][title]" placeholder="Title" value="' . esc_attr($item['title']) . '" />';
            echo '<input type="text" name="custom_repeater_data[' . $index . '][image_url]" placeholder="Image URL" value="' . esc_attr($item['image_url']) . '" />';
            echo '<textarea name="custom_repeater_data[' . $index . '][caption]" placeholder="Caption">' . esc_textarea($item['caption']) . '</textarea>';
            echo '<button class="button remove-repeater-item">Remove</button>';
            echo '</div>';
        }
    }
    echo '</div>';
    echo '<button class="button" id="add-repeater-item">Add Item</button>';
}

// Step 3: Save Meta Box Data
function save_custom_repeater_meta_data($post_id) {
    if (!isset($_POST['custom-repeater-meta-box-nonce']) || !wp_verify_nonce($_POST['custom-repeater-meta-box-nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if ('page' === $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    }

    if (isset($_POST['custom_repeater_data'])) {
        update_post_meta($post_id, 'custom_repeater_data', $_POST['custom_repeater_data']);
    }
}
add_action('save_post', 'save_custom_repeater_meta_data');

// Step 4: Enqueue JavaScript and CSS using the footer hook
function custom_repeater_footer_script() {
    global $post;
    
    if ($post->post_type == 'page') {
        $template_file = get_post_meta($post->ID, '_wp_page_template', true);
        
        if ($template_file == 'pages/homepasge.php') {
            ?>
            <style>
            #custom-repeater-wrapper {
                margin-top: 10px;
            }
            .repeater-item {
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 10px;
                background: #f9f9f9;
                position: relative;
            }
            .repeater-item input[type="text"],
            .repeater-item textarea {
                width: 100%;
                margin-bottom: 10px;
            }
            .repeater-item button.remove-repeater-item {
                background: #dc3545;
                color: #fff;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
                position: absolute;
                top: 10px;
                right: 10px;
            }
            button#add-repeater-item {
                background: #007bff;
                color: #fff;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
            }
            </style>
            <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#add-repeater-item').on('click', function () {
                    var index = $('#custom-repeater-wrapper .repeater-item').length;
                    var newItem = '<div class="repeater-item">' +
                        '<input type="text" name="custom_repeater_data[' + index + '][title]" placeholder="Title" />' +
                        '<input type="text" name="custom_repeater_data[' + index + '][image_url]" placeholder="Image URL" />' +
                        '<textarea name="custom_repeater_data[' + index + '][caption]" placeholder="Caption"></textarea>' +
                        '<button class="button remove-repeater-item">Remove</button>' +
                        '</div>';
                    $('#custom-repeater-wrapper').append(newItem);
                    return false;
                });

                $(document).on('click', '.remove-repeater-item', function () {
                    $(this).parent('.repeater-item').remove();
                    return false;
                });
            });
            </script>
            <?php
        }
    }
}
add_action('admin_footer', 'custom_repeater_footer_script');
?>
