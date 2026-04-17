<?php
/**
 * Main template file.
 *
 * @package EduCraft_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_title( '<h1 class="entry-title">', '</h1>' );
			the_content();
		}
	}
	?>
</main>

<?php
get_footer();
