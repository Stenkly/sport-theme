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
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
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
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 70%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
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
        .org-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            row-gap: 50px;
            column-gap: 40px;
            margin-bottom: 30px;
        }
        .org-card {
            display: flex;
            background: transparent;
        }
        .org-card-photo {
            flex: 0 0 45%;
            max-width: 45%;
        }
        .org-card-photo img {
            width: 100%;
            height: auto;
            display: block;
            aspect-ratio: 4/5;
            object-fit: cover;
        }
        .org-card-info {
            flex: 1;
            margin-left: 20px;
            padding: 10px 0 0 20px;
            border-left: 1px solid var(--c-primary);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .org-card-role {
            color: var(--c-primary);
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 1px;
            display: block;
            line-height: 1.4;
            max-width: 90%;
        }
        .org-card-name {
            color: white;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.1;
            display: block;
        }

        /* ---- MOBILE ---- */
        @media (max-width: 768px) {
            .org-grid {
                grid-template-columns: 1fr;
                row-gap: 30px;
            }
            .org-card {
                flex-direction: row; /* mantieni foto+testo affiancati */
            }
            .org-card-photo {
                flex: 0 0 38%;
                max-width: 38%;
            }
            .org-card-info {
                margin-left: 15px;
                padding: 8px 0 0 15px;
            }
            .org-card-name {
                font-size: 18px;
            }
            .org-card-role {
                font-size: 9px;
            }
        }

        @media (max-width: 480px) {
            .org-card-photo {
                flex: 0 0 35%;
                max-width: 35%;
            }
            .org-card-name {
                font-size: 16px;
            }
        }
        </style>

        <?php foreach($prima_squadra_groups as $area_name => $dirigenti): ?>
            
            <?php if($area_name !== 'DIREZIONE'): ?>
            <h3 style="color: var(--c-primary); font-size: 20px; font-weight: 700; text-transform: uppercase; margin: 40px 0 20px 0; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;"><?php echo esc_html($area_name); ?></h3>
            <?php endif; ?>
            
            <div class="org-grid">
                <?php foreach($dirigenti as $post): setup_postdata($post); 
                    $ruolo = get_post_meta(get_the_ID(), '_ruolo_specifico', true);
                    $foto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://via.placeholder.com/300x400/222222/FFFFFF?text=' . get_the_title();
                    $parti_nome = explode(' ', get_the_title(), 2);
                    $nome_riga1 = $parti_nome[0];
                    $nome_riga2 = isset($parti_nome[1]) ? $parti_nome[1] : '';
                ?>
                <div class="org-card">
                    <div class="org-card-photo">
                        <img src="<?php echo esc_url($foto); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    </div>
                    <div class="org-card-info">
                        <?php if(!empty($ruolo)): ?>
                        <span class="org-card-role"><?php echo esc_html($ruolo); ?></span>
                        <?php endif; ?>
                        <span class="org-card-name">
                            <?php echo esc_html($nome_riga1); ?><br>
                            <?php echo esc_html($nome_riga2); ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>


    <?php if(count($settore_giovanile) > 0): ?>
    <section class="ps-section container" style="padding-top: 40px; padding-bottom: 60px;">
        <h2 class="section-title text-white" style="margin-bottom: 30px;">SETTORE GIOVANILE</h2>
        <div class="dirigenti-grid">
            <?php foreach($settore_giovanile as $post): setup_postdata($post); 
                $ruolo = get_post_meta(get_the_ID(), '_ruolo_specifico', true);
                $foto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://via.placeholder.com/300x400/222222/FFFFFF?text=' . get_the_title();
                $parti_nome = explode(' ', get_the_title(), 2);
                $nome_riga1 = $parti_nome[0];
                $nome_riga2 = isset($parti_nome[1]) ? $parti_nome[1] : '';
            ?>
            <div class="dirigente-card">
                <div class="dirigente-photo cover-bg" style="background-image: url('<?php echo esc_url($foto); ?>');">
                    <div class="dirigente-photo-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent); pointer-events: none;"></div>
                </div>
                <div class="dirigente-info">
                    <?php if(!empty($ruolo)): ?>
                    <div class="dirigente-role"><?php echo esc_html($ruolo); ?></div>
                    <?php endif; ?>
                    <div class="dirigente-name" style="margin-top: 5px; margin-bottom: 15px;">
                        <?php echo esc_html($nome_riga1); ?><br>
                        <span style="color: var(--c-primary);"><?php echo esc_html($nome_riga2); ?></span>
                    </div>
                    <div class="dirigente-desc text-white" style="font-size: 16px; line-height: 1.6;">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php endif; ?>
    
    <?php if(count($prima_squadra) == 0 && count($settore_giovanile) == 0): ?>
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
