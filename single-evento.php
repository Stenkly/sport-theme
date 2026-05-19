<?php
/**
 * Single Event Template
 * 
 * @package Sport_Theme
 */

// Scegli l'header corretto in base alla categoria dell'evento
if ( has_category('settore-giovanile') ) {
    get_header('societa');
} else {
    get_header();
}

$data_raw = get_post_meta(get_the_ID(), '_data_evento', true);
$ora      = get_post_meta(get_the_ID(), '_ora_evento', true);
$luogo    = get_post_meta(get_the_ID(), '_luogo_evento', true);
$tipo     = get_post_meta(get_the_ID(), '_tipo_evento', true);

$data_format = $data_raw ? date('d/m/Y', strtotime($data_raw)) : '';
$is_past = $data_raw && (date('Y-m-d') > $data_raw);
?>

<main id="primary" class="site-main single-evento">

    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh; min-height: 400px;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 70%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 30px;">
                <div style="background-color: var(--c-primary); color: #000; display: inline-block; padding: 5px 15px; font-weight: bold; font-size: 14px; text-transform: uppercase; margin-bottom: 15px;">
                    <?php echo $is_past ? 'EVENTO PASSATO' : 'PROSSIMO EVENTO'; ?>
                </div>
                <h1 class="text-white" style="font-size: 50px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 1px; line-height: 1.1;"><?php the_title(); ?></h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid var(--c-primary); margin: 20px 0; width: 100px;">
                
                <div style="display: flex; gap: 30px; color: white; font-size: 16px; font-weight: bold; flex-wrap: wrap;">
                    <?php if($data_format): ?>
                        <div><i class="far fa-calendar-alt" style="color: var(--c-primary); margin-right: 8px;"></i> <?php echo esc_html($data_format); ?></div>
                    <?php endif; ?>
                    <?php if($ora): ?>
                        <div><i class="far fa-clock" style="color: var(--c-primary); margin-right: 8px;"></i> <?php echo esc_html($ora); ?></div>
                    <?php endif; ?>
                    <?php if($luogo): ?>
                        <div><i class="fas fa-map-marker-alt" style="color: var(--c-primary); margin-right: 8px;"></i> <?php echo esc_html($luogo); ?></div>
                    <?php endif; ?>
                    <?php if($tipo): ?>
                        <div><i class="fas fa-tag" style="color: var(--c-primary); margin-right: 8px;"></i> <?php echo esc_html($tipo); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <div class="container" style="padding-top: 50px; padding-bottom: 60px;">
        <div class="evento-content" style="color: white; font-size: 16px; line-height: 1.8; background-color: #111; padding: 40px; border: 1px solid #333; border-top: 3px solid var(--c-primary);">
            <?php
            while ( have_posts() ) :
                the_post();
                
                if ( empty( get_the_content() ) ) {
                    if ( $is_past ) {
                        echo '<p>Nessuna galleria o descrizione inserita per questo evento passato.</p>';
                    } else {
                        echo '<p>Maggiori dettagli su questo evento verranno pubblicati a breve.</p>';
                    }
                } else {
                    the_content();
                }
                
            endwhile;
            ?>
        </div>
        
        <div style="margin-top: 40px;">
            <a href="javascript:history.back()" style="color: var(--c-primary); text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 14px;"><i class="fas fa-arrow-left"></i> TORNA INDIETRO</a>
        </div>
    </div>

    <!-- SPONSOR GLOBALI -->
    <section class="ps-section container" style="margin-top: 40px; padding-bottom: 60px;">
        <h2 class="section-title text-white">SPONSOR</h2>
        <?php sport_theme_render_global_sponsors(); ?>
    </section>

</main>

<style>
/* Piccoli aggiustamenti per far rendere bene i contenuti inseriti da WordPress dentro l'evento (es. la galleria) */
.evento-content img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
    margin-bottom: 20px;
}
.evento-content h2, .evento-content h3 {
    color: var(--c-primary);
    margin-top: 30px;
    margin-bottom: 15px;
}
.evento-content p {
    margin-bottom: 20px;
}
.evento-content ul, .evento-content ol {
    margin-bottom: 20px;
    padding-left: 20px;
}
</style>

<?php
if ( has_category('settore-giovanile') ) {
    get_footer('societa');
} else {
    get_footer();
}
?>
