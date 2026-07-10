<?php
/**
 * Template Name: Pagina Infrastruttura
 *
 * @package Sport_Theme
 */

get_header('societa');

$hero_sottotitolo = get_post_meta( get_the_ID(), '_infra_hero_sottotitolo', true ) ?: 'I NOSTRI SPAZI, IL CUORE OPERATIVO DEL CLUB.';

if ( ! function_exists( 'sport_theme_ensure_calendar_weekly_view' ) ) {
    function sport_theme_ensure_calendar_weekly_view( $iframe_html ) {
        if ( empty( $iframe_html ) ) {
            return '';
        }
        if ( preg_match( '/src=["\']([^"\']+)["\']/', $iframe_html, $matches ) ) {
            $src = $matches[1];
            if ( strpos( $src, 'calendar.google.com' ) !== false ) {
                if ( strpos( $src, 'mode=' ) === false ) {
                    $separator = ( strpos( $src, '?' ) === false ) ? '?' : '&';
                    $new_src = $src . $separator . 'mode=WEEK';
                    $iframe_html = str_replace( $src, $new_src, $iframe_html );
                } else {
                    $iframe_html = preg_replace( '/mode=[a-zA-Z]+/i', 'mode=WEEK', $iframe_html );
                }
            }
        }
        return $iframe_html;
    }
}

if ( ! function_exists( 'sport_theme_infrastruttura_calendar_iframe' ) ) {
    function sport_theme_infrastruttura_calendar_iframe( $post_id, $meta_key, $calendar_id ) {
        $calendar_html = get_post_meta( $post_id, $meta_key, true );

        if ( empty( $calendar_html ) ) {
            return '<iframe src="https://calendar.google.com/calendar/embed?src=' . esc_attr( urlencode( $calendar_id ) ) . '&ctz=Europe%2FZurich&mode=WEEK" style="border: 0; width: 100%; height: 700px;" frameborder="0" scrolling="no"></iframe>';
        }

        return sport_theme_ensure_calendar_weekly_view( $calendar_html );
    }
}
?>

