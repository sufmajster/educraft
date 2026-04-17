<?php
/**
 * Additional Checkout Fields (Checkout Block) and B2B NIP logic.
 *
 * @package EduCraft_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CheckoutFields {

	private static bool $checkout_field_registered = false;

	private static bool $cart_extension_registered = false;

	public function register_hooks(): void {
		add_action( 'woocommerce_init', array( $this, 'register_additional_checkout_field' ), 20 );
		add_action( 'woocommerce_blocks_loaded', array( $this, 'register_cart_extension' ) );
	}

	public function register_additional_checkout_field(): void {
		if ( self::$checkout_field_registered ) {
			return;
		}

		if ( ! function_exists( 'woocommerce_register_additional_checkout_field' ) ) {
			return;
		}

		woocommerce_register_additional_checkout_field(
			array(
				'id'                => 'educraft-core/billing-nip',
				'label'             => __( 'NIP', 'educraft-core' ),
				'optionalLabel'     => __( 'NIP (opcjonalnie)', 'educraft-core' ),
				'location'          => 'order',
				'type'              => 'text',
				'required'          => $this->get_nip_required_schema(),
				'hidden'            => $this->get_nip_hidden_schema(),
				'attributes'        => array(
					'maxLength' => 15,
				),
				'sanitize_callback' => array( $this, 'sanitize_nip_value' ),
				'validate_callback' => array( $this, 'validate_nip_value' ),
			)
		);

		self::$checkout_field_registered = true;
	}

	public function register_cart_extension(): void {
		if ( self::$cart_extension_registered ) {
			return;
		}

		if ( ! function_exists( 'woocommerce_store_api_register_endpoint_data' ) ) {
			return;
		}

		if ( ! class_exists( \Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema::class ) ) {
			return;
		}

		woocommerce_store_api_register_endpoint_data(
			array(
				'endpoint'        => \Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema::IDENTIFIER,
				'namespace'       => 'educraft-core',
				'data_callback'   => array( $this, 'get_cart_extension_data' ),
				'schema_callback' => array( $this, 'get_cart_extension_schema' ),
				'schema_type'     => ARRAY_A,
			)
		);

		self::$cart_extension_registered = true;
	}

	public function get_cart_extension_data(): array {
		return array(
			'has_b2b' => $this->cart_contains_b2b_product(),
		);
	}

	public function get_cart_extension_schema(): array {
		return array(
			'has_b2b' => array(
				'description' => __( 'Whether the cart contains a B2B product.', 'educraft-core' ),
				'type'        => 'boolean',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
			),
		);
	}

	public function sanitize_nip_value( $value ): string {
		if ( ! is_string( $value ) ) {
			return '';
		}

		return (string) preg_replace( '/[\s\-]/', '', $value );
	}

	public function validate_nip_value( $value ) {
		if ( ! $this->cart_contains_b2b_product() ) {
			return true;
		}

		$nip = is_string( $value ) ? $value : '';

		if ( '' === $nip ) {
			return new WP_Error(
				'educraft_nip_required',
				__( 'Pole NIP jest wymagane.', 'educraft-core' )
			);
		}

		if ( ! $this->is_valid_nip( $nip ) ) {
			return new WP_Error(
				'educraft_nip_invalid',
				__( 'Podano nieprawidłowy numer NIP.', 'educraft-core' )
			);
		}

		return true;
	}

	public static function get_nip_from_order( $order ): string {
		if ( ! $order instanceof \WC_Order ) {
			return '';
		}

		if ( class_exists( \Automattic\WooCommerce\Blocks\Package::class ) ) {
			$checkout_fields = \Automattic\WooCommerce\Blocks\Package::container()->get(
				\Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields::class
			);

			$value = $checkout_fields->get_field_from_object( 'educraft-core/billing-nip', $order, 'other' );

			return is_string( $value ) ? $value : '';
		}

		return (string) $order->get_meta( '_wc_other/educraft-core/billing-nip', true );
	}

	private function get_nip_required_schema(): array {
		return array(
			'type'       => 'object',
			'properties' => array(
				'cart' => array(
					'properties' => array(
						'extensions' => array(
							'properties' => array(
								'educraft-core' => array(
									'properties' => array(
										'has_b2b' => array(
											'const' => true,
										),
									),
								),
							),
						),
					),
				),
			),
		);
	}

	private function get_nip_hidden_schema(): array {
		return array(
			'type'       => 'object',
			'properties' => array(
				'cart' => array(
					'properties' => array(
						'extensions' => array(
							'properties' => array(
								'educraft-core' => array(
									'properties' => array(
										'has_b2b' => array(
											'const' => false,
										),
									),
								),
							),
						),
					),
				),
			),
		);
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

			$product = wc_get_product( $product_id );

			if ( ! $product instanceof WC_Product ) {
				continue;
			}

			$post_id_for_terms = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product_id;

			if ( has_term( 'b2b', 'product_cat', $post_id_for_terms ) ) {
				return true;
			}
		}

		return false;
	}
}
