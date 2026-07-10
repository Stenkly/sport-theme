<?php
/**
 * Front page gateway.
 *
 * @package Sport_Theme
 */
$site_logo = get_template_directory_uri() . '/assets/images/logo-ac-gateway.jpg';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AC Taverne 1950</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php wp_head(); ?>
    <style>
    *, *::before, *::after { box-sizing: border-box; }
    html, body { min-height: 100%; }
    body.home-standalone {
        margin: 0;
        background: #000;
        color: #fff;
        font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
        overflow: hidden;
    }
    .hp-gateway {
        --hp-primary: #F2E302;
        --hp-secondary: #F9EA86;
        position: relative;
        height: 100vh;
        min-height: 100svh;
        background: #000;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .hp-logo {
        position: absolute;
        top: 20px;
        left: 50%;
        z-index: 40;
        width: auto;
        height: 96px;
        transform: translateX(-50%);
        pointer-events: none;
        transition: left .55s cubic-bezier(.4, 0, .2, 1), transform .55s cubic-bezier(.4, 0, .2, 1);
    }
    .hp-logo img {
        display: block;
        width: auto;
        height: 96px;
        object-fit: contain;
    }
    .hp-split {
        position: relative;
        flex: 1 1 auto;
        height: 100%;
        min-height: 1px;
        display: flex;
        overflow: hidden;
    }
    .hp-panel {
        position: relative;
        flex: 0 0 auto;
        width: 50%;
        min-width: 0;
        overflow: hidden;
        color: #fff;
        text-decoration: none;
        isolation: isolate;
        transition: width .55s cubic-bezier(.4, 0, .2, 1), filter .55s cubic-bezier(.4, 0, .2, 1);
    }
    .hp-panel::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        background: linear-gradient(to top, rgba(0,0,0,.7) 0%, rgba(0,0,0,.3) 50%, transparent 100%);
        transition: background .55s ease, opacity .55s ease;
        pointer-events: none;
    }
    .hp-panel + .hp-panel {
        border-left: 0;
    }
    .hp-panel--prima::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 10;
        width: 1px;
        background: rgba(244, 226, 80, .4);
        pointer-events: none;
    }
    .hp-panel-link {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: block;
        color: inherit;
        text-decoration: none;
    }
    .hp-panel img {
        position: absolute;
        inset: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transform: scale(1.01);
        transition: transform .55s cubic-bezier(.4, 0, .2, 1), filter .55s ease;
    }
    .hp-panel--prima img { object-position: 50% 48%; }
    .hp-panel--societa img { object-position: 58% 48%; }
    .hp-content {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 3;
        max-width: none;
        padding: 2.5rem 3rem;
        transform: translateX(0);
        transition: transform .5s cubic-bezier(.4, 0, .2, 1);
        pointer-events: none;
    }
    .hp-title {
        margin: 0;
        color: rgb(244, 226, 80);
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        line-height: 1.1;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        text-shadow: none;
        white-space: nowrap;
    }
    .hp-reveal {
        margin-top: .75rem;
        opacity: 0;
        transform: translateY(14px);
        transition: opacity .35s .15s, transform .35s .15s;
        pointer-events: none;
    }
    .hp-reveal p {
        margin: 0 0 22px;
        max-width: 390px;
        color: rgba(255,255,255,.88);
        font-size: 15px;
        line-height: 1.5;
        font-weight: 600;
    }
    .hp-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 132px;
        min-height: 38px;
        border: 1px solid var(--hp-secondary);
        color: var(--hp-secondary);
        background: transparent;
        text-transform: uppercase;
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .18em;
        text-decoration: none;
        pointer-events: auto;
    }
    .hp-social {
        display: flex;
        gap: 1rem;
        margin-top: 1.25rem;
        opacity: .4;
        transition: opacity .4s;
        pointer-events: auto;
    }
    .hp-social a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        color: #fff;
        text-decoration: none;
        font-size: 18px;
        transition: color .2s ease, transform .2s ease;
    }
    .hp-social a:hover {
        color: #fff;
        transform: translateY(-2px);
    }
    .hp-footer {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10;
        min-height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--hp-secondary);
        color: #000;
        padding: .5rem;
        font-size: .6rem;
        font-weight: 800;
        letter-spacing: .1em;
    }
    .hp-footer a {
        color: inherit;
        text-decoration: none;
    }
    .hp-footer a:hover { text-decoration: underline; }
    body.home-standalone #wp-chat-floating {
        display: none !important;
    }
    @media (hover: hover) and (pointer: fine) {
        .hp-split:has(.hp-panel--prima:hover) .hp-panel--prima,
        .hp-split:has(.hp-panel--societa:hover) .hp-panel--societa {
            width: 62%;
        }
        .hp-split:has(.hp-panel--prima:hover) .hp-panel--societa,
        .hp-split:has(.hp-panel--societa:hover) .hp-panel--prima {
            width: 38%;
            filter: brightness(.58);
        }
        .hp-split:has(.hp-panel:hover) .hp-panel:hover img {
            transform: scale(1.055);
        }
        .hp-split:has(.hp-panel:hover) .hp-panel:hover::after {
            background: linear-gradient(to top, rgba(0,0,0,.7) 0%, rgba(0,0,0,.3) 50%, transparent 100%);
        }
        .hp-panel:hover .hp-content {
            transform: translateX(16px);
        }
        .hp-panel:hover .hp-reveal {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .hp-panel:hover .hp-social {
            opacity: 1;
        }
        .hp-gateway:has(.hp-panel--prima:hover) .hp-logo { left: 31%; }
        .hp-gateway:has(.hp-panel--societa:hover) .hp-logo { left: 69%; }
    }
    @media (max-width: 980px) {
        body.home-standalone { overflow: auto; }
        .hp-logo {
            position: absolute;
            top: 14px;
            height: 70px;
        }
        .hp-logo img { height: 70px; }
        .hp-split {
            min-height: 1px;
            flex-direction: column;
        }
        .hp-panel {
            flex: 0 0 calc((100svh - 28px) / 2);
            width: 100%;
            min-height: 360px;
        }
        .hp-panel + .hp-panel {
            border-left: 0;
            border-top: 0;
        }
        .hp-panel--prima::before {
            top: auto;
            right: 0;
            bottom: 0;
            left: 0;
            width: auto;
            height: 1px;
        }
        .hp-content {
            left: 0;
            right: 0;
            bottom: 0;
            max-width: none;
            padding: 0 24px 32px;
        }
        .hp-title {
            font-size: clamp(34px, 10vw, 58px);
            white-space: normal;
        }
        .hp-reveal {
            opacity: 1;
            transform: none;
            pointer-events: auto;
            margin-top: 14px;
        }
        .hp-reveal p {
            display: none;
        }
        .hp-btn {
            min-width: 116px;
            min-height: 40px;
        }
        .hp-social {
            gap: 1rem;
            margin-top: 1.25rem;
            opacity: .4;
        }
    }
    </style>
</head>
<body <?php body_class( 'home-standalone' ); ?>>
<?php wp_body_open(); ?>

<main class="hp-gateway" aria-label="Ingresso AC Taverne">
    <a class="hp-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="AC Taverne 1950">
        <img src="<?php echo esc_url( $site_logo ); ?>" alt="AC Taverne 1950">
    </a>

    <section class="hp-split" aria-label="Scegli sezione">
        <article class="hp-panel hp-panel--prima">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/home-prima-squadra.jpg' ); ?>" alt="Prima Squadra AC Taverne">
            <a class="hp-panel-link" href="<?php echo esc_url( site_url( '/prima-squadra' ) ); ?>" aria-label="Vai alla Prima Squadra"></a>
            <div class="hp-content">
                <h1 class="hp-title">Prima Squadra</h1>
                <div class="hp-reveal">
                    <a class="hp-btn" href="<?php echo esc_url( site_url( '/prima-squadra' ) ); ?>">Scopri</a>
                </div>
                <div class="hp-social" aria-label="Social Prima Squadra">
                    <a href="https://www.instagram.com/ac_taverne?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" rel="noopener" aria-label="Instagram Prima Squadra"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.facebook.com/share/1BZrVQUTfb/?mibextid=wwXIfr" target="_blank" rel="noopener" aria-label="Facebook Prima Squadra"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.linkedin.com/company/actaverne/" target="_blank" rel="noopener" aria-label="LinkedIn Prima Squadra"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://whatsapp.com/channel/0029VbBqO0G7YSd4VsRANF2G" target="_blank" rel="noopener" aria-label="WhatsApp Prima Squadra"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="https://www.tiktok.com/@actaverne?_r=1&_t=ZN-96cub3rtWfm" target="_blank" rel="noopener" aria-label="TikTok Prima Squadra"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
        </article>

        <article class="hp-panel hp-panel--societa">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/home-ac-taverne.jpg' ); ?>" alt="AC Taverne settore giovanile">
            <a class="hp-panel-link" href="<?php echo esc_url( site_url( '/ac-taverne' ) ); ?>" aria-label="Vai ad AC Taverne"></a>
            <div class="hp-content">
                <h2 class="hp-title">AC Taverne</h2>
                <div class="hp-reveal">
                    <a class="hp-btn" href="<?php echo esc_url( site_url( '/ac-taverne' ) ); ?>">Scopri</a>
                </div>
                <div class="hp-social" aria-label="Social AC Taverne">
                    <a href="https://www.instagram.com/ac_taverne/" target="_blank" rel="noopener" aria-label="Instagram AC Taverne"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="Facebook AC Taverne"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.tiktok.com/@actaverne?_r=1&_t=ZN-96cub3rtWfm" target="_blank" rel="noopener" aria-label="TikTok AC Taverne"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
        </article>
    </section>

    <footer class="hp-footer">
        <span>Copyright &copy; AC TAVERNE &middot; <a href="<?php echo esc_url( site_url( '/privacy-policy' ) ); ?>">Privacy Policy</a></span>
    </footer>
</main>

<?php wp_footer(); ?>
</body>
</html>
