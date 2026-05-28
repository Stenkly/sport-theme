<?php
/**
 * The front page template file — pagina di ingresso standalone
 *
 * @package Sport_Theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AC Taverne 1950</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php wp_head(); ?>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body.home-standalone {
        background: #000;
        color: #fff;
        font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* ── TOP: Logo ── */
    .hp-top {
        text-align: center;
        padding: 30px 20px 15px;
        background: #000;
    }
    .hp-top img {
        max-height: 100px;
        width: auto;
    }

    /* ── Benvenuto ── */
    .hp-welcome {
        text-align: center;
        padding: 0 20px 30px;
        background: #000;
    }
    .hp-welcome h1 {
        font-size: 32px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        color: #fff;
    }
    .hp-welcome h1 span { color: #f2c800; }
    .hp-welcome p {
        font-size: 17px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #fff;
    }

    /* ── Split Cards ── */
    .hp-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        width: 100%;
        flex: 1;
        border-top: 1px solid rgba(255,255,255,0.1);
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .hp-card {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        min-height: 450px;
        border: 2px solid #fff;
    }
    .hp-card img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
    }
    .hp-card:hover img { transform: scale(1.04); }
    .hp-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0.4) 100%);
    }
    .hp-card-content {
        position: relative;
        z-index: 2;
        padding: 30px;
        width: 100%;
        max-width: 500px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .hp-card-content h2 {
        font-size: 38px;
        font-weight: 800;
        text-transform: uppercase;
        color: #fff;
        letter-spacing: 2px;
        margin-bottom: 15px;
        line-height: 1.1;
    }
    .hp-card-content p {
        font-size: 13px;
        color: rgba(255,255,255,0.9);
        line-height: 1.6;
        margin-bottom: 25px;
        max-width: 380px;
    }
    .hp-card-content .hp-btn {
        display: inline-block;
        width: 100%;
        max-width: 220px;
        background: #f2c800;
        color: #000;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        padding: 12px 20px;
        text-decoration: none;
        text-align: center;
        transition: background 0.2s;
    }
    .hp-card-content .hp-btn:hover { background: #fff; }

    /* ── Card Socials ── */
    .hp-card-social {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 25px;
    }
    .hp-card-social a {
        width: 35px;
        height: 35px;
        background: #f2c800;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        font-size: 16px;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }
    .hp-card-social a:hover {
        background: #fff;
        color: #000;
    }

    /* ── Footer ── */
    .hp-footer {
        background: #000;
        text-align: center;
        padding: 0;
    }
    
    .hp-footer-copy {
        background: #f2c800;
        color: #000;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 16px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }
    .hp-footer-copy a { color: #000; text-decoration: none; }
    .hp-footer-copy a:hover { text-decoration: underline; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .hp-split { grid-template-columns: 1fr; }
        .hp-card  { min-height: 350px; }
        .hp-welcome h1 { font-size: 24px; }
        .hp-card-content h2 { font-size: 30px; }
        .hp-top img { max-height: 70px; }
    }
    </style>
</head>
<body <?php body_class('home-standalone'); ?>>
<?php wp_body_open(); ?>

<!-- Logo -->
<div class="hp-top">
    <?php if ( has_custom_logo() ): the_custom_logo(); else: ?>
    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="AC Taverne 1950">
    <?php endif; ?>
</div>

<!-- Benvenuto -->
<div class="hp-welcome">
    <h1>BENVENUTO IN <span>AC TAVERNE</span></h1>
    <p>PASSIONE, VALORI E FUTURO. DAL 1950, INSIEME.</p>
</div>

<!-- Split Cards -->
<div class="hp-split">

    <!-- Prima Squadra -->
    <div class="hp-card">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/prima-squadra-gateway.jpg'); ?>" alt="Prima Squadra" loading="lazy">
        <div class="hp-card-overlay"></div>
        <div class="hp-card-content">
            <h2>PRIMA SQUADRA</h2>
            <p>La massima espressione sportiva del nostro club. Passione, determinazione e spirito di squadra per onorare i colori gialloneri in ogni partita e puntare a traguardi sempre più alti.</p>
            <a href="<?php echo esc_url(site_url('/prima-squadra')); ?>" class="hp-btn">ENTRA</a>
            <div class="hp-card-social">
                <a href="https://www.instagram.com/ac_taverne?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.facebook.com/share/1BZrVQUTfb/?mibextid=wwXIfr" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="https://www.linkedin.com/company/actaverne/" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="https://whatsapp.com/channel/0029VbBqO0G7YSd4VsRANF2G" target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="https://www.tiktok.com/@actaverne?_r=1&_t=ZN-96cub3rtWfm" target="_blank" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
            </div>
        </div>
    </div>

    <!-- AC Taverne -->
    <div class="hp-card">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/campo-taverne.jpg'); ?>" alt="AC Taverne" loading="lazy">
        <div class="hp-card-overlay"></div>
        <div class="hp-card-content">
            <h2>AC TAVERNE</h2>
            <p>Esplora il mondo societario giallonero. Trova le informazioni per la Scuola Calcio e le sezioni giovanili, i moduli di iscrizione, la nostra storia e i contatti della sede.</p>
            <a href="<?php echo esc_url(site_url('/ac-taverne')); ?>" class="hp-btn">ENTRA</a>
            <div class="hp-card-social">
                <a href="https://www.instagram.com/ac_taverne/" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
            </div>
        </div>
    </div>

</div>

<!-- Footer -->
<footer class="hp-footer">
    <div class="hp-footer-copy">
        <span>Copyright &copy; AC TAVERNE &middot; <a href="<?php echo esc_url(site_url('/privacy-policy')); ?>">Privacy Policy</a></span>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
