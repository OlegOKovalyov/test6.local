<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://test6.local/
 * @since      1.0.0
 *
 * @package    Urich_Tourbook
 * @subpackage Urich_Tourbook/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Urich_Tourbook
 * @subpackage Urich_Tourbook/includes
 * @author     Oleg Kovalyov <koa2003@ukr.net>
 */
class Urich_Tourbook {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Urich_Tourbook_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'URICH_TOURBOOK_VERSION' ) ) {
			$this->version = URICH_TOURBOOK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'urich-tourbook';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Urich_Tourbook_Loader. Orchestrates the hooks of the plugin.
	 * - Urich_Tourbook_i18n. Defines internationalization functionality.
	 * - Urich_Tourbook_Admin. Defines all hooks for the admin area.
	 * - Urich_Tourbook_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-urich-tourbook-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-urich-tourbook-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-urich-tourbook-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-urich-tourbook-public.php';

		$this->loader = new Urich_Tourbook_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Urich_Tourbook_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Urich_Tourbook_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Urich_Tourbook_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Init Custom Post Type (CPT) 'tours'
        $this->loader->add_action( 'init', $plugin_admin, 'urich_tourbook_post_type' );

        // Load the Urich Tourbook' All meta boxes class and create its hooks
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-all-meta-boxes.php';
        if( class_exists('Urich_Tourbook_All_Meta_Boxes' ) ) {
            $all_meta_boxes = new Urich_Tourbook_All_Meta_Boxes();
        }
        $this->loader->add_action( 'add_meta_boxes_tours', $all_meta_boxes, 'register' );
        $this->loader->add_action( 'save_post', $all_meta_boxes, 'save', 10 , 2 );

        // Display additional columns and make them sortable
        $this->loader->add_filter( 'manage_tours_posts_columns', $plugin_admin, 'urich_tourbook_set_columns' );
        $this->loader->add_action( 'manage_tours_posts_custom_column', $plugin_admin, 'urich_tourbook_column', 10, 2 );
        $this->loader->add_filter( 'manage_edit-tours_sortable_columns', $plugin_admin, 'urich_tourbook_sortable_columns' );
        $this->loader->add_action( 'pre_get_posts', $plugin_admin, 'urich_tourbook_orderby' );

        // Add Recent Tours Widget
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-urich-tourbook-recent-tours.php';
        if( class_exists('Urich_Tourbook_Recent_Tours' ) ) {
            $recent_tours = new Urich_Tourbook_Recent_Tours();
        }
        $this->loader->add_action( 'widgets_init', $recent_tours, 'urich_tourbook_register_widget');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Urich_Tourbook_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Display Urich Tourbook' Post Type 'tours' on the Front Page
        $this->loader->add_action( 'pre_get_posts', $plugin_public, 'add_urich_tourbook_to_query' );

        // Append Urich Tourbook' Tour Data on 'tours' single page
        $this->loader->add_filter( 'the_content', $plugin_public, 'append_tour_data_before_content', 10, 1 );
        // Register a function for CPT 'tours' dedicated templates
        $this->loader->add_filter( 'template_include', $plugin_public, 'include_tours_template_function', 1 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Urich_Tourbook_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
