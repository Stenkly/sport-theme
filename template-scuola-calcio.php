<?php
/**
 * Template Name: Pagina Scuola Calcio
 *
 * @package Sport_Theme
 */

get_header('societa');

// Recupero dati dai campi personalizzati (o default)
$email = get_post_meta( get_the_ID(), '_sc_email', true ) ?: 'INFO@ACTAVERNE.COM';
$orario_prova = get_post_meta( get_the_ID(), '_sc_orario_prova', true ) ?: '09:45';
$testo_prova = get_post_meta( get_the_ID(), '_sc_testo_prova', true ) ?: 'LA PRIMA PROVA È GRATUITA, TI ASPETTIAMO!';
$inizio_stagione = get_post_meta( get_the_ID(), '_sc_inizio_stagione', true ) ?: '1° ALLENAMENTO DELLA STAGIONE 2025/2026 SABATO 30 AGOSTO 2025';
$giorni_allenamento = get_post_meta( get_the_ID(), '_sc_giorni_allenamento', true ) ?: 'Sabato 10:00 - 11:30';
$responsabile = get_post_meta( get_the_ID(), '_sc_responsabile', true ) ?: 'Angelo Clemente';
$anno_1 = get_post_meta( get_the_ID(), '_sc_anno_1', true ) ?: '2017';
$anno_2 = get_post_meta( get_the_ID(), '_sc_anno_2', true ) ?: '2018';
$anno_3 = get_post_meta( get_the_ID(), '_sc_anno_3', true ) ?: '2019';
$anno_4 = get_post_meta( get_the_ID(), '_sc_anno_4', true ) ?: '2020';
$formatori_2017 = get_post_meta( get_the_ID(), '_sc_formatori_2017', true ) ?: "Mario Mesquita\nMario Mengoni\nCiro Bove";
$formatori_2018 = get_post_meta( get_the_ID(), '_sc_formatori_2018', true ) ?: "Ignazio Gatto\nMarcello Clemente\nLino Mazzei";
$formatori_2019 = get_post_meta( get_the_ID(), '_sc_formatori_2019', true ) ?: "Moritz Roth\nLorenzo Pignatiello\nDomenico Criniti";
$formatori_2020 = get_post_meta( get_the_ID(), '_sc_formatori_2020', true ) ?: "Marco Tognola\nFrancesco Foresta";
$formatori_portieri = get_post_meta( get_the_ID(), '_sc_formatori_portieri', true ) ?: "Marcello Clemente";
?>

