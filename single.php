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
        <div class="header-split">
            <!-- Dettagli Articolo -->
            <div class="article-meta-info">
                <div class="category-badge">NEWS</div>
                <span class="article-date"><?php echo get_the_date('d F Y'); ?></span>
                <h1 class="article-title text-white"><?php the_title(); ?></h1>
                
                <div class="article-author">
                    <span class="author-label">Scritto da</span>
                    <span class="author-name">AC Taverne</span>
                </div>
            </div>
            
            <!-- Immagine Articolo -->
            <div class="article-featured-image-wrapper">
                <div class="featured-image-card">
                    <img src="<?php echo esc_url( $hero_image_url ); ?>" alt="<?php the_title_attribute(); ?>">
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENUTO DELL'ARTICOLO -->
    <section class="single-content container">
        <div class="article-body-wrapper">
            <div class="entry-content text-white">
                <?php the_content(); ?>
            </div>

            <!-- CONDIVISIONE SOCIAL -->
            <div class="social-share-box">
                <h4 class="share-title text-white">Condividi l'articolo</h4>
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
                    <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" class="share-btn twitter-btn">
                        <i class="fa-brands fa-twitter"></i> Twitter
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ALTRE NEWS RELATE -->
    <section class="related-news-section container">
        <h2 class="section-title text-white">ALTRE NEWS</h2>
        
        <div class="news-grid grid-3">
            <?php
            $args_related = array(
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post__not_in'   => array($post_id),
            );
            $related_query = new WP_Query($args_related);
            
            if ($related_query->have_posts()) :
                while ($related_query->have_posts()) : $related_query->the_post();
                    $thumb_url = has_post_thumbnail()
                        ? get_the_post_thumbnail_url(get_the_ID(), 'medium_large')
                        : 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=600';
            ?>
            <div class="news-card cover-bg" style="background-image: url('<?php echo esc_url($thumb_url); ?>');">
                <div class="news-date"><?php echo get_the_date('d.m'); ?></div>
                <div class="news-content">
                    <h3 class="news-title text-white"><?php echo wp_trim_words(get_the_title(), 7, '...'); ?></h3>
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="btn-sm btn-primary" style="display:inline-block;">LEGGI ARTICOLO</a>
                </div>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="text-white">Nessuna altra news disponibile.</p>';
            endif;
            ?>
        </div>
    </section>

    <?php
    endwhile; // Fine ciclo WordPress.
    ?>

</main>

<?php
get_footer();
