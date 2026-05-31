<?php
/**
 * Template Name: Pagina Organigramma
 *
 * @package Sport_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-organigramma">

    <section class="news-hero">
        <?php
        $page_id = get_queried_object_id();
        if ( has_post_thumbnail( $page_id ) ) {
            $hero_image_url = get_the_post_thumbnail_url( $page_id, 'full' );
        } else {
            $club_page = get_page_by_path( 'club' );
            if ( $club_page && has_post_thumbnail( $club_page->ID ) ) {
                $hero_image_url = get_the_post_thumbnail_url( $club_page->ID, 'full' );
            } else {
                $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
            }
        }
        ?>
        <style>
        .org-hero-title {
            font-size: 55px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 2px;
            color: white;
        }
        .org-submenu {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .org-submenu a {
            padding: 10px 30px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            text-decoration: none;
            white-space: nowrap;
        }
        .org-submenu a.active-btn {
            border: 2px solid var(--c-primary);
            background-color: var(--c-primary);
            color: var(--c-black);
        }
        .org-submenu a.outline-btn {
            border: 2px solid white;
            background-color: transparent;
            color: white;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .org-hero-title { font-size: 32px; letter-spacing: 1px; }
            .org-submenu { gap: 8px; }
            .org-submenu a { padding: 8px 18px; font-size: 11px; }
            .news-hero-content.container { bottom: 20px !important; }
        }
        @media (max-width: 480px) {
            .org-hero-title { font-size: 26px; }
            .org-submenu { flex-direction: column; gap: 8px; }
            .org-submenu a { text-align: center; }
        }
        </style>

        <div class="club-hero-wrapper">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="club-hero-title">ORGANIGRAMMA</h1>

                <hr style="border: 0; border-top: 2px solid white; margin: 15px 0;">

                <div class="org-submenu">
                    <a href="<?php echo esc_url( site_url('/organigramma') ); ?>" class="active-btn">ORGANIGRAMMA</a>
                    <a href="<?php echo esc_url( site_url('/storia') ); ?>" class="outline-btn">STORIA</a>
                    <a href="<?php echo esc_url( site_url('/progetto-sportivo') ); ?>" class="outline-btn">PROGETTO SPORTIVO</a>
                </div>
            </div>
        </div>
    </section>


    <div style="padding-top: 60px;">
    <?php
    $all_dirigenti = new WP_Query(array(
        'post_type' => 'dirigente',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'menu_order title'
    ));

    $prima_squadra = [];
    $settore_giovanile = [];

    if($all_dirigenti->have_posts()) {
        while($all_dirigenti->have_posts()) {
            $all_dirigenti->the_post();
            $sezione = get_post_meta(get_the_ID(), '_sezione_comitato', true);
            if(empty($sezione) || $sezione === 'prima-squadra') {
                $prima_squadra[] = get_post();
            } else {
                $settore_giovanile[] = get_post();
            }
        }
    }
    wp_reset_postdata();
    ?>

    <?php if(count($prima_squadra) > 0): 
        // Group by Area
        $prima_squadra_groups = [];
        foreach($prima_squadra as $post) {
            $area = get_post_meta($post->ID, '_area_organigramma', true);
            if(empty($area)) $area = 'DIREZIONE'; // Fallback se non c'è area
            if(!isset($prima_squadra_groups[$area])) $prima_squadra_groups[$area] = [];
            $prima_squadra_groups[$area][] = $post;
        }
    ?>
    <section class="ps-section container" style="padding-top: 20px; padding-bottom: 40px;">
        <h2 class="section-title text-white" style="margin-bottom: 50px;">ORGANIGRAMMA</h2>

        <style>
        /* Ingrandimento dell'immagine (foto) della card del comitato / organigramma a 335px x 447px */
        @media (min-width: 769px) {
            .page-organigramma .dirigente-photo {
                width: 335px !important;
            }
            .page-organigramma .dirigente-photo::before {
                padding-top: 133.43% !important; /* Aspect ratio 3:4 (335px x 447px) */
            }
            .page-organigramma .dirigente-info {
                min-height: 447px !important; /* Allineamento all'altezza della foto */
            }
            
            /* Allineamento perfetto a sinistra con il container sottostante */
            .page-organigramma .news-hero-content {
                left: 50% !important;
                right: auto !important;
                transform: translateX(-50%) !important;
                width: 100% !important;
                max-width: 1400px !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }
        </style>

        <?php foreach($prima_squadra_groups as $area_name => $dirigenti): ?>
            
            <?php if($area_name !== 'DIREZIONE'): ?>
            <h3 style="color: var(--c-primary); font-size: 20px; font-weight: 700; text-transform: uppercase; margin: 40px 0 20px 0; border-bottom: 2px solid white; padding-bottom: 10px;"><?php echo esc_html($area_name); ?></h3>
            <?php endif; ?>
            
            <div class="dirigenti-grid">
                <?php foreach($dirigenti as $post): 
                    $ruolo = get_post_meta($post->ID, '_ruolo_specifico', true);
                    $foto = has_post_thumbnail($post->ID) ? get_the_post_thumbnail_url($post->ID, 'large') : 'https://via.placeholder.com/300x400/222222/FFFFFF?text=' . get_the_title($post->ID);
                    $parti_nome = explode(' ', get_the_title($post->ID), 2);
                    $nome_riga1 = $parti_nome[0];
                    $nome_riga2 = isset($parti_nome[1]) ? $parti_nome[1] : '';
                ?>
                <?php
                $foto_pos_y = get_post_meta($post->ID, '_foto_position_y', true);
                $bg_pos_style = ($foto_pos_y !== '') ? ' background-position: center ' . esc_attr($foto_pos_y) . '%;' : '';
                ?>
                <div class="dirigente-card">
                    <div class="dirigente-photo cover-bg" style="background-image: url('<?php echo esc_url($foto); ?>');<?php echo $bg_pos_style; ?>">
                        <div class="dirigente-photo-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent); pointer-events: none;"></div>
                    </div>
                    <div class="dirigente-info">
                        <?php if(!empty($ruolo)): 
                            $ruolo_formattato = nl2br(esc_html($ruolo));
                            $ruolo_formattato = str_ireplace(array(' / ', ' + ', ' - ', ' e '), '<br>', $ruolo_formattato);
                            $ruolo_formattato = str_replace(array('/', '+'), '<br>', $ruolo_formattato);
                        ?>
                        <div class="dirigente-role" style="line-height: 1.3; margin-bottom: 10px;"><?php echo $ruolo_formattato; ?></div>
                        <?php endif; ?>
                        <div class="dirigente-name" style="margin-top: 5px; margin-bottom: 15px;">
                            <?php echo esc_html($nome_riga1); ?><br>
                            <span style="color: var(--c-primary);"><?php echo esc_html($nome_riga2); ?></span>
                        </div>
                        <div class="dirigente-desc text-white" style="font-size: 16px; line-height: 1.6;">
                            <?php echo wpautop(apply_filters('the_content', $post->post_content)); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>


    <?php if(count($prima_squadra) == 0): ?>
        <div class="container text-center" style="padding: 100px 0;"><h3 class="text-white">Nessun dirigente inserito. (Aggiungine uno dal menu "Dirigenza")</h3></div>
    <?php endif; ?>
    </div>

    <!-- PARTNER E SPONSOR -->
    <section class="ps-section container">
        <h2 class="section-title text-white">PARTNER E SPONSOR</h2>
        <?php sport_theme_render_global_sponsors(); ?>
    </section>

    <!-- INSTAGRAM -->
    <section class="ps-section container text-center" style="padding-top: 10px; padding-bottom: 50px;">
        <?php echo do_shortcode('[instagram-feed]'); ?>
    </section>

</main>

<?php get_footer(); ?>
