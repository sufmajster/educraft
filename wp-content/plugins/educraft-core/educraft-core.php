<?php
/**
 * Plugin Name: EduCraft Core
 * Plugin URI: https://educraft.local
 * Description: Core functionality for the EduCraft recruitment task (CPT, WooCommerce hooks, etc.).
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Mateusz Sufa
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: educraft-core
 *
 * @package EduCraft_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EDUCRAFT_CORE_VERSION', '1.0.0' );
define( 'EDUCRAFT_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'EDUCRAFT_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once EDUCRAFT_CORE_PATH . 'src/Core/Plugin.php';
require_once EDUCRAFT_CORE_PATH . 'src/PostTypes/CaseStudyPostType.php';
require_once EDUCRAFT_CORE_PATH . 'src/Taxonomies/IndustryTaxonomy.php';
require_once EDUCRAFT_CORE_PATH . 'src/Ajax/CaseStudyFilterAjax.php';
require_once EDUCRAFT_CORE_PATH . 'src/WooCommerce/CheckoutFields.php';

$educraft_core = new Plugin();
$educraft_core->init();
