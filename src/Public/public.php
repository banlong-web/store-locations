<?php

if ( ! class_exists( 'STL_WOO_PLUGIN_Public_Public' ) ) {
	class STL_WOO_PLUGIN_Public_Public {
		public function __construct() {
			add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'custom_woocommerce_product_meta_end' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'stl_wp_enqueue_scripts' ) );
			add_action( 'wp_ajax_show_store_locations', array( $this, 'show_store_locations' ) );
			add_action( 'wp_ajax_nopriv_show_store_locations', array( $this, 'show_store_locations' ) );
		}

		public function custom_woocommerce_product_meta_end() {
			global $product;
			$product_id = $product->get_id();
			$taxonomy   = 'store_locations_tax';
			$terms      = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'orderby'    => 'count', // Order by term count
				'order'      => 'DESC',   // Order in ascending order
			] );
			// $store_location_meta = get_post_meta($product_id, 'store_location_5cm', true);
			// $store_location_string = $store_location_meta['store_location'];
			// $store_locations = json_decode($store_location_string);
			if ( $terms ) { ?>
                <div class="ui container">
                    <div class="label-product header"><?php echo esc_html__( 'Cửa hàng còn sản phẩm này', 'store-loctions-5cm' ); ?></div>
                    <div class="content-select">
                        <select class="search tax_location_select" name="select_location"
                                data-product_id="<?php echo esc_attr( $product_id ); ?>">
                            <option value=""><?php echo esc_html__( 'Tỉnh thành', 'store-loctions-5cm' ); ?></option>
							<?php foreach ( $terms as $term ) : ?>
                                <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
							<?php endforeach; ?>
                        </select>
                        <div class="ui segment">
                            <div class="ui dimmer loader-content">
                                <div class="ui tiny text loader"><?php echo esc_html__( 'Loading', 'store-loctions-5cm' ); ?></div>
                            </div>
                            <div id="result_location">
                                <p class="warning-notice"><?php echo esc_html__( 'Bạn chưa chọn địa điểm', 'store-loctions-5cm' ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
		}

		public function show_store_locations() {
			$product_id            = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : '';
			$location              = isset( $_POST['location'] ) ? sanitize_text_field( $_POST['location'] ) : '';
			$store_location_meta   = get_post_meta( $product_id, 'store_location_5cm', true );
			$store_location_string = isset( $store_location_meta ) && $store_location_meta !== '' ? $store_location_meta['store_location'] : '';
			$store_locations       = json_decode( $store_location_string, true );
			$html                  = "";
			if ( $store_locations ) {
				$arr_name_location = [];
				$html              .= '<div class="ui list-store-locations list">';
				foreach ( $store_locations as $store_location ) {
					array_push( $arr_name_location, $store_location['location'] );
				}
				if ( in_array( $location, $arr_name_location ) ) {
					foreach ( $store_locations as $store_location ) {
						if ( $store_location['location'] == $location ) {
							$stock_html = '';
							if ( $store_location['stock'] == 0 ) {
								$stock_html = '<strong style="color: red">Hết hàng</strong>';
							} else {
								$stock_html = '<strong style="color: green">Còn hàng(' . $store_location['stock'] . ')</strong>';
							}
							$html .= '<div class="item">
                                <div class="content">
                                    <i class="map marker alternate icon"></i>
                                    <span class="name">' . $store_location['name'] . '</span>
                                </div>
                                <div class="content">
                                    <i class="phone icon"></i>
                                    <span class="phone">' . $store_location['phone'] . '</span>
                                </div>
                                <div class="content">
                                    <i class="road icon"></i>
                                    <span class="address">' . $store_location['address'] . '</span>
                                </div>
                                <div class="content">
                                    <i class="time icon"></i>
                                    <span class="timeStore">' . $stock_html . '</span>
                                </div>
                            </div>';
						}
					}
				} else {
					$html .= '<p class="warning-notice">Không có cửa hàng nào tại khu vực này</p>';
				}
				$html .= '</div>';
			}
			wp_send_json_success( $html );
		}

		public function stl_wp_enqueue_scripts() {
			wp_enqueue_style( 'stl-5cm-semantic-ui', STL_WOO_PLUGIN_DIR_CSS . 'semantic.min.css' );
			wp_enqueue_style( 'stl-5cm-icon-ui', STL_WOO_PLUGIN_DIR_CSS . 'icon.min.css' );
			wp_enqueue_style( 'select2-ui', STL_WOO_PLUGIN_DIR_CSS . 'select2.min.css' );
			wp_enqueue_style( 'stl-5cm-public', STL_WOO_PLUGIN_DIR_CSS . 'public.css' );
			wp_enqueue_script( 'stl-5cm-semantic-ui', STL_WOO_PLUGIN_DIR_JS . 'semantic.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'select2-ui', STL_WOO_PLUGIN_DIR_JS . 'select2.js', array( 'jquery' ) );
			wp_enqueue_script( 'stl-5cm-custom', STL_WOO_PLUGIN_DIR_JS . 'custom.js', array( 'jquery' ) );
			wp_localize_script( 'stl-5cm-custom', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}
	}
}
