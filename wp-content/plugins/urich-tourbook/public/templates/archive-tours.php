<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">

	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</header><!-- .page-header -->
	<?php endif; ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :
			?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
            ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php
                    if ( is_sticky() && is_home() ) :
                        echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
                    endif;
                    ?>
                    <header class="entry-header">
                        <?php
                        if ( 'post' === get_post_type() ) {
                            echo '<div class="entry-meta">';
                            if ( is_single() ) {
                                twentyseventeen_posted_on();
                            } else {
                                echo twentyseventeen_time_link();
                                twentyseventeen_edit_link();
                            };
                            echo '</div><!-- .entry-meta -->';
                        };

                        if ( is_single() ) {
                            the_title( '<h1 class="entry-title">', '</h1>' );
                        } elseif ( is_front_page() && is_home() ) {
                            the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
                        } else {
                            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                        }
                        ?>
                    </header><!-- .entry-header -->

                    <?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
                            </a>
                        </div><!-- .post-thumbnail -->
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        /* translators: %s: Name of current post */
                        the_content(
                            sprintf(
                                __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
                                get_the_title()
                            )
                        );

                        wp_link_pages(
                            array(
                                'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
                                'after'       => '</div>',
                                'link_before' => '<span class="page-number">',
                                'link_after'  => '</span>',
                            )
                        );
                        ?>
                    </div><!-- .entry-content -->

                    <?php
                    if ( is_single() ) {
                        twentyseventeen_entry_footer();
                    }
                    ?>

                </article><!-- #post-<?php the_ID(); ?> -->

			<?php endwhile;

			the_posts_pagination(
				array(
					'prev_text'          => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
					'next_text'          => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
				)
			);

		else :

			get_template_part( 'template-parts/post/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php
get_footer();
