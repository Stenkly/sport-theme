<?php
/**
 * Template Name: Pagina La Società
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-la-societa">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh; min-height: 400px;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 30px;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">AC TAVERNE</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 20px 0;">
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="container" style="padding-top: 50px; padding-bottom: 60px;">
        
        <?php
        // Recupero campi custom se esistono, altrimenti placeholder
        $titolo_1 = get_post_meta(get_the_ID(), '_soc_titolo_1', true) ?: 'LA SOCIETÀ';
        $sottotitolo_1 = get_post_meta(get_the_ID(), '_soc_sottotitolo_1', true) ?: 'LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.';
        $testo_1 = get_post_meta(get_the_ID(), '_soc_testo_1', true) ?: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";
        
        $titolo_2 = get_post_meta(get_the_ID(), '_soc_titolo_2', true) ?: 'IL PROGETTO';
        $sottotitolo_2 = get_post_meta(get_the_ID(), '_soc_sottotitolo_2', true) ?: 'LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.';
        $testo_2 = get_post_meta(get_the_ID(), '_soc_testo_2', true) ?: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";

        $titolo_3 = get_post_meta(get_the_ID(), '_soc_titolo_3', true) ?: 'LA VISIONE';
        $sottotitolo_3 = get_post_meta(get_the_ID(), '_soc_sottotitolo_3', true) ?: 'LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.';
        $testo_3 = get_post_meta(get_the_ID(), '_soc_testo_3', true) ?: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";

        $titolo_4 = get_post_meta(get_the_ID(), '_soc_titolo_4', true) ?: 'LO STATUTO';
        $sottotitolo_4 = get_post_meta(get_the_ID(), '_soc_sottotitolo_4', true) ?: 'LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.';
        $file_statuto = get_post_meta(get_the_ID(), '_soc_file_statuto', true) ?: '#';
        ?>

        <!-- Sezione 1 -->
        <div class="soc-section" style="margin-bottom: 60px;">
            <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($titolo_1); ?></h2>
            <h3 class="text-white" style="font-size: 18px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; line-height: 1.4;"><?php echo esc_html($sottotitolo_1); ?></h3>
            <p class="text-white" style="font-size: 14px; line-height: 1.6;"><?php echo nl2br(esc_html($testo_1)); ?></p>
        </div>

        <!-- Sezione 2 -->
        <div class="soc-section" style="margin-bottom: 60px;">
            <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($titolo_2); ?></h2>
            <h3 class="text-white" style="font-size: 18px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; line-height: 1.4;"><?php echo esc_html($sottotitolo_2); ?></h3>
            <p class="text-white" style="font-size: 14px; line-height: 1.6;"><?php echo nl2br(esc_html($testo_2)); ?></p>
        </div>

        <!-- Sezione 3 -->
        <div class="soc-section" style="margin-bottom: 60px;">
            <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($titolo_3); ?></h2>
            <h3 class="text-white" style="font-size: 18px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; line-height: 1.4;"><?php echo esc_html($sottotitolo_3); ?></h3>
            <p class="text-white" style="font-size: 14px; line-height: 1.6;"><?php echo nl2br(esc_html($testo_3)); ?></p>
        </div>

        <!-- Sezione 4 -->
        <div class="soc-section" style="margin-bottom: 60px;">
            <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($titolo_4); ?></h2>
            <h3 class="text-white" style="font-size: 18px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; line-height: 1.4;"><?php echo esc_html($sottotitolo_4); ?></h3>
            <a href="<?php echo esc_url($file_statuto); ?>" target="_blank" class="btn-statuto" style="display: inline-block; background-color: var(--c-primary); color: var(--c-black); font-weight: 700; padding: 10px 40px; text-transform: uppercase; text-decoration: none; font-size: 14px; letter-spacing: 1px; margin-top: 10px; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">SCARICA</a>
        </div>

        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.4); margin-bottom: 40px;">

        <!-- SPONSOR -->
        <h3 class="text-white" style="font-size: 26px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px;">SPONSOR</h3>
        <?php sport_theme_render_global_sponsors(); ?>

    </div>
</main>

<?php get_footer('societa'); ?>
