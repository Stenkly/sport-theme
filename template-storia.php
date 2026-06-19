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
                    <a href="<?php echo esc_url( site_url('/presente-e-futuro') ); ?>" class="<?php echo $is_progetto ? '' : 'btn-outline-hover'; ?>" style="<?php echo $is_progetto ? $btn_active : $btn_inactive; ?>">PRESENTE E FUTURO</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="container club-content" style="padding-top: 16px; padding-bottom: 30px;">
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

    <!-- FOTOGALLERY -->
    <?php
    $storia_gallery_ids = get_post_meta( $page_id, '_storia_gallery_ids', true );
    $storia_gallery_ids = array_filter( array_map( 'absint', explode( ',', (string) $storia_gallery_ids ) ) );
    $storia_gallery_items = array();

    foreach ( $storia_gallery_ids as $attachment_id ) {
        $large_url = wp_get_attachment_image_url( $attachment_id, 'large' );
        $full_url  = wp_get_attachment_image_url( $attachment_id, 'full' );
        if ( $large_url ) {
            $storia_gallery_items[] = array(
                'large' => $large_url,
                'full'  => $full_url ? $full_url : $large_url,
                'alt'   => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
            );
        }
    }

    if ( empty( $storia_gallery_items ) ) {
        $gallery_query = new WP_Query(array(
            'post_type'      => 'fotogallery',
            'posts_per_page' => -1,
            'tax_query'      => array(array(
                'taxonomy' => 'categoria_galleria',
                'field'    => 'slug',
                'terms'    => 'storia',
            )),
        ));

        if ( $gallery_query->have_posts() ) {
            while ( $gallery_query->have_posts() ) {
                $gallery_query->the_post();
                if ( has_post_thumbnail() ) {
                    $large_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                    $full_url  = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                    $storia_gallery_items[] = array(
                        'large' => $large_url,
                        'full'  => $full_url ? $full_url : $large_url,
                        'alt'   => get_the_title(),
                    );
                }
            }
            wp_reset_postdata();
        }
    }
    ?>
    <?php if ( ! empty( $storia_gallery_items ) ) : ?>
        <section class="container ps-section storia-gallery-section">
            <h2 class="section-title text-white" style="margin-bottom:30px;">FOTOGALLERY</h2>

            <div id="storia-gallery-carousel" class="storia-gallery-carousel" style="display:flex; gap:20px; align-items:center; overflow:hidden; scroll-behavior:smooth;">
                <?php foreach ( $storia_gallery_items as $index => $gallery_item ) : ?>
                    <a data-fancybox="storia-gallery" href="<?php echo esc_url( $gallery_item['full'] ); ?>" class="gallery-slide<?php echo $index === 0 ? ' active' : ''; ?>">
                        <div class="gallery-item cover-bg" style="background-image: url('<?php echo esc_url( $gallery_item['large'] ); ?>')" role="img" aria-label="<?php echo esc_attr( $gallery_item['alt'] ? $gallery_item['alt'] : 'Foto storia AC Taverne' ); ?>"></div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="carousel-nav gallery-nav storia-gallery-nav" style="margin-top:15px;">
                <span class="nav-arrow text-primary" id="storia-gallery-prev" style="cursor:pointer;"><i class="fa-solid fa-chevron-left"></i></span>
                <span class="nav-dots" id="storia-gallery-dots">
                    <?php foreach ( $storia_gallery_items as $index => $gallery_item ) : ?>
                        <i class="<?php echo $index === 0 ? 'fa-solid' : 'fa-regular'; ?> fa-circle<?php echo $index === 0 ? ' active' : ''; ?>" data-page="<?php echo esc_attr( $index ); ?>"></i>
                    <?php endforeach; ?>
                </span>
                <span class="nav-arrow text-primary" id="storia-gallery-next" style="cursor:pointer;"><i class="fa-solid fa-chevron-right"></i></span>
            </div>

            <script>
            (function(){
                var car = document.getElementById('storia-gallery-carousel');
                if (!car) return;

                var prev = document.getElementById('storia-gallery-prev');
                var next = document.getElementById('storia-gallery-next');
                var dots = document.querySelectorAll('#storia-gallery-dots .fa-circle');
                var slides = car.querySelectorAll('.gallery-slide');
                var cur = 0;
                var isAnimating = false;

                function getGap() {
                    var styles = window.getComputedStyle(car);
                    return parseFloat(styles.columnGap || styles.gap || 20) || 20;
                }

                function getScrollPosition(index) {
                    var pos = 0;
                    var gap = getGap();
                    for (var i = 0; i < index; i++) {
                        pos += slides[i].offsetWidth + gap;
                    }
                    return pos;
                }

                function updateActiveState(index) {
                    cur = index;
                    dots.forEach(function(d,i){
                        if (i === cur) {
                            d.classList.remove('fa-regular');
                            d.classList.add('fa-solid', 'active');
                        } else {
                            d.classList.remove('fa-solid', 'active');
                            d.classList.add('fa-regular');
                        }
                    });
                    slides.forEach(function(s,i){ s.classList.toggle('active', i === cur); });
                }

                function go(n) {
                    var max = slides.length - 1;
                    cur = Math.max(0, Math.min(n, max));
                    var maxScroll = car.scrollWidth - car.clientWidth;
                    var targetScroll = Math.min(getScrollPosition(cur), maxScroll);

                    isAnimating = true;
                    car.scrollTo({ left: targetScroll, behavior: 'smooth' });
                    updateActiveState(cur);

                    setTimeout(function(){ isAnimating = false; }, 400);
                }

                if (prev) prev.addEventListener('click', function(){ go(cur - 1); });
                if (next) next.addEventListener('click', function(){ go(cur + 1); });
                dots.forEach(function(d,i){ d.addEventListener('click', function(){ go(i); }); });

                car.addEventListener('scroll', function() {
                    if (isAnimating) return;
                    var scrollLeft = car.scrollLeft;
                    var closestIndex = 0;
                    var minDiff = Infinity;
                    for (var i = 0; i < slides.length; i++) {
                        var diff = Math.abs(getScrollPosition(i) - scrollLeft);
                        if (diff < minDiff) {
                            minDiff = diff;
                            closestIndex = i;
                        }
                    }
                    if (closestIndex !== cur && closestIndex >= 0 && closestIndex < slides.length) {
                        updateActiveState(closestIndex);
                    }
                });
            })();
            </script>
        </section>
    <?php endif; ?>

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