<main id="primary" class="site-main page-infrastruttura">

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
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 20px;">
                <h1 class="text-white" style="font-size: 60px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">INFRASTRUTTURA</h1>
                <hr class="sc-divider" style="border: 0; border-top: 5px solid #ffffff; opacity: 1; margin: 20px 0;">
                <p class="text-white hero-subtitle" style="font-size: 24px; font-weight: 700; text-transform: uppercase; margin: 20px 0 0 0; line-height: 1.3;">
                    <?php echo esc_html($hero_sottotitolo); ?>
                </p>
                
                <div class="infra-tabs" style="display: flex; gap: 20px; margin-top: 30px; margin-bottom: 10px; flex-wrap: wrap;">
                    <button class="infra-tab-btn active" data-target="tab-campo" style="background-color: var(--c-primary); color: #000; border: 2px solid var(--c-primary); padding: 8px 40px; font-weight: bold; text-transform: uppercase; font-size: 22px; cursor: pointer; transition: 0.3s;">CAMPO SPORTIVO</button>
                    <button class="infra-tab-btn" data-target="tab-buvette" style="background-color: transparent; color: white; border: 2px solid white; padding: 8px 40px; font-weight: bold; text-transform: uppercase; font-size: 22px; cursor: pointer; transition: 0.3s;">BUVETTE</button>
                    <button class="infra-tab-btn" data-target="tab-occupazione" style="background-color: transparent; color: white; border: 2px solid white; padding: 8px 40px; font-weight: bold; text-transform: uppercase; font-size: 22px; cursor: pointer; transition: 0.3s;">OCCUPAZIONE</button>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENT TABS -->
    <div class="container infrastruttura-content" style="padding-top: 0px; padding-bottom: 60px;">
        
        <?php
        $testo_campo = get_post_meta( get_the_ID(), '_infra_testo_campo', true ) ?: "L'AC TAVERNE METTE A DISPOSIZIONE LE SUE STRUTTURE SPORTIVE PER IL NOLEGGIO, OFFRENDO CAMPI DA CALCIO E ALTRE INFRASTRUTTURE PER EVENTI SPORTIVI, ALLENAMENTI, TORNEI, INCONTRI AZIENDALI E ATTIVITÀ RICREATIVE. SCOPRITE LE NOSTRE ECCELLENTI STRUTTURE E LE MODALITÀ DI NOLEGGIO.";
        $testo_buvette = get_post_meta( get_the_ID(), '_infra_testo_buvette', true ) ?: "L'AC TAVERNE OFFRE UN SERVIZIO BUVETTE DURANTE LE PARTITE CON UNA STRUTTURA ACCOGLIENTE E BEN ATTREZZATA, INOLTRE ABBIAMO LA POSSIBILITÀ DI AFFITTARE IL CAPANNONE ESTERNO ALLA BUVETTE PER FESTE, INCONTRI E ALTRE OCCASIONI, IDEALE PER OSPITARE I VOSTRI EVENTI IN UN AMBIENTE SPORTIVO E CONVIVIALE.";
        $testo_occupazione = get_post_meta( get_the_ID(), '_infra_testo_occupazione', true ) ?: '';
        
        // Recupero immagini galleria
        $img1 = get_post_meta( get_the_ID(), '_infra_img_1', true );
        $img2 = get_post_meta( get_the_ID(), '_infra_img_2', true ) ?: 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=1200&auto=format&fit=crop';
        $img3 = get_post_meta( get_the_ID(), '_infra_img_3', true );
        $img4 = get_post_meta( get_the_ID(), '_infra_img_4', true ) ?: 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=800&auto=format&fit=crop';
        $img5 = get_post_meta( get_the_ID(), '_infra_img_5', true );
        $img6 = get_post_meta( get_the_ID(), '_infra_img_6', true );
        ?>

        <!-- TAB: CAMPO SPORTIVO -->
        <div id="tab-campo" class="infra-tab-content" style="display: block;">
            <h2 class="text-white" style="font-size: 42px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;">CAMPO SPORTIVO E INFRASTRUTTURE</h2>
            <p style="color: white; font-size: 22px; line-height: 1.3; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 30px;">
                <?php echo nl2br(esc_html($testo_campo)); ?>
            </p>

            <?php 
            $regolamento = get_post_meta( get_the_ID(), '_infra_pdf_regolamento', true );
            
            // Se il campo personalizzato è vuoto, cerchiamo il file caricato tra i media di WordPress
            if ( empty( $regolamento ) ) {
                $pdf_query = new WP_Query( array(
                    'post_type'      => 'attachment',
                    'post_status'    => 'any',
                    'posts_per_page' => 1,
                    'post_mime_type' => 'application/pdf',
                    'name'           => 'regolamento_noleggio_campi',
                ) );
                
                if ( $pdf_query->have_posts() ) {
                    $regolamento = wp_get_attachment_url( $pdf_query->posts[0]->ID );
                } else {
                    $pdf_query_dash = new WP_Query( array(
                        'post_type'      => 'attachment',
                        'post_status'    => 'any',
                        'posts_per_page' => 1,
                        'post_mime_type' => 'application/pdf',
                        'name'           => 'regolamento-noleggio-campi',
                    ) );
                    if ( $pdf_query_dash->have_posts() ) {
                        $regolamento = wp_get_attachment_url( $pdf_query_dash->posts[0]->ID );
                    }
                }
            }

            if ( !empty($regolamento) ): 
            ?>
            <div style="margin-bottom: 40px;">
                <a href="<?php echo esc_url($regolamento); ?>" target="_blank" class="infra-pdf-btn" style="display: inline-block; background-color: var(--c-primary); color: #000; border: 2px solid var(--c-primary); padding: 8px 45px; font-weight: bold; text-transform: uppercase; font-size: 22px; text-decoration: none; transition: 0.3s;" onmouseover="this.style.backgroundColor='transparent'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--c-primary)'; this.style.color='#000';">SCARICA IL REGOLAMENTO</a>
            </div>
            <?php endif; ?>

            <!-- GALLERIA IMMAGINI -->
            <div class="infra-gallery" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 80px;">
                <!-- Riga Superiore (25% - 50% - 25%) -->
                <div style="display: flex; gap: 15px; height: 400px;">
                    <div style="flex: 1; background-color: #333; <?php if($img1) echo "background-image: url('".esc_url($img1)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 2; background-color: #333; <?php if($img2) echo "background-image: url('".esc_url($img2)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 1; background-color: #333; <?php if($img3) echo "background-image: url('".esc_url($img3)."'); background-size: cover; background-position: center;"; ?>"></div>
                </div>
                <!-- Riga Inferiore (33% - 33% - 33%) -->
                <div style="display: flex; gap: 15px; height: 400px;">
                    <div style="flex: 1; background-color: #333; <?php if($img4) echo "background-image: url('".esc_url($img4)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 1; background-color: #333; <?php if($img5) echo "background-image: url('".esc_url($img5)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 1; background-color: #333; <?php if($img6) echo "background-image: url('".esc_url($img6)."'); background-size: cover; background-position: center;"; ?>"></div>
                </div>
            </div>
        </div>

        <!-- TAB: BUVETTE -->
        <div id="tab-buvette" class="infra-tab-content" style="display: none;">
            <h2 class="text-white" style="font-size: 42px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;">BUVETTE</h2>
            <p style="color: white; font-size: 22px; line-height: 1.3; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 40px;">
                <?php echo nl2br(esc_html($testo_buvette)); ?>
            </p>

            <?php
            // Recupero immagini galleria Buvette
            $b_img1 = get_post_meta( get_the_ID(), '_infra_buvette_img_1', true );
            $b_img2 = get_post_meta( get_the_ID(), '_infra_buvette_img_2', true ) ?: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1200&auto=format&fit=crop';
            $b_img3 = get_post_meta( get_the_ID(), '_infra_buvette_img_3', true );
            ?>

            <!-- GALLERIA IMMAGINI BUVETTE -->
            <div class="infra-gallery" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 80px;">
                <!-- Prime 3 immagini (25% - 50% - 25%) -->
                <div style="display: flex; gap: 15px; height: 400px;">
                    <div style="flex: 1; background-color: #333; <?php if($b_img1) echo "background-image: url('".esc_url($b_img1)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 2; background-color: #333; <?php if($b_img2) echo "background-image: url('".esc_url($b_img2)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 1; background-color: #333; <?php if($b_img3) echo "background-image: url('".esc_url($b_img3)."'); background-size: cover; background-position: center;"; ?>"></div>
                </div>
            </div>
        </div>

        <!-- TAB: OCCUPAZIONE -->
        <div id="tab-occupazione" class="infra-tab-content" style="display: none;">
            <h2 class="text-white" style="font-size: 42px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;">PIANO OCCUPAZIONE</h2>
            <?php if ( ! empty( $testo_occupazione ) ) : ?>
            <div style="color: white; font-size: 14px; line-height: 1.8; margin-bottom: 40px;">
                <?php echo wpautop(wp_kses_post($testo_occupazione)); ?>
            </div>
            <?php endif; ?>

            <?php
            $infra_calendars = array(
                'campo' => array(
                    'label'       => 'Campo',
                    'title'       => 'Piano occupazione Campo',
                    'meta_key'    => '_infra_calendar_iframe',
                    'calendar_id' => 'q5annq4orol4ue2pipv70hlmsc@group.calendar.google.com',
                ),
                'buvette' => array(
                    'label'       => 'Buvette',
                    'title'       => 'Piano occupazione Buvette',
                    'meta_key'    => '_infra_calendar_buvette_iframe',
                    'calendar_id' => 'f7b2100de53n0cp2a4nc700i9s@group.calendar.google.com',
                ),
                'infra' => array(
                    'label'       => 'Infrastruttura',
                    'title'       => 'Piano occupazione Infrastruttura',
                    'meta_key'    => '_infra_calendar_infra_iframe',
                    'calendar_id' => 'i9i8o8n999k36rfllaua5aoes0@group.calendar.google.com',
                ),
            );
            ?>

            <div class="infra-calendar-tabs" data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-post-id="<?php echo esc_attr( get_the_ID() ); ?>">
                <div class="infra-calendar-tab-buttons" role="tablist" aria-label="Calendari occupazione">
                    <?php $first_calendar = true; ?>
                    <?php foreach ( $infra_calendars as $calendar_key => $calendar_data ) : ?>
                        <button type="button" class="infra-calendar-tab-btn <?php echo $first_calendar ? 'active' : ''; ?>" data-calendar-tab="<?php echo esc_attr( $calendar_key ); ?>">
                            <?php echo esc_html( $calendar_data['label'] ); ?>
                        </button>
                        <?php $first_calendar = false; ?>
                    <?php endforeach; ?>
                </div>

                <?php $first_calendar = true; ?>
                <?php foreach ( $infra_calendars as $calendar_key => $calendar_data ) : ?>
                    <section class="infra-calendar-panel <?php echo $first_calendar ? 'active' : ''; ?>" data-calendar-panel="<?php echo esc_attr( $calendar_key ); ?>">
                        <h3 class="text-white" style="font-size: 26px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">
                            <?php echo esc_html( $calendar_data['title'] ); ?>
                        </h3>

                        <div class="google-calendar-wrapper" style="width: 100%; margin-bottom: 30px; background-color: #fff; padding: 10px; border-radius: 5px;">
                            <?php echo sport_theme_infrastruttura_calendar_iframe( get_the_ID(), $calendar_data['meta_key'], $calendar_data['calendar_id'] ); ?>
                        </div>

                        <div class="infra-mobile-events" data-calendar-events="<?php echo esc_attr( $calendar_key ); ?>">
                            <div class="infra-mobile-events-title">Prossime occupazioni</div>
                            <div class="infra-mobile-events-status">Caricamento calendario...</div>
                        </div>
                    </section>
                    <?php $first_calendar = false; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="prenotazioni-wrapper">
            <!-- SEZIONE PRENOTAZIONI -->
            <h2 class="text-white" style="font-size: 42px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">PRENOTAZIONI?</h2>
            <hr class="sc-divider" style="border: 0; border-top: 5px solid #ffffff; opacity: 1; margin-bottom: 40px;">

            <div class="prenotazioni-section" style="display: flex; flex-wrap: wrap; gap: 50px;">
                
                <!-- Lato Sinistro: Info Generali -->
                <div class="prenotazioni-left" style="flex: 1; min-width: 280px; max-width: 300px;">
                    <?php
                    $gen_email = get_post_meta( get_the_ID(), "_infra_email", true ) ?: 'info@actaverne.com';
                    $gen_tel   = get_post_meta( get_the_ID(), "_infra_tel", true ) ?: '+41 91 945 22 95';
                    $gen_ind   = get_post_meta( get_the_ID(), "_infra_ind", true ) ?: "Via Taverne 2\nCP 703\n6807 Taverne";
                    ?>
                    
                    <div style="margin-bottom: 30px;">
                        <h4 style="color: var(--c-primary); font-size: 22px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">EMAIL</h4>
                        <a href="mailto:<?php echo esc_attr($gen_email); ?>" style="color: white; font-size: 17px; text-decoration: none;"><?php echo esc_html($gen_email); ?></a>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <h4 style="color: var(--c-primary); font-size: 22px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">TELEFONO</h4>
                        <a href="tel:<?php echo esc_attr(str_replace(' ', '', $gen_tel)); ?>" style="color: white; font-size: 17px; text-decoration: none;"><?php echo esc_html($gen_tel); ?></a>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <h4 style="color: var(--c-primary); font-size: 22px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">INDIRIZZO</h4>
                        <div style="color: white; font-size: 17px; line-height: 1.6;">
                            <?php echo nl2br(esc_html($gen_ind)); ?>
                        </div>
                    </div>
                </div>

                <!-- Lato Destro: Form -->
                <div class="prenotazioni-right" style="flex: 2; min-width: 300px;">
                    <h4 style="color: var(--c-primary); font-size: 22px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px;">CONTATTACI!</h4>
                    
                    <?php if ( isset($_GET['prenotazione']) && $_GET['prenotazione'] == '1' ) : ?>
                        <div style="background-color: var(--c-primary); color: #000; padding: 15px; margin-bottom: 20px; font-weight: bold;">
                            Grazie! La tua richiesta di prenotazione è stata inviata.
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="hs-prenotazioni-form">
                        <input type="hidden" name="action" value="prenotazioni_submit">
                        <?php wp_nonce_field('prenotazioni_form_nonce', 'prenotazioni_nonce'); ?>
                        
                        <div style="display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 200px;">
                                <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 15px;">Nome e Cognome*</label>
                                <input type="text" name="pr_nome" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 17px;" placeholder="Nome e Cognome">
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 15px;">Numero di telefono*</label>
                                <input type="text" name="pr_telefono" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 17px;" placeholder="Numero di telefono">
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 15px;">E-mail*</label>
                                <input type="email" name="pr_email" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 17px;" placeholder="E-mail">
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 200px;">
                                <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 15px;">Azienda</label>
                                <input type="text" name="pr_azienda" style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 17px;" placeholder="Azienda">
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 15px;">Oggetto*</label>
                                <input type="text" name="pr_oggetto" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 17px;" placeholder="Oggetto">
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 15px;">Periodo*</label>
                                <input type="text" name="pr_periodo" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 17px;" placeholder="Periodo">
                            </div>
                        </div>

                        <div style="margin-bottom: 25px;">
                            <label class="text-white" style="display: block; margin-bottom: 12px; font-size: 15px;">Infrastruttura*</label>
                            <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                                <label style="color: white; font-size: 15px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" name="pr_infra[]" value="Campo sportivo" style="accent-color: var(--c-primary); width: 16px; height: 16px;"> Campo sportivo
                                </label>
                                <label style="color: white; font-size: 15px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" name="pr_infra[]" value="Buvette" style="accent-color: var(--c-primary); width: 16px; height: 16px;"> Buvette
                                </label>
                                <label style="color: white; font-size: 15px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" name="pr_infra[]" value="Infrastruttura" style="accent-color: var(--c-primary); width: 16px; height: 16px;"> Infrastruttura
                                </label>
                            </div>
                        </div>

                        <div style="margin-bottom: 30px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 15px;">La tua domanda*</label>
                            <textarea name="pr_domanda" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 17px; min-height: 100px;" placeholder="Domanda"></textarea>
                        </div>

                        <button type="submit" style="background-color: var(--c-primary); color: #000; border: none; padding: 8px 40px; font-weight: bold; text-transform: uppercase; cursor: pointer; letter-spacing: 1px; font-size: 22px; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">INVIA RICHIESTA</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.infra-tab-btn');
    const contents = document.querySelectorAll('.infra-tab-content');
    const calendarRoot = document.querySelector('.infra-calendar-tabs');
    const calendarButtons = document.querySelectorAll('.infra-calendar-tab-btn');
    const calendarPanels = document.querySelectorAll('.infra-calendar-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active classes
            tabs.forEach(t => {
                t.style.backgroundColor = 'transparent';
                t.style.color = 'white';
                t.style.borderColor = 'white';
            });
            contents.forEach(c => c.style.display = 'none');

            // Add active class to clicked tab
            this.style.backgroundColor = 'var(--c-primary)';
            this.style.color = '#000';
            this.style.borderColor = 'var(--c-primary)';

            // Show corresponding content
            const targetId = this.getAttribute('data-target');
            document.getElementById(targetId).style.display = 'block';
            
            // Hide prenotazioni form if tab is Occupazione
            if (targetId === 'tab-occupazione') {
                document.getElementById('prenotazioni-wrapper').style.display = 'none';
            } else {
                document.getElementById('prenotazioni-wrapper').style.display = 'block';
            }
        });
    });

    function unfoldIcs(icsText) {
        return (icsText || '').replace(/\r?\n[ \t]/g, '');
    }

    function readIcsValue(lines, fieldName) {
        const prefix = fieldName + ';';
        const direct = fieldName + ':';
        const line = lines.find(item => item.indexOf(direct) === 0 || item.indexOf(prefix) === 0);
        if (!line) return '';
        const colonIndex = line.indexOf(':');
        return colonIndex >= 0 ? line.slice(colonIndex + 1) : '';
    }

    function decodeIcsText(value) {
        return (value || '')
            .replace(/\\n/gi, ' ')
            .replace(/\\,/g, ',')
            .replace(/\\;/g, ';')
            .replace(/\\\\/g, '\\')
            .trim();
    }

    function parseIcsDate(value) {
        if (!value) return null;
        const compact = value.trim();

        if (/^\d{8}$/.test(compact)) {
            return new Date(
                Number(compact.slice(0, 4)),
                Number(compact.slice(4, 6)) - 1,
                Number(compact.slice(6, 8))
            );
        }

        const match = compact.match(/^(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})(Z?)$/);
        if (!match) return null;

        const year = Number(match[1]);
        const month = Number(match[2]) - 1;
        const day = Number(match[3]);
        const hour = Number(match[4]);
        const minute = Number(match[5]);
        const second = Number(match[6]);

        if (match[7] === 'Z') {
            return new Date(Date.UTC(year, month, day, hour, minute, second));
        }

        return new Date(year, month, day, hour, minute, second);
    }

    function parseIcsEvents(icsText) {
        const lines = unfoldIcs(icsText).split(/\r?\n/);
        const events = [];
        let current = null;

        lines.forEach(line => {
            if (line === 'BEGIN:VEVENT') {
                current = [];
            } else if (line === 'END:VEVENT' && current) {
                const startValue = readIcsValue(current, 'DTSTART');
                const endValue = readIcsValue(current, 'DTEND');
                const start = parseIcsDate(startValue);
                const end = parseIcsDate(endValue) || start;
                const title = decodeIcsText(readIcsValue(current, 'SUMMARY')) || 'Occupato';
                const location = decodeIcsText(readIcsValue(current, 'LOCATION'));

                if (start) {
                    events.push({ start, end, title, location, allDay: /^\d{8}$/.test(startValue) });
                }
                current = null;
            } else if (current) {
                current.push(line);
            }
        });

        const now = new Date();
        now.setHours(0, 0, 0, 0);

        return events
            .filter(event => event.end >= now)
            .sort((a, b) => a.start - b.start)
            .slice(0, 10);
    }

    function formatEventDate(event) {
        const date = new Intl.DateTimeFormat('it-CH', {
            weekday: 'short',
            day: '2-digit',
            month: '2-digit'
        }).format(event.start);

        if (event.allDay) {
            return date;
        }

        const timeFormat = new Intl.DateTimeFormat('it-CH', {
            hour: '2-digit',
            minute: '2-digit'
        });
        return date + ' · ' + timeFormat.format(event.start) + ' - ' + timeFormat.format(event.end);
    }

    function renderCalendarEvents(container, events) {
        container.innerHTML = '';

        const title = document.createElement('div');
        title.className = 'infra-mobile-events-title';
        title.textContent = 'Prossime occupazioni';
        container.appendChild(title);

        if (!events.length) {
            const empty = document.createElement('div');
            empty.className = 'infra-mobile-events-status';
            empty.textContent = 'Nessuna occupazione prevista nei prossimi giorni.';
            container.appendChild(empty);
            return;
        }

        const list = document.createElement('div');
        list.className = 'infra-mobile-events-list';

        events.forEach(event => {
            const item = document.createElement('article');
            item.className = 'infra-mobile-event';

            const date = document.createElement('div');
            date.className = 'infra-mobile-event-date';
            date.textContent = formatEventDate(event);
            item.appendChild(date);

            const name = document.createElement('div');
            name.className = 'infra-mobile-event-title';
            name.textContent = event.title;
            item.appendChild(name);

            if (event.location) {
                const location = document.createElement('div');
                location.className = 'infra-mobile-event-location';
                location.textContent = event.location;
                item.appendChild(location);
            }

            list.appendChild(item);
        });

        container.appendChild(list);
    }

    function loadCalendarEvents(calendarKey) {
        if (!calendarRoot) return;

        const container = document.querySelector('[data-calendar-events="' + calendarKey + '"]');
        if (!container || container.dataset.loaded === '1') return;

        const params = new URLSearchParams({
            action: 'get_calendar_ics',
            field: calendarKey,
            post_id: calendarRoot.dataset.postId || ''
        });

        fetch((calendarRoot.dataset.ajaxUrl || '') + '?' + params.toString())
            .then(response => {
                if (!response.ok) {
                    throw new Error('calendar');
                }
                return response.text();
            })
            .then(text => {
                container.dataset.loaded = '1';
                renderCalendarEvents(container, parseIcsEvents(text));
            })
            .catch(() => {
                const status = container.querySelector('.infra-mobile-events-status');
                if (status) {
                    status.textContent = 'Calendario non disponibile al momento.';
                }
            });
    }

    calendarButtons.forEach(button => {
        button.addEventListener('click', function() {
            const calendarKey = this.getAttribute('data-calendar-tab');

            calendarButtons.forEach(item => item.classList.remove('active'));
            calendarPanels.forEach(panel => panel.classList.remove('active'));

            this.classList.add('active');
            const panel = document.querySelector('[data-calendar-panel="' + calendarKey + '"]');
            if (panel) {
                panel.classList.add('active');
            }

            loadCalendarEvents(calendarKey);
        });
    });

    const activeCalendarButton = document.querySelector('.infra-calendar-tab-btn.active');
    if (activeCalendarButton) {
        loadCalendarEvents(activeCalendarButton.getAttribute('data-calendar-tab'));
    }
});
</script>

