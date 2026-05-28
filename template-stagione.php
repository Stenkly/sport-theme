<?php
/**
 * Template Name: Pagina Stagione
 *
 * @package Sport_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-stagione">

    <!-- HERO IMMAGINE STAGIONE -->
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="Stagione">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">STAGIONE</h1>
            </div>
        </div>
    </section>

    <?php 
    // PREPARAZIONE QUERY: Dividiamo "Da giocare" (Calendario) da "Giocate" (Risultati)
    $all_matches = new WP_Query(array('post_type' => 'partita', 'posts_per_page' => -1, 'order' => 'ASC'));
    $calendario_posts = [];
    $risultati_posts = [];
    while($all_matches->have_posts()) {
        $all_matches->the_post();
        if (get_post_meta(get_the_ID(), '_risultato', true) == '') {
            $calendario_posts[] = get_post();
        } else {
            $risultati_posts[] = get_post();
        }
    }
    wp_reset_postdata();

    // FILTRO CALENDARIO: Limitiamo alle prossime 3 partite in programma
    $calendario_posts = array_slice($calendario_posts, 0, 3);
    
    // FILTRO RISULTATI: Invertiamo l'ordine cronologico (dalla più recente ripercorrendo verso il passato) e prendiamo le ultime 3
    $risultati_posts = array_reverse($risultati_posts);
    $risultati_posts = array_slice($risultati_posts, 0, 3);

    // LOGO FALLBACK E LOGO TAVERNE BASE
    $taverne_logo = has_custom_logo() ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : 'https://via.placeholder.com/40';
    ?>

    <!-- CALENDARIO PARTITE -->
    <section class="ps-section container" style="padding-top: 60px;">
        <h2 class="section-title text-white" style="margin-bottom: 30px;">CALENDARIO PARTITE</h2>
        
        <div class="match-list" style="display: flex; flex-direction: column; gap: 15px;">
            <?php 
            if(count($calendario_posts) === 0) {
                echo '<p class="text-white">Nessuna partita futura in programma.</p>';
            }
            foreach($calendario_posts as $post) : setup_postdata($post); 
                $data_p = get_post_meta($post->ID, '_data_partita', true);
                $ora_p = get_post_meta($post->ID, '_ora_partita', true);
                $stadio = sport_theme_get_match_stadium($post->ID);
                $avversario = get_post_meta($post->ID, '_avversario', true) ? get_post_meta($post->ID, '_avversario', true) : 'Sfidante';
                $logo_avversario = sport_theme_get_opponent_logo($post->ID);
                $in_casa = get_post_meta($post->ID, '_in_casa', true);

                $t1_name = $in_casa == '1' ? 'AC Taverne' : $avversario;
                $t1_logo = $in_casa == '1' ? $taverne_logo : $logo_avversario;
                $t2_name = $in_casa == '1' ? $avversario : 'AC Taverne';
                $t2_logo = $in_casa == '1' ? $logo_avversario : $taverne_logo;
            ?>
            <div class="match-card match-row">
                <div class="match-info">
                    <div class="match-date text-white">
                        <?php echo esc_html($data_p); ?><br><?php echo esc_html($ora_p); ?><br>
                        <span class="text-primary" style="font-weight:400; font-size:12px;"><?php echo esc_html($stadio); ?></span>
                    </div>
                </div>
                <div class="match-teams">
                    <div class="team">
                        <span class="d-none-mobile"><?php echo esc_html($t1_name); ?></span>
                        <img class="match-row-logo" src="<?php echo esc_url($t1_logo); ?>" alt="<?php echo esc_attr($t1_name); ?>">
                    </div>
                    <div class="vs">VS</div>
                    <div class="team">
                        <img class="match-row-logo" src="<?php echo esc_url($t2_logo); ?>" alt="<?php echo esc_attr($t2_name); ?>">
                        <span class="d-none-mobile"><?php echo esc_html($t2_name); ?></span>
                    </div>
                </div>
                <div class="match-action">
                    <div class="vertical-divider"></div>
                    <?php $match_link = get_post_meta($post->ID, '_match_link', true); ?>
                    <a href="<?php echo esc_url($match_link ? $match_link : get_permalink()); ?>" class="btn-sm btn-outline" <?php if($match_link) echo 'target="_blank"'; ?>>DETTAGLI</a>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    </section>

    <!-- RISULTATI -->
    <section class="ps-section container">
        <h2 class="section-title text-white" style="margin-bottom: 30px;">RISULTATI</h2>
        
        <div class="match-list" style="display: flex; flex-direction: column; gap: 15px;">
            <?php 
            if(count($risultati_posts) === 0) {
                echo '<p class="text-white">Nessun risultato disponibile.</p>';
            }
            foreach($risultati_posts as $post) : setup_postdata($post); 
                $data_p = get_post_meta($post->ID, '_data_partita', true);
                $ora_p = get_post_meta($post->ID, '_ora_partita', true);
                $stadio = sport_theme_get_match_stadium($post->ID);
                $avversario = get_post_meta($post->ID, '_avversario', true) ? get_post_meta($post->ID, '_avversario', true) : 'Sfidante';
                $logo_avversario = sport_theme_get_opponent_logo($post->ID);
                $in_casa = get_post_meta($post->ID, '_in_casa', true);
                $risultato = get_post_meta($post->ID, '_risultato', true);

                $t1_name = $in_casa == '1' ? 'AC Taverne' : $avversario;
                $t1_logo = $in_casa == '1' ? $taverne_logo : $logo_avversario;
                $t2_name = $in_casa == '1' ? $avversario : 'AC Taverne';
                $t2_logo = $in_casa == '1' ? $logo_avversario : $taverne_logo;
            ?>
            <div class="match-card match-row">
                <div class="match-info">
                    <div class="match-date text-white">
                        <?php echo esc_html($data_p); ?><br><?php echo esc_html($ora_p); ?><br>
                        <span class="text-primary" style="font-weight:400; font-size:12px;"><?php echo esc_html($stadio); ?></span>
                    </div>
                </div>
                <div class="match-teams">
                    <div class="team">
                        <span class="d-none-mobile"><?php echo esc_html($t1_name); ?></span>
                        <img class="match-row-logo" src="<?php echo esc_url($t1_logo); ?>" alt="<?php echo esc_attr($t1_name); ?>">
                    </div>
                    <div class="score" style="font-size: 24px; font-weight: 700; color: white; letter-spacing: 2px;"><?php echo esc_html($risultato); ?></div>
                    <div class="team">
                        <img class="match-row-logo" src="<?php echo esc_url($t2_logo); ?>" alt="<?php echo esc_attr($t2_name); ?>">
                        <span class="d-none-mobile"><?php echo esc_html($t2_name); ?></span>
                    </div>
                </div>
                <div class="match-action">
                    <div class="vertical-divider"></div>
                    <?php $match_link = get_post_meta($post->ID, '_match_link', true); ?>
                    <a href="<?php echo esc_url($match_link ? $match_link : get_permalink()); ?>" class="btn-sm btn-outline" <?php if($match_link) echo 'target="_blank"'; ?>>DETTAGLI</a>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    </section>

    <!-- CLASSIFICA -->
    <section class="ps-section container">
        <h2 class="section-title text-white" style="margin-bottom: 20px;">CLASSIFICA</h2>
        
        <div class="custom-classifica-wrapper" style="overflow-x:auto; margin-top: 20px;">
            <?php 
                $page_content = get_the_content();
                if(empty($page_content)) {
                    echo '<p style="color:#aaa;">(Nessuna classifica inserita. Modifica la pagina su WordPress e aggiungi una Tabella)</p>';
                } else {
                    the_content(); 
                }
            ?>
        </div>
        
        <style>
            /* Stili applicati automaticamente alla tabella creata con l'editor di WordPress */
            .custom-classifica-wrapper table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
                color: white;
                font-size: 15px;
                background-color: #383b3e;
            }
            .custom-classifica-wrapper table thead tr {
                background-color: var(--c-primary);
                color: #000;
                font-size: 14px;
            }
            .custom-classifica-wrapper table th {
                padding: 15px 10px;
                border: 1px solid #000;
                font-weight: bold;
            }
            .custom-classifica-wrapper table th:nth-child(2) {
                text-align: left;
                padding-left: 20px;
            }
            .custom-classifica-wrapper table td {
                padding: 15px 10px;
                border: 1px solid #000;
            }
            .custom-classifica-wrapper table td:nth-child(1) {
                font-weight: bold;
                font-size: 16px;
            }
            .custom-classifica-wrapper table td:nth-child(2) {
                text-align: left;
                padding-left: 20px;
                font-weight: 600;
                font-size: 14px;
            }
            .custom-classifica-wrapper table td:last-child {
                font-weight: bold;
            }
            .custom-classifica-wrapper table tbody tr {
                transition: background 0.3s;
            }
            .custom-classifica-wrapper table tbody tr:hover {
                background-color: rgba(255,255,255,0.05) !important;
            }
        </style>

        <script>
            // Trova la tabella generata da WordPress e applica la riga gialla per l'AC Taverne
            document.addEventListener('DOMContentLoaded', function() {
                var tableRows = document.querySelectorAll('.custom-classifica-wrapper tbody tr');
                tableRows.forEach(function(row) {
                    var teamCell = row.cells[1];
                    if(teamCell && teamCell.textContent.trim().includes('AC Taverne')) {
                        row.style.backgroundColor = 'rgba(255, 204, 0, 0.1)';
                    }
                });
            });
        </script>
    </section>

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
