<?php
/**
 * Case Study archive template.
 *
 * @package EduCraft_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$industry_terms = get_terms( 
	array(
		'taxonomy' => 'industry',
		'hide_empty' => false,
	)
);

if ( is_wp_error( $industry_terms ) ) {
	$industry_terms = array();
}
?>

<main id="primary" class="site-main case-study-archive">
	<header class="case-study-archive__header">
		<h1 class="case-study-archive__title"><?php esc_html_e( 'Case study', 'educraft-theme' ); ?></h1>
	</header>
	<div class="case-study-archive__filters">
		<label for="case-study-industry-filter"><?php esc_html_e( 'Branża:', 'educraft-theme' ); ?></label>

		<select id="case-study-industry-filter" name="industry">
			<option value=""><?php esc_html_e( 'Wszystkie branże', 'educraft-theme' ); ?></option>

			<?php foreach ( $industry_terms as $industry ) : ?>
				<option value="<?php echo esc_attr( $industry->slug ); ?>">
					<?php echo esc_html( $industry->name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
	<div id="case-study-archive-results" class="case-study-archive__results">
		<?php if ( have_posts() ) : ?>
			<div class="case-study-archive__list">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/case-study/card' );
				}
				?>
			</div>
		<?php else : ?>
			<p class="case-study-archive__empty"><?php esc_html_e( 'Brak case studies do wyświetlenia.', 'educraft-theme' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
