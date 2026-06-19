<?php
/* Template Name: Sponsor / Partner */
get_header();

$render_sponsor_marquee = function($level, $empty_message, $extra_class = '') {
    $query = new WP_Query([
        'post_type' => 'sponsor',
        'posts_per_page' => -1,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => '_livello_sponsor',
                'value' => $level,
                'compare' => '='
            ],
            [
                'relation' => 'OR',
                [
                    'key' => '_destinazione_sponsor',
                    'value' => 'prima_squadra',
                    'compare' => '='
                ],
                [
                    'key' => '_destinazione_sponsor',
                    'value' => 'entrambi',
                    'compare' => '='
                ],
                [
                    'key' => '_destinazione_sponsor',
                    'compare' => 'NOT EXISTS'
                ]
            ]
        ]
    ]);

    if (!$query->have_posts()) {
        echo '<p class="sponsor-empty-message">' . esc_html($empty_message) . '</p>';
        return;
    }

    $items = [];
    while ($query->have_posts()) {
        $query->the_post();
        $sito = get_post_meta(get_the_ID(), '_sito_url', true);
        $logo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : '';

        ob_start();
        ?>
        <div class="sponsor-item">
            <?php if ($sito): ?><a href="<?php echo esc_url($sito); ?>" target="_blank" rel="noopener"><?php endif; ?>
                <?php if ($logo): ?>
                    <img src="<?php echo esc_url($logo); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                <?php else: ?>
                    <div class="sponsor-placeholder"><?php the_title(); ?></div>
                <?php endif; ?>
            <?php if ($sito): ?></a><?php endif; ?>
        </div>
        <?php
        $items[] = ob_get_clean();
    }
    wp_reset_postdata();

    $visible_items = $items;
    while (count($visible_items) < 8) {
        $visible_items = array_merge($visible_items, $items);
    }
    ?>
    <div class="sponsor-marquee <?php echo esc_attr($extra_class); ?>">
        <div class="sponsor-marquee-track">
            <div class="sponsor-marquee-group">
                <?php echo implode('', $visible_items); ?>
            </div>
            <div class="sponsor-marquee-group" aria-hidden="true">
                <?php echo implode('', $visible_items); ?>
            </div>
        </div>
    </div>
    <?php
};
?>

<main class="site-main" style="background-color: #000;">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center 20%;" alt="<?php echo esc_attr(get_the_title()); ?>">
            
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">NETWORK</h1>
                <hr style="border: 0; border-top: 2px solid white; margin: 20px 0;">
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION GIALLA -->
    <section class="container" style="padding-top: 0; padding-bottom: 30px;">
        <div class="sponsor-cta">
            <div class="cta-container">
                <h2>Unisciti a noi,<br>entra nel network giallonero</h2>
                <p>Gli sponsor della prima squadra contribuiscono<br>allo sviluppo sportivo e organizzativo del progetto.</p>
                <button type="button" class="cta-btn network-popup-open" data-network-open>SCOPRI</button>
                <?php if ( isset($_GET['network_inviato']) && $_GET['network_inviato'] === '1' ) : ?>
                    <p class="network-form-status network-form-status-success">Richiesta inviata correttamente.</p>
                <?php elseif ( isset($_GET['network_inviato']) && $_GET['network_inviato'] === '0' ) : ?>
                    <p class="network-form-status network-form-status-error">Controlla i campi e riprova.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Linea Decorativa -->
    <div class="container container-hr"><hr class="sponsor-hr"></div>

    <!-- SPONSOR -->
    <section class="container" style="padding-top: 20px; padding-bottom: 20px;">
        <h2 class="sponsor-section-title">SPONSOR</h2>
        <?php $render_sponsor_marquee('main', 'Nessun Sponsor inserito. Aggiungi i loghi dal pannello WordPress sotto "Sponsor".', 'sponsor-marquee-main'); ?>
    </section>

    <!-- Linea Decorativa -->
    <div class="container container-hr"><hr class="sponsor-hr"></div>

    <!-- PARTNER -->
    <section class="container" style="padding-top: 20px; padding-bottom: 60px;">
        <h2 class="sponsor-section-title">PARTNER</h2>
        <?php $render_sponsor_marquee('partner', 'Nessun Network inserito. Aggiungi i loghi dal pannello WordPress sotto "Sponsor".', 'sponsor-marquee-network'); ?>
    </section>

    <!-- INSTAGRAM -->
    <section class="ps-section container text-center" style="padding-top: 10px; padding-bottom: 50px;">
        <?php echo do_shortcode('[instagram-feed]'); ?>
    </section>

    <div class="network-modal" data-network-modal aria-hidden="true">
        <div class="network-modal-backdrop" data-network-close></div>
        <div class="network-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="network-modal-title">
            <button type="button" class="network-modal-close" data-network-close aria-label="Chiudi formulario">×</button>
            <div class="network-modal-head">
                <h2 id="network-modal-title">Entra nel network giallonero</h2>
                <p>Raccontaci come vuoi collaborare con AC Taverne.</p>
            </div>
            <form class="network-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="network_submit">
                <?php wp_nonce_field('network_form_nonce', 'network_nonce'); ?>
                <input type="text" name="network_website" class="network-hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">

                <div class="network-form-grid">
                    <label>
                        <span>Azienda*</span>
                        <input type="text" name="network_azienda" required>
                    </label>
                    <label>
                        <span>Referente*</span>
                        <input type="text" name="network_nome" required>
                    </label>
                    <label>
                        <span>E-mail*</span>
                        <input type="email" name="network_email" required>
                    </label>
                    <label>
                        <span>Telefono</span>
                        <input type="tel" name="network_telefono">
                    </label>
                </div>

                <label>
                    <span>Tipo di interesse</span>
                    <select name="network_interesse">
                        <option value="Sponsor">Sponsor</option>
                        <option value="Network">Network</option>
                        <option value="Collaborazione">Collaborazione</option>
                    </select>
                </label>

                <label>
                    <span>Messaggio*</span>
                    <textarea name="network_messaggio" rows="5" required></textarea>
                </label>

                <button type="submit" class="network-form-submit">INVIA RICHIESTA</button>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.querySelector('[data-network-modal]');
        var openEls = document.querySelectorAll('[data-network-open]');
        var closeEls = document.querySelectorAll('[data-network-close]');

        function openModal() {
            if (!modal) return;
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('network-modal-open');
            var firstInput = modal.querySelector('input:not([type="hidden"]):not(.network-hp-field)');
            if (firstInput) firstInput.focus();
        }

        function closeModal() {
            if (!modal) return;
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('network-modal-open');
        }

        openEls.forEach(function(el) { el.addEventListener('click', openModal); });
        closeEls.forEach(function(el) { el.addEventListener('click', closeModal); });
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeModal();
        });
    });
    </script>

</main>

<?php get_footer(); ?>
