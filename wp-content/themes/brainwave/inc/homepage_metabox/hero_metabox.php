<?php
// Step 1: Add Meta Box
function hero_meta_box() {
    global $post;
    
    if (is_object($post) && $post->post_type == 'page') {
        $template_file = get_post_meta($post->ID, '_wp_page_template', true);
        
        if ($template_file == 'pages/homepage.php') {
            add_meta_box(
                'hero-meta-box',
                __('Hero Section', 'textdomain'),
                'hero_meta_box_callback',
                'page',
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'hero_meta_box');

// Step 2: Meta Box Callback Function
function hero_meta_box_callback($post) {
    wp_nonce_field(basename(__FILE__), 'hero-meta-box-nonce');

    $hero_heading = get_post_meta($post->ID, 'hero_heading', true);
    $hero_text = get_post_meta($post->ID, 'hero_text', true);
    $gallery_images = get_post_meta($post->ID, 'hero_image', true);
    $image_src = wp_get_attachment_image_src($gallery_images, 'thumbnail');
    $pages = get_pages();
    $links = array();

    foreach ($pages as $page) {
        $links[get_permalink($page->ID)] = $page->post_title;
    }

    echo '<p><label for="hero_image">' . __('Image:', 'textdomain') . '</label></p>';
    echo '<input type="hidden" name="hero_image" class="hero-image-input" value="' . esc_attr(isset($image_src[0]) ? $image_src[0] : '') . '" />';
    echo '<button type="button" class="button hero-image-button">Select Image</button>';
    echo '<div class="hero-image-container">';
            if ($image_src) {
                echo '<img src="' . esc_url($image_src[0]) . '" style="margin: 5px; max-width: 100px;" />';
    }
    echo '</div>';

    echo '<p><label for="hero_heading">' . __('Heading:', 'textdomain') . '</label></p>';
    echo '<input type="text" id="hero_heading" name="hero_heading" value="' . esc_attr($hero_heading) . '" size="50" />';

    echo '<p><label for="hero_text">' . __('Description:', 'textdomain') . '</label></p>';
    echo '<textarea id="hero_text" name="hero_text" rows="5" cols="50">' . esc_textarea($hero_text) . '</textarea>';
    echo '<p><label for="shop_now_btn_link">' . __('Shop now Button link:', 'textdomain') . '</label></p>';
    echo '<select id="shop_now_btn_link" name="shop_now_btn_link">';
    echo '<option value="">Shop now Button link</option>';
    foreach ($links as $url => $title) {
        echo '<option value="' . esc_attr($url) . '" ' . selected($url, get_post_meta($post->ID, 'shop_now_btn_link', true), false) . '>' . esc_html($title) . '</option>';
    }
    echo '</select>';

    echo '<p><label for="explore_btn_link">' . __('Explore Button link:', 'textdomain') . '</label></p>';
    echo '<select id="explore_btn_link" name="explore_btn_link">';
    echo '<option value="">Explore Button link</option>';
    foreach ($links as $url => $title) {
        echo '<option value="' . esc_attr($url) . '" ' . selected($url, get_post_meta($post->ID, 'explore_btn_link', true), false) . '>' . esc_html($title) . '</option>';
    }
    echo '</select>';
}

// Step 3: Save Meta Box Data
function save_hero_meta_data($post_id) {
    if (!isset($_POST['hero-meta-box-nonce']) || !wp_verify_nonce($_POST['hero-meta-box-nonce'], basename(__FILE__))) {
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

    $fields = array('hero_heading', 'hero_text', 'hero_image', 'shop_now_btn_link', 'explore_btn_link');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_hero_meta_data');


// Step 4: Enqueue JavaScript and CSS using the footer hook
function hero_footer_script() {
    global $post;
    
    if ($post->post_type == 'page') {
        $template_file = get_post_meta($post->ID, '_wp_page_template', true);
        
        if ($template_file == 'pages/homepage.php') {
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
            select {
                width: 40%;
                padding: 8px;
                border-radius: 4px;
                box-sizing: border-box;
            }
            </style>
            <script type="text/javascript">
             <?php wp_enqueue_script('single-image-picker', get_stylesheet_directory_uri() . '/inc/images_js/single-image-picker.js', array('jquery'), null, true); ?>
            </script>
            <?php
        }
    }
}
add_action('admin_footer', 'hero_footer_script');
?>
