<?php
/**
 * Checkout-related WooCommerce logic.
 *
 * @package EduCraft_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CheckoutFields {

	public function register_hooks(): void {
		add_filter( 'woocommerce_checkout_fields', array( $this, 'add_nip_field_if_needed' ) );
	}

	public function add_nip_field_if_needed( array $fields ): array {
		if ( ! $this->cart_contains_b2b_product() ) {
			return $fields;
		}

		if ( ! isset( $fields['billing'] ) || ! is_array( $fields['billing'] ) ) {
			$fields['billing'] = array();
		}

		$fields['billing']['billing_nip'] = array(
			'type'        => 'text',
			'label'       => __( 'NIP', 'educraft-core' ),
			'required'    => true,
			'class'       => array( 'form-row-wide' ),
			'priority'    => 120,
		);

		return $fields;
	}

	private function cart_contains_b2b_product(): bool {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return false;
		}
	
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product_id = isset( $cart_item['product_id'] ) ? (int) $cart_item['product_id'] : 0;
	
			if ( ! $product_id ) {
				continue;
			}
	
			if ( has_term( 'b2b', 'product_cat', $product_id ) ) {
				return true;
			}
		}
	
		return false;
	}
}
