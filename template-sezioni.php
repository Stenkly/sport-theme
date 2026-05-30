<?php
/**
 * Template Name: Pagina Sezioni
 *
 * @package Sport_Theme
 */

get_header('societa');

// Legge il parametro ?cat= per pre-selezionare una categoria (es: /sezioni?cat=allievi)
$default_cat = isset($_GET['cat']) ? sanitize_title($_GET['cat']) : '';

// Recupera le categorie Root (Livello 1)
$root_categories = get_terms(array(
    'taxonomy'   => 'categoria_sezione',
    'parent'     => 0,
    'hide_empty' => false,
));

$has_real_data = false;
$struttura = array();

if ( ! empty($root_categories) && ! is_wp_error($root_categories) ) {
    // Ordine personalizzato per le categorie principali se esistono
    $ordine_cat = ['Attivi', 'Allievi', 'Femminile'];
    usort($root_categories, function($a, $b) use ($ordine_cat) {
        $pos_a = array_search($a->name, $ordine_cat);
        $pos_b = array_search($b->name, $ordine_cat);
        if($pos_a === false) $pos_a = 99;
        if($pos_b === false) $pos_b = 99;
        return $pos_a <=> $pos_b;
    });

    foreach ($root_categories as $root_cat) {
        $sub_cats = get_terms(array(
            'taxonomy' => 'categoria_sezione',
            'parent' => $root_cat->term_id,
            'hide_empty' => false
        ));

        $cat_data = array('type' => '', 'items' => array());

        if ( !empty($sub_cats) && !is_wp_error($sub_cats) ) {
            $cat_data['type'] = 'subcats';
            foreach ($sub_cats as $sub) {
                $teams = get_posts(array(
                    'post_type' => 'squadra_sezione',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array('taxonomy' => 'categoria_sezione', 'field' => 'term_id', 'terms' => $sub->term_id)
                    ),
                    'orderby' => 'menu_order title',
                    'order' => 'ASC'
                ));
                if (!empty($teams)) $has_real_data = true;

                $teams_data = array();
                foreach($teams as $t) {
                    $teams_data[] = array(
                        'id'          => $t->ID,
                        'titolo'      => $t->post_title,
                        'descrizione' => $t->post_content,
                        'giorni'      => get_post_meta($t->ID, '_ss_giorni', true),
                        'allenatore'  => get_post_meta($t->ID, '_ss_allenatore', true),
                        'assistente'  => get_post_meta($t->ID, '_ss_assistente', true),
                        'immagine'    => has_post_thumbnail($t->ID) ? get_the_post_thumbnail_url($t->ID, 'large') : 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                        'iframe'      => get_post_meta($t->ID, '_ss_iframe', true)
                    );
                }
                $cat_data['items'][$sub->name] = $teams_data;
            }
        } else {
            $cat_data['type'] = 'teams';
            $teams = get_posts(array(
                'post_type' => 'squadra_sezione',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array('taxonomy' => 'categoria_sezione', 'field' => 'term_id', 'terms' => $root_cat->term_id)
                ),
                'orderby' => 'menu_order title',
                'order' => 'ASC'
            ));
            if (!empty($teams)) $has_real_data = true;

            $teams_data = array();
            foreach($teams as $t) {
                $teams_data[] = array(
                    'id'          => $t->ID,
                    'titolo'      => $t->post_title,
                    'descrizione' => $t->post_content,
                    'giorni'      => get_post_meta($t->ID, '_ss_giorni', true),
                    'allenatore'  => get_post_meta($t->ID, '_ss_allenatore', true),
                    'assistente'  => get_post_meta($t->ID, '_ss_assistente', true),
                    'immagine'    => has_post_thumbnail($t->ID) ? get_the_post_thumbnail_url($t->ID, 'large') : 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                    'iframe'      => get_post_meta($t->ID, '_ss_iframe', true)
                );
            }
            $cat_data['items'] = $teams_data;
        }

        $struttura[$root_cat->name] = $cat_data;
    }
}

