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
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_nip_field' ), 10, 2 );
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

	public function validate_nip_field( array $data, WP_Error $errors ): void {
		if ( ! $this->cart_contains_b2b_product() ) {
			return;
		}

		$raw = isset( $data['billing_nip'] ) ? (string) $data['billing_nip'] : '';
		$nip = preg_replace( '/[\s\-]/', '', $raw );

		if ( ! is_string( $nip ) ) {
			$nip = '';
		}

		if ( '' === $nip ) {
			$errors->add( 'billing_nip', __( 'Pole NIP jest wymagane.', 'educraft-core' ) );
			return;
		}

		if ( ! $this->is_valid_nip( $nip ) ) {
			$errors->add( 'billing_nip', __( 'Podano nieprawidłowy numer NIP.', 'educraft-core' ) );
		}
	}

	private function is_valid_nip( string $nip ): bool {
		if ( 10 !== strlen( $nip ) || ! ctype_digit( $nip ) ) {
			return false;
		}

		$weights = array( 6, 5, 7, 2, 3, 4, 5, 6, 7 );
		$sum     = 0;

		for ( $i = 0; $i < 9; $i++ ) {
			$sum += (int) $nip[ $i ] * $weights[ $i ];
		}

		$checksum = $sum % 11;

		if ( 10 === $checksum ) {
			return false;
		}

		return (int) $nip[9] === $checksum;
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
