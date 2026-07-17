<?php
/**
 * The template for displaying all single posts
 *
 * @package Sport_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-single">

    <?php
    while ( have_posts() ) :
        the_post();
        $post_id = get_the_ID();
        
        // Prendiamo l'immagine dell'articolo, altrimenti mettiamo un elegante fallback
        $hero_image_url = has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'large' ) : 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1200';
    ?>

    <!-- HERO ARTICOLO -->
    <header class="single-article-header container">
        <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="article-back-link">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            <span>Torna alle news</span>
        </a>

        <div class="article-kicker-row">
            <span class="category-badge">NEWS</span>
            <span class="article-date"><?php echo esc_html( get_the_date('d.m.Y') ); ?></span>
        </div>

        <h1 class="article-title text-white"><?php the_title(); ?></h1>

        <div class="article-author">
            <span class="author-label">AC Taverne</span>
            <span class="author-separator"></span>
            <span class="author-name">Prima Squadra</span>
        </div>

        <div class="article-featured-image-wrapper">
            <div class="featured-image-card">
                <img src="<?php echo esc_url( $hero_image_url ); ?>" alt="<?php the_title_attribute(); ?>">
            </div>
        </div>
    </header>

    <!-- CONTENUTO DELL'ARTICOLO -->
    <section class="single-content container">
        <div class="article-body-wrapper">
            <div class="entry-content text-white">
                <?php the_content(); ?>
            </div>

            <div class="social-share-box">
                <h4 class="share-title text-white">Condividi</h4>
                <div class="share-buttons">
                    <?php
                    $share_url = urlencode(get_permalink());
                    $share_title = urlencode(get_the_title());
                    ?>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="share-btn facebook-btn">
                        <i class="fa-brands fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . ' ' . $share_url; ?>" target="_blank" class="share-btn whatsapp-btn">
                        <i class="fa-brands fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://www.instagram.com/ac_taverne/" target="_blank" class="share-btn instagram-btn">
                        <i class="fa-brands fa-instagram"></i> Instagram
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php
    endwhile; // Fine ciclo WordPress.
    ?>

</main>

<?php
get_footer();
