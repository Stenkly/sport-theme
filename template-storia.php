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
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        $subtitle = get_the_excerpt();
        ?>
        <style>
        .storia-hero-title {
            font-size: 70px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0 0 15px 0;
            letter-spacing: 2px;
            color: white;
        }
        .club-submenu {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .club-submenu a {
            padding: 10px 30px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            text-decoration: none;
            white-space: nowrap;
        }
        .club-submenu a.active-btn {
            border: 2px solid var(--c-primary);
            background-color: var(--c-primary);
            color: var(--c-black);
        }
        .club-submenu a.outline-btn {
            border: 2px solid white;
            background-color: transparent;
            color: white;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .storia-hero-title { font-size: 36px; letter-spacing: 1px; }
            .club-submenu { gap: 8px; }
            .club-submenu a { padding: 8px 18px; font-size: 11px; }
            .storia-hero-content { bottom: 20px !important; }
        }
        @media (max-width: 480px) {
            .storia-hero-title { font-size: 28px; }
            .club-submenu { flex-direction: column; gap: 8px; }
            .club-submenu a { text-align: center; }
        }
        </style>

        <div class="club-hero-wrapper">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 80%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content storia-hero-content container" style="position: absolute; bottom: 60px; left: 0; right: 0; text-align: left;">
                <h1 class="club-hero-title"><?php echo get_the_title(); ?></h1>

                <hr style="border: 0; border-top: 2px solid white; margin: 15px 0;">

                <div class="club-submenu">
                    <a href="<?php echo esc_url( site_url('/organigramma') ); ?>" class="outline-btn">ORGANIGRAMMA</a>
                    <a href="<?php echo esc_url( site_url('/storia') ); ?>" class="active-btn">STORIA</a>
                    <a href="<?php echo esc_url( site_url('/progetto-sportivo') ); ?>" class="outline-btn">PROGETTO SPORTIVO</a>
                </div>
            </div>
        </div>
    </section>


    <section class="ps-section container" style="padding-top: 60px; padding-bottom: 60px;">
        <style>
            .storia-content h2, .storia-content h3 {
                color: var(--c-primary);
                font-size: 32px;
                font-weight: 800;
                text-transform: uppercase;
                margin-top: 60px;
                margin-bottom: 25px;
                letter-spacing: 1px;
            }
            .storia-content h4 {
                color: white;
                font-size: 16px;
                font-weight: 700;
                text-transform: uppercase;
                margin-bottom: 15px;
                line-height: 1.5;
            }
            .storia-content p {
                color: #ffffff;
                font-size: 15px;
                line-height: 1.6;
                font-weight: 400;
                margin-bottom: 20px;
            }
            .storia-content ul {
                color: #ffffff;
                font-size: 15px;
                line-height: 1.6;
                font-weight: 400;
                margin-bottom: 20px;
                padding-left: 20px;
            }
            .storia-content li {
                margin-bottom: 10px;
            }
        </style>
        <div class="storia-content" style="max-width: 100%;">
            <?php 
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    $raw = get_the_content();
                    
                    // Se non ci sono tag <h2> significa che l'utente non ha eseguito lo script di aggiornamento.
                    // Formattiamo il testo on-the-fly per assicurarci che appaia perfetto come nel mockup.
                    if (strpos($raw, '<h2>') === false && strpos($raw, 'IDENTITÀ') !== false) {
                        $formatted = '<h2>IDENTITÀ DEL CLUB</h2>';
                        $formatted .= '<h4>Nel secondo dopoguerra nacque l’Associazione Calcio Taverne, sostenuta da grande entusiasmo popolare.</h4>';
                        $formatted .= '<p>La storia della prima squadra di Taverne affonda le sue radici negli anni Venti, quando nacque il Football Club Stella Taverne, prima vera formazione locale, seppur con notizie frammentarie e denominazioni variabili. Inizialmente si giocava nella zona di Livorno, lungo il corso del Vedeggio, con una caratteristica maglia nera impreziosita da una stella bianca sul petto. Successivamente il campo si spostò a Taverne Superiore, nel Comune di Sigirino, e negli anni Quaranta, con il F.C. Taverne, nell’area della stazione, tra il fiume e la ferrovia.</p>';
                        $formatted .= '<p>La società divenne un punto di riferimento sportivo per tutta la valle, grazie a un ambiente favorevole, buoni allenatori e una solida comunità. A questo periodo, definito “eroico”, sono legati nomi importanti come Mario Banfi, Flaminio Petrocchi e Gino Gova, mentre già negli anni Trenta si distinsero a livello regionale i fratelli Zambelli, in particolare il portiere Emilio, soprannominato “Zamorra”.</p>';
                        $formatted .= '<p>Un momento chiave arrivò nel 1950, quando l’AC Taverne entrò ufficialmente nella Federazione calcistica ticinese partecipando al campionato di Quarta Divisione, ottenendo subito la promozione in Terza. La crescita proseguì fino alla stagione 1956-57, in cui, sotto la guida di Bruno Passardi, la squadra conquistò il titolo di campione di Terza Divisione. L’anno successivo, con Dino Leoni alla guida, il Taverne si confermò vincendo sia il campionato sia il Trofeo Ticino, segnando una delle pagine più significative della sua storia.</p>';
                        
                        $formatted .= '<h2>EVOLUZIONE NEL TEMPO</h2>';
                        $formatted .= '<h4>Nel corso della sua storia, il Taverne ha costruito un percorso solido e coerente, caratterizzato da tappe significative e da una crescita costante nel panorama calcistico regionale e nazionale.</h4>';
                        $formatted .= '<p>Tra gli anni Ottanta e Novanta, la prima squadra ha partecipato con regolarità ai campionati di Seconda e Terza Lega, consolidando la propria presenza e gettando le basi per i successi futuri. Un primo importante salto di qualità si registra nella stagione 2009-2010, quando il Taverne conquista il terzo rango in Seconda Lega regionale ottenendo la promozione in Seconda Lega Interregionale. Si tratta di un traguardo storico, che segna l’ingresso del club, a partire dal 2010, in un contesto di competizione nazionale.</p>';
                        $formatted .= '<p>Nel campionato 2011-2012, il Taverne tenta la scalata alla Seconda Lega élite, categoria già raggiunta con merito due anni prima e sfortunatamente persa nella stagione successiva. L’annata seguente, 2012-2013, si apre con segnali positivi: la squadra si presenta briosa e promettente, mantenendo il primo posto in classifica al termine del girone d’andata.</p>';
                        $formatted .= '<p>A partire dalla stagione 2019-2020, il Taverne milita stabilmente in Prima Lega Classic, raggiungendo il livello più alto nella storia della società dopo alcune stagioni di consolidamento nei campionati interregionali.</p>';
                        
                        $formatted .= '<h2>IL SETTORE GIOVANILE E I SUCCESSI</h2>';
                        $formatted .= '<h4>Parallelamente ai risultati della prima squadra, il club ha sempre attribuito grande importanza al settore giovanile. I ragazzi delle categorie Allievi, sempre più numerosi, rappresentano una risorsa fondamentale e una prospettiva concreta per il futuro.</h4>';
                        $formatted .= '<p>Il loro sviluppo è affidato a dirigenti, allenatori e preparatori che privilegiano una crescita progressiva e duratura rispetto ai risultati immediati.</p>';
                        $formatted .= '<p>Nel corso degli anni, il club ha collezionato numerosi successi, tra cui:</p>';
                        $formatted .= '<ul><li>Campione ticinese di Terza Divisione e promozione in Seconda Lega (stagione 1956-1957)</li><li>Campione ticinese di Seconda Divisione (stagione 1958-1959)</li><li>Vincitore di gruppo di Terza Divisione e promozione in Seconda Lega (stagione 1979-1980)</li><li>Vincitore di gruppo di Terza Lega e promozione in Seconda Lega (stagioni 1992-1993 e 2004-2005)</li><li>Terzo posto in Seconda Lega regionale e promozione in Seconda Lega Interregionale (stagione 2009-2010)</li></ul>';
                        
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
    </section>

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
        <h2 class="text-white" style="font-size: 32px; font-weight: 800; text-transform: uppercase; margin-bottom: 20px; color: white;">FOTOGALLERY</h2>
        
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
                <figure class="wp-block-image"><img src="https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=800&auto=format&fit=crop" style="filter: grayscale(1);"></figure>
                <figure class="wp-block-image"><img src="https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1000&auto=format&fit=crop" style="filter: grayscale(1);"></figure>
                <figure class="wp-block-image"><img src="https://images.unsplash.com/photo-1551280857-2b9eb02029c3?q=80&w=800&auto=format&fit=crop" style="filter: grayscale(1);"></figure>
                <figure class="wp-block-image"><img src="https://images.unsplash.com/photo-1522778526582-12002162a043?q=80&w=800&auto=format&fit=crop" style="filter: grayscale(1);"></figure>
                <figure class="wp-block-image"><img src="https://images.unsplash.com/photo-1508344928928-7137b29de218?q=80&w=800&auto=format&fit=crop" style="filter: grayscale(1);"></figure>
                <figure class="wp-block-image"><img src="https://images.unsplash.com/photo-1553147573-0ff7d2b45053?q=80&w=800&auto=format&fit=crop" style="filter: grayscale(1);"></figure>
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
        <p style="font-size: 15px; color: #aaaaaa; margin-top: 20px;">[ Sezione Instagram: Installa il plugin "Smash Balloon" per attivare questa area ]</p>
    </section>

</main>

<?php get_footer(); ?>
