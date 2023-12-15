<?php

/**
 * Plugin Name: Tìm kiếm địa điểm cửa hàng cho sản phẩm của WooCommerce
 * Description: Tìm kiếm các cửa hàng với sản phẩm còn hàng trong cửa hàng.
 * Plugin URI: http://wordpress.org/plugins/store-locations/
 * Author: 5ChauTeamDev
 * Version: 1.0.0
 * Text Domain: store-loctions-5cm
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

define('STL_WOO_PLUGIN_VERSION', '1.0.0');
define('STL_WOO_PLUGIN_PATH', plugin_dir_path(__FILE__));
// define('STL_PLUGIN_DIR', WP_PLUGIN_DIR);

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (!is_plugin_active('woocommerce/woocommerce.php')) {
    add_action('admin_notices', function () {
        echo 'Bạn cần phải cài đặt plugin WooCommerce!';
    });
}

register_activation_hook(__FILE__, 'activation_hook');
register_deactivation_hook(__FILE__, 'deactivation_hook');
function activation_hook()
{
    flush_rewrite_rules();
}

function deactivation_hook()
{
    flush_rewrite_rules();
}
require_once STL_WOO_PLUGIN_PATH . '/includes/define.php';
if (!class_exists('STL_Woo_Setting')) {
    class STL_Woo_Setting
    {
        public function __construct()
        {
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'stl_woo_setting_links']);
        }

        public function stl_woo_setting_links($links)
        {
            $urlSetting    = get_admin_url() . "edit-tags.php?taxonomy=store_locations_tax&post_type=product";
            $setting_links = '<a href="' . $urlSetting . '">' . esc_html__('Settings', 'store-loctions-5cm') . '</a>';
            array_push($links, $setting_links);

            return $links;
        }
    }

    new STL_Woo_Setting();
}
