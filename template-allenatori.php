<?php
/**
 * Template Name: Pagina Area Allenatori
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-allenatori">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        $hero_image_url = sport_theme_get_societa_home_hero_url();
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">AREA ALLENATORI</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid white; margin: 20px 0;">
                <?php sport_theme_render_societa_submenu(); ?>
                <p class="text-white" style="font-size: 24px; font-weight: 700; text-transform: uppercase; margin: 20px 0 0 0; line-height: 1.3;">
                    DOCUMENTI E MODULISTICA<br>PER GLI STAFF TECNICI
                </p>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="container" style="padding-top: 10px; padding-bottom: 60px;">
        
        <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px;">DOCUMENTI DA SCARICARE</h2>
        
        <?php if ( is_user_logged_in() ) : ?>
            
            <div class="documents-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 60px;">
                <?php
                $has_documents = false;
                for ( $i = 1; $i <= 8; $i++ ) {
                    $doc_titolo = get_post_meta( get_the_ID(), "_all_titolo_$i", true );
                    $doc_desc   = get_post_meta( get_the_ID(), "_all_desc_$i", true );
                    $doc_url    = get_post_meta( get_the_ID(), "_all_url_$i", true );

                    if ( ! empty( $doc_url ) ) {
                        $has_documents = true;
                        $titolo_display = !empty($doc_titolo) ? $doc_titolo : "Documento $i";
                        ?>
                        <div class="doc-card" style="background-color: #111; border: 1px solid #333; padding: 25px; display: flex; flex-direction: column; justify-content: space-between; border-bottom: 3px solid var(--c-primary);">
                            <div>
                                <div style="color: var(--c-primary); font-size: 30px; margin-bottom: 15px;"><i class="fas fa-file-pdf"></i></div>
                                <h3 style="color: white; font-size: 20px; font-weight: 700; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($titolo_display); ?></h3>
                                <?php if(!empty($doc_desc)): ?>
                                <p style="color: #aaa; font-size: 13px; line-height: 1.5; margin-bottom: 25px;"><?php echo esc_html($doc_desc); ?></p>
                                <?php else: ?>
                                <div style="height: 25px;"></div>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo esc_url($doc_url); ?>" target="_blank" style="display: inline-block; background-color: var(--c-primary); color: #000; text-align: center; font-weight: 700; padding: 12px 0; text-transform: uppercase; text-decoration: none; font-size: 14px; letter-spacing: 1px; transition: opacity 0.3s; width: 100%;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">SCARICA <i class="fas fa-download" style="margin-left: 5px;"></i></a>
                        </div>
                        <?php
                    }
                }

                if ( ! $has_documents ) {
                    for($j = 1; $j <= 3; $j++) {
                        ?>
                        <div class="doc-card" style="background-color: #111; border: 1px solid #333; padding: 25px; display: flex; flex-direction: column; justify-content: space-between; border-bottom: 3px solid var(--c-primary);">
                            <div>
                                <div style="color: var(--c-primary); font-size: 30px; margin-bottom: 15px;"><i class="fas fa-file-alt"></i></div>
                                <h3 style="color: white; font-size: 20px; font-weight: 700; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">Modulo Esempio <?php echo $j; ?></h3>
                                <p style="color: #aaa; font-size: 13px; line-height: 1.5; margin-bottom: 25px;">Esempio di documento scaricabile. Inserisci i documenti reali tramite il pannello di WordPress.</p>
                            </div>
                            <a href="#" target="_blank" style="display: inline-block; background-color: var(--c-primary); color: #000; text-align: center; font-weight: 700; padding: 12px 0; text-transform: uppercase; text-decoration: none; font-size: 14px; letter-spacing: 1px; transition: opacity 0.3s; width: 100%;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">SCARICA <i class="fas fa-download" style="margin-left: 5px;"></i></a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

        <?php else : ?>

            <div class="login-wrapper" style="background-color: #111; border: 1px solid #333; border-top: 3px solid var(--c-primary); padding: 40px; max-width: 500px; margin: 0 auto 60px auto;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <i class="fas fa-lock" style="font-size: 40px; color: var(--c-primary); margin-bottom: 15px;"></i>
                    <h3 style="color: white; font-size: 24px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">ACCESSO RISERVATO</h3>
                    <p style="color: #aaa; font-size: 14px;">Inserisci nome utente e password per accedere ai documenti dell'Area Allenatori.</p>
                </div>
                
                <?php
                if ( isset($_GET['login']) && $_GET['login'] == 'failed' ) {
                    echo '<p style="color: #ff4444; font-size: 14px; text-align: center; margin-bottom: 20px;">Credenziali non valide. Riprova.</p>';
                }
                ?>

                <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                    <div style="margin-bottom: 20px;">
                        <label for="user_login" style="display: block; color: white; margin-bottom: 8px; font-size: 13px;">Nome utente</label>
                        <input type="text" name="log" id="user_login" style="width: 100%; background: transparent; border: 1px solid #555; color: white; padding: 12px; font-size: 14px;" value="" size="20" required>
                    </div>
                    <div style="margin-bottom: 30px;">
                        <label for="user_pass" style="display: block; color: white; margin-bottom: 8px; font-size: 13px;">Password</label>
                        <input type="password" name="pwd" id="user_pass" style="width: 100%; background: transparent; border: 1px solid #555; color: white; padding: 12px; font-size: 14px;" value="" size="20" required>
                    </div>
                    <p class="forgetmenot" style="margin-bottom: 20px;">
                        <label for="rememberme" style="color: #aaa; font-size: 13px;"><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Ricordami</label>
                    </p>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" style="width: 100%; background-color: var(--c-primary); color: #000; border: none; padding: 14px; font-weight: 700; text-transform: uppercase; cursor: pointer; letter-spacing: 1px; transition: opacity 0.3s;" value="ACCEDI" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        <input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>">
                        <input type="hidden" name="testcookie" value="1">
                    </p>
                </form>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer('societa'); ?>
