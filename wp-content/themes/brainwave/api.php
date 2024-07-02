<?php

require_once 'php-jwt-main/src/JWT.php';
require_once 'php-jwt-main/src/Key.php';
require_once 'php-jwt-main/src/ExpiredException.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\SignatureInvalidException;
use \Firebase\JWT\BeforeValidException;

define('JWT_AUTH_SECRET_KEY', ']J%8k*Qs!Enp`J1Ok-)XZ[;/r:5cK*q|b1|oMbnjGZo}JWp^+~-)Iz/(gdq`Ae-4');

//geneerating the JWT token
function generate_jwt_token($user_id, $secret_key) {
    $issued_at = time();
    $expiration_time = $issued_at + (60 * 5);

    $payload = array(
        'iat' => $issued_at,
        'exp' => $expiration_time,
        'sub' => $user_id
    );

    $token = JWT::encode($payload, $secret_key, 'HS256');
    return array(
        'token' => $token,
        'expiration_time' => $expiration_time
    );
}

//validating the JWT token
function validate_jwt_token($jwt_token, $secret_key) {
    try {
        return JWT::decode($jwt_token, new Key($secret_key, 'HS256'));
    } catch (ExpiredException $e) {
        return('Token expired');
    } catch (SignatureInvalidException $e) {
        throw new Exception('Invalid token signature');
    } catch (BeforeValidException $e) {
        throw new Exception('Token not valid yet');
    } catch (Exception $e) {
        throw new Exception('Invalid token');
    }
}

//authenticating the JWT token
function myplugin_jwt_authenticate(WP_REST_Request $request) {
    $auth_header = $request->get_header('Authorization');

    if (!$auth_header) {
        return new WP_Error('no_token', 'No token provided', array('status' => 403));
    }

    list($token) = sscanf($auth_header, 'Bearer %s');

    if (!$token) {
        return new WP_Error('bad_token', 'Invalid token format', array('status' => 403));
    }

    $decoded_token = validate_jwt_token($token, JWT_AUTH_SECRET_KEY);

    if (!$decoded_token) {
        return new WP_Error('invalid_token', 'Invalid token', array('status' => 403));
    }
    if ($decoded_token=="Token expired") {
        return new WP_Error('token_expired', 'Token expired', array('status' => 403));
    }
    $request->set_param('user_id', $decoded_token);

    return true;
}

//login to my system
function myplugin_login(WP_REST_Request $request) {
    $username = $request->get_param('username');
    $password = $request->get_param('password');

    $user = wp_authenticate($username, $password);

    if (is_wp_error($user)) {
        return new WP_Error('invalid_credentials', 'Invalid username or password', array('status' => 403));
    }

    $token_data = generate_jwt_token($user->ID, JWT_AUTH_SECRET_KEY);

    return array(
        'jwt_token' => $token_data['token'],
        'user_id' => $user->ID,
        'expiration_time' => $token_data['expiration_time']
    );
}

//registering my API's here
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/login', array(
        'methods' => 'POST',
        'callback' => 'myplugin_login',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('wp/v2', '/post_by_id/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'post_by_id',
        'permission_callback' => 'myplugin_jwt_authenticate'
    ));

    register_rest_route('wp/v2', '/posts_by_author/(?P<author_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'posts_by_author',
        'permission_callback' => 'myplugin_jwt_authenticate',
    ));

    register_rest_route('wp/v2', '/create_post', array(
        'methods' => 'POST',
        'callback' => 'create_post',
        'permission_callback' => 'myplugin_jwt_authenticate',
    ));

    register_rest_route('wp/v2', '/custom_edit_post', array(
        'methods' => 'POST',
        'callback' => 'custom_edit_post',
        'permission_callback' => 'myplugin_jwt_authenticate',
    ));

});



