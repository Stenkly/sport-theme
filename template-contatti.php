<?php
/**
 * Template Name: Pagina Contatti
 *
 * @package Sport_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-contatti">

    <!-- SEZIONE 1: HERO con foto squadra che sfuma al nero -->
    <section class="contatti-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div style="position: relative; width: 100%; height: 45vh; overflow: hidden;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: center top;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <!-- Sfumatura: foto → nero in basso -->
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 70%; background: linear-gradient(to top, #000000 0%, rgba(0,0,0,0.7) 40%, transparent 100%); pointer-events: none;"></div>
            <!-- Titolo CONTATTI -->
            <div class="container" style="position: absolute; bottom: 30px; left: 0; right: 0;">
                <h1 style="font-size: 48px; font-weight: 700; text-transform: uppercase; color: white; margin: 0; letter-spacing: 2px;">Contatti</h1>
            </div>
        </div>
        <!-- Linea bianca a larghezza piena -->
        <div class="container">
            <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.4); margin: 0;">
        </div>
    </section>

    <!-- SEZIONE 2: Info contatto + Form (su sfondo nero) -->
    <section class="container" style="padding: 40px 0 60px;">
        <div class="contatti-grid">

            <!-- Colonna sinistra: informazioni -->
            <div class="contatti-info">
                <div class="contatti-info-block">
                    <h3 class="contatti-label">EMAIL</h3>
                    <p><a href="mailto:primasquadra@actaverne.ch">primasquadra@actaverne.ch</a></p>
                </div>

                <div class="contatti-info-block">
                    <h3 class="contatti-label">TELEFONO</h3>
                    <p><a href="tel:+41000000000">+41 xx xxx xx xx</a></p>
                </div>

                <div class="contatti-info-block">
                    <h3 class="contatti-label">INDIRIZZO</h3>
                    <p>Via Traversa 2<br>CP 703<br>6807 Taverne</p>
                </div>

                <div class="contatti-info-block">
                    <h3 class="contatti-label">SEGUICI</h3>
                    <div class="contatti-social">
                        <a href="https://www.instagram.com/ac_taverne?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://www.facebook.com/share/1BZrVQUTfb/?mibextid=wwXIfr" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>

            <!-- Colonna destra: form -->
            <div class="contatti-form-wrapper">
                <h2 class="contatti-form-title">DOMANDE? CONTATTACI!</h2>

                <form id="contattiForm" class="contatti-form" method="post" action="">
                    <?php wp_nonce_field('contatti_form_nonce', 'contatti_nonce'); ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contatti-nome">Nome*</label>
                            <input type="text" id="contatti-nome" name="contatti_nome" placeholder="Nome" required>
                        </div>
                        <div class="form-group">
                            <label for="contatti-telefono">Numero di telefono</label>
                            <input type="tel" id="contatti-telefono" name="contatti_telefono" placeholder="Numero di telefono">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contatti-email">La tua e-mail*</label>
                            <input type="email" id="contatti-email" name="contatti_email" placeholder="E-mail" required>
                        </div>
                        <div class="form-group">
                            <label for="contatti-oggetto">Oggetto*</label>
                            <input type="text" id="contatti-oggetto" name="contatti_oggetto" placeholder="Oggetto" required>
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <label for="contatti-domanda">La tua domanda*</label>
                        <textarea id="contatti-domanda" name="contatti_domanda" placeholder="Domanda" rows="5" required></textarea>
                    </div>

                    <button type="submit" class="btn-primary contatti-submit">INVIA MESSAGGIO</button>

                    <?php if ( isset($_GET['contatti_inviato']) && $_GET['contatti_inviato'] == '1' ) : ?>
                    <div class="contatti-success">
                        <p>✓ Messaggio inviato con successo! Ti risponderemo al più presto.</p>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>

    <!-- SEZIONE 3: Partner e Sponsor -->
    <section class="ps-section container" style="padding-top: 40px;">
        <h2 class="section-title text-white">PARTNER E SPONSOR</h2>
        <?php sport_theme_render_global_sponsors(); ?>
    </section>

    <!-- SEZIONE 4: Instagram Feed -->
    <section class="ps-section container text-center" style="padding-top: 10px; padding-bottom: 50px;">
        <?php echo do_shortcode('[instagram-feed]'); ?>
    </section>

</main>

<?php get_footer(); ?>
