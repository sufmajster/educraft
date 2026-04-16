<?php
/**
 * Case Study card for archive listings.
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

$client_name       = function_exists( 'get_field' ) ? get_field( 'client_name' ) : null;
$short_description = function_exists( 'get_field' ) ? get_field( 'short_description' ) : null;

$has_client  = is_string( $client_name ) && '' !== trim( $client_name );
$has_summary = is_string( $short_description ) && '' !== trim( $short_description );
$permalink   = get_permalink();
?>

<article <?php post_class( 'case-study-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php
		$thumbnail_id = get_post_thumbnail_id();
		$alt_text     = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );

		if ( ! is_string( $alt_text ) || '' === trim( $alt_text ) ) {
			$alt_text = get_the_title();
		}
		?>
		<figure class="case-study-card__media">
			<a href="<?php echo esc_url( $permalink ); ?>">
				<?php
				the_post_thumbnail(
					'medium',
					array(
						'class' => 'case-study-card__image',
						'alt'   => $alt_text,
					)
				);
				?>
			</a>
		</figure>
	<?php endif; ?>

	<header class="case-study-card__header">
		<h2 class="case-study-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a>
		</h2>
	</header>

	<?php if ( ! empty( $industries ) ) : ?>
		<ul class="case-study-card__industries">
			<?php foreach ( $industries as $industry ) : ?>
				<li><?php echo esc_html( $industry->name ); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( $has_client ) : ?>
		<p class="case-study-card__client"><?php echo esc_html( $client_name ); ?></p>
	<?php endif; ?>

	<?php if ( $has_summary ) : ?>
		<div class="case-study-card__summary">
			<?php echo wp_kses_post( wpautop( $short_description ) ); ?>
		</div>
	<?php endif; ?>

	<p class="case-study-card__more">
		<a href="<?php echo esc_url( $permalink ); ?>"><?php esc_html_e( 'Zobacz więcej', 'educraft-theme' ); ?></a>
	</p>
</article>