//my 1st API
function post_by_id(WP_REST_Request $request) {
    $post_id = $request['id'];
    $post = get_post($post_id);

    if (empty($post)) {
        return new WP_REST_Response('Post not found', 404);
    }

    $data = array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'content' => $post->post_content,
        'author' => get_the_author_meta('display_name', $post->post_author),
        'date' => $post->post_date,
    );

    return new WP_REST_Response($data, 200);
}

//my 2nd API
function posts_by_author(WP_REST_Request $request) {
    $author_id = $request['author_id'];

    $args = array(
        'author' => $author_id,
        'post_type' => 'post',
        'posts_per_page' => -1,
    );

    $posts = get_posts($args);

    if (empty($posts)) {
        return new WP_REST_Response('No posts found for this author', 404);
    }

    $data = array();
    foreach ($posts as $post) {
        $data[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => $post->post_content,
            'date' => $post->post_date,
        );
    }

    return new WP_REST_Response($data, 200);
}

//my 3rd API
function create_post(WP_REST_Request $request) {
    $post_title = $request->get_param('title');
    $post_content = $request->get_param('content');
    $post_author = $request->get_param('author');

    $new_post = array(
        'post_title'    => $post_title,
        'post_content'  => $post_content,
        'post_author'   => $post_author,
        'post_status'   => 'publish',
        'post_type'     => 'post',
    );

    $post_id = wp_insert_post($new_post);

    if ($post_id) {
        $post = get_post($post_id);

        $data = array(
        	'message' => 'Post created successfully!',
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => $post->post_content,
            'author' => get_the_author_meta('display_name', $post->post_author),
            'date' => $post->post_date,
        );

        return new WP_REST_Response($data, 200);
    } else {
        return new WP_REST_Response('Failed to create post', 500);
    }
}

//my 4th API
function custom_edit_post(WP_REST_Request $request) {
    $post_id = $request->get_param('id');
    $post_title = $request->get_param('title');
    $post_content = $request->get_param('content');
    $post_author = $request->get_param('author');

    $post = get_post($post_id);

    if (empty($post)) {
        return new WP_REST_Response('Post not found', 404);
    }

    $updated_post = array(
        'ID'            => $post_id,
        'post_title'    => $post_title,
        'post_content'  => $post_content,
        'post_author'   => $post_author,
    );

    $updated = wp_update_post($updated_post, true);

    if (is_wp_error($updated)) {
        return new WP_REST_Response('Failed to update post', 500);
    }

    $updated_post = get_post($post_id);

    $data = array(
        'id' => $updated_post->ID,
        'title' => $updated_post->post_title,
        'content' => $updated_post->post_content,
        'author' => get_the_author_meta('display_name', $updated_post->post_author),
        'date' => $updated_post->post_date,
    );

    return new WP_REST_Response($data, 200);
}

function manage_post(WP_REST_Request $request) {

    $post_title = $request->get_param('title');
    $post_content = $request->get_param('content');
    $post_author = $request->get_param('author');

    if(isset($request->get_param('id')))
    {
    $post_id = $request->get_param('id');
    $post = get_post($post_id);

    if (empty($post)) {
        return new WP_REST_Response('Post not found', 404);
    }

    $updated_post = array(
        'ID'            => $post_id,
        'post_title'    => $post_title,
        'post_content'  => $post_content,
        'post_author'   => $post_author,
    );

    $updated = wp_update_post($updated_post, true);

    if (is_wp_error($updated)) {
        return new WP_REST_Response('Failed to update post', 500);
    }
    }
    else{
        $new_post = array(
        'post_title'    => $post_title,
        'post_content'  => $post_content,
        'post_author'   => $post_author,
        'post_status'   => 'publish',
        'post_type'     => 'post',
    );

    $post_id = wp_insert_post($new_post);
    }

    $post_data = get_post($post_id);

    $data = array(
        'id' => $post_data->ID,
        'title' => $post_data->post_title,
        'content' => $post_data->post_content,
        'author' => get_the_author_meta('display_name', $post_data->post_author),
        'date' => $post_data->post_date,
    );

    return new WP_REST_Response($data, 200);
}
?>