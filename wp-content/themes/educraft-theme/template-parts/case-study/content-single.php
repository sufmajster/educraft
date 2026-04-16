<?php
/**
 * Single Case Study content.
 *
 * @package EduCraft_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$industries = get_the_terms( get_the_ID(), 'industry' );

if ( is_wp_error( $industries ) ) {
	$industries = array();
}

$client_name          = function_exists( 'get_field' ) ? get_field( 'client_name' ) : null;
$short_description    = function_exists( 'get_field' ) ? get_field( 'short_description' ) : null;
$client_website_url   = function_exists( 'get_field' ) ? get_field( 'client_website_url' ) : null;
$gallery_image_fields = array( 'gallery_image_1', 'gallery_image_2', 'gallery_image_3' );
$gallery_items        = array();

foreach ( $gallery_image_fields as $field_name ) {
	if ( ! function_exists( 'get_field' ) ) {
		break;
	}

	$image = get_field( $field_name );

	if ( empty( $image ) ) {
		continue;
	}

	if ( is_array( $image ) && isset( $image['ID'] ) ) {
		$gallery_items[] = array(
			'type' => 'attachment',
			'id'   => (int) $image['ID'],
		);
	} elseif ( is_numeric( $image ) ) {
		$gallery_items[] = array(
			'type' => 'attachment',
			'id'   => (int) $image,
		);
	} elseif ( is_string( $image ) ) {
		$maybe_url = esc_url_raw( $image );

		if ( '' !== $maybe_url ) {
			$gallery_items[] = array(
				'type' => 'url',
				'url'  => $maybe_url,
			);
		}
	}
}

$has_client_link = is_string( $client_website_url ) && '' !== trim( $client_website_url );
$client_url      = $has_client_link ? esc_url( $client_website_url ) : '';
$has_client_name = is_string( $client_name ) && '' !== trim( $client_name );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'case-study' ); ?>>
	<header class="case-study__header">
		<?php the_title( '<h1 class="case-study__title">', '</h1>' ); ?>
	</header>

	<?php if ( ! empty( $industries ) ) : ?>
		<section class="case-study__industries" aria-label="<?php esc_attr_e( 'Branże', 'educraft-theme' ); ?>">
			<h2 class="case-study__section-title"><?php esc_html_e( 'Branże', 'educraft-theme' ); ?></h2>
			<ul class="case-study__industry-list">
				<?php foreach ( $industries as $industry ) : ?>
					<li><?php echo esc_html( $industry->name ); ?></li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

	<?php if ( $has_client_name ) : ?>
		<section class="case-study__client" aria-label="<?php esc_attr_e( 'Klient', 'educraft-theme' ); ?>">
			<h2 class="case-study__section-title"><?php esc_html_e( 'Klient', 'educraft-theme' ); ?></h2>
			<p class="case-study__client-name"><?php echo esc_html( $client_name ); ?></p>
		</section>
	<?php endif; ?>

	<?php if ( has_post_thumbnail() ) : ?>
		<?php
		$thumbnail_id = get_post_thumbnail_id();
		$alt_text     = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );

		if ( ! is_string( $alt_text ) || '' === trim( $alt_text ) ) {
			$alt_text = get_the_title();
		}
		?>
		<figure class="case-study__featured-image">
			<?php
			the_post_thumbnail(
				'large',
				array(
					'class' => 'case-study__featured-image-img',
					'alt'   => $alt_text,
				)
			);
			?>
		</figure>
	<?php endif; ?>

	<?php if ( is_string( $short_description ) && '' !== trim( $short_description ) ) : ?>
		<section class="case-study__summary" aria-label="<?php esc_attr_e( 'Krótki opis', 'educraft-theme' ); ?>">
			<h2 class="case-study__section-title"><?php esc_html_e( 'Krótki opis', 'educraft-theme' ); ?></h2>
			<div class="case-study__summary-text">
				<?php echo wp_kses_post( wpautop( $short_description ) ); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$content = get_post_field( 'post_content', get_the_ID() );
	if ( is_string( $content ) && '' !== trim( $content ) ) :
		?>
		<section class="case-study__content" aria-label="<?php esc_attr_e( 'Szczegóły', 'educraft-theme' ); ?>">
			<h2 class="case-study__section-title"><?php esc_html_e( 'Szczegóły', 'educraft-theme' ); ?></h2>
			<div class="case-study__entry-content">
				<?php the_content(); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $has_client_link && '' !== $client_url ) : ?>
		<section class="case-study__client-link" aria-label="<?php esc_attr_e( 'Strona klienta', 'educraft-theme' ); ?>">
			<h2 class="case-study__section-title"><?php esc_html_e( 'Strona klienta', 'educraft-theme' ); ?></h2>
			<p>
				<a href="<?php echo esc_url( $client_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Odwiedź stronę klienta', 'educraft-theme' ); ?>
				</a>
			</p>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $gallery_items ) ) : ?>
		<section class="case-study__gallery" aria-label="<?php esc_attr_e( 'Galeria', 'educraft-theme' ); ?>">
			<h2 class="case-study__section-title"><?php esc_html_e( 'Galeria', 'educraft-theme' ); ?></h2>
			<div class="case-study__gallery-grid">
				<?php
				foreach ( $gallery_items as $item ) {
					if ( 'attachment' === $item['type'] ) {
						echo wp_get_attachment_image(
							$item['id'],
							'medium',
							false,
							array(
								'class'   => 'case-study__gallery-image',
								'loading' => 'lazy',
							)
						);
					} elseif ( 'url' === $item['type'] && ! empty( $item['url'] ) ) {
						printf(
							'<img class="case-study__gallery-image" src="%1$s" alt="" loading="lazy" decoding="async" />',
							esc_url( $item['url'] )
						);
					}
				}
				?>
			</div>
		</section>
	<?php endif; ?>
</article>
