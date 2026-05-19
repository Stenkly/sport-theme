<?php
/**
 * Template Name: Pagina Club dei 100
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-club100">

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
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">CLUB DEI 100</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 20px 0;">
                <p class="text-white" style="font-size: 24px; font-weight: 700; text-transform: uppercase; margin: 0; line-height: 1.3;">
                    LOREM IPSUM DOLOR SIT AMET, CONSECTETUR<br>ADIPISCING ELIT, SED DO EIUSMOD TEMPOR<br>INCIDIDUNT UT LABORE ET DOLORE MAGNA.
                </p>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <div class="container" style="padding-top: 60px; padding-bottom: 60px;">
        
        <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;">CLUB DEI 100</h2>
        
        <div class="text-white club100-content" style="font-size: 14px; line-height: 1.6; margin-bottom: 50px;">
            <?php 
            if ( have_posts() ) : 
                while ( have_posts() ) : the_post();
                    $content = get_the_content();
                    if(empty(trim($content))) {
                        ?>
                        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; color: var(--c-primary);">LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.</h3>
                        <p style="margin-bottom: 20px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <?php
                    } else {
                        the_content(); 
                    }
                endwhile; 
            endif; 
            ?>
        </div>

        <!-- CAROUSEL IMMAGINI -->
        <div class="club100-carousel-wrapper" style="margin-bottom: 60px; position: relative;">
            <div class="club100-carousel" style="display: flex; gap: 20px; overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: 20px; scrollbar-width: none;">
                <?php
                // TBD: Could be populated dynamically with custom fields, but for now placeholder images based on the mockup.
                $carousel_img = 'https://images.unsplash.com/photo-1574629810360-7efbb1b2e88b?q=80&w=800&auto=format&fit=crop'; // Yellow/black team placeholder
                for($i=0; $i<4; $i++):
                ?>
                <img src="<?php echo esc_url($carousel_img); ?>" style="width: calc(25% - 15px); min-width: 250px; height: auto; object-fit: cover; scroll-snap-align: start;" alt="Gallery">
                <?php endfor; ?>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; color: var(--c-primary); font-size: 24px; padding: 0 5px; margin-top: 10px;">
                <i class="fas fa-chevron-left" style="cursor: pointer;"></i>
                <div class="carousel-dots" style="display: flex; gap: 10px; justify-content: center;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: var(--c-primary);"></span>
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: transparent; border: 1px solid var(--c-primary);"></span>
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: transparent; border: 1px solid var(--c-primary);"></span>
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: transparent; border: 1px solid var(--c-primary);"></span>
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: transparent; border: 1px solid var(--c-primary);"></span>
                </div>
                <i class="fas fa-chevron-right" style="cursor: pointer;"></i>
            </div>
        </div>

    </div>

    <!-- FORM SEZIONE -->
    <div style="border-top: 1px solid #333; border-bottom: 1px solid #333; background-color: #000;">
        <div class="club100-form-section" style="display: flex; flex-wrap: wrap;">
            <!-- Form -->
            <div class="club100-form-left" style="flex: 1; min-width: 300px; padding: 60px 5%; display: flex; flex-direction: column; justify-content: center;">
                <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px;">ISCRIVITI</h2>
                
                <?php if ( isset($_GET['iscritto']) && $_GET['iscritto'] == '1' ) : ?>
                    <div style="background-color: var(--c-primary); color: #000; padding: 15px; margin-bottom: 20px; font-weight: bold;">
                        Grazie! La tua richiesta è stata inviata con successo.
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="hs-contact-form">
                    <input type="hidden" name="action" value="club100_subscribe">
                    <?php wp_nonce_field('club100_form_nonce', 'club100_nonce'); ?>
                    
                    <div style="display: flex; gap: 30px; margin-bottom: 25px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Nome*</label>
                            <input type="text" name="c100_nome" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="Nome">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Numero di telefono*</label>
                            <input type="text" name="c100_telefono" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="Numero di telefono">
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 30px; margin-bottom: 25px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">La tua e-mail*</label>
                            <input type="email" name="c100_email" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="E-mail">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Oggetto*</label>
                            <input type="text" name="c100_oggetto" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="Oggetto">
                        </div>
                    </div>

                    <div style="margin-bottom: 40px;">
                        <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Testo*</label>
                        <input type="text" name="c100_testo" required style="width: 100%; background: transparent; border: none; border-bottom: 1px solid white; color: white; padding: 12px 0; font-size: 14px;" placeholder="Testo">
                    </div>

                    <button type="submit" style="background-color: var(--c-primary); color: #000; border: none; padding: 14px 40px; font-weight: bold; text-transform: uppercase; cursor: pointer; letter-spacing: 1px; font-size: 14px; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">INVIA RICHIESTA</button>
                </form>
            </div>

            <!-- Immagine Lato -->
            <div class="club100-form-right" style="flex: 1; min-width: 300px; min-height: 400px; background-image: url('<?php echo esc_url($hero_image_url); ?>'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>

    <!-- SPONSOR -->
    <div class="container" style="padding-top: 60px; padding-bottom: 60px;">
        <h3 class="text-white" style="font-size: 26px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px;">SPONSOR</h3>
        <?php sport_theme_render_global_sponsors(); ?>
    </div>

</main>

<style>
.club100-carousel::-webkit-scrollbar {
    display: none;
}
.club100-content h3 {
    color: var(--c-primary) !important;
    font-size: 18px !important;
    margin-bottom: 20px !important;
}
.club100-content p {
    margin-bottom: 20px;
}
</style>

<?php get_footer('societa'); ?>
