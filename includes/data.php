<?php
/**
 * Class render default post metadata for this plugin
 * This is core of plugin
 */
if ( ! class_exists( 'VISE_DEFAULT_P_meta_data' ) ) {
	class VISE_DEFAULT_P_meta_data {
		private $default_data;

		public function __construct() {
			$this->default_data = array(
				'title'     => 'Search Everything Default',
				'meta_data' => array(
					'search_data'  => array
					(
						'post_type'              => array( 'post' ),
						'include_other_post'     => array(),
						'enable_date'            => 'off',
						'list_filter_date'       => '',
						'enable_author'          => 'off',
						'enable_meta'            => 'off',
						'enable_post_pass'       => 'off',
						'relation_query'         => 'OR',
						'include_post'           => array(),
						'exclude_post'           => array(),
						'taxonomies'    => array( 'post' => array( 'category', 'post_tag' ) ),
						'include_terms_selected' => array(),
						'exclude_terms_selected' => array(),
					),
					'general_data' => array
					(
						'use_ajax'            => 'off',
						'mobile_device'       => 'off',
						'enable_highlight'    => 'off',
						'post_per_page'       => '10',
						'order_posts'         => 'asc',
						'order_by'            => 'date',
						'cache_params_search' => 'off',
					),

					'design_data' => array
					(
						'layout_search'      => '',
						'droppable_data'     => '',
						'style_input_search' => array
						(
							'placeholder'             => '',
							'color_placeholder'       => '',
							'background_search_color' => '',
							'border_color'            => '',
						),

						'style_filter_item' => array
						(
							'enable_label'            => '',
							'background_filter_item'  => '',
							'font_size_dropdown'      => '',
							'color_text_filter'       => '',
							'color_label_filter_item' => '',
							'label_size'              => '',
							'font_weight_label'       => '',
							'font_transform_label'    => '',
						),
					),
				),
			);
		}

		public function vise_create_post_default() {
			$args_post   = array(
				'post_type'     => array( 'vi_se_wp' ),
				'post_per_page' => - 1
			);
			$post_exists = get_posts( $args_post );
			if ( ! $post_exists ) {
				$default_post = $this->default_data;
				$new_post_id  = wp_insert_post( array(
					'post_title'  => $default_post['title'],
					'post_status' => 'publish',
					'post_type'   => 'vi_se_wp',
					'meta_input'  => array( $default_post['meta_data'] )
				) );
				update_post_meta( $new_post_id, 'vise_search_form', $default_post['meta_data'] );
			}
		}
	}
}
