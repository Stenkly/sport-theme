<?php
/**
 * Template Name: Pagina Contatti Società
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-contatti">

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
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 30px;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">CONTATTI</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 20px 0;">
                <?php sport_theme_render_societa_submenu(); ?>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="container" style="padding-top: 50px; padding-bottom: 60px;">
        
        <!-- SEZIONE RESPONSABILI -->
        <h2 class="text-white" style="font-size: 34px; font-weight: 700; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px;">CONTATTI RESPONSABILI ALLIEVI</h2>
        
        <div class="responsabili-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 40px 30px; margin-bottom: 60px;">
            <?php
            $has_resp = false;
            for ( $i = 1; $i <= 9; $i++ ) {
                $ruolo = get_post_meta( get_the_ID(), "_cont_ruolo_$i", true );
                $info  = get_post_meta( get_the_ID(), "_cont_info_$i", true );
                
                if ( ! empty( $ruolo ) || ! empty( $info ) ) {
                    $has_resp = true;
                    ?>
                    <div class="resp-card">
                        <h4 style="color: var(--c-primary); font-size: 15px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;"><?php echo esc_html($ruolo); ?></h4>
                        <div style="color: white; font-size: 14px; line-height: 1.6;">
                            <?php echo nl2br(esc_html($info)); ?>
                        </div>
                    </div>
                    <?php
                }
            }

            if ( ! $has_resp ) {
                // Default placeholders dal mockup
                $placeholders = [
                    ['RESPONSABILE ALLIEVI', "Francesco Ruberto\nE-mail: info@actaverne.com"],
                    ['ASSISTENTE RESP.ALLIEVI', "Daniele Meneghelli\nTel: +41 79 393 76 52"],
                    ['RESPONSABILE TECNICO', "Stefano Marrazzo\nTel: +41 78 719 77 75"],
                    ['PREPARATORI PORTIERI ALLIEVI A + B', "A: Antonio Pace\nTel: +41 76 387 76 06\n\nB: Marcello Clemente\nTel: +41 76 506 19 05"],
                    ['PREPARATORE PORTIERI FEMMINILE', "Danilo Muschietti\nTel: +41 78 629 24 42"],
                    ['RESP ALLENATORI PORTIERI + ALLENATORE PORTIERI ALLIEVI C + D + E', "Andrea Pasquot\nTel: +39 375 633 72 69"]
                ];
                foreach($placeholders as $p) {
                    ?>
                    <div class="resp-card">
                        <h4 style="color: var(--c-primary); font-size: 14px; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px;"><?php echo esc_html($p[0]); ?></h4>
                        <div style="color: white; font-size: 14px; line-height: 1.6;">
                            <?php echo nl2br(esc_html($p[1])); ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <!-- SEZIONE DOMANDE -->
        <h2 class="text-white" style="font-size: 40px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; margin-top: 80px;">DOMANDE?</h2>
        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.4); margin-bottom: 40px;">

        <div class="domande-section" style="display: flex; flex-wrap: wrap; gap: 50px;">
            
            <!-- Lato Sinistro: Info Generali -->
            <div class="domande-left" style="flex: 1; min-width: 280px; max-width: 400px;">
                <?php
                $gen_email = get_post_meta( get_the_ID(), "_cont_email", true ) ?: 'info@actaverne.com';
                $gen_tel   = get_post_meta( get_the_ID(), "_cont_tel", true ) ?: '+41 91 945 22 95';
                $gen_ind   = get_post_meta( get_the_ID(), "_cont_ind", true ) ?: "Via Taverne 2\nCP 703\n6807 Taverne";
                ?>
                
                <div style="margin-bottom: 30px;">
                    <h4 style="color: var(--c-primary); font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">EMAIL</h4>
                    <a href="mailto:<?php echo esc_attr($gen_email); ?>" style="color: white; font-size: 14px; text-decoration: none;"><?php echo esc_html($gen_email); ?></a>
                </div>

                <div style="margin-bottom: 30px;">
                    <h4 style="color: var(--c-primary); font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">TELEFONO</h4>
                    <a href="tel:<?php echo esc_attr(str_replace(' ', '', $gen_tel)); ?>" style="color: white; font-size: 14px; text-decoration: none;"><?php echo esc_html($gen_tel); ?></a>
                </div>

                <div style="margin-bottom: 30px;">
                    <h4 style="color: var(--c-primary); font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">INDIRIZZO</h4>
                    <div style="color: white; font-size: 14px; line-height: 1.6;">
                        <?php echo nl2br(esc_html($gen_ind)); ?>
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <h4 style="color: var(--c-primary); font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 15px;">SEGUICI</h4>
                    <div class="social-icons" style="display: flex; gap: 15px;">
                        <a href="#" style="color: #000; background-color: #fff; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-size: 18px;"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" style="color: #000; background-color: #fff; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-size: 18px;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" style="color: #000; background-color: #fff; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-size: 18px;"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#" style="color: #000; background-color: #fff; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-size: 18px;"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#" style="color: #000; background-color: #fff; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-size: 18px;"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>
            </div>

            <!-- Lato Destro: Form -->
            <div class="domande-right" style="flex: 2; min-width: 300px;">
                <h4 style="color: var(--c-primary); font-size: 18px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px;">CONTATTACI!</h4>
                
                <?php if ( isset($_GET['inviato']) && $_GET['inviato'] == '1' ) : ?>
                    <div style="background-color: var(--c-primary); color: #000; padding: 15px; margin-bottom: 20px; font-weight: bold;">
                        Grazie! Il tuo messaggio è stato inviato.
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="hs-contact-form">
                    <input type="hidden" name="action" value="contatti_societa_submit">
                    <?php wp_nonce_field('contatti_soc_form_nonce', 'contatti_soc_nonce'); ?>
                    
                    <div style="display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Nome*</label>
                            <input type="text" name="cs_nome" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="Nome">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Numero di telefono</label>
                            <input type="text" name="cs_telefono" style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="Numero di telefono">
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">La tua e-mail*</label>
                            <input type="email" name="cs_email" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="E-mail">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Oggetto*</label>
                            <input type="text" name="cs_oggetto" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="Oggetto">
                        </div>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">La tua domanda*</label>
                        <textarea name="cs_domanda" required style="width: 100%; background: transparent; border: 1px solid white; color: white; padding: 12px; font-size: 14px; min-height: 120px;" placeholder="Domanda"></textarea>
                    </div>

                    <button type="submit" style="background-color: var(--c-primary); color: #000; border: none; padding: 14px 40px; font-weight: bold; text-transform: uppercase; cursor: pointer; letter-spacing: 1px; font-size: 14px; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">INVIA MESSAGGIO</button>
                </form>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.4); margin-top: 60px; margin-bottom: 40px;">

        <!-- SPONSOR -->
        <h3 class="text-white" style="font-size: 26px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px;">SPONSOR</h3>
        <?php sport_theme_render_global_sponsors(); ?>

    </div>
</main>

<?php get_footer('societa'); ?>