<style>
.infra-calendar-tab-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 30px;
}
.infra-calendar-tab-btn {
    background: transparent;
    border: 2px solid #fff;
    color: #fff;
    cursor: pointer;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 1px;
    min-width: 170px;
    padding: 11px 24px;
    text-transform: uppercase;
    transition: 0.2s ease;
}
.infra-calendar-tab-btn.active,
.infra-calendar-tab-btn:hover {
    background: var(--c-primary);
    border-color: var(--c-primary);
    color: #000;
}
.infra-calendar-panel {
    display: none;
}
.infra-calendar-panel.active {
    display: block;
}
.google-calendar-wrapper {
    width: 100%;
    height: 750px;
    max-height: 85vh;
}
.google-calendar-wrapper iframe {
    width: 100%;
    height: 100%;
    border: 0;
}
.infra-mobile-events {
    display: none;
}
@media (max-width: 768px) {
    .infra-calendar-tab-buttons {
        display: grid;
        gap: 10px;
        grid-template-columns: 1fr;
    }
    .infra-calendar-tab-btn {
        min-width: 0;
        width: 100%;
    }
    .google-calendar-wrapper {
        display: block;
        height: 700px;
        max-height: none;
        overflow: hidden;
    }
    .infra-mobile-events {
        display: none;
    }
    .infra-mobile-events-title {
        color: var(--c-primary);
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 16px;
        text-transform: uppercase;
    }
    .infra-mobile-events-status {
        color: #aaa;
        font-size: 14px;
        line-height: 1.5;
    }
    .infra-mobile-events-list {
        display: grid;
        gap: 12px;
    }
    .infra-mobile-event {
        border-bottom: 1px solid #242424;
        padding-bottom: 12px;
    }
    .infra-mobile-event:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }
    .infra-mobile-event-date {
        color: #aaa;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 4px;
        text-transform: uppercase;
    }
    .infra-mobile-event-title {
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        line-height: 1.35;
    }
    .infra-mobile-event-location {
        color: #aaa;
        font-size: 13px;
        line-height: 1.4;
        margin-top: 4px;
    }
}
</style>

<?php get_footer('societa'); ?>
