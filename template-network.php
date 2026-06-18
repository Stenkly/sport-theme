<?php
/* Template Name: Sponsor / Network */
get_header(); ?>

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
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">Network</h1>
                <hr style="border: 0; border-top: 2px solid white; margin: 20px 0;">
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION GIALLA -->
    <section class="container" style="padding-top: 0; padding-bottom: 30px;">
        <div class="sponsor-cta">
            <div class="cta-container">
                <h2>Unisciti a noi, entra nel<br>network giallonero</h2>
                <p>Gli sponsor della prima squadra contribuiscono<br>allo sviluppo sportivo e organizzativo del progetto.</p>
                <a href="#" class="cta-btn" id="openNetworkModalBtn">SCOPRI</a>
            </div>
        </div>
    </section>

    <!-- Linea Decorativa -->
    <div class="container container-hr"><hr class="sponsor-hr"></div>

    <!-- SPONSOR IN MOVIMENTO -->
    <section class="container" style="padding-top: 20px; padding-bottom: 25px; overflow: hidden;">
        <h2 class="sponsor-section-title" style="margin-bottom: 25px; text-transform: uppercase;">Sponsor in movimento</h2>
        <?php
        $sponsor_query = new WP_Query([
            'post_type' => 'sponsor',
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_livello_sponsor',
                    'value' => 'partner',
                    'compare' => '='
                ],
                [
                    'relation' => 'OR',
                    [
                        'key' => '_destinazione_sponsor',
                        'value' => 'societa',
                        'compare' => '='
                    ],
                    [
                        'key' => '_destinazione_sponsor',
                        'value' => 'entrambi',
                        'compare' => '='
                    ]
                ]
            ]
        ]);

        if ($sponsor_query->have_posts()) {
            echo '<div class="ps-sponsors">';
            while ($sponsor_query->have_posts()) {
                $sponsor_query->the_post();
                $sito = get_post_meta(get_the_ID(), '_sito_url', true);
                $logo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
                if ($logo) {
                    if ($sito) {
                        echo '<a href="' . esc_url($sito) . '" target="_blank">';
                    }
                    echo '<img src="' . esc_url($logo) . '" alt="' . esc_attr(get_the_title()) . '">';
                    if ($sito) {
                        echo '</a>';
                    }
                }
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p style="color:#666;">(Nessun Sponsor in movimento inserito)</p>';
        }
        ?>
    </section>

    <!-- Linea Decorativa -->
    <div class="container container-hr"><hr class="sponsor-hr"></div>

    <!-- PARTNER IN MOVIMENTO -->
    <section class="container" style="padding-top: 20px; padding-bottom: 60px; overflow: hidden;">
        <h2 class="sponsor-section-title" style="margin-bottom: 25px; text-transform: uppercase;">Partner in movimento</h2>
        <?php
        $partner_query = new WP_Query([
            'post_type' => 'sponsor',
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_livello_sponsor',
                    'value' => 'partner',
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

        if ($partner_query->have_posts()) {
            echo '<div class="ps-sponsors">';
            while ($partner_query->have_posts()) {
                $partner_query->the_post();
                $sito = get_post_meta(get_the_ID(), '_sito_url', true);
                $logo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
                if ($logo) {
                    if ($sito) {
                        echo '<a href="' . esc_url($sito) . '" target="_blank">';
                    }
                    echo '<img src="' . esc_url($logo) . '" alt="' . esc_attr(get_the_title()) . '">';
                    if ($sito) {
                        echo '</a>';
                    }
                }
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p style="color:#666;">(Nessun Partner in movimento inserito)</p>';
        }
        ?>
    </section>

    <!-- INSTAGRAM -->
    <section class="ps-section container text-center" style="padding-top: 10px; padding-bottom: 50px;">
        <?php echo do_shortcode('[instagram-feed]'); ?>
    </section>

    <!-- POPUP FORMULARIO PERSONALIZZATO -->
    <div id="networkModal" class="network-modal-overlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; align-items: center; justify-content: center;">
        <div class="network-modal-content" style="position: relative; background: #0c0c0c; border: 2px solid var(--c-primary, #F9EA86); max-width: 550px; width: 90%; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); box-sizing: border-box;">
            <button id="closeNetworkModalBtn" class="network-modal-close" style="position: absolute; top: 15px; right: 20px; background: none; border: 0; color: #fff; font-size: 24px; cursor: pointer; font-weight: 700;">&times;</button>
            
            <h3 style="color: var(--c-primary, #F9EA86); font-size: 26px; font-weight: 900; margin-top: 0; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">Entra nel Network</h3>
            <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin-bottom: 25px; line-height: 1.5;">Compila il modulo per essere ricontattato e scoprire tutte le opportunità di partnership con l'AC Taverne.</p>
            
            <form id="networkContactForm" method="post" style="display: grid; gap: 18px;">
                <input type="hidden" name="action" value="act_submit_network_form">
                <?php wp_nonce_field('act_network_form_nonce_action', 'network_form_nonce'); ?>
                
                <div>
                    <label style="display: block; color: #fff; font-size: 12px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Nome Azienda *</label>
                    <input type="text" name="azienda" required style="width: 100%; padding: 12px; background: #181818; border: 1px solid #333; color: #fff; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--c-primary, #F9EA86)'" onblur="this.style.borderColor='#333'">
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; color: #fff; font-size: 12px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Nome Referente *</label>
                        <input type="text" name="nome" required style="width: 100%; padding: 12px; background: #181818; border: 1px solid #333; color: #fff; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--c-primary, #F9EA86)'" onblur="this.style.borderColor='#333'">
                    </div>
                    <div>
                        <label style="display: block; color: #fff; font-size: 12px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Cognome Referente *</label>
                        <input type="text" name="cognome" required style="width: 100%; padding: 12px; background: #181818; border: 1px solid #333; color: #fff; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--c-primary, #F9EA86)'" onblur="this.style.borderColor='#333'">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; color: #fff; font-size: 12px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Email *</label>
                        <input type="email" name="email" required style="width: 100%; padding: 12px; background: #181818; border: 1px solid #333; color: #fff; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--c-primary, #F9EA86)'" onblur="this.style.borderColor='#333'">
                    </div>
                    <div>
                        <label style="display: block; color: #fff; font-size: 12px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Telefono</label>
                        <input type="tel" name="telefono" style="width: 100%; padding: 12px; background: #181818; border: 1px solid #333; color: #fff; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--c-primary, #F9EA86)'" onblur="this.style.borderColor='#333'">
                    </div>
                </div>
                
                <div>
                    <label style="display: block; color: #fff; font-size: 12px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Messaggio *</label>
                    <textarea name="messaggio" required rows="4" style="width: 100%; padding: 12px; background: #181818; border: 1px solid #333; color: #fff; font-size: 14px; box-sizing: border-box; outline: none; font-family: inherit; resize: vertical; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--c-primary, #F9EA86)'" onblur="this.style.borderColor='#333'"></textarea>
                </div>
                
                <div id="networkFormStatus" style="display: none; padding: 12px; font-size: 14px; font-weight: 700; border-radius: 2px;"></div>
                
                <button type="submit" id="networkSubmitBtn" style="padding: 14px; background: var(--c-primary, #F9EA86); color: #000; border: 0; font-size: 14px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: background 0.2s; width: 100%;" onmouseover="this.style.background='#e5d475'" onmouseout="this.style.background='var(--c-primary, #F9EA86)'">Invia Richiesta</button>
            </form>
        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('networkModal');
    var openBtn = document.getElementById('openNetworkModalBtn');
    var closeBtn = document.getElementById('closeNetworkModalBtn');
    var form = document.getElementById('networkContactForm');
    var statusBox = document.getElementById('networkFormStatus');
    var submitBtn = document.getElementById('networkSubmitBtn');

    if (!modal || !openBtn || !closeBtn || !form) return;

    openBtn.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        form.reset();
        if (statusBox) {
            statusBox.style.display = 'none';
            statusBox.textContent = '';
            statusBox.className = '';
        }
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Invia Richiesta';
        }
    }

    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Invio in corso...';
        }

        if (statusBox) {
            statusBox.style.display = 'none';
            statusBox.textContent = '';
            statusBox.className = '';
        }

        var formData = new FormData(form);
        formData.append('action', 'act_submit_network_form');

        fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            if (result.success) {
                statusBox.style.display = 'block';
                statusBox.style.background = '#1b4d22';
                statusBox.style.color = '#76e288';
                statusBox.style.border = '1px solid #2e7d32';
                statusBox.textContent = result.data.message || 'Richiesta inviata correttamente! Ti risponderemo al più presto.';
                
                if (submitBtn) {
                    submitBtn.style.display = 'none';
                }
                
                setTimeout(function() {
                    closeModal();
                    if (submitBtn) {
                        submitBtn.style.display = 'block';
                    }
                }, 4000);
            } else {
                throw new Error(result.data.message || 'Si è verificato un errore durante l’invio.');
            }
        })
        .catch(function(error) {
            statusBox.style.display = 'block';
            statusBox.style.background = '#5c1919';
            statusBox.style.color = '#ff9e9e';
            statusBox.style.border = '1px solid #ab2c2c';
            statusBox.textContent = error.message || 'Errore durante l’invio. Riprova tra poco.';
            
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Invia Richiesta';
            }
        });
    });
});
</script>

<?php get_footer(); ?>
