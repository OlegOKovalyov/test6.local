<?php
/**
 * This class is responsible of the 'Urich Tourbook Booking Data' meta box.
 *
 * In particular, it's responsible of displaying the meta box and
 * saving the value(s) its form contains.
 *
 * @since      1.0.0
 * @package    Urich_Tourbook
 * @subpackage Urich_Tourbook/partials
 * @author     Oleg Kovalyov <koa2003@ukr.net>
 */

class Urich_Tourbook_All_Meta_Boxes
{
    /**
     * Displays the meta box.
     *
     * @param  WP_Post   $tour   The object for the current post/page.
     *
     * @since    1.0.0
     */
    public function display( $tour ) {
        $tour_id = $tour->ID;
        $urich_country = get_post_meta( $tour_id, '_urich_country', true );
        $urich_start_date = get_post_meta( $tour_id, '_urich_start_date', true );
        $urich_end_date = get_post_meta( $tour_id, '_urich_end_date', true );
        $urich_price = get_post_meta( $tour_id, '_urich_price', true );
        include plugin_dir_path( __FILE__ ) . 'partials/urich-tourbook-all-meta-boxes.templ.php';
    }

    /**
     * Registers the meta box and makes it available in the Post Editor display.
     *
     * @param  WP_Post   $tour   The object for the current post/page.
     *
     * @since    1.0.0
     */
    public function register() {
        add_meta_box(
            'urich-tour-all-meta-boxes',
            __('Urich Tour Booking Data', 'urich-tourbook'),
            array( $this, 'display' ),
            'tours',
            'side'
        );
    }

    /**
     * Saves all the fields displayed in the meta box.
     *
     * @param  int   $tour_id   The ID of the post that's about to be saved.
     *
     * @since    1.0.0
     */
    public function save( $tour_id, $tour ) {
        if ( 'tours' != $tour->post_type ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! isset( $_POST['urich_data'] ) || ! wp_verify_nonce( $_POST['urich_data'], 'set_urich_data' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $tour_id ) ){
            return;
        }

        if ( isset( $_POST['urich-country'] ) && $_POST['urich-country'] != '' ) {
            $urich_country = trim( sanitize_text_field( $_POST['urich-country'] ) );
            update_post_meta( $tour_id, '_urich_country', $urich_country );
        }

        if ( isset( $_POST['urich-start-date'] ) && $_POST['urich-start-date'] != '' ) {
            $urich_start_date = trim( sanitize_text_field( $_POST['urich-start-date'] ) );
            update_post_meta( $tour_id, '_urich_start_date', $urich_start_date );
        }
        if ( isset( $_POST['urich-end-date'] ) && $_POST['urich-end-date'] != '' ) {
            $urich_end_date = trim(sanitize_text_field($_POST['urich-end-date']));
            update_post_meta( $tour_id, '_urich_end_date', $urich_end_date );
        }

        if ( isset( $_POST['urich-price'] ) && $_POST['urich-price'] != '' ) {
            $urich_price = trim(sanitize_text_field($_POST['urich-price']));
            update_post_meta( $tour_id, '_urich_price', $urich_price );
        }
    }



}