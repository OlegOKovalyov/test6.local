<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://test6.local/
 * @since             1.0.0
 * @package           Urich_Tourbook
 *
 * @wordpress-plugin
 * Plugin Name:       Urich Tourbook
 * Plugin URI:        https://urich.org/
 * Description:       Plugin for displaying 'tours' custom post type for Tours Booking System.
 * Version:           1.0.0
 * Author:            Oleg Kovalyov
 * Author URI:        http://test6.local/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       urich-tourbook
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'URICH_TOURBOOK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-urich-tourbook-activator.php
 */
function activate_urich_tourbook() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-urich-tourbook-activator.php';
	Urich_Tourbook_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-urich-tourbook-deactivator.php
 */
function deactivate_urich_tourbook() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-urich-tourbook-deactivator.php';
	Urich_Tourbook_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_urich_tourbook' );
register_deactivation_hook( __FILE__, 'deactivate_urich_tourbook' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-urich-tourbook.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_urich_tourbook() {

	$plugin = new Urich_Tourbook();
	$plugin->run();

}
run_urich_tourbook();
