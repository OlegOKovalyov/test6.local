<?php
/**
 * Widget API: WP_Widget_Urich_Tourbook_Recent_Tours class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Core class used to implement a Urich Booking Recent Tours widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class Urich_Tourbook_Recent_Tours  extends WP_Widget {

    /**
     * Sets up a new Recent Tours widget instance.
     *
     * @since 2.8.0
     */
    public function __construct() {
        parent::__construct(
            'urich_tourbook_recent_tours', // Base ID
            esc_html__( 'Recent Tours', 'urich-tourbook' ), // Name
            array( 'description' => esc_html__( 'Urich Booking Recent Tours', 'urich-tourbook' ) ) // Args
        );

        add_action( 'save_post', array(&$this, 'flush_widget_cache') );
        add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
        add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
    }

    /**
     * Outputs the content for the current Recent Tours widget instance.
     *
     * @since 2.8.0
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Recent Tours widget instance.
     */
    function widget($args, $instance) {
        $cache = wp_cache_get('widget_urich_recent_tours', 'widget');

        if ( ! is_array($cache) )
            $cache = array();

        if ( isset($cache[$args['widget_id']]) ) {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? __('Urich Recent Tours') : $instance['title'], $instance, $this->id_base);
        if ( ! $number = (int) $instance['number'] )
            $number = 10;
        else if ( $number < 1 )
            $number = 1;
        else if ( $number > 15 )
            $number = 15;

        /**
         * Filters the arguments for the Recent Tours widget.
         *
         * @since 3.4.0
         * @since 4.9.0 Added the `$instance` parameter.
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args     An array of arguments used to retrieve the recent tours.
         * @param array $instance Array of settings for the current widget.
         */
        $r = new WP_Query( array(
            'posts_per_page' => $number,
            'no_found_rows' => true,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
            'post_type' => array('tours')
        ));
        if ($r->have_posts()) :
            ?>
            <?php echo $before_widget; ?>
            <?php if ( $title ) echo $before_title . $title . $after_title; ?>
            <ul>
                <?php  while ($r->have_posts()) : $r->the_post(); ?>
                    <li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
                <?php endwhile; ?>
            </ul>
            <?php echo $after_widget; ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_urich_recent_tours', $cache, 'widget');
    }

    /**
     * Handles updating the settings for the current Recent Tours widget instance.
     *
     * @since 2.8.0
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_urich_recent_entries']) )
            delete_option('widget_urich_recent_entries');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_urich_recent_tours', 'widget');
    }

    /**
     * Outputs the settings form for the Recent Tours widget.
     *
     * @since 2.8.0
     *
     * @param array $instance   Current settings.
     */
    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        if ( ! isset($instance['number']) || !$number = (int) $instance['number'] )
            $number = 5;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        <?php
    }

    /**
     * Registers the 'Urich Tourbook Recent Tours' widget.
     */
    public function urich_tourbook_register_widget() {
        register_widget( 'Urich_Tourbook_Recent_Tours' );
    }

}