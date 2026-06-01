<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package Sport_Theme
 */

get_header();
?>
    <!-- HERO IMMAGINE (Sfocatura universale) -->
    <?php
    while ( have_posts() ) :
        the_post();
    ?>
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            // Unsplash fallback
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            
            <!-- La Famosa "Sfumatura verso il basso" che si fonde col nero -->
            <?php if ( is_page( 'club' ) || is_page( 'team' ) ) : ?>
                <div class="club-hero-fade"></div>
            <?php else : ?>
                <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            <?php endif; ?>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;"><?php the_title(); ?></h1>
                <?php if ( is_page( 'team' ) ) : ?>
                    <hr style="border: 0; border-top: 2px solid white; margin: 20px 0;">

                    <div class="page-submenu" style="display: flex; gap: 20px;">
                        <h4 style="margin: 0; display: inline-block;">
                            <a href="<?php echo esc_url( site_url('/rosa') ); ?>" class="btn-outline-hover" style="padding: 8px 40px; font-weight: 700; text-transform: uppercase; font-size: 22px; text-decoration: none; border: 2px solid white; background-color: transparent; color: white; transition: all 0.3s; display: inline-block;">ROSA</a>
                        </h4>
                        <h4 style="margin: 0; display: inline-block;">
                            <a href="<?php echo esc_url( site_url('/staff') ); ?>" class="btn-outline-hover" style="padding: 8px 40px; font-weight: 700; text-transform: uppercase; font-size: 22px; text-decoration: none; border: 2px solid white; background-color: transparent; color: white; transition: all 0.3s; display: inline-block;">STAFF</a>
                        </h4>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

	<main id="primary" class="site-main container" style="padding-top: 50px; padding-bottom: 50px;">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pagine:', 'sport-theme' ),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div><!-- .entry-content -->
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

<?php
get_footer();
