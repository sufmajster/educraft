<?php
/**
 * Theme footer.
 *
 * @package EduCraft_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner">
		<p class="site-footer__brand"><?php esc_html_e( 'EduCraft', 'educraft-theme' ); ?></p>
		<p class="site-footer__tagline"><?php esc_html_e( 'Copyright by Mateusz Sufa © 2026', 'educraft-theme' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
