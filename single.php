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
        
        // Prendiamo l'immagine dell'articolo, altrimenti mettiamo un elegante fallback
        $hero_image_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
    ?>

    <!-- HERO ARTICOLO -->
    <section class="single-hero">
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php the_title_attribute(); ?>">
            
            <!-- Sfumatura a nero per amalgamare la foto al testo -->
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 80%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <div class="single-meta text-primary" style="font-weight: 700; letter-spacing: 2px; margin-bottom: 15px; font-size: 13px;">
                    <?php echo get_the_date('d.m.Y'); ?> — AC TAVERNE NEWS
                </div>
                <!-- Titolo Masiccio -->
                <h1 class="text-white" style="font-size: 50px; font-weight: 700; text-transform: uppercase; margin: 0; line-height: 1.1; max-width: 1000px;"><?php the_title(); ?></h1>
            </div>
        </div>
    </section>

    <!-- CONTENUTO DELL'ARTICOLO -->
    <!-- Max-width 800px centrato per far sì che il testo sia sempre comodissimo da leggere su desktop -->
    <section class="single-content ps-section container" style="max-width: 800px; margin: 0 auto; padding-top: 60px;">
        
        <div class="entry-content text-white" style="font-size: 17px; line-height: 1.8; color: #dddddd;">
            <?php the_content(); ?>
        </div>

        <!-- BLOCCO CONDIVISIONE SOCIAL NATIVA -->
        <div class="social-share" style="margin-top: 80px; padding-top: 40px; padding-bottom: 20px; border-top: 1px solid #333;">
            <h4 class="text-white" style="text-transform: uppercase; margin-bottom: 25px; font-weight: 700; font-size: 20px;">CONDIVIDI QUESTO ARTICOLO:</h4>
            
            <div class="share-buttons" style="display: flex; gap: 15px; flex-wrap: wrap;">
                <?php
                // Prepariamo i link dinamici per i social network
                $share_url = urlencode(get_permalink());
                $share_title = urlencode(get_the_title());
                ?>
                <!-- Pulsante Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="btn-sm btn-outline" style="display:inline-flex; align-items:center; gap:8px;">
                    <i class="fa-brands fa-facebook-f"></i> Facebook
                </a>
                
                <!-- Pulsante WhatsApp (Ottimo da mobile!) -->
                <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . ' ' . $share_url; ?>" target="_blank" class="btn-sm btn-outline" style="display:inline-flex; align-items:center; gap:8px;">
                    <i class="fa-brands fa-whatsapp"></i> WhatsApp
                </a>

                <!-- Pulsante X / Twitter -->
                <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" class="btn-sm btn-outline" style="display:inline-flex; align-items:center; gap:8px;">
                    <i class="fa-brands fa-twitter"></i> Twitter
                </a>
            </div>
        </div>

    </section>

    <?php
    endwhile; // Fine ciclo WordPress.
    ?>

</main>

<?php
get_footer();
