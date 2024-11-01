<?php
/**
 * Plugin Name: Trinity Post Views
 * Plugin URI: https://github.com/thivalls/trinity-postviews
 * Description: It is a simple plugin for count views of posts. There is a widget that show the most views to with or without thumbnails.
 * Version: 1.1
 * Author: Thiago Valls, André Bertolino, Trinity Web
 * Author URI: http://www.trinityweb.com.br
 *
 * Text Domain: trinity-post-views
 * Domain Path: /languages
 *
 * @author Trinity Web
 */

define('TW_PLUGIN_VERSION', '1.1');
define('TW_PLUGIN_URL', plugins_url('', __FILE__));
define('TW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TEXT_DOMAIN', 'trinity-post-views');

/*
 * Load CSS file.
 */
add_action('admin_init', "add_my_css_and_my_js_files");

function add_my_css_and_my_js_files() {
    //wp_enqueue_script('your-script-name', $this->urlpath . '/your-script-filename.js', array('jquery'), '1.2.3', true);
    wp_enqueue_style('trinity-post-views', plugins_url('/css/trinity-post-views.css', __FILE__), false, '1.0.0', 'all');
}

/*
 * Load Translate file.
 */
add_action('plugins_loaded', 'tw_postviews_textdomain');

function tw_postviews_textdomain() {
    $locale = apply_filters('plugin_locale', get_locale(), 'trinity-post-views');

    load_textdomain('woocommerce-incremental-product-quantities', trailingslashit(WP_LANG_DIR) . 'trinity-post-views/trinity-post-views-' . $locale . '.mo');
    load_plugin_textdomain('trinity-post-views', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

/*
 * Checking if the version is supported.
 */
register_activation_hook(__FILE__, 'tw_function_activate');

function tw_function_activate() {
    global $wp_version;
    $versionRequired = '4.3';
    if (version_compare($wp_version, $versionRequired, '<')) {
        wp_die('Este plugin só é válido a partir da versão ' . $versionRequired . ' do wordpress. Favor Atualizar !!!');
    }
}

/*
 * Create the session to not duplicate the views/access.
 */
add_action('init', 'tw_session_start');
if (!function_exists('tw_session_start')) {

    function tw_session_start() {
        if (!session_id()):
            session_start();
        endif;
    }

}

/*
 *  Add key of the Post->ID
 */
add_action('publish_post', 'add_views_fields');
add_action('update_post', 'add_views_fields');

function add_views_fields($post_ID) {
    if (!wp_is_post_revision($post_ID)) {
        add_post_meta($post_ID, "tw_counter", 0, true);
    }
}

/*
 *  Delete key of the Post->ID
 */
add_action('delete_post', 'tw_delete_postmeta');

function tw_delete_postmeta($id) {
    delete_post_meta($id, "tw_counter");
}

/*
 *  Conta os views do post
 */
add_action('get_header', 'tw_count_post_views');

if (!function_exists('tw_count_post_views')) {

    function tw_count_post_views() {
        if (is_single() || is_page()) {
            remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
            global $post;
            $id = get_the_ID();
            //var_dump($id); die('pegou ou nao');
            if (empty($_SESSION['tw_counter_' . $id])) {

                $_SESSION['tw_counter_' . $id] = true;

                $key = "tw_counter";
                $key_value = get_post_meta($id, $key, true);

                if ($key_value >= 0) {
                    $key_value += 1;
                    update_post_meta($id, $key, $key_value);
                }
            }
        }
        return;
    }

}

/*
 * get the number views of the post
 */
if (!function_exists('tw_get_postview')) {

    function tw_get_postview($id) {
        $tw_post_counter = get_post_meta($id, "tw_counter", true);
        return $tw_post_counter;
    }

}

/*
 * print the number views of the post
 */
if (!function_exists('tw_the_postview')) {

    function tw_the_postview($id) {
        $tw_post_counter = get_post_meta($id, "tw_counter", true);
        echo $tw_post_counter;
    }

}

/*
 * load functions and addons
 */
require_once ( \TW_PLUGIN_DIR . 'includes/shortcode.php');
require_once ( \TW_PLUGIN_DIR . 'includes/widgets.php');
