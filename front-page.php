<?php
/**
 * The front page template file
 *
 * @package Sport_Theme
 */

get_header();
?>

	<main id="primary" class="site-main">

        <!-- Intro testuale -->
        <section class="front-intro">
            <h1>BENVENUTO IN <span class="text-primary">AC TAVERNE</span></h1>
            <p>PASSIONE, VALORI E FUTURO. DAL 1950, INSIEME.</p>
        </section>

        <!-- Sezione Split Cards -->
        <section class="split-sections">
            
            <!-- Card Sinistra (Prima Squadra) -->
            <div class="split-card card-left">
                <div class="split-card-content">
                    <h3>PRIMA SQUADRA</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <!-- Link per la prima squadra: modificalo con lo slug reale della pagina -->
                    <a href="<?php echo esc_url( site_url('/prima-squadra') ); ?>" class="btn btn-primary">ENTRA</a>
                </div>
            </div>

            <!-- Card Destra (AC Taverne Società / Settore Giovanile) -->
            <div class="split-card card-right">
                <div class="split-card-content">
                    <h3>AC TAVERNE</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <!-- Link per la società: modificalo con lo slug reale della pagina -->
                    <a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>" class="btn btn-primary">ENTRA</a>
                </div>
            </div>

        </section>

	</main><!-- #primary -->

<?php
get_footer();
