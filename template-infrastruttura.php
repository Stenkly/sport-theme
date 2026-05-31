<?php
/**
 * Template Name: Pagina Infrastruttura
 *
 * @package Sport_Theme
 */

get_header('societa');
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
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 20px;">
                <h1 class="text-white" style="font-size: 60px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">INFRASTRUTTURA</h1>
                <hr class="sc-divider" style="border: 0; border-top: 5px solid #ffffff; opacity: 1; margin: 20px 0;">
                
                <div class="infra-tabs" style="display: flex; gap: 20px; margin-top: 30px; margin-bottom: 10px; flex-wrap: wrap;">
                    <button class="infra-tab-btn active" data-target="tab-campo" style="background-color: var(--c-primary); color: #000; border: 2px solid var(--c-primary); padding: 8px 40px; font-weight: bold; text-transform: uppercase; font-size: 22px; cursor: pointer; transition: 0.3s;">CAMPO SPORTIVO</button>
                    <button class="infra-tab-btn" data-target="tab-buvette" style="background-color: transparent; color: white; border: 2px solid white; padding: 8px 40px; font-weight: bold; text-transform: uppercase; font-size: 22px; cursor: pointer; transition: 0.3s;">BUVETTE</button>
                    <button class="infra-tab-btn" data-target="tab-occupazione" style="background-color: transparent; color: white; border: 2px solid white; padding: 8px 40px; font-weight: bold; text-transform: uppercase; font-size: 22px; cursor: pointer; transition: 0.3s;">OCCUPAZIONE</button>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENT TABS -->
    <div class="container" style="padding-top: 0px; padding-bottom: 60px;">
        
        <?php
        $testo_campo = get_post_meta( get_the_ID(), '_infra_testo_campo', true ) ?: "L'AC TAVERNE METTE A DISPOSIZIONE LE SUE STRUTTURE SPORTIVE PER IL NOLEGGIO, OFFRENDO CAMPI DA CALCIO E ALTRE INFRASTRUTTURE PER EVENTI SPORTIVI, ALLENAMENTI, TORNEI, INCONTRI AZIENDALI E ATTIVITÀ RICREATIVE. SCOPRITE LE NOSTRE ECCELLENTI STRUTTURE E LE MODALITÀ DI NOLEGGIO.";
        $testo_buvette = get_post_meta( get_the_ID(), '_infra_testo_buvette', true ) ?: "L'AC TAVERNE OFFRE UN SERVIZIO BUVETTE DURANTE LE PARTITE CON UNA STRUTTURA ACCOGLIENTE E BEN ATTREZZATA, INOLTRE ABBIAMO LA POSSIBILITÀ DI AFFITTARE IL CAPANNONE ESTERNO ALLA BUVETTE PER FESTE, INCONTRI E ALTRE OCCASIONI, IDEALE PER OSPITARE I VOSTRI EVENTI IN UN AMBIENTE SPORTIVO E CONVIVIALE.";
        $testo_occupazione = get_post_meta( get_the_ID(), '_infra_testo_occupazione', true ) ?: "Verifica l'occupazione dei campi e scopri gli orari disponibili per il noleggio.";
        
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
            $b_img4 = get_post_meta( get_the_ID(), '_infra_buvette_img_4', true ) ?: 'https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?q=80&w=800&auto=format&fit=crop';
            $b_img5 = get_post_meta( get_the_ID(), '_infra_buvette_img_5', true );
            $b_img6 = get_post_meta( get_the_ID(), '_infra_buvette_img_6', true );
            ?>

            <!-- GALLERIA IMMAGINI BUVETTE -->
            <div class="infra-gallery" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 80px;">
                <!-- Riga Superiore (25% - 50% - 25%) -->
                <div style="display: flex; gap: 15px; height: 400px;">
                    <div style="flex: 1; background-color: #333; <?php if($b_img1) echo "background-image: url('".esc_url($b_img1)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 2; background-color: #333; <?php if($b_img2) echo "background-image: url('".esc_url($b_img2)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 1; background-color: #333; <?php if($b_img3) echo "background-image: url('".esc_url($b_img3)."'); background-size: cover; background-position: center;"; ?>"></div>
                </div>
                <!-- Riga Inferiore (33% - 33% - 33%) -->
                <div style="display: flex; gap: 15px; height: 400px;">
                    <div style="flex: 1; background-color: #333; <?php if($b_img4) echo "background-image: url('".esc_url($b_img4)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 1; background-color: #333; <?php if($b_img5) echo "background-image: url('".esc_url($b_img5)."'); background-size: cover; background-position: center;"; ?>"></div>
                    <div style="flex: 1; background-color: #333; <?php if($b_img6) echo "background-image: url('".esc_url($b_img6)."'); background-size: cover; background-position: center;"; ?>"></div>
                </div>
            </div>
        </div>

        <!-- TAB: OCCUPAZIONE -->
        <div id="tab-occupazione" class="infra-tab-content" style="display: none;">
            <h2 class="text-white" style="font-size: 42px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;">PIANO OCCUPAZIONE</h2>
            <div style="color: white; font-size: 14px; line-height: 1.8; margin-bottom: 40px;">
                <?php echo wpautop(wp_kses_post($testo_occupazione)); ?>
            </div>

            <!-- Calendario Campo Sportivo -->
            <h3 class="text-white" style="font-size: 26px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Piano occupazione Campo</h3>
            <div id="calendar-campo" style="margin-bottom: 60px; width: 100%;"></div>

            <!-- Calendario Buvette -->
            <h3 class="text-white" style="font-size: 26px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Piano occupazione Buvette</h3>
            <div id="calendar-buvette" style="margin-bottom: 60px; width: 100%;"></div>

            <!-- Calendario Infrastruttura -->
            <h3 class="text-white" style="font-size: 26px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Piano occupazione Infrastruttura</h3>
            <div id="calendar-infra" style="margin-bottom: 80px; width: 100%;"></div>
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

        <hr class="sc-divider" style="border: 0; border-top: 5px solid #ffffff; opacity: 1; margin-top: 60px; margin-bottom: 40px;">

        <!-- SPONSOR -->
        <h3 class="text-white" style="font-size: 26px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px;">SPONSOR</h3>
        <?php sport_theme_render_global_sponsors(); ?>

    </div>
</main>

<!-- Load FullCalendar and dependency scripts -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ical.js@1.5.0/dist/ical.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/icalendar@6.1.20/index.global.min.js"></script>

<style>
/* FullCalendar Premium Dark & Yellow Theme Overrides */
.fc {
    font-family: 'Josefin Sans', sans-serif !important;
    background-color: #111 !important;
    color: #fff !important;
    border: 1px solid #333 !important;
    border-radius: 8px;
    padding: 15px;
}
.fc-theme-standard td, .fc-theme-standard th {
    border: 1px solid #222 !important;
}
.fc-col-header-cell {
    background-color: #222 !important;
    color: #fff !important;
    font-weight: bold;
    text-transform: uppercase;
    padding: 10px 0 !important;
}
.fc-col-header-cell a {
    color: #fff !important;
    text-decoration: none !important;
}
.fc-timegrid-slot {
    background-color: #111 !important;
    border-bottom: 1px solid #222 !important;
    height: 3.5em !important;
}
.fc-timegrid-slot-label-cushion {
    color: #aaa !important;
    font-size: 13px;
}
.fc-event {
    background-color: var(--c-primary) !important;
    border: 1px solid var(--c-primary) !important;
    color: #000 !important;
    font-weight: bold;
    border-radius: 4px;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    transition: transform 0.2s, opacity 0.2s;
}
.fc-event:hover {
    transform: scale(1.02);
    opacity: 0.9;
}
.fc-event-main {
    padding: 4px;
    color: #000 !important;
}
.fc-event-title, .fc-event-time {
    color: #000 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
}
.fc-button-primary {
    background-color: #222 !important;
    border-color: #333 !important;
    color: #fff !important;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 13px !important;
    padding: 8px 16px !important;
    transition: all 0.3s;
}
.fc-button-primary:hover {
    background-color: var(--c-primary) !important;
    border-color: var(--c-primary) !important;
    color: #000 !important;
}
.fc-button-primary:disabled {
    background-color: #111 !important;
    border-color: #222 !important;
    color: #444 !important;
}
.fc-button-active {
    background-color: var(--c-primary) !important;
    border-color: var(--c-primary) !important;
    color: #000 !important;
}
.fc-toolbar-title {
    font-size: 22px !important;
    text-transform: uppercase;
    font-weight: bold;
    color: var(--c-primary) !important;
    letter-spacing: 1px;
}
.fc-timegrid-now-indicator-line {
    border-color: var(--c-primary) !important;
}
.fc-timegrid-now-indicator-arrow {
    border-color: var(--c-primary) !important;
}
.fc-list-day-cushion {
    background-color: #222 !important;
}
.fc-list-event:hover td {
    background-color: #222 !important;
}
.fc-list-event-title a {
    color: #fff !important;
}
.fc-list-event-time {
    color: var(--c-primary) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.infra-tab-btn');
    const contents = document.querySelectorAll('.infra-tab-content');
    let calendarsInitialized = false;
    const calendars = [];

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
                
                // Initialize calendars on first click with a small delay for DOM layout
                setTimeout(() => {
                    if (!calendarsInitialized) {
                        initCalendars();
                        calendarsInitialized = true;
                    } else {
                        // Update layout size to prevent rendering glitches
                        calendars.forEach(cal => cal.updateSize());
                    }
                }, 50);
            } else {
                document.getElementById('prenotazioni-wrapper').style.display = 'block';
            }
        });
    });

    const pageId = <?php echo get_the_ID(); ?>;
    const ajaxUrl = "<?php echo esc_url(admin_url('admin-ajax.php')); ?>";

    function initCalendars() {
        const configs = [
            { id: 'calendar-campo', field: 'campo' },
            { id: 'calendar-buvette', field: 'buvette' },
            { id: 'calendar-infra', field: 'infra' }
        ];

        configs.forEach(conf => {
            const el = document.getElementById(conf.id);
            if (!el) return;

            const cal = new FullCalendar.Calendar(el, {
                initialView: 'timeGridWeek',
                height: 600,
                locale: 'it',
                firstDay: 1, // Start on Monday
                timeZone: 'Europe/Zurich',
                slotMinTime: '08:00:00',
                slotMaxTime: '22:00:00',
                allDaySlot: false,
                slotDuration: '00:30:00',
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    meridiem: false
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'Oggi',
                    month: 'Mese',
                    week: 'Settimana',
                    day: 'Giorno',
                    list: 'Agenda'
                },
                events: {
                    url: `${ajaxUrl}?action=get_calendar_ics&post_id=${pageId}&field=${conf.field}`,
                    format: 'ics'
                },
                eventDidMount: function(info) {
                    if (info.event.extendedProps.description) {
                        info.el.setAttribute('title', info.event.extendedProps.description);
                    }
                }
            });

            cal.render();
            calendars.push(cal);
        });
    }
});
</script>

<?php get_footer('societa'); ?>