<main id="primary" class="site-main page-scuola-calcio">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            // Immagine bambini default placeholder
            $hero_image_url = get_template_directory_uri() . '/assets/images/scuola-calcio.jpg';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh; min-height: 400px;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0px; left: 0; right: 0; text-align: left; padding-bottom: 20px;">
                <h1 class="text-white" style="font-size: 60px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">SCUOLA CALCIO</h1>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="container scuola-calcio-content" style="padding-top: 10px; padding-bottom: 60px;">
        
        <!-- Linea separatrice sotto l'header (allineata al container) -->
        <hr class="sc-divider" style="border: 0; border-top: 1px solid rgba(255,255,255,0.7); margin-bottom: 40px; margin-top: 0;">

        <!-- VUOI PROVARE -->
        <div class="sc-section" style="margin-bottom: 40px;">
            <h2 class="text-primary" style="font-size: 42px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;">VUOI PROVARE?</h2>
            
            <p style="font-size: 17px; font-weight: 700; text-transform: uppercase; margin-bottom: 20px; color: white; letter-spacing: 0.5px;">
                CONTATTA LA SOCIETÀ ALL'E-MAIL <a href="mailto:<?php echo esc_attr(strtolower($email)); ?>" class="text-primary"><?php echo esc_html($email); ?></a>
            </p>
            
            <p style="font-size: 17px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px; color: white; letter-spacing: 0.5px; line-height: 1.5;">
                OPPURE PRESENTATI DAL RESPONSABILE SUL CAMPO ALLE <span class="text-primary"><?php echo esc_html($orario_prova); ?></span>.<br>
                <span class="text-primary"><?php echo esc_html($testo_prova); ?></span>
            </p>
            
            <p style="font-size: 17px; font-weight: 700; text-transform: uppercase; margin-bottom: 40px; color: white; letter-spacing: 0.5px;">
                <?php echo esc_html($inizio_stagione); ?>
            </p>
        </div>

        <hr class="sc-divider" style="border: 0; border-top: 1px solid rgba(255,255,255,0.7); margin-bottom: 40px;">

        <!-- GIORNI DI ALLENAMENTO -->
        <div class="sc-section" style="margin-bottom: 30px;">
            <h3 class="text-white" style="font-size: 42px; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 1px;">GIORNI DI ALLENAMENTO</h3>
            <p style="font-size: 17px; font-weight: 700; color: white; letter-spacing: 0.5px;"><?php echo esc_html($giorni_allenamento); ?></p>
        </div>

        <!-- EDUCATORI -->
        <div class="sc-section" style="margin-bottom: 30px;">
            <h3 class="text-white" style="font-size: 42px; font-weight: 700; text-transform: uppercase; margin-bottom: 20px; letter-spacing: 1px;">EDUCATORI</h3>
            <div style="margin-bottom: 20px;">
                <p class="text-primary" style="font-size: 22px; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; letter-spacing: 1px;">RESPONSABILE TECNICO SCUOLA CALCIO</p>
                <p style="font-size: 17px; font-weight: 700; color: white; letter-spacing: 0.5px;"><?php echo esc_html($responsabile); ?></p>
            </div>
        </div>

        <!-- FORMATORI -->
        <div class="sc-section" style="margin-bottom: 60px;">
            <h3 class="text-white" style="font-size: 42px; font-weight: 700; text-transform: uppercase; margin-bottom: 25px; letter-spacing: 1px;">FORMATORI</h3>
            
            <div class="formatori-grid">
                <!-- Colonna 1 -->
                <div class="formatori-col">
                    <div class="formatore-group">
                        <p class="text-primary formatore-title">ALLIEVI <?php echo esc_html($anno_1); ?></p>
                        <p class="formatore-names"><?php echo nl2br(esc_html($formatori_2017)); ?></p>
                    </div>
                    <div class="formatore-group">
                        <p class="text-primary formatore-title">ALLIEVI <?php echo esc_html($anno_2); ?></p>
                        <p class="formatore-names"><?php echo nl2br(esc_html($formatori_2018)); ?></p>
                    </div>
                </div>
                <!-- Colonna 2 -->
                <div class="formatori-col">
                    <div class="formatore-group">
                        <p class="text-primary formatore-title">ALLIEVI <?php echo esc_html($anno_3); ?></p>
                        <p class="formatore-names"><?php echo nl2br(esc_html($formatori_2019)); ?></p>
                    </div>
                    <div class="formatore-group">
                        <p class="text-primary formatore-title">ALLIEVI <?php echo esc_html($anno_4); ?></p>
                        <p class="formatore-names"><?php echo nl2br(esc_html($formatori_2020)); ?></p>
                    </div>
                </div>
                <!-- Colonna 3 -->
                <div class="formatori-col">
                    <div class="formatore-group">
                        <p class="text-primary formatore-title">PORTIERI</p>
                        <p class="formatore-names"><?php echo nl2br(esc_html($formatori_portieri)); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="sc-divider" style="border: 0; border-top: 1px solid rgba(255,255,255,0.7); margin-bottom: 40px;">

        <!-- SPONSOR -->
        <div class="sc-section">
            <h3 class="text-white" style="font-size: 42px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px;">SPONSOR</h3>
            <?php sport_theme_render_global_sponsors(); ?>
        </div>
    </div>
</main>

<?php get_footer('societa'); ?>
