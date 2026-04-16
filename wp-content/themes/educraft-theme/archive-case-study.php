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
?>

<main id="primary" class="site-main case-study-archive">
	<header class="case-study-archive__header">
		<h1 class="case-study-archive__title"><?php esc_html_e( 'Case study', 'educraft-theme' ); ?></h1>
	</header>

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
</main>

<?php
get_footer();
