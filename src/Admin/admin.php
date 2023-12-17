<?php

if (!class_exists('STL_WOO_PLUGIN_Admin')) {
    class STL_WOO_PLUGIN_Admin
    {
        public function __construct()
        {
            add_action('init', array($this, 'inits'));
            add_filter('woocommerce_product_data_tabs', array($this, 'add_custom_product_data_tab'));
            add_action('woocommerce_product_data_panels', array($this, 'custom_product_data_tab_content'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 99);
            add_action('save_post', array($this, 'store_location_save_custom_data'), 10, 3);
        }
        public function inits()
        {
            $this->load_plugin_textdomain();
            $this->register_taxonomy_store_locations();
        }

        public function load_plugin_textdomain()
        {
            $locale   = apply_filters('plugin_locale', determine_locale(), 'store-loctions-5cm');
            $basename = 'store-loctions-5cm';
            unload_textdomain('store-loctions-5cm');

            load_textdomain('store-loctions-5cm', WP_LANG_DIR . "/{$basename}/{$basename}-{$locale}.mo");
            load_plugin_textdomain('store-loctions-5cm', false, $basename . '/languages');
        }

        public function register_taxonomy_store_locations()
        {

            $labels = array(
                'name'                       => _x('Location', 'Store Location', 'store-loctions-5cm'),
                'singular_name'              => _x('Location', 'Location', 'store-loctions-5cm'),
                'menu_name'                  => __('Store Location', 'store-loctions-5cm'),
                'all_items'                  => __('All Locations', 'store-loctions-5cm'),
                'parent_item'                => __('Parent Location', 'store-loctions-5cm'),
                'parent_item_colon'          => __('Parent Location:', 'store-loctions-5cm'),
                'new_item_name'              => __('New Location Name', 'store-loctions-5cm'),
                'add_new_item'               => __('Add New Location', 'store-loctions-5cm'),
                'edit_item'                  => __('Edit Location', 'store-loctions-5cm'),
                'update_item'                => __('Update Location', 'store-loctions-5cm'),
                'separate_items_with_commas' => __('Separate Locations with commas', 'store-loctions-5cm'),
                'search_items'               => __('Search Locations', 'store-loctions-5cm'),
                'add_or_remove_items'        => __('Add or remove Locations', 'store-loctions-5cm'),
                'choose_from_most_used'      => __('Choose from the most used Locations', 'store-loctions-5cm'),
            );

            $rewrite = array(
                'slug'                       => 'store_locations_tax',
                'with_front'                 => true,
                'hierarchical'               => true,
            );

            $args = array(
                'labels'                     => $labels,
                'hierarchical'               => true,
                'public'                     => true,
                'show_ui'                    => true,
                'show_admin_column'          => true,
                'show_in_nav_menus'          => true,
                'show_tagcloud'              => true,
                'rewrite'                    => $rewrite,
            );

            register_taxonomy('store_locations_tax', 'product', $args);
        }

        public function add_custom_product_data_tab($tabs)
        {
            $tabs['custom_tab'] = array(
                'label'    => __('Store Locations', 'store-loctions-5cm'),
                'target'   => 'custom_store_tab',
                'class'    => array('show_if_simple', 'show_if_variable'),
                'priority' => 40
            );
            return $tabs;
        }

        public function custom_product_data_tab_content()
        {
            global $woocommerce, $post;
            $taxonomy = 'store_locations_tax';
            $product = wc_get_product($post->ID);
            $qty_product = '';
            if ($product->managing_stock()) {
                // Get stock quantity
                $qty_product = $product->get_stock_quantity();
            }
            $terms = wp_get_post_terms($post->ID, $taxonomy) ?>
            <div id="custom_store_tab" class="panel woocommerce_options_panel">
                <?php
                if (!empty($terms)) { ?>
                    <div class="ui header"><?php echo esc_html__('Thông tin các cửa hàng', 'store-loctions-5cm'); ?></div>
                    <div class="ui grid container">
                        <div class="row">
                            <div class="ui location-accordion styled fluid accordion">
                                <?php foreach ($terms as $term) { ?>
                                    <input type="hidden" name="tax_location_<?php echo esc_attr($term->slug); ?>" value="<?php echo esc_attr($term->slug); ?>">
                                    <div class="title">
                                        <i class="dropdown icon"></i>
                                        <?php echo $term->name; ?>
                                    </div>
                                    <div class="content">
                                        <div class="ui form">
                                            <div class="two fields">
                                                <div class="field">
                                                    <label><?php echo esc_html__('Tên cửa hàng', 'store-loctions-5cm'); ?></label>
                                                    <input type="text" name="store_location_name" value="" placeholder="<?php echo esc_attr__('Tên cửa hàng', 'store-loctions-5cm'); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php echo esc_html__('Địa chỉ', 'store-loctions-5cm'); ?></label>
                                                    <input type="text" name="store_location_address" value="" placeholder="<?php echo esc_attr__('Địa chỉ', 'store-loctions-5cm'); ?>">
                                                </div>
                                            </div>
                                            <div class="two fields">
                                                <div class="field">
                                                    <label><?php echo esc_html__('Số điện thoại', 'store-loctions-5cm'); ?></label>
                                                    <input type="text" name="store_location_phone" value="" placeholder="<?php echo '0123456789'; ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php echo esc_html__('Số lượng trong kho', 'store-loctions-5cm'); ?></label>
                                                    <input type="number" name="store_location_stock" value="<?php if(!empty($qty_product)) echo $qty_product; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <button class="ui right add-store positive button">
                                            <i class="plus icon"></i>
                                            <?php echo esc_html__('Thêm Cửa Hàng', 'store-loctions-5cm'); ?>
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php 
                } else { ?>
                    <p class="warning-notice"> <?php echo esc_html__('Bạn phải chọn Địa điểm của cửa hàng trước tiên!', 'store-loctions-5cm'); ?> </p>
                <?php }
                ?>
            </div>
        <?php
        }

        public function store_location_save_custom_data($post_id, $post, $update)
        {
           
        }
        public function admin_enqueue_scripts()
        {
            $current_screen = get_current_screen()->id;
            if ('product' !== $current_screen) {
                return;
            }

            wp_enqueue_style('stl-5cm-semantic-ui', STL_WOO_PLUGIN_DIR_CSS . 'semantic.min.css');
            wp_enqueue_style('stl-5cm-icon-ui', STL_WOO_PLUGIN_DIR_CSS . 'icon.min.css');
            wp_enqueue_style('stl-5cm-admin', STL_WOO_PLUGIN_DIR_CSS . 'admin.css');
            wp_enqueue_script('stl-5cm-semantic-ui', STL_WOO_PLUGIN_DIR_JS . 'semantic.min.js', array('jquery'));
            wp_enqueue_script('stl-5cm-admin', STL_WOO_PLUGIN_DIR_JS . 'admin.js', array('jquery'), false);
        }
    }
}
