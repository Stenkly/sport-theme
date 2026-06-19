<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<!-- Carichiamo FontAwesome per le icone social nel footer -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

	<?php wp_head(); ?>
    <!-- Fancybox CSS for Lightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$sport_theme_logo_url = function_exists('sport_theme_get_context_logo_url') ? sport_theme_get_context_logo_url() : home_url('/');
$sport_theme_logo_src = function_exists('sport_theme_get_site_logo_url') ? sport_theme_get_site_logo_url() : get_template_directory_uri() . '/assets/images/logo.png';
$sport_theme_prima_menu_context = is_page(array(
    'prima-squadra',
    'news',
    'news-prima-squadra',
    'giocatori',
    'staff',
    'stagione',
    'organigramma',
    'storia',
    'presente-e-futuro',
    'partner',
    'sponsor',
    'contatti',
)) || is_page_template(array(
    'template-prima-squadra.php',
    'template-news.php',
    'template-rosa.php',
    'template-staff.php',
    'template-stagione.php',
    'template-organigramma.php',
    'template-storia.php',
    'template-club-page.php',
    'template-partner.php',
    'template-contatti.php',
)) || is_singular(array('giocatore', 'partita'))
   || ( is_singular('post') && ! has_category('settore-giovanile') )
   || ( is_singular('evento') && ! has_category('settore-giovanile') );
?>

<!-- ===================== MOBILE MENU OVERLAY ===================== -->
<div id="mobile-menu-overlay" style="
    display: none;
    position: fixed;
    inset: 0;
    background: #000;
    z-index: 9999;
    overflow-y: auto;
    flex-direction: column;
    padding: 25px 30px 40px;
