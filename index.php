<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package Sport_Theme
 */

get_header();
?>

	<main id="primary" class="site-main container">

		<?php
		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				// Include il template part per visualizzare il contenuto (es. in base al post format).
				// Per semplicità qui creiamo un output diretto.
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div><!-- .entry-content -->
                </article>
                <?php
			endwhile;

		else :
			echo '<p>Nessun contenuto trovato.</p>';

		endif;
		?>

	</main><!-- #primary -->

<?php
get_footer();