// Fallback fittizio (Mockup esatto a 3 livelli) se non c'è nessun dato
if ( ! $has_real_data ) {
    $struttura = array(
        'ATTIVI' => array(
            'type' => 'teams',
            'items' => array(
                array(
                    'id' => 'mock-1',
                    'titolo' => 'IV LEGA',
                    'descrizione' => "La Squadra di IV Lega è composta da giocatori esperti ed ex allievi ora attivi cresciuti nella nostra società e appassionati che, nonostante l'età, mantengono viva la passione per il calcio e continuano a competere con grinta e determinazione.",
                    'giorni' => "Martedì: 19:30 - 21:00\nGiovedì: 19:30 - 21:00",
                    'allenatore' => 'Stefano Mamezza',
                    'assistente' => 'Domenico Saporito',
                    'immagine' => 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                    'iframe' => ''
                ),
                array(
                    'id' => 'mock-2',
                    'titolo' => 'SENIORI 30+',
                    'descrizione' => "La Squadra Seniori 30+ è composta da giocatori esperti e appassionati che, nonostante l'età, mantengono viva la passione per il calcio e continuano a competere con grinta e determinazione.",
                    'giorni' => "Martedì: 19:30 - 21:00",
                    'allenatore' => "Angelo Clemente\n+41 78 676 46 06",
                    'assistente' => 'Maurizio Marcon / Antonio Casale',
                    'immagine' => 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1200&auto=format&fit=crop',
                    'iframe' => ''
                )
            )
        ),
        'ALLIEVI' => array(
            'type' => 'subcats',
            'items' => array(
                'ALLIEVI A' => array(
                    array(
                        'id' => 'mock-allievi-a',
                        'titolo' => 'ALLIEVI A',
                        'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nei giovani, offrendo loro formazione tecnica e valori sportivi.",
                        'giorni' => "Lunedi: 19:30 - 21:00\nMercoledì: 19:30 - 21:00\nVenerdì: 19:30 - 21:00",
                        'allenatore' => 'Gianpiero Zoppi',
                        'assistente' => 'Marco Gerosa',
                        'immagine' => 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1200&auto=format&fit=crop',
                        'iframe' => ''
                    )
                ),
                'ALLIEVI B' => array(
                    array(
                        'id' => 'mock-allievi-b',
                        'titolo' => 'ALLIEVI B',
                        'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nei giovani, offrendo loro formazione tecnica e valori sportivi.",
                        'giorni' => "Lunedì: 19:30 - 21:00\nMercoledì: 19:30 - 21:00",
                        'allenatore' => 'Leonardo Massera',
                        'assistente' => '',
                        'immagine' => 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                        'iframe' => ''
                    )
                ),
                'ALLIEVI C' => array(
                    array(
                        'id' => 'mock-allievi-c',
                        'titolo' => 'ALLIEVI C',
                        'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nei giovani, offrendo loro formazione tecnica e valori sportivi.",
                        'giorni' => "Martedì: 17:45 - 19:15\nGiovedì: 17:45 - 19:15",
                        'allenatore' => 'Daniele Meneghelli',
                        'assistente' => 'Francesco Foresta',
                        'immagine' => 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1200&auto=format&fit=crop',
                        'iframe' => ''
                    )
                ),
                'ALLIEVI D' => array(
                    array(
                        'id' => 'mock-3',
                        'titolo' => 'ALLIEVI D9 2012 - 2013',
                        'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nei giovani, offrendo loro formazione tecnica e valori sportivi.",
                        'giorni' => "Martedì: 17:45 - 19:15\nVenerdì: 17:45 - 19:15",
                        'allenatore' => 'Siro Pacchioni',
                        'assistente' => 'Francesco Fera',
                        'immagine' => 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1200&auto=format&fit=crop',
                        'iframe' => ''
                    ),
                    array(
                        'id' => 'mock-4',
                        'titolo' => 'ALLIEVI D9 2013 - 2014',
                        'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nei giovani, offrendo loro formazione tecnica e valori sportivi.",
                        'giorni' => "Martedì: 17:45 - 19:15\nVenerdì: 17:45 - 19:15",
                        'allenatore' => 'Andrea Cistaro',
                        'assistente' => '',
                        'immagine' => 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                        'iframe' => ''
                    )
                ),
                'ALLIEVI E' => array(
                    array(
                        'id' => 'mock-allievi-e1',
                        'titolo' => 'ALLIEVI E1 2015',
                        'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nei giovani, offrendo loro formazione tecnica e valori sportivi.",
                        'giorni' => "Mercoledì: 17:30 - 19:00\nVenerdì: 17:30 - 19:00",
                        'allenatore' => "Maicol Prudente\nOreste Zeppetella\nPietro Foresta",
                        'assistente' => '',
                        'immagine' => 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                        'iframe' => ''
                    ),
                    array(
                        'id' => 'mock-allievi-e2',
                        'titolo' => 'ALLIEVI E2 2016',
                        'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nei giovani, offrendo loro formazione tecnica e valori sportivi.",
                        'giorni' => "Mercoledì: 17:30 - 19:00\nVenerdì: 17:30 - 19:00",
                        'allenatore' => "Maicol Prudente\nOreste Zeppetella\nPietro Foresta",
                        'assistente' => '',
                        'immagine' => 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1200&auto=format&fit=crop',
                        'iframe' => ''
                    ),
                    array(
                        'id' => 'mock-allievi-e3',
                        'titolo' => 'ALLIEVI E3 2016',
                        'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nei giovani, offrendo loro formazione tecnica e valori sportivi.",
                        'giorni' => "Mercoledì: 17:30 - 19:00\nVenerdì: 17:30 - 19:00",
                        'allenatore' => "Maicol Prudente\nOreste Zeppetella\nPietro Foresta",
                        'assistente' => '',
                        'immagine' => 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                        'iframe' => ''
                    )
                ),
                'PORTIERI' => array(
                    array(
                        'id' => 'mock-portieri',
                        'titolo' => 'PORTIERI',
                        'descrizione' => "Comprendendo l'importanza cruciale di questo ruolo in ogni squadra di calcio.\n\nOffriamo programmi di allenamento specifici per sviluppare le abilità tecniche, tattiche e mentali dei nostri portieri, dalle categorie giovanili fino alla Prima Squadra.",
                        'giorni' => "<b>Allievi A</b><br>Lunedì - Mercoledì - Venerdì: 19:30 - 21:00<br><br><b>Allievi B</b><br>Lunedì - Mercoledì - Venerdì: 19:30 - 21:00<br><br><b>Allievi C</b><br>Martedì e Giovedì: 17:45 - 19:15<br><br><b>Allievi D</b><br>Martedì e Venerdì: 17:45 - 19:15",
                        'allenatore' => "<b>RESPONSABILE TECNICO</b><br>Andrea Pasquot<br><br><b>ALLENATORI</b><br>Allievi A: Antonio Pace<br>Allievi B: Marcello Clemente<br>Allievi C+D: Andrea Pasquot",
                        'assistente' => "<b>FEMMINILE</b><br>Danilo Muschietti",
                        'immagine' => 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                        'iframe' => ''
                    )
                )
            )
        ),
        'FEMMINILE' => array(
            'type' => 'teams',
            'items' => array(
                array(
                    'id' => 'mock-femm-c9',
                    'titolo' => 'SEZ. FEMMINILE ALLIEVE C9',
                    'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nelle giovani, offrendo loro formazione tecnica e valori sportivi.\n\n<b>Per informazioni e iscrizioni:</b><br>Tel: Marco +41 79 206 85 24<br>Tel: Rosanna +41 79 655 52 90<br>Mail: rosanna.michelotti@outlook.com",
                    'giorni' => "Lunedì: 17:45 - 19:15\nGiovedì: 17:45 - 19:15",
                    'allenatore' => "Alfredo Moghini\nLidia Marcionelli",
                    'assistente' => 'Danilo Muschietti',
                    'immagine' => 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                    'iframe' => ''
                ),
                array(
                    'id' => 'mock-femm-d7-1',
                    'titolo' => 'SEZ. FEMMINILE ALLIEVE D7-1',
                    'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nelle giovani, offrendo loro formazione tecnica e valori sportivi.\n\n<b>Per informazioni e iscrizioni:</b><br>Tel: Marco +41 79 206 85 24<br>Tel: Rosanna +41 79 655 52 90<br>Mail: rosanna.michelotti@outlook.com",
                    'giorni' => "Lunedì: 17:45 - 19:15\nMercoledì: 16:00 - 17:30",
                    'allenatore' => "Marco Maggi\nRosanna Michelotti",
                    'assistente' => 'Danilo Muschietti',
                    'immagine' => 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop',
                    'iframe' => ''
                ),
                array(
                    'id' => 'mock-femm-d7',
                    'titolo' => 'SEZ. FEMMINILE ALLIEVE D7 - 2',
                    'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nelle giovani, offrendo loro formazione tecnica e valori sportivi.\n\n<b>Per informazioni e iscrizioni:</b><br>Tel: Marco +41 79 206 85 24<br>Tel: Rosanna +41 79 655 52 90<br>Mail: rosanna.michelotti@outlook.com",
                    'giorni' => "Lunedì: 17:45 - 19:15\nMercoledì: 16:00 - 17:30",
                    'allenatore' => "Christian Lamprecht\nEnrico Conte",
                    'assistente' => 'Danilo Muschietti',
                    'immagine' => 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1200&auto=format&fit=crop',
                    'iframe' => ''
                ),
                array(
                    'id' => 'mock-femm-e',
                    'titolo' => 'SEZ. FEMMINILE ALLIEVE E',
                    'descrizione' => "Il Settore Giovanile è il cuore pulsante della nostra associazione.\n\nInvestiamo molto nelle giovani, offrendo loro formazione tecnica e valori sportivi.\n\n<b>Per informazioni e iscrizioni:</b><br>Tel: Marco +41 79 206 85 24<br>Tel: Rosanna +41 79 655 52 90<br>Mail: rosanna.michelotti@outlook.com",
                    'giorni' => "Lunedì: 17:45 - 19:15\nMercoledì: 16:00 - 17:30",
                    'allenatore' => "Marco Maggi\nRosanna Michelotti",
                    'assistente' => 'Danilo Muschietti',
                    'immagine' => 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=1200&auto=format&fit=crop',
                    'iframe' => ''
                )
            )
        )
    );
}
// Funzione helper per stampare il pannello di una singola squadra
if(!function_exists('print_sezione_panel')) {
    function print_sezione_panel($sq) {
        ?>
        <!-- SEZIONE SPLIT: INFO E IMMAGINE -->
        <div class="container" style="padding-top: 50px;">
            <div style="display: flex; flex-wrap: wrap; background-color: #050505; border: 1px solid #1a1a1a;">
                
                <!-- LATO SINISTRO (Testo) -->
                <div style="flex: 1; min-width: 300px; padding: 60px; display: flex; flex-direction: column; justify-content: center;">
                    <h2 class="text-white" style="font-size: 35px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;">
                        <?php echo esc_html($sq['titolo']); ?>
                    </h2>
                    
                    <div style="color: white; font-size: 14px; line-height: 1.8; margin-bottom: 40px;">
                        <?php echo wpautop(wp_kses_post($sq['descrizione'])); ?>
                    </div>

                    <?php if($sq['giorni']): ?>
                        <div style="margin-bottom: 25px;">
                            <h4 style="color: var(--c-primary); font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">GIORNI DI ALLENAMENTO</h4>
                            <div style="color: white; font-size: 14px; line-height: 1.6;">
                                <?php echo nl2br(wp_kses_post($sq['giorni'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($sq['allenatore']): ?>
                        <div style="margin-bottom: 25px;">
                            <h4 style="color: var(--c-primary); font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">ALLENATORE</h4>
                            <div style="color: white; font-size: 14px; line-height: 1.6;">
                                <?php echo nl2br(wp_kses_post($sq['allenatore'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($sq['assistente']): ?>
                        <div style="margin-bottom: 25px;">
                            <h4 style="color: var(--c-primary); font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">ASSISTENTE</h4>
                            <div style="color: white; font-size: 14px; line-height: 1.6;">
                                <?php echo nl2br(wp_kses_post($sq['assistente'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- LATO DESTRO (Immagine) -->
                <div style="flex: 1; min-width: 300px; position: relative;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 50%); z-index: 1;"></div>
                    <img src="<?php echo esc_url($sq['immagine']); ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr($sq['titolo']); ?>">
                </div>

            </div>
        </div>

        <!-- SEZIONE CLASSIFICA -->
        <?php 
        $titolo_up = strtoupper($sq['titolo']);
        $hide_classifica = ($titolo_up === 'PORTIERI' || strpos($titolo_up, 'ALLIEVI E') !== false || strpos($titolo_up, 'ALLIEVE E') !== false);
        if(!$hide_classifica): 
        ?>
        <div class="container" style="padding-top: 60px; padding-bottom: 40px;">
            <h2 class="text-white" style="font-size: 35px; font-weight: 700; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 1px;">CLASSIFICA</h2>
            
            <?php if($sq['iframe']): ?>
                <div class="classifica-iframe-wrapper" style="width: 100%; overflow: hidden; background: white; padding: 20px; border-radius: 5px;">
                    <?php echo $sq['iframe']; ?>
                </div>
            <?php else: ?>
                <div style="width: 100%; padding: 40px; border: 2px dashed #444; text-align: center; color: #888; border-radius: 5px;">
                    [La classifica verrà mostrata qui. Incolla il codice Iframe da WordPress per questa squadra]
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php
    }
}
?>

<main id="primary" class="site-main page-sezioni">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh; min-height: 400px;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="Sezioni AC Taverne">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 20px;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">SEZIONI</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 20px 0;">
                
                <!-- MENU TABS LIVELLO 1 (CATEGORIE PRINCIPALI) -->
                <div class="sezioni-tabs-l1" style="display: flex; gap: 20px; margin-top: 30px; margin-bottom: 30px; flex-wrap: wrap;">
                    <?php 
                    $first_cat = true;
                    foreach ($struttura as $cat_name => $cat_data): 
                        $cat_slug = sanitize_title($cat_name);
                        // Attivo se corrisponde al parametro ?cat= oppure se è il primo e nessun parametro
                        $is_active = ($default_cat && $cat_slug === $default_cat) || (!$default_cat && $first_cat);
                        $active_class = $is_active ? 'active' : '';
                        $bg_color     = $is_active ? 'var(--c-primary)' : 'transparent';
                        $text_color   = $is_active ? '#000' : 'white';
                        $border_color = $is_active ? 'var(--c-primary)' : 'white';
                    ?>
                        <button class="sez-tab-btn-l1 <?php echo $active_class; ?>" data-cat="<?php echo esc_attr($cat_slug); ?>" style="background-color: <?php echo $bg_color; ?>; color: <?php echo $text_color; ?>; border: 2px solid <?php echo $border_color; ?>; padding: 10px 30px; font-weight: bold; text-transform: uppercase; font-size: 13px; cursor: pointer; transition: 0.3s; width: 220px;">
                            <?php echo esc_html(strtoupper($cat_name)); ?>
                        </button>
                    <?php 
                        $first_cat = false;
                    endforeach; 
                    ?>
                </div>

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.2); margin-bottom: 30px;">

                <!-- MENU TABS LIVELLO 2 E LIVELLO 3 -->
                <div class="sezioni-tabs-l2-container">
                    <?php 
                    $first_cat = true;
                    foreach ($struttura as $cat_name => $cat_data): 
                        $cat_slug = sanitize_title($cat_name);
                        $is_active   = ($default_cat && $cat_slug === $default_cat) || (!$default_cat && $first_cat);
                        $display_l2  = $is_active ? 'block' : 'none';
                    ?>
                        <div class="sezioni-tabs-l2-wrapper" id="tabs-l2-wrapper-<?php echo esc_attr($cat_slug); ?>" style="display: <?php echo $display_l2; ?>;">
                            
                            <!-- RIGA LIVELLO 2 -->
                            <div class="sezioni-tabs-l2" style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
                                <?php 
                                if($cat_data['type'] == 'teams') {
                                    // Sottolineato solo se ci sono squadre, altrimenti niente. Mostriamo le squadre direttamente come L2.
                                    if(empty($cat_data['items'])) {
                                        echo '<span style="color: white; font-style: italic; font-size: 14px;">Nessuna squadra inserita.</span>';
                                    } else {
                                        $first_item = true;
                                        foreach ($cat_data['items'] as $sq):
                                            $sq_id = esc_attr($sq['id']);
                                            $active_class = $first_item ? 'active' : '';
                                            $bg_color = $first_item ? 'var(--c-primary)' : 'transparent';
                                            $text_color = $first_item ? '#000' : 'white';
                                            $border_color = $first_item ? 'var(--c-primary)' : 'white';
                                ?>
                                            <button class="sez-tab-btn-team <?php echo $active_class; ?>" data-sq="sq-<?php echo $sq_id; ?>" data-parent-wrapper="tabs-l2-wrapper-<?php echo esc_attr($cat_slug); ?>" style="background-color: <?php echo $bg_color; ?>; color: <?php echo $text_color; ?>; border: 2px solid <?php echo $border_color; ?>; padding: 8px 25px; font-weight: bold; text-transform: uppercase; font-size: 12px; cursor: pointer; transition: 0.3s; min-width: 150px; text-align: center;">
                                                <?php echo esc_html(strtoupper($sq['titolo'])); ?>
                                            </button>
                                <?php 
                                            $first_item = false;
                                        endforeach;
                                    }
                                } else if($cat_data['type'] == 'subcats') {
                                    // Mostra le sottocategorie
                                    $first_sub = true;
                                    foreach ($cat_data['items'] as $sub_name => $sub_teams) {
                                        $sub_slug = sanitize_title($sub_name);
                                        $active_class = $first_sub ? 'active' : '';
                                        $bg_color = $first_sub ? 'transparent' : 'transparent'; // Nel mockup Allievi D è evidenziata... ma usiamo la logica standard
                                        // Wait, the mockup shows L2 tabs are not filled yellow unless active? No, yellow text or yellow bg?
                                        // Mockup: "ALLIEVI" is yellow filled. "ALLIEVI D" is yellow text, white border? Wait, "ALLIEVI D" is yellow filled, black text!
                                        $bg_color = $first_sub ? 'var(--c-primary)' : 'transparent';
                                        $text_color = $first_sub ? '#000' : 'white';
                                        $border_color = $first_sub ? 'var(--c-primary)' : 'white';
                                ?>
                                        <button class="sez-tab-btn-subcat <?php echo $active_class; ?>" data-sub="<?php echo esc_attr($cat_slug . '-' . $sub_slug); ?>" data-parent-wrapper="tabs-l2-wrapper-<?php echo esc_attr($cat_slug); ?>" style="background-color: <?php echo $bg_color; ?>; color: <?php echo $text_color; ?>; border: 2px solid <?php echo $border_color; ?>; padding: 8px 25px; font-weight: bold; text-transform: uppercase; font-size: 12px; cursor: pointer; transition: 0.3s; min-width: 150px; text-align: center;">
                                            <?php echo esc_html(strtoupper($sub_name)); ?>
                                        </button>
                                <?php
                                        $first_sub = false;
                                    }
                                }
                                ?>
                            </div>

                            <!-- RIGA LIVELLO 3 (Solo se type == subcats) -->
                            <?php if($cat_data['type'] == 'subcats'): ?>
                                <hr class="l2-divider" style="border: 0; border-top: 1px solid rgba(255,255,255,0.2); margin-bottom: 30px;">
                                <div class="sezioni-tabs-l3-container">
                                    <?php 
                                    $first_sub = true;
                                    foreach ($cat_data['items'] as $sub_name => $sub_teams):
                                        $sub_slug = sanitize_title($sub_name);
                                        $display_l3 = $first_sub ? 'flex' : 'none';
                                    ?>
                                        <div class="sezioni-tabs-l3" id="tabs-l3-<?php echo esc_attr($cat_slug . '-' . $sub_slug); ?>" style="display: <?php echo $display_l3; ?>; gap: 20px; flex-wrap: wrap;">
                                            <?php 
                                            if(empty($sub_teams)) {
                                                echo '<span style="color: white; font-style: italic; font-size: 14px;">Nessuna squadra in questa categoria.</span>';
                                            } else {
                                                $first_sq = true;
                                                foreach ($sub_teams as $sq):
                                                    $sq_id = esc_attr($sq['id']);
                                                    $active_class = $first_sq ? 'active' : '';
                                                    $bg_color = $first_sq ? 'var(--c-primary)' : 'transparent';
                                                    $text_color = $first_sq ? '#000' : 'white';
                                                    $border_color = $first_sq ? 'var(--c-primary)' : 'white';
                                            ?>
                                                    <button class="sez-tab-btn-team <?php echo $active_class; ?>" data-sq="sq-<?php echo $sq_id; ?>" data-parent-wrapper="tabs-l3-<?php echo esc_attr($cat_slug . '-' . $sub_slug); ?>" style="background-color: <?php echo $bg_color; ?>; color: <?php echo $text_color; ?>; border: 2px solid <?php echo $border_color; ?>; padding: 8px 25px; font-weight: bold; text-transform: uppercase; font-size: 12px; cursor: pointer; transition: 0.3s; min-width: 150px; text-align: center;">
                                                        <?php echo esc_html(strtoupper($sq['titolo'])); ?>
                                                    </button>
                                            <?php 
                                                    $first_sq = false;
                                                endforeach;
                                            }
                                            ?>
                                        </div>
                                    <?php 
                                        $first_sub = false;
                                    endforeach;
                                    ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php 
                        $first_cat = false;
                    endforeach; 
                    ?>
                </div>

            </div>
        </div>
    </section>

    <!-- CONTENT PANELS -->
    <div style="background-color: #000; padding-top: 0; padding-bottom: 60px;">
        
        <?php 
        $first_cat = true;
        foreach ($struttura as $cat_name => $cat_data): 
            $cat_slug  = sanitize_title($cat_name);
            $cat_active = ($default_cat && $cat_slug === $default_cat) || (!$default_cat && $first_cat);
            
            if ($cat_data['type'] == 'teams') {
                $first_sq = true;
                if(!empty($cat_data['items'])) {
                    foreach ($cat_data['items'] as $sq):
                        $sq_id = esc_attr($sq['id']);
                        $display = ($cat_active && $first_sq) ? 'block' : 'none';
                        ?>
                        <div id="content-sq-<?php echo $sq_id; ?>" class="sezioni-content-panel" style="display: <?php echo $display; ?>;">
                            <?php print_sezione_panel($sq); ?>
                        </div>
                        <?php 
                        $first_sq = false;
                    endforeach;
                }
            } else if ($cat_data['type'] == 'subcats') {
                $first_sub = true;
                foreach ($cat_data['items'] as $sub_name => $sub_teams) {
                    $first_sq = true;
                    if(!empty($sub_teams)) {
                        foreach ($sub_teams as $sq):
                            $sq_id = esc_attr($sq['id']);
                            $display = ($cat_active && $first_sub && $first_sq) ? 'block' : 'none';
                            ?>
                            <div id="content-sq-<?php echo $sq_id; ?>" class="sezioni-content-panel" style="display: <?php echo $display; ?>;">
                                <?php print_sezione_panel($sq); ?>
                            </div>
                            <?php 
                            $first_sq = false;
                        endforeach;
                    }
                    $first_sub = false;
                }
            }
            $first_cat = false;
        endforeach; 
        ?>

        <!-- SPONSOR -->
        <div class="container" style="padding-top: 40px;">
            <h3 class="text-white" style="font-size: 26px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px;">SPONSOR</h3>
            <?php sport_theme_render_global_sponsors(); ?>
        </div>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const catBtns = document.querySelectorAll('.sez-tab-btn-l1');
    const subBtns = document.querySelectorAll('.sez-tab-btn-subcat');
    const teamBtns = document.querySelectorAll('.sez-tab-btn-team');
    const l2Wrappers = document.querySelectorAll('.sezioni-tabs-l2-wrapper');
    const contentPanels = document.querySelectorAll('.sezioni-content-panel');

    function setBtnActive(btn) {
        btn.style.backgroundColor = 'var(--c-primary)';
        btn.style.color = '#000';
        btn.style.borderColor = 'var(--c-primary)';
        btn.classList.add('active');
    }

    function setBtnInactive(btn) {
        btn.style.backgroundColor = 'transparent';
        btn.style.color = 'white';
        btn.style.borderColor = 'white';
        btn.classList.remove('active');
    }

    // Click su Categoria (L1)
    catBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const cat = this.getAttribute('data-cat');
            
            catBtns.forEach(b => setBtnInactive(b));
            setBtnActive(this);

            l2Wrappers.forEach(w => w.style.display = 'none');
            contentPanels.forEach(panel => panel.style.display = 'none');
            
            const targetL2Wrapper = document.getElementById('tabs-l2-wrapper-' + cat);
            if(targetL2Wrapper) {
                targetL2Wrapper.style.display = 'block';
                const firstBtn = targetL2Wrapper.querySelector('.sez-tab-btn-subcat, .sez-tab-btn-team');
                if(firstBtn) firstBtn.click();
            }
        });
    });

    // Click su Sottocategoria (L2 -> Apre L3)
    subBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const sub = this.getAttribute('data-sub');
            const parentWrapperId = this.getAttribute('data-parent-wrapper');
            
            const siblings = document.querySelectorAll('#' + parentWrapperId + ' .sez-tab-btn-subcat');
            siblings.forEach(b => setBtnInactive(b));
            setBtnActive(this);

            const l3s = document.querySelectorAll('#' + parentWrapperId + ' .sezioni-tabs-l3');
            l3s.forEach(l => l.style.display = 'none');
            contentPanels.forEach(panel => panel.style.display = 'none');

            const targetL3 = document.getElementById('tabs-l3-' + sub);
            if(targetL3) {
                targetL3.style.display = 'flex';
                const firstSqBtn = targetL3.querySelector('.sez-tab-btn-team');
                if(firstSqBtn) firstSqBtn.click();
            }
        });
    });

    // Click su Squadra (L2 o L3 -> Apre Pannello)
    teamBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const sqId = this.getAttribute('data-sq');
            const parentContainerId = this.getAttribute('data-parent-wrapper');
            
            const siblings = document.querySelectorAll('#' + parentContainerId + ' .sez-tab-btn-team');
            siblings.forEach(b => setBtnInactive(b));
            setBtnActive(this);

            contentPanels.forEach(panel => panel.style.display = 'none');
            const targetContent = document.getElementById('content-' + sqId);
            if(targetContent) targetContent.style.display = 'block';
        });
    });

    // ── Attiva il tab corretto dall'URL hash (es: /sezioni#allievi) ──
    var hash = window.location.hash.replace('#', '').toLowerCase();
    if (hash) {
        var matchBtn = null;
        catBtns.forEach(function(btn) {
            if (btn.getAttribute('data-cat') === hash) matchBtn = btn;
        });
        if (matchBtn) {
            matchBtn.click();
            // Piccolo scroll verso la sezione tabs
            setTimeout(function() {
                var hero = document.querySelector('.news-hero');
                if (hero) window.scrollTo({ top: hero.offsetHeight - 80, behavior: 'smooth' });
            }, 100);
        }
    }

});
</script>

<?php get_footer('societa'); ?>
