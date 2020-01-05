<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://test6.local/
 * @since      1.0.0
 *
 * @package    Urich_Tourbook
 * @subpackage Urich_Tourbook/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Urich_Tourbook
 * @subpackage Urich_Tourbook/admin
 * @author     Oleg Kovalyov <koa2003@ukr.net>
 */
class Urich_Tourbook_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Urich_Tourbook_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Urich_Tourbook_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/urich-tourbook-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Urich_Tourbook_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Urich_Tourbook_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/urich-tourbook-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Creates a new custom post type 'tours'
     *
     * @since 1.0.0
     * @access public
     * @uses register_post_type()
     */
    public static function urich_tourbook_post_type() {
        $cap_type = 'post';
        $plural = 'Tours';
        $single = 'Tour';
        $cpt_name = 'tours';
        $opts['can_export'] = TRUE;
        $opts['capability_type'] = $cap_type;
        $opts['description'] = 'Booking Tour Custom Post Type';
        $opts['exclude_from_search'] = FALSE;
        $opts['has_archive'] = TRUE;
        $opts['hierarchical'] = FALSE;
        $opts['map_meta_cap'] = TRUE;
        $opts['menu_icon'] = 'dashicons-palmtree';
        $opts['menu_position'] = 20;
        $opts['public'] = TRUE;
        $opts['publicly_querable'] = TRUE;
        $opts['query_var'] = TRUE;
        $opts['register_meta_box_cb'] = '';
        $opts['rewrite'] = TRUE;
        $opts['show_in_admin_bar'] = TRUE;
        $opts['show_in_menu'] = TRUE;
        $opts['show_in_nav_menu'] = TRUE;
        $opts['supports'] = ['title', 'editor', 'thumbnail', 'revisions', 'post-formats'];

        $opts['labels']['add_new'] = esc_html__( "Add New {$single}", 'urich-tourbook' );
        $opts['labels']['add_new_item'] = esc_html__( "Add New {$single}", 'urich-tourbook' );
        $opts['labels']['all_items'] = esc_html__( $plural, 'urich-tourbook' );
        $opts['labels']['edit_item'] = esc_html__( "Edit {$single}" , 'urich-tourbook' );
        $opts['labels']['menu_name'] = esc_html__( $plural, 'urich-tourbook' );
        $opts['labels']['name'] = esc_html__( $plural, 'urich-tourbook' );
        $opts['labels']['name_admin_bar'] = esc_html__( $single, 'urich-tourbook' );
        $opts['labels']['new_item'] = esc_html__( "New {$single}", 'urich-tourbook' );
        $opts['labels']['not_found'] = esc_html__( "No {$plural} Found", 'urich-tourbook' );
        $opts['labels']['not_found_in_trash'] = esc_html__( "No {$plural} Found in Trash", 'urich-tourbook' );
        $opts['labels']['parent_item_colon'] = esc_html__( "Parent {$plural} :", 'urich-tourbook' );
        $opts['labels']['search_items'] = esc_html__( "Search {$plural}", 'urich-tourbook' );
        $opts['labels']['singular_name'] = esc_html__( $single, 'urich-tourbook' );
        $opts['labels']['view_item'] = esc_html__( "View {$single}", 'urich-tourbook' );
        register_post_type( strtolower( $cpt_name ), $opts );
    }

    /**
     * Sets new columns for custom post type 'Tours' list of admin page
     *
     * @param $columns
     * @return mixed
     *
     * @since 1.0.0
     * @access public
     */
    public function urich_tourbook_set_columns( $columns ) {
        $columns['tour_country'] = __( 'Tour Country', 'urich-tourbook' );
        $columns['tour_start_date'] = __( 'Tour Start', 'urich-tourbook' );
        $columns['tour_end_date'] = __( 'Tour End', 'urich-tourbook' );
        $columns['tour_price'] = __( 'Tour Price', 'urich-tourbook' );
        unset($columns['comments']);
        unset($columns['date']);
        return $columns;
    }

    /**
     * Gets new columns values from DB `ur_postmeta` table
     *
     * @param $column
     * @param $tour_id
     *
     * @since 1.0.0
     * @access public
     */
    public function urich_tourbook_column( $column, $tour_id ) {
        switch ( $column ) {
            case 'tour_country' :
                $urich_country = get_post_meta( $tour_id, '_urich_country', true );
                if ( is_string( $urich_country ) )
                    echo $urich_country;
                else
                    _e( 'Unable to get country(ies)', 'urich-tourbook' );
                break;
            case 'tour_start_date' :
                $tour_start_date = get_post_meta( $tour_id , '_urich_start_date' , true );
                echo mysql2date('d M Y', $tour_start_date );
                break;
            case 'tour_end_date' :
                $tour_end_date = get_post_meta( $tour_id , '_urich_end_date' , true );
                echo mysql2date('d M Y', $tour_end_date );
                break;
            case 'tour_price' :
                echo '$ ' .  get_post_meta( $tour_id , '_urich_price' , true );
                break;
        }
    }

    /**
     * Makes sortable new columns for custom post type 'Tours' list of admin page
     *
     * @param $columns
     * @return mixed
     *
     * @since 1.0.0
     * @access public
     */
    public function urich_tourbook_sortable_columns( $columns ) {
        $columns['tour_country'] = 'tour_country';
        $columns['tour_price'] =  'tour_price';
        $columns['tour_start_date'] =  'tour_start_date';
        $columns['tour_end_date'] =  'tour_end_date';
        return $columns;
    }

    /**
     * Sets order rules for new sortable columns
     *
     * @param $query
     *
     * @since 1.0.0
     * @access public
     */
    public function urich_tourbook_orderby( $query ) {
        if( ! is_admin() )
            return;
        $orderby = $query->get( 'orderby' );
        if( 'tour_country' == $orderby ) {
            $query->set('meta_key','_urich_country');
            $query->set('orderby','meta_value');
        }
        if( 'tour_price' == $orderby ) {
            $query->set('meta_key','_urich_price');
            $query->set('orderby','meta_value_num');
        }
        if( 'tour_start_date' == $orderby ) {
            $query->set('meta_key','_urich_start_date');
            $query->set('orderby','meta_value');
        }
        if( 'tour_end_date' == $orderby ) {
            $query->set('meta_key','_urich_end_date');
            $query->set('orderby','meta_value');
        }
    }

}
