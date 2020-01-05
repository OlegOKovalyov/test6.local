<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://test6.local/
 * @since      1.0.0
 *
 * @package    Urich_Tourbook
 * @subpackage Urich_Tourbook/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Urich_Tourbook
 * @subpackage Urich_Tourbook/includes
 * @author     Oleg Kovalyov <koa2003@ukr.net>
 */
class Urich_Tourbook_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'urich-tourbook',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
