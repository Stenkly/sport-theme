<?php
/**
 * Template Name: Pagina Rosa
 *
 * @package Sport_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-rosa">

    <!-- HERO IMMAGINE SQUADRA -->
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
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">TEAM</h1>
                
                <hr style="border: 0; border-top: 2px solid white; margin: 20px 0;">
                
                <div class="page-submenu" style="display: flex; gap: 20px;">
                    <h4 style="margin: 0; display: inline-block;">
                        <a href="<?php echo esc_url( site_url('/rosa') ); ?>" style="padding: 10px 40px; font-weight: 700; text-transform: uppercase; font-size: 22px; text-decoration: none; border: 2px solid var(--c-primary); background-color: var(--c-primary); color: var(--c-black); display: inline-block;">ROSA</a>
                    </h4>
                    <h4 style="margin: 0; display: inline-block;">
                        <a href="<?php echo esc_url( site_url('/staff') ); ?>" class="btn-outline-hover" style="padding: 10px 40px; font-weight: 700; text-transform: uppercase; font-size: 22px; text-decoration: none; border: 2px solid white; background-color: transparent; color: white; transition: all 0.3s; display: inline-block;">STAFF</a>
                    </h4>
                </div>
            </div>
        </div>
    </section>

    <!-- LISTA GIOCATORI SECONDO I REPARTI -->
    <div style="padding-top: 0px;">
    <?php
    $found_any = false;

    // Recupera TUTTI i reparti che hai deciso di creare (nascondendo quelli vuoti)
    $terms = get_terms(array(
        'taxonomy' => 'ruolo_giocatore',
        'hide_empty' => true,
    ));
    
    // Filtriamo via le categorie "Staff" / "Dirigenza" da questa pagina in modo che compaiano SOLO giocatori
    if (!empty($terms) && !is_wp_error($terms)) {
        $terms = array_filter($terms, function($t) {
            $n = strtolower($t->name);
            return !in_array($n, ['staff', 'dirigenza', 'management', 'allenatori', 'mister']);
        });
    }

    if (!empty($terms) && !is_wp_error($terms)) :
        // Mettiamo i ruoli più famosi in cima se per caso li hai usati
        $ordine_ideal = ['portieri', 'difensori', 'centrocampisti', 'attaccanti'];
        usort($terms, function($a, $b) use ($ordine_ideal) {
            $pos_a = array_search(strtolower($a->name), $ordine_ideal);
            $pos_b = array_search(strtolower($b->name), $ordine_ideal);
            if($pos_a === false) $pos_a = 99;
            if($pos_b === false) $pos_b = 99;
            return $pos_a <=> $pos_b;
        });

        foreach($terms as $term):
            $team_query = new WP_Query(array(
                'post_type' => 'giocatore',
                'posts_per_page' => -1,
                'order' => 'ASC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'ruolo_giocatore',
                        'field' => 'term_id',
                        'terms' => $term->term_id
                    )
                ),
            ));

            if($team_query->have_posts()):
                $found_any = true;
                $nome_ruolo = strtoupper($term->name);
    ?>
    <section class="ps-section container team-section" style="padding-top: 10px; padding-bottom: 20px;">
        <h2 class="section-title text-white" style="margin-bottom: 15px;"><?php echo $nome_ruolo; ?></h2>
        <div class="ps-grid grid-4 ps-team">
            <?php while($team_query->have_posts()): $team_query->the_post(); 
                $numero = get_post_meta(get_the_ID(), '_numero_maglia', true);
                $ruolo_spec = get_post_meta(get_the_ID(), '_ruolo_specifico', true);
                $foto_ritratto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://via.placeholder.com/300x400/222222/FFFFFF?text=' . get_the_title();
                $foto_esultanza = get_post_meta(get_the_ID(), '_foto_esultanza', true);
                $foto_dettaglio = !empty($foto_esultanza) ? $foto_esultanza : $foto_ritratto;
                
                $data        = get_post_meta(get_the_ID(), '_data_nascita', true) ?: '-';
                $altezza     = get_post_meta(get_the_ID(), '_altezza', true) ?: '-';
                $peso        = get_post_meta(get_the_ID(), '_peso', true) ?: '-';
                $nazionalita = get_post_meta(get_the_ID(), '_nazionalita', true) ?: '-';
                $htp         = get_post_meta(get_the_ID(), '_htp', true) ?: '-';
                $shop_url    = get_post_meta(get_the_ID(), '_shop_url', true);
                $ruolo_str   = sport_theme_get_singular_role($nome_ruolo);
                $zoom_foto   = get_post_meta(get_the_ID(), '_zoom_foto', true) ?: 'cover';
                $allineamento_foto = get_post_meta(get_the_ID(), '_allineamento_foto', true) ?: 'center top';
                
                // Dividiamo il nome su due righe per emulare il design "Nome \n Cognome"
                $split_name = sport_theme_get_giocatore_split_name(get_the_ID());
                $nome_riga1 = $split_name['nome'];
                $nome_riga2 = $split_name['cognome'];
            ?>
            
            <!-- Card Giocatore Pixel Perfect -->
            <a href="#" class="open-player-modal" 
               data-foto="<?php echo esc_attr($foto_dettaglio); ?>" 
               data-numero="<?php echo esc_attr($numero); ?>" 
               data-nome1="<?php echo esc_attr($nome_riga1); ?>" 
               data-nome2="<?php echo esc_attr($nome_riga2); ?>" 
               data-nascita="<?php echo esc_attr($data); ?>" 
               data-altezza="<?php echo esc_attr($altezza); ?>" 
               data-peso="<?php echo esc_attr($peso); ?>" 
               data-nazionalita="<?php echo esc_attr($nazionalita); ?>" 
               data-htp="<?php echo esc_attr($htp); ?>" 
               data-shop="<?php echo esc_attr($shop_url); ?>" 
               data-ruolo="<?php echo esc_attr($ruolo_str); ?>"
               style="text-decoration: none; display: block; overflow: hidden; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <div class="player-card" style="position: relative; aspect-ratio: 3/4; overflow: hidden; background-color: #111;">
                    <div class="player-photo cover-bg" style="background-image: url('<?php echo esc_url($foto_ritratto); ?>'); background-size: <?php echo esc_attr($zoom_foto); ?>; background-position: <?php echo esc_attr($allineamento_foto); ?>; width: 100%; height: 100%; border: none; transition: transform 0.5s ease;"></div>
                    <!-- Velo Sfumato dal Basso -->
                    <div class="player-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, transparent 100%); pointer-events: none;"></div>
                    
                    <!-- Nome e numero stampigliati -->
                    <div class="player-info" style="position: absolute; bottom: 2px; left: 12px; z-index: 2; flex-direction: column; gap: 0; padding: 0;">
                        <?php if(!empty($numero)): ?>
                        <span class="player-number" style="display: block; font-size: 50px; font-weight: 700; margin-bottom: 20px; line-height: 1; color: #F2E302;"><?php echo esc_html($numero); ?></span>
                        <?php endif; ?>
                        <span class="player-name text-white" style="display: block; font-size: 34px; font-weight: 700; line-height: 1.2; margin-top: 0px;">
                            <?php echo esc_html($nome_riga1); ?><br>
                            <span style="color: #F9EA86; display:block; margin-top:-3px;"><?php echo esc_html($nome_riga2); ?></span>
                        </span>
                    </div>
                </div>
            </a>
            
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php 
        endif; 
    endforeach; 
    endif; // Chiude if(!empty($terms))

    if(!$found_any):
        echo '<div class="container text-center" style="padding: 100px 0;"><h3 class="text-white">Nessun giocatore inserito. Caricali dal pannello WordPress sotto "Team" ed assegna un Reparto come "Portieri", "Difensori" ecc!</h3></div>';
    endif;
    ?>
    </div>
    </div>

    <!-- PARTNER E SPONSOR -->
    <section class="ps-section container">
        <h2 class="section-title text-white">PARTNER E SPONSOR</h2>
        <?php sport_theme_render_global_sponsors(); ?>
    </section>

    <!-- INSTAGRAM MOCKUP -->
    <section class="ps-section container text-center" style="padding-top: 10px; padding-bottom: 50px;">
        <?php echo do_shortcode('[instagram-feed]'); ?>
    </section>

</main>

<?php get_footer(); ?>
