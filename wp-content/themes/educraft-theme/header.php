<?php
/**
 * Theme header.
 *
 * @package EduCraft_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Przejdź do treści', 'educraft-theme' ); ?></a>

<header class="site-header" role="banner">
	<div class="site-header__inner">
		<a class="site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'EduCraft', 'educraft-theme' ); ?></a>

		<button
			type="button"
			class="site-header__toggle"
			aria-controls="primary-navigation"
			aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Otwórz lub zamknij menu', 'educraft-theme' ); ?>"
		>
			<span class="site-header__toggle-bars" aria-hidden="true"></span>
		</button>

		<nav id="primary-navigation" class="site-header__nav" role="navigation" aria-label="<?php esc_attr_e( 'Menu główne', 'educraft-theme' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'site-header__menu',
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>
	</div>
</header>
