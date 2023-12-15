<?php

if (!class_exists('STL_WOO_PLUGIN_Admin')) {
    class STL_WOO_PLUGIN_Admin
    {
        public function __construct()
        {
            add_action('init', array($this, 'inits'));
            add_action('admin_head', array($this, 'action_admin_head'));
            add_filter('woocommerce_product_data_tabs', array($this, 'add_custom_product_data_tab', 10, 1));
            add_action('woocommerce_product_data_panels', array($this, 'custom_product_data_tab_content'), 10, 0);
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
                'target'   => 'store_locations',
                'class'    => array('show_if_simple', 'show_if_variable'),
                'priority' => 40
            );
            return $tabs;
        }

        public function custom_product_data_tab_content()
        {
            global $post;
            
        }

        // Add CSS - icon
        public function action_admin_head()
        {
            echo '<style>
                #woocommerce-product-data ul.wc-tabs li.custom_tab_options a::before {
                    content: "\f230";
                } 
            </style>';
        }
    }
}
