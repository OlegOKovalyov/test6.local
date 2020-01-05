<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://test6.local/
 * @since      1.0.0
 *
 * @package    Urich_Tourbook
 * @subpackage Urich_Tourbook/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Urich_Tourbook
 * @subpackage Urich_Tourbook/public
 * @author     Oleg Kovalyov <koa2003@ukr.net>
 */
class Urich_Tourbook_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/urich-tourbook-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/urich-tourbook-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Modifies the content of the given post and appends Tour Data (if any).
     *
     * @param   string   $content   the post content.
     * @param   int      $id      the ID of the post.
     *
     * @return  string   the original content with Urich Tourbook' Tour Data appended (if any).
     *
     * @since    1.0.0
     */
    public function append_tour_data_before_content( $content ) {
        $id = get_the_ID();
        $tour_country = esc_attr( get_post_meta( $id, '_urich_country', true));
        $tour_start_date = esc_attr( get_post_meta( $id, '_urich_start_date', true ) );
        $tour_end_date = esc_attr( get_post_meta( $id, '_urich_end_date', true ) );
        $tour_price = esc_attr( get_post_meta( $id, '_urich_price', true ) );
        if ( is_singular('tours') ) {
            $beforecountry = $beforedate = $price = '';
            $tour_length = 0;
            if ( ! empty( $tour_country ) ) {
                if ( 'Cote Divoire' == $tour_country ) $tour_country = 'Cote D\'ivoire';
                if ( 'Korea, Democratic Peoples Republic of' == $tour_country ) $tour_country = 'Korea, Democratic People\'s Republic of';
                if ( 'Lao Peoples Democratic Republic' == $tour_country ) $tour_country = 'Lao People\'s Democratic Republic';
                 $beforecountry = '<p>Country: <strong>' . $tour_country . '</strong></p>';
            }
            if ( ! empty( $tour_start_date ) && ! empty( $tour_end_date ) ) {
                $datetime1 = date_create( $tour_start_date );
                $datetime2 = date_create( $tour_end_date );
                $datetime2->modify('+1 day');
                $tour_length = date_diff( $datetime1, $datetime2 );
                $beforedate = '<p style="font-size: 1rem;"><span style="text-decoration: underline;">' . mysql2date('d M Y', $tour_start_date ) . '</span> ';
                $beforedate .= ' - <span style="text-decoration: underline;"> ' . mysql2date('d M Y', $tour_end_date ) . ' </span> ';

                $beforedate .= ' <span style="font-size: 0.8rem;"> ( ' . $tour_length->format( '%a days' ) . ' ) </span></p>';
            }
            if ( ! empty( $tour_price ) ) {
                $price = '<p>Tour price: <strong>' . '$ ' . number_format_i18n( $tour_price ) . '</strong> per person.</p>';
            }
            $content = $beforecountry . $beforedate . $content . $price;
        }
        return $content;
    }

    /**
     * Displaying Custom Post Types (CPT) 'tours on  the Front Page.
     *
     * @since    1.0.0
     */
    function add_urich_tourbook_to_query( $query ) {
        if (is_home() && $query->is_main_query())
            $query->set('post_type', array('post', 'tours'));
        return $query;
    }


    /**
     * Implements CPT 'tours' template.
     *
     * @return  string   $template_path  path to 'single-tours.php' file.
     *
     * @since    1.0.0
     */
    public function include_tours_template_function( $template ) {
        global $post;
        if ( 'tours' === $post->post_type ) {
            if ( is_single() ) {
                // checks if the file exists in the theme first, otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'single-tours.php' ) ) ) {
                    $template = $theme_file;
                } else {
                    $template = plugin_dir_path( __FILE__ ) . 'templates/single-tours.php';
                }
            }
            elseif ( is_archive() ) {
                // checks if the file exists in the theme first, otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'archive-tours.php' ) ) ) {
                       $template = $theme_file;
                } else {
                    $template = plugin_dir_path( __FILE__ ) . 'templates/archive-tours.php';
                }
            }
        }
        return $template;
    }


}