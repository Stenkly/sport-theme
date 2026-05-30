<?php
/**
 * Template Name: Pagina Comitato Società
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-comitato">

    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 30px;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">COMITATO</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 20px 0;">
                <?php sport_theme_render_societa_submenu(); ?>
            </div>
        </div>
    </section>

    <div style="padding-top: 60px;">
    <?php
    $dirigenti_query = new WP_Query(array(
        'post_type' => 'dirigente',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'menu_order title',
        'meta_query' => array(
            array(
                'key' => '_sezione_comitato',
                'value' => 'settore-giovanile',
                'compare' => '='
            )
        )
    ));

    if($dirigenti_query->have_posts()):
    ?>
    <section class="ps-section container" style="padding-top: 20px; padding-bottom: 60px;">
        <div class="dirigenti-grid">
            <?php while($dirigenti_query->have_posts()): $dirigenti_query->the_post(); 
                $ruolo = get_post_meta(get_the_ID(), '_ruolo_specifico', true);
                $foto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://via.placeholder.com/300x400/222222/FFFFFF?text=' . get_the_title();
                
                $parti_nome = explode(' ', get_the_title(), 2);
                $nome_riga1 = $parti_nome[0];
                $nome_riga2 = isset($parti_nome[1]) ? $parti_nome[1] : '';
                
                $is_pres_onorario = (stripos($ruolo, 'presidente onorario') !== false);
                $card_style = $is_pres_onorario ? 'grid-column: 1 / -1; justify-self: center; width: 100%; max-width: 580px; margin-bottom: 20px;' : '';
            ?>
            <div class="dirigente-card" style="<?php echo esc_attr($card_style); ?>">
                <div class="dirigente-photo cover-bg" style="background-image: url('<?php echo esc_url($foto); ?>');">
                    <div class="dirigente-photo-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent); pointer-events: none;"></div>
                    <div class="dirigente-name" style="position: absolute; bottom: 15px; left: 15px; z-index: 2;">
                        <?php echo esc_html($nome_riga1); ?><br>
                        <span style="color: var(--c-primary);"><?php echo esc_html($nome_riga2); ?></span>
                    </div>
                </div>
                <div class="dirigente-info">
                    <?php if(!empty($ruolo)): ?>
                    <div class="dirigente-role"><?php echo esc_html($ruolo); ?></div>
                    <?php endif; ?>
                    <div class="dirigente-desc text-white" style="font-size: 12px; line-height: 1.6;">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
            
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php else:
        echo '<div class="container text-center" style="padding: 100px 0;"><h3 class="text-white">Nessun dirigente inserito.</h3></div>';
    endif;
    ?>
    </div>

    <!-- PARTNER E SPONSOR -->
    <section class="ps-section container">
        <h2 class="section-title text-white">SPONSOR</h2>
        <?php sport_theme_render_global_sponsors(); ?>
    </section>

</main>

<?php get_footer('societa'); ?>
