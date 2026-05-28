<?php
/**
 * Template Name: Pagina Club Condivisa
 *
 * @package Sport_Theme
 */

get_header();

// Setup active states for the submenu
$current_page_title = strtolower(get_the_title());
$is_storia = ($current_page_title === 'storia');
$is_progetto = ($current_page_title === 'progetto sportivo');

// Style helpers
$btn_active = "padding: 10px 40px; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none; border: 2px solid var(--c-primary); background-color: var(--c-primary); color: var(--c-black);";
$btn_inactive = "padding: 10px 40px; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none; border: 2px solid white; background-color: transparent; color: white; transition: all 0.3s;";
?>

<main id="primary" class="site-main page-club">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="club-hero-wrapper">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 70%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="club-hero-title"><?php the_title(); ?></h1>
                
                <?php if (has_excerpt()) : ?>
                <p class="hero-subtitle text-white" style="font-size: 22px; font-weight: 700; text-transform: uppercase; max-width: 800px; margin-top: 15px; line-height: 1.4;"><?php echo get_the_excerpt(); ?></p>
                <?php endif; ?>

                <hr style="border: 0; border-top: 2px solid white; margin: 20px 0;">
                
                <div class="page-submenu" style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <a href="<?php echo esc_url( site_url('/organigramma') ); ?>" class="btn-outline-hover" style="<?php echo $btn_inactive; ?>">ORGANIGRAMMA</a>
                    <a href="<?php echo esc_url( site_url('/storia') ); ?>" class="<?php echo $is_storia ? '' : 'btn-outline-hover'; ?>" style="<?php echo $is_storia ? $btn_active : $btn_inactive; ?>">STORIA</a>
                    <a href="<?php echo esc_url( site_url('/progetto-sportivo') ); ?>" class="<?php echo $is_progetto ? '' : 'btn-outline-hover'; ?>" style="<?php echo $is_progetto ? $btn_active : $btn_inactive; ?>">PROGETTO SPORTIVO</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="container club-content" style="padding-top: 60px; padding-bottom: 30px;">
        <?php
        while ( have_posts() ) :
            the_post();
            
            $raw_content = get_the_content();
            $clean_content = trim(strip_tags($raw_content));
            $current_slug = get_post_field('post_name', get_the_ID());
            
            // Finto testo se la pagina non è stata ancora scritta
            if ( empty($clean_content) || strlen($clean_content) < 150 ) {
                $lorem_p = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>";
                $lorem_h3 = "<h3>LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.</h3>";
                
                $titolo_check = strtolower(get_the_title());
                if ($current_slug === 'storia' || strpos($titolo_check, 'storia') !== false) {
                    echo '<h2>IDENTITÀ DEL CLUB</h2>';
                    echo '<h3>Un' . "identità forte e radicata, nata nel dopoguerra dall'entusiasmo popolare e dall'amore per lo sport.</h3>";
                    echo '<p>Le origini della prima squadra di Taverne affondano le proprie radici negli anni Venti, con la nascita del Football Club Stella Taverne, prima vera formazione locale. Inizialmente si giocava nella zona di Livorno, lungo il corso del Vedeggio, indossando una maglia nera con una stella bianca sul petto. Negli anni successivi, il campo fu spostato a Taverne Superiore e, negli anni Quaranta, nell\'area della stazione.</p>';
                    echo '<p>Nel secondo dopoguerra nacque l’Associazione Calcio Taverne, sostenuta da grande entusiasmo popolare. La società divenne rapidamente un punto di riferimento sportivo per tutta la valle, grazie a un ambiente favorevole, ottimi allenatori e ad una solida comunità. A questo periodo eroico sono legati nomi importanti come Mario Banfi, Flaminio Petrocchi e Gino Gova, mentre già negli anni Trenta si distinsero i fratelli Zambelli, in particolare il portiere Emilio, detto "Zamorra".</p>';
                    echo '<p>Un momento chiave arrivò nel 1950, quando l’AC Taverne entrò ufficialmente nella Federazione calcistica ticinese partecipando al campionato di Quarta Divisione, ottenendo subito la promozione in Terza. La crescita proseguì fino alla stagione 1956-57, in cui, sotto la guida di Bruno Passardi, la squadra conquistò il titolo di campione di Terza Divisione. L’anno successivo, con Dino Leoni alla guida, il Taverne si confermò vincendo sia il campionato sia il Trofeo Ticino, segnando una delle pagine più significative della sua storia.</p>';

                    echo '<h2>RUOLO DELLA PRIMA SQUADRA</h2>';
                    echo '<h3>La prima squadra come traino e punto di riferimento di tutta l\'attività agonistica giallonera.</h3>';
                    echo '<p>La prima squadra rappresenta la vetrina principale del club e il traguardo naturale per tutti i giovani che crescono nel settore giovanile. Nel corso degli anni, essa ha svolto il ruolo di traino per l\'intera comunità sportiva di Taverne, ispirando generazioni di atleti locali e promuovendo l\'attaccamento dei tesserati e dei tifosi ai colori gialloneri.</p>';
                    echo '<p>Oggi, la prima squadra partecipa a campionati di livello nazionale, portando con orgoglio il nome del club e del territorio oltre i confini regionali, sempre guidata dai valori storici di lealtà, dedizione e passione.</p>';

                    echo '<h2>EVOLUZIONE NEL TEMPO</h2>';
                    echo '<h3>Una crescita costante nel tempo, costruendo un percorso solido nel panorama calcistico nazionale.</h3>';
                    echo '<p>Nel corso della sua storia, il Taverne ha saputo tracciare una linea coerente, caratterizzata da tappe significative. Tra gli anni Ottanta e Novanta, la prima squadra ha partecipato con regolarità ai campionati di Seconda e Terza Lega, consolidando la propria presenza e gettando le basi per i successi futuri. Un primo importante salto di qualità si registra nella stagione 2009-2010, quando il Taverne conquista il terzo rango in Seconda Lega regionale ottenendo la promozione in Seconda Lega Interregionale. Si tratta di un traguardo storico, che segna l’ingresso del club, a partire dal 2010, in un contesto di competizione nazionale.</p>';
                    echo '<p>Nel campionato 2011-2012, il Taverne tenta la scalata alla Seconda Lega élite, categoria già raggiunta con merito due anni prima e sfortunatamente persa nella stagione successiva. L’annata seguente, 2012-2013, si apre con segnali positivi: la squadra si presenta briosa e promettente, mantenendo il primo posto in classifica al termine del girone d’andata.</p>';
                    echo '<p>A partire dalla stagione 2019-2020, il Taverne milita stabilmente in Prima Lega Classic, raggiungendo il livello più alto nella storia della società dopo alcune stagioni di consolidamento nei campionati interregionali.</p>';

                    echo '<h2>IL SETTORE GIOVANILE E I SUCCESSI</h2>';
                    echo '<h3>Il vivaio e le squadre giovanili come risorsa fondamentale e garanzia per il futuro del club.</h3>';
                    echo '<p>Parallelamente ai risultati della prima squadra, il club ha sempre attribuito una rilevanza strategica al settore giovanile. I ragazzi delle categorie Allievi, sempre più numerosi, rappresentano una prospettiva concreta ed una risorsa fondamentale su cui fondare il domani.</p>';
                    echo '<p>Il loro sviluppo è affidato a dirigenti, allenatori e preparatori che privilegiano una crescita progressiva e duratura rispetto ai risultati immediati.</p>';
                    echo '<p>Nel corso degli anni, il club ha collezionato numerosi successi, tra cui:</p>';
                    echo '<ul>';
                    echo '<li>Campione ticinese di Terza Divisione e promozione in Seconda Lega (stagione 1956-1957)</li>';
                    echo '<li>Campione ticinese di Seconda Divisione e promozione in Seconda Lega (stagione 1958-1959)</li>';
                    echo '<li>Vincitore di gruppo di Terza Divisione e promozione in Seconda Lega (stagione 1979-1980)</li>';
                    echo '<li>Vincitore di gruppo di Terza Lega e promozione in Seconda Lega (stagioni 1992-1993 e 2004-2005)</li>';
                    echo '<li>Terzo posto in Seconda Lega regionale e promozione in Seconda Lega Interregionale (stagione 2009-2010)</li>';
                    echo '</ul>';
                    echo '<p>A questi si aggiungono i risultati del settore giovanile e della seconda squadra:</p>';
                    echo '<ul>';
                    echo '<li>Campione ticinese Allievi A e promozione nella categoria Interregionale (stagione 1986-1987)</li>';
                    echo '<li>Seconda squadra campione di gruppo in Quinta Lega e promossa in Quarta Lega (stagione 2007-2008)</li>';
                    echo '</ul>';
                    echo '<p>Di particolare rilievo anche il percorso nelle competizioni regionali: il Taverne ha conquistato sei Coppe Ticino, stabilendo un record prestigioso, e ha ottenuto un primo e un secondo posto nella Coppa Campioni del calcio regionale ticinese.</p>';
                    echo '<p>Dalla stagione attuale, la prima squadra si presenta con un nuovo assetto societario, segnando l’inizio di una nuova fase nel percorso di sviluppo del club, nel segno della continuità e dell’attenzione alla propria storia.</p>';
                } else if ($current_slug === 'progetto-sportivo' || strpos($titolo_check, 'progetto') !== false) {
                    echo '<h2>VISIONE, OBIETTIVI E VALORI SPORTIVI</h2>';
                    echo '<h3>La Prima Squadra del Taverne si fonda su una filosofia chiara: unire un calcio dinamico, giovane e di qualità.</h3>';
                    echo '<p>L’obiettivo è esprimere un gioco moderno e propositivo, capace di valorizzare il talento e favorire la crescita dei giocatori, sempre nel rispetto dei valori fondamentali dello sport.</p>';
                    
                    echo '<h2>FILOSOFIA DI GIOCO</h2>';
                    echo '<h3>Giocare bene non è solo un obiettivo estetico, ma il modo più autentico per rappresentare e onorare gli ideali del club.</h3>';
                    echo '<p>Il lavoro quotidiano, svolto con serietà e determinazione, è la base su cui costruire prestazioni convincenti.</p>';
                    
                    echo '<h2>OBIETTIVI SPORTIVI</h2>';
                    echo '<h3>Elemento distintivo è proprio la giovane età del gruppo, punto di forza su cui costruire presente e futuro.</h3>';
                    echo '<p>Attraverso la valorizzazione delle giovani promesse, il club mira a consolidare la propria competitività ed esprimere un calcio propositivo.</p>';
                    
                    echo '<h2>VALORI</h2>';
                    echo '<h3>Particolare attenzione è rivolta allo sviluppo dei giovani talenti del panorama ticinese e non solo.</h3>';
                    echo '<p>Accompagniamo i giovani calciatori in un percorso di crescita che mira a portarli al livello più alto possibile. Eleganza, rispetto e spirito di sacrificio guidano ogni allenamento e ogni partita.</p>';
                }
            } else {
                the_content();
            }

        endwhile;
        ?>
    </div>

    <!-- DYNAMIC FOTOGALLERY (dal Custom Post Type Foto Gallery) -->
    <?php
    $gallery_args = array(
        'post_type' => 'fotogallery',
    );
    
    if ($current_slug === 'storia') {
        $gallery_args['posts_per_page'] = -1;
        $gallery_args['tax_query'] = array(
            array(
                'taxonomy' => 'categoria_galleria',
                'field'    => 'slug',
                'terms'    => 'storia',
            ),
        );
    } else {
        // Progetto Sportivo: ultime 4 foto ESCLUDENDO quelle caricate per Storia
        $gallery_args['posts_per_page'] = 4;
        $gallery_args['tax_query'] = array(
            array(
                'taxonomy' => 'categoria_galleria',
                'field'    => 'slug',
                'terms'    => 'storia',
                'operator' => 'NOT IN',
            ),
        );
    }
    
    $gallery_query = new WP_Query($gallery_args);
    ?>
    <section class="container <?php echo $current_slug === 'storia' ? 'club-content' : ''; ?> ps-section" style="padding-bottom: 60px;">
        <h2 class="section-title text-white" style="margin-bottom: 30px;">FOTOGALLERY</h2>
        
        <?php if ($current_slug === 'storia') : ?>
            <!-- Layout MASONRY per Storia -->
            <div class="custom-cpt-gallery wp-block-gallery">
                <?php 
                if ($gallery_query->have_posts()) :
                    while ($gallery_query->have_posts()) : $gallery_query->the_post(); 
                        if (has_post_thumbnail()) :
                            $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    ?>
                        <figure class="wp-block-image">
                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                        </figure>
                    <?php 
                        endif;
                    endwhile; wp_reset_postdata(); 
                else :
                    // Mockup Segnaposto per STORIA
                ?>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-1.jpg'); ?>" alt="Storia 1"></figure>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-2.jpg'); ?>" alt="Storia 2"></figure>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-3.jpg'); ?>" alt="Storia 3"></figure>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-4.jpg'); ?>" alt="Storia 4"></figure>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-5.jpg'); ?>" alt="Storia 5"></figure>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <!-- Layout CAROUSEL 4-GRID per Progetto Sportivo (uguale a Prima Squadra) -->
            <div class="ps-grid grid-4 ps-gallery">
                <?php 
                if ($gallery_query->have_posts()) :
                    while ($gallery_query->have_posts()) : $gallery_query->the_post(); 
                        if (has_post_thumbnail()) :
                            $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                            echo '<a data-fancybox="gallery" href="' . esc_url($img_url) . '"><div class="gallery-item cover-bg" style="background-image: url(\'' . esc_url($img_url) . '\')"></div></a>';
                        endif;
                    endwhile; wp_reset_postdata(); 
                else :
                    // Mockup Segnaposto per PROGETTO SPORTIVO
                    for($i=0; $i<4; $i++) {
                        $demo_img = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1000';
                        echo '<a data-fancybox="gallery" href="' . esc_url($demo_img) . '"><div class="gallery-item cover-bg" style="background-image: url(\'' . esc_url($demo_img) . '\')"></div></a>';
                    }
                endif; 
                ?>
            </div>
            
            <!-- Indicatori Slider (Estetica / Mockup per Progetto Sportivo) -->
            <div class="gallery-navigation" style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px;">
                <span style="color: var(--c-primary); font-size: 24px; font-weight: bold; cursor: pointer;">&lt;</span>
                <div class="gallery-dots" style="display: flex; gap: 8px;">
                    <span style="width: 8px; height: 8px; background-color: var(--c-primary); border-radius: 50%; display: inline-block;"></span>
                    <span style="width: 8px; height: 8px; border: 1px solid var(--c-primary); border-radius: 50%; display: inline-block;"></span>
                    <span style="width: 8px; height: 8px; border: 1px solid var(--c-primary); border-radius: 50%; display: inline-block;"></span>
                    <span style="width: 8px; height: 8px; border: 1px solid var(--c-primary); border-radius: 50%; display: inline-block;"></span>
                </div>
                <span style="color: var(--c-primary); font-size: 24px; font-weight: bold; cursor: pointer;">&gt;</span>
            </div>
        <?php endif; ?>
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
