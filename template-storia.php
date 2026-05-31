<?php
/**
 * Template Name: Pagina Storia
 *
 * @package Sport_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-storia">

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
        
        $hero_title = get_the_title();
        if ( strtolower($hero_title) === 'storia' ) {
            $hero_title = 'Storia del Club';
        }

        // Setup active states for the submenu
        $is_storia = true;
        $is_progetto = false;

        // Style helpers
        $btn_active = "padding: 10px 40px; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none; border: 2px solid var(--c-primary); background-color: var(--c-primary); color: var(--c-black);";
        $btn_inactive = "padding: 10px 40px; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none; border: 2px solid white; background-color: transparent; color: white; transition: all 0.3s;";
        ?>

        <div class="club-hero-wrapper">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block;" alt="<?php echo esc_attr($hero_title); ?>">
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="club-hero-title"><?php echo esc_html($hero_title); ?></h1>
                
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
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    $raw = get_the_content();
                    
                    // Se non ci sono tag <h2> significa che l'utente non ha eseguito lo script di aggiornamento.
                    // Formattiamo il testo on-the-fly per assicurarci che appaia perfetto come nel mockup.
                    if (strpos($raw, '<h2>') === false && strpos($raw, 'IDENTITÀ') !== false) {
                        $formatted = '<h2>IDENTITÀ DEL CLUB</h2>';
                        $formatted .= '<h3>Un’identità forte e radicata, nata nel dopoguerra dall’entusiasmo popolare e dall’amore per lo sport.</h3>';
                        $formatted .= '<p>Le origini della prima squadra di Taverne affondano le proprie radici negli anni Venti, con la nascita del Football Club Stella Taverne, prima vera formazione locale. Inizialmente si giocava nella zona di Livorno, lungo il corso del Vedeggio, indossando una maglia nera con una stella bianca sul petto. Negli anni successivi, il campo fu spostato a Taverne Superiore e, negli anni Quaranta, nell’area della stazione.</p>';
                        $formatted .= '<p>Nel secondo dopoguerra nacque l’Associazione Calcio Taverne, sostenuta da grande entusiasmo popolare. La società divenne rapidamente un punto di riferimento sportivo per tutta la valle, grazie a un ambiente favorevole, ottimi allenatori e ad una solida comunità. A questo periodo eroico sono legati nomi importanti come Mario Banfi, Flaminio Petrocchi e Gino Gova, mentre già negli anni Trenta si distinsero i fratelli Zambelli, in particolare il portiere Emilio, detto “Zamorra”.</p>';
                        $formatted .= '<p>Un momento chiave arrivò nel 1950, quando l’AC Taverne entrò ufficialmente nella Federazione calcistica ticinese partecipando al campionato di Quarta Divisione, ottenendo subito la promozione in Terza. La crescita proseguì fino alla stagione 1956-57, in cui, sotto la guida di Bruno Passardi, la squadra conquistò il titolo di campione di Terza Divisione. L’anno successivo, con Dino Leoni alla guida, il Taverne si confermò vincendo sia il campionato sia il Trofeo Ticino, segnando una delle pagine più significative della sua storia.</p>';
                        
                        $formatted .= '<h2>RUOLO DELLA PRIMA SQUADRA</h2>';
                        $formatted .= '<h3>La prima squadra come traino e punto di riferimento di tutta l\'attività agonistica giallonera.</h3>';
                        $formatted .= '<p>La prima squadra rappresenta la vetrina principale del club e il traguardo naturale per tutti i giovani che crescono nel settore giovanile. Nel corso degli anni, essa ha svolto il ruolo di traino per l\'intera comunità sportiva di Taverne, ispirando generazioni di atleti locali e promuovendo l\'attaccamento dei tesserati e dei tifosi ai colori gialloneri.</p>';
                        $formatted .= '<p>Oggi, la prima squadra partecipa a campionati di livello nazionale, portando con orgoglio il nome del club e del territorio oltre i confini regionali, sempre guidata dai valori storici di lealtà, dedizione e passione.</p>';

                        $formatted .= '<h2>EVOLUZIONE NEL TEMPO</h2>';
                        $formatted .= '<h3>Una crescita costante nel tempo, costruendo un percorso solido nel panorama calcistico nazionale.</h3>';
                        $formatted .= '<p>Nel corso della sua storia, il Taverne ha saputo tracciare una linea coerente, caratterizzata da tappe significative. Tra gli anni Ottanta e Novanta, la prima squadra ha partecipato con regolarità ai campionati di Seconda e Terza Lega, consolidando la propria presenza e gettando le basi per i successi futuri. Un primo importante salto di qualità si registra nella stagione 2009-2010, quando il Taverne conquista il terzo rango in Seconda Lega regionale ottenendo la promozione in Seconda Lega Interregionale. Si tratta di un traguardo storico, che segna l’ingresso del club, a partire dal 2010, in un contesto di competizione nazionale.</p>';
                        $formatted .= '<p>Nel campionato 2011-2012, il Taverne tenta la scalata alla Seconda Lega élite, categoria già raggiunta con merito due anni prima e sfortunatamente persa nella stagione successiva. L’annata seguente, 2012-2013, si apre con segnali positivi: la squadra si presenta briosa e promettente, mantenendo il primo posto in classifica al termine del girone d’andata.</p>';
                        $formatted .= '<p>A partire dalla stagione 2019-2020, il Taverne milita stabilmente in Prima Lega Classic, raggiungendo il livello più alto nella storia della società dopo alcune stagioni di consolidamento nei campionati interregionali.</p>';
                        
                        $formatted .= '<h2>IL SETTORE GIOVANILE E I SUCCESSI</h2>';
                        $formatted .= '<h3>Il vivaio e le squadre giovanili come risorsa fondamentale e garanzia per il futuro del club.</h3>';
                        $formatted .= '<p>Parallelamente ai risultati della prima squadra, il club ha sempre attribuito una rilevanza strategica al settore giovanile. I ragazzi delle categorie Allievi, sempre più numerosi, rappresentano una prospettiva concreta ed una risorsa fondamentale su cui fondare il domani.</p>';
                        $formatted .= '<p>Il loro sviluppo è affidato a dirigenti, allenatori e preparatori che privilegiano una crescita progressiva e duratura rispetto ai risultati immediati.</p>';
                        $formatted .= '<p>Nel corso degli anni, il club ha collezionato numerosi successi, tra cui:</p>';
                        $formatted .= '<ul><li>Campione ticinese di Terza Divisione e promozione in Seconda Lega (stagione 1956-1957)</li><li>Campione ticinese di Seconda Divisione e promozione in Seconda Lega (stagione 1958-1959)</li><li>Vincitore di gruppo di Terza Divisione e promozione in Seconda Lega (stagione 1979-1980)</li><li>Vincitore di gruppo di Terza Lega e promozione in Seconda Lega (stagioni 1992-1993 e 2004-2005)</li><li>Terzo posto in Seconda Lega regionale e promozione in Seconda Lega Interregionale (stagione 2009-2010)</li></ul>';
                        
                        $formatted .= '<p>A questi si aggiungono i risultati del settore giovanile e della seconda squadra:</p>';
                        $formatted .= '<ul><li>Campione ticinese Allievi A e promozione nella categoria Interregionale (stagione 1986-1987)</li><li>Seconda squadra campione di gruppo in Quinta Lega e promossa in Quarta Lega (stagione 2007-2008)</li></ul>';
                        
                        $formatted .= '<p>Di particolare rilievo anche il percorso nelle competizioni regionali: il Taverne ha conquistato sei Coppe Ticino, stabilendo un record prestigioso, e ha ottenuto un primo e un secondo posto nella Coppa Campioni del calcio regionale ticinese.</p>';
                        $formatted .= '<p>Dalla stagione attuale, la prima squadra si presenta con un nuovo assetto societario, segnando l’inizio di una nuova fase nel percorso di sviluppo del club, nel segno della continuità e dell’attenzione alla propria storia.</p>';
                        
                        echo $formatted;
                    } else {
                        the_content();
                    }
                endwhile;
            endif;
            ?>
    </div>

    <!-- DYNAMIC FOTOGALLERY (dal Custom Post Type Foto Gallery) -->
    <?php
    $gallery_args = array(
        'post_type'      => 'fotogallery',
        'posts_per_page' => -1,
        'tax_query'      => array(
            array(
                'taxonomy' => 'categoria_galleria',
                'field'    => 'slug',
                'terms'    => 'storia',
            ),
        ),
    );
    $gallery_query = new WP_Query($gallery_args);
    ?>
    <section class="container club-content ps-section" style="padding-bottom: 60px;">
        <h2 class="section-title text-white" style="margin-bottom: 30px;">FOTOGALLERY</h2>
        
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