">
    <!-- Top bar: X + Logo -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
        <button id="mobile-menu-close" style="background:none; border:none; color:white; font-size:26px; cursor:pointer; padding:0;">✕</button>
        <a href="<?php echo esc_url($sport_theme_logo_url); ?>" class="mobile-menu-logo-link custom-logo-link" rel="home" aria-label="AC Taverne">
            <img src="<?php echo esc_url($sport_theme_logo_src); ?>" alt="AC Taverne" class="mobile-menu-logo site-logo">
        </a>
        <div style="width:40px;"></div><!-- Spacer for centering -->
    </div>

    <hr style="border-color: rgba(255,255,255,0.15); margin-bottom: 10px;">

    <!-- Nav items -->
    <nav style="flex: 1;">
        <ul style="list-style:none; margin:0; padding:0;">

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/news') ); ?>" class="<?php if (is_page_template('template-news.php') || is_page('news') || is_page('news-prima-squadra')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">News</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="https://actaverneshop.com/" target="_blank" style="display:block; color:var(--c-primary); font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">Shop</a>
            </li>

            <!-- Team con sottomenu -->
            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div class="mob-toggle" style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; padding:18px 0;">
                    <span class="<?php if (is_page_template('template-rosa.php') || is_page_template('template-staff.php') || is_page('giocatori') || is_page('staff') || is_singular('giocatore')) echo 'mob-active'; ?>" style="color:white; font-size:20px; font-weight:700; text-transform:uppercase; letter-spacing:1px;">Team</span>
                    <i class="fa-solid fa-chevron-down" style="color:white; font-size:14px; transition: transform 0.3s;"></i>
                </div>
                <ul class="mob-submenu" style="display:none; list-style:none; padding: 0 0 10px 20px; margin:0;">
                    <li><a href="<?php echo esc_url( site_url('/giocatori') ); ?>" class="<?php if (is_page_template('template-rosa.php') || is_page('giocatori')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Giocatori</a></li>
                    <li><a href="<?php echo esc_url( site_url('/staff') ); ?>" class="<?php if (is_page_template('template-staff.php') || is_page('staff')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Staff</a></li>
                </ul>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/stagione') ); ?>" class="<?php if (is_page_template('template-stagione.php') || is_page('stagione')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">Stagione</a>
            </li>

            <!-- Club con sottomenu -->
            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div class="mob-toggle" style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; padding:18px 0;">
                    <span class="<?php if (is_page_template('template-organigramma.php') || is_page_template('template-storia.php') || is_page_template('template-club-page.php') || is_page('organigramma') || is_page('storia') || is_page('presente-e-futuro')) echo 'mob-active'; ?>" style="color:white; font-size:20px; font-weight:700; text-transform:uppercase; letter-spacing:1px;">Club</span>
                    <i class="fa-solid fa-chevron-down" style="color:white; font-size:14px; transition: transform 0.3s;"></i>
                </div>
                <ul class="mob-submenu" style="display:none; list-style:none; padding: 0 0 10px 20px; margin:0;">
                    <li><a href="<?php echo esc_url( site_url('/organigramma') ); ?>" class="<?php if (is_page_template('template-organigramma.php') || is_page('organigramma')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Organigramma</a></li>
                    <li><a href="<?php echo esc_url( site_url('/storia') ); ?>" class="<?php if (is_page_template('template-storia.php') || is_page('storia') || (is_page_template('template-club-page.php') && !is_page('presente-e-futuro'))) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Storia del Club</a></li>
                    <li><a href="<?php echo esc_url( site_url('/presente-e-futuro') ); ?>" class="<?php if (is_page('presente-e-futuro') || (is_page_template('template-club-page.php') && is_page('presente-e-futuro'))) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Presente e Futuro</a></li>
                </ul>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( sport_theme_get_page_url('partner', 'NETWORK') ); ?>" class="<?php if (is_page_template('template-partner.php') || is_page('partner') || is_page('sponsor')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">NETWORK</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/contatti') ); ?>" class="<?php if (is_page_template('template-contatti.php') || is_page('contatti')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">Contatti</a>
            </li>

            <?php if ( $sport_theme_prima_menu_context ) : ?>
                <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">AC Taverne</a>
                </li>
            <?php else : ?>
                <!-- AC Taverne con sottomenu -->
                <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <div class="mob-toggle" style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; padding:18px 0;">
                        <span class="<?php if (is_page('ac-taverne') || is_page('societa') || is_page('scuola-calcio') || is_page('infrastruttura') || is_page('iscritti') || is_page_template('template-home-societa.php') || is_page_template('template-scuola-calcio.php') || is_page_template('template-infrastruttura.php') || is_page_template('template-la-societa.php') || is_page_template('template-comitato-societa.php') || is_page_template('template-club-dei-100.php') || is_page_template('template-sezioni.php') || is_page_template('template-news-societa.php') || is_page_template('template-contatti-societa.php') || is_page_template('template-iscritti.php')) echo 'mob-active'; ?>" style="color:white; font-size:20px; font-weight:700; text-transform:uppercase; letter-spacing:1px;">AC Taverne</span>
                        <i class="fa-solid fa-chevron-down" style="color:white; font-size:14px; transition: transform 0.3s;"></i>
                    </div>
                    <ul class="mob-submenu" style="display:none; list-style:none; padding: 0 0 10px 20px; margin:0;">
                        <li><a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>" class="<?php if (is_page('ac-taverne') || is_page('societa') || is_page_template('template-home-societa.php') || is_page_template('template-la-societa.php')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Società</a></li>
                        <li><a href="<?php echo esc_url( site_url('/scuola-calcio') ); ?>" class="<?php if (is_page_template('template-scuola-calcio.php') || is_page('scuola-calcio')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Scuola Calcio</a></li>
                        <li><a href="<?php echo esc_url( site_url('/infrastruttura') ); ?>" class="<?php if (is_page_template('template-infrastruttura.php') || is_page('infrastruttura')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Infrastruttura</a></li>
                        <li><a href="<?php echo esc_url( site_url('/iscritti') ); ?>" class="<?php if (is_page_template('template-iscritti.php') || is_page('iscritti')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Iscriviti</a></li>
                    </ul>
                </li>
            <?php endif; ?>

        </ul>
    </nav>

    <hr style="border-color: rgba(255,255,255,0.15); margin: 20px 0;">

    <!-- Social icons -->
    <div style="display:flex; gap:18px; justify-content:flex-start; align-items:center;">
        <a href="https://www.instagram.com/ac_taverne?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" style="color:white; font-size:22px;"><i class="fa-brands fa-instagram"></i></a>
        <a href="https://www.facebook.com/share/1BZrVQUTfb/?mibextid=wwXIfr" target="_blank" style="color:white; font-size:22px;"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="https://www.linkedin.com/company/actaverne/" target="_blank" style="color:white; font-size:22px;"><i class="fa-brands fa-linkedin-in"></i></a>
        <a href="https://whatsapp.com/channel/0029VbBqO0G7YSd4VsRANF2G" target="_blank" style="color:white; font-size:22px;"><i class="fa-brands fa-whatsapp"></i></a>
        <a href="https://www.tiktok.com/@actaverne?_r=1&_t=ZN-96cub3rtWfm" target="_blank" style="color:white; font-size:22px;"><i class="fa-brands fa-tiktok"></i></a>
    </div>
</div>
<!-- ===================== END MOBILE MENU ===================== -->

<div id="page" class="site">
	
	<header id="masthead" class="site-header">
        <div class="site-header-inner container" style="position: relative; text-align: center; padding: 20px 0 0px;">
            
            <!-- Hamburger button (solo mobile) -->
            <button id="mobile-menu-open" data-mobile-menu-open style="
                display: none;
                position: absolute;
                top: 50%;
                left: 15px;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
                padding: 5px;
                z-index: 100;
            " aria-label="Apri menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="site-branding" style="display: inline-block;">
                <a href="<?php echo esc_url($sport_theme_logo_url); ?>" class="custom-logo-link" rel="home" aria-label="AC Taverne">
                    <img src="<?php echo esc_url($sport_theme_logo_src); ?>" alt="AC Taverne Logo" class="site-logo" style="max-height: 120px;">
                </a>
            </div>

            <!-- Icone in alto a destra (Tutti i social) -->
            <div class="header-right-icons" style="position: absolute; top: 30px; right: 70px; display: flex; gap: 15px;">
                <a href="https://www.instagram.com/ac_taverne?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.facebook.com/share/1BZrVQUTfb/?mibextid=wwXIfr" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="https://www.linkedin.com/company/actaverne/" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="https://whatsapp.com/channel/0029VbBqO0G7YSd4VsRANF2G" target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="https://www.tiktok.com/@actaverne?_r=1&_t=ZN-96cub3rtWfm" target="_blank" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
            </div>
        </div>

		<?php if ( ! is_front_page() ) : ?>
			<!-- Navigazione mostrata solo nelle pagine interne -->
			<nav id="site-navigation" class="main-navigation">
				<?php
                $sport_theme_prima_menu_context = is_page(array(
                    'prima-squadra',
                    'news',
                    'news-prima-squadra',
                    'giocatori',
                    'staff',
                    'stagione',
                    'organigramma',
                    'storia',
                    'presente-e-futuro',
                    'partner',
                    'sponsor',
                    'contatti',
                )) || is_page_template(array(
                    'template-prima-squadra.php',
                    'template-news.php',
                    'template-rosa.php',
                    'template-staff.php',
                    'template-stagione.php',
                    'template-organigramma.php',
                    'template-storia.php',
                    'template-club-page.php',
                    'template-partner.php',
                    'template-contatti.php',
                )) || is_singular(array('giocatore', 'partita'))
                   || ( is_singular('post') && ! has_category('settore-giovanile') )
                   || ( is_singular('evento') && ! has_category('settore-giovanile') );

				if ( ! $sport_theme_prima_menu_context && has_nav_menu( 'menu-1' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						)
					);
				} else {
					// Fallback manuale per rispecchiare l'immagine finché il menu non è creato su WP
					?>
					<ul>
						<li class="<?php if (is_page_template('template-news.php') || is_page('news') || is_page('news-prima-squadra')) echo 'current-menu-item'; ?>"><a href="<?php echo esc_url( site_url('/news') ); ?>">News</a></li>
						<li class="menu-item-shop"><a href="https://actaverneshop.com/" target="_blank">Shop</a></li>
						<li class="<?php if (is_page_template('template-rosa.php') || is_page_template('template-staff.php') || is_page('giocatori') || is_page('staff') || is_singular('giocatore')) echo 'current-menu-item'; ?>"><a href="<?php echo esc_url( site_url('/giocatori') ); ?>">Team</a></li>
						<li class="<?php if (is_page_template('template-stagione.php') || is_page('stagione')) echo 'current-menu-item'; ?>"><a href="<?php echo esc_url( site_url('/stagione') ); ?>">Stagione</a></li>
						<li class="<?php if (is_page_template('template-organigramma.php') || is_page_template('template-storia.php') || is_page_template('template-club-page.php') || is_page('organigramma') || is_page('storia') || is_page('presente-e-futuro')) echo 'current-menu-item'; ?>">
							<a href="<?php echo esc_url( site_url('/organigramma') ); ?>">Club</a>
						</li>
						<li class="<?php if (is_page_template('template-partner.php') || is_page('partner') || is_page('sponsor')) echo 'current-menu-item'; ?>"><a href="<?php echo esc_url( sport_theme_get_page_url('partner', 'NETWORK') ); ?>">NETWORK</a></li>
						<li class="<?php if (is_page_template('template-contatti.php') || is_page('contatti')) echo 'current-menu-item'; ?>"><a href="<?php echo esc_url( site_url('/contatti') ); ?>">Contatti</a></li>
						<li class="menu-item-ac-taverne <?php if (is_page('ac-taverne') || is_page('societa') || is_page('scuola-calcio') || is_page('infrastruttura') || is_page('iscritti') || is_page_template('template-home-societa.php') || is_page_template('template-scuola-calcio.php') || is_page_template('template-infrastruttura.php') || is_page_template('template-la-societa.php') || is_page_template('template-comitato-societa.php') || is_page_template('template-club-dei-100.php') || is_page_template('template-sezioni.php') || is_page_template('template-news-societa.php') || is_page_template('template-contatti-societa.php') || is_page_template('template-iscritti.php')) echo 'current-menu-item'; ?>"><a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>">AC Taverne</a></li>
					</ul>
					<?php
				}
				?>
			</nav>
		<?php endif; ?>
	</header><!-- #masthead -->

<style>
@media (max-width: 768px) {
    #mobile-menu-open { display: block !important; }
    #site-navigation { display: none !important; }
    .header-right-icons { display: none !important; }
    .site-header-inner { padding: 15px 0 !important; }
    /* Fix custom_logo size on mobile header */
    .custom-logo-link img { max-height: 70px !important; }
    .site-logo { max-height: 70px !important; }
}
</style>

<script>
(function(){
    var closeBtn = document.getElementById('mobile-menu-close');
    var overlay  = document.getElementById('mobile-menu-overlay');

    if (!closeBtn || !overlay) return;

    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-mobile-menu-open]')) {
            e.preventDefault();
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    });

    closeBtn.addEventListener('click', function(){
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    });

    // Sottomenu toggle
    document.querySelectorAll('.mob-toggle').forEach(function(toggle){
        toggle.addEventListener('click', function(e){
            // Non bloccare il click sul link figlio
            if (e.target.tagName === 'A') return;
            var li   = toggle.parentElement;
            var sub  = li.querySelector('.mob-submenu');
            var icon = toggle.querySelector('i');
            if (!sub) return;
            var open = sub.style.display === 'block';
            sub.style.display = open ? 'none' : 'block';
            if (icon) icon.style.transform = open ? '' : 'rotate(180deg)';
        });
    });

    // Allinea i colori del menu interno alla home Prima Squadra.
    var menuLinks = document.querySelectorAll('.main-navigation a, #mobile-menu-overlay a');
    menuLinks.forEach(function(link) {
        var label = link.textContent.trim().toUpperCase();
        if (label === 'SHOP' || link.href.indexOf('actaverneshop.com') !== -1 || link.href.indexOf('/shop') !== -1) {
            link.style.setProperty('color', 'var(--c-primary)', 'important');
        } else if (label === 'AC TAVERNE') {
            link.style.setProperty('color', '#fff', 'important');
        }
    });
})();
</script>
