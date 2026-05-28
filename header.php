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
        <a href="<?php echo esc_url( home_url('/') ); ?>">
            <?php if ( has_custom_logo() ): the_custom_logo(); else: ?>
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="AC Taverne" style="max-height:70px;">
            <?php endif; ?>
        </a>
        <div style="width:40px;"></div><!-- Spacer for centering -->
    </div>

    <hr style="border-color: rgba(255,255,255,0.15); margin-bottom: 10px;">

    <!-- Nav items -->
    <nav style="flex: 1;">
    <nav style="flex: 1;">
        <ul style="list-style:none; margin:0; padding:0;">

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/news') ); ?>" class="<?php if (is_page_template('template-news.php') || is_page('news') || is_page('news-prima-squadra')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">News</a>
            </li>

            <!-- Team con sottomenu -->
            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div class="mob-toggle" style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; padding:18px 0;">
                    <span class="<?php if (is_page_template('template-rosa.php') || is_page_template('template-staff.php') || is_page('rosa') || is_page('staff') || is_singular('giocatore')) echo 'mob-active'; ?>" style="color:white; font-size:20px; font-weight:700; text-transform:uppercase; letter-spacing:1px;">Team</span>
                    <i class="fa-solid fa-chevron-down" style="color:white; font-size:14px; transition: transform 0.3s;"></i>
                </div>
                <ul class="mob-submenu" style="display:none; list-style:none; padding: 0 0 10px 20px; margin:0;">
                    <li><a href="<?php echo esc_url( site_url('/rosa') ); ?>" class="<?php if (is_page_template('template-rosa.php') || is_page('rosa')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Rosa</a></li>
                    <li><a href="<?php echo esc_url( site_url('/staff') ); ?>" class="<?php if (is_page_template('template-staff.php') || is_page('staff')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Staff</a></li>
                </ul>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/stagione') ); ?>" class="<?php if (is_page_template('template-stagione.php') || is_page('stagione')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">Stagione</a>
            </li>

            <!-- Club con sottomenu -->
            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div class="mob-toggle" style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; padding:18px 0;">
                    <span class="<?php if (is_page_template('template-organigramma.php') || is_page_template('template-storia.php') || is_page_template('template-club-page.php') || is_page('organigramma') || is_page('storia') || is_page('progetto-sportivo')) echo 'mob-active'; ?>" style="color:white; font-size:20px; font-weight:700; text-transform:uppercase; letter-spacing:1px;">Club</span>
                    <i class="fa-solid fa-chevron-down" style="color:white; font-size:14px; transition: transform 0.3s;"></i>
                </div>
                <ul class="mob-submenu" style="display:none; list-style:none; padding: 0 0 10px 20px; margin:0;">
                    <li><a href="<?php echo esc_url( site_url('/organigramma') ); ?>" class="<?php if (is_page_template('template-organigramma.php') || is_page('organigramma')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Organigramma</a></li>
                    <li><a href="<?php echo esc_url( site_url('/storia') ); ?>" class="<?php if (is_page_template('template-storia.php') || is_page('storia') || (is_page_template('template-club-page.php') && !is_page('progetto-sportivo'))) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Storia del Club</a></li>
                    <li><a href="<?php echo esc_url( site_url('/progetto-sportivo') ); ?>" class="<?php if (is_page('progetto-sportivo') || (is_page_template('template-club-page.php') && is_page('progetto-sportivo'))) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Progetto Sportivo</a></li>
                </ul>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/partner') ); ?>" class="<?php if (is_page_template('template-partner.php') || is_page('partner') || is_page('sponsor')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">Partner</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/contatti') ); ?>" class="<?php if (is_page_template('template-contatti.php') || is_page('contatti')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">Contatti</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="https://actaverneshop.com/" target="_blank" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">Shop</a>
            </li>

            <!-- AC Taverne con sottomenu -->
            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div class="mob-toggle" style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; padding:18px 0;">
                    <span class="<?php if (is_page('ac-taverne') || is_page('societa') || is_page('scuola-calcio') || is_page('infrastruttura') || is_page_template('template-home-societa.php') || is_page_template('template-scuola-calcio.php') || is_page_template('template-infrastruttura.php') || is_page_template('template-la-societa.php') || is_page_template('template-comitato-societa.php') || is_page_template('template-club-dei-100.php') || is_page_template('template-sezioni.php') || is_page_template('template-news-societa.php') || is_page_template('template-contatti-societa.php') || is_page_template('template-iscritti.php')) echo 'mob-active'; ?>" style="color:var(--c-primary); font-size:20px; font-weight:700; text-transform:uppercase; letter-spacing:1px;">AC Taverne</span>
                    <i class="fa-solid fa-chevron-down" style="color:white; font-size:14px; transition: transform 0.3s;"></i>
                </div>
                <ul class="mob-submenu" style="display:none; list-style:none; padding: 0 0 10px 20px; margin:0;">
                    <li><a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>" class="<?php if (is_page('ac-taverne') || is_page('societa') || is_page_template('template-home-societa.php') || is_page_template('template-la-societa.php')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Società</a></li>
                    <li><a href="<?php echo esc_url( site_url('/scuola-calcio') ); ?>" class="<?php if (is_page_template('template-scuola-calcio.php') || is_page('scuola-calcio')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Scuola Calcio</a></li>
                    <li><a href="<?php echo esc_url( site_url('/infrastruttura') ); ?>" class="<?php if (is_page_template('template-infrastruttura.php') || is_page('infrastruttura')) echo 'mob-active'; ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Infrastruttura</a></li>
                </ul>
            </li>

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
            <button id="mobile-menu-open" style="
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
                <?php
                if ( has_custom_logo() ) :
                    the_custom_logo();
                else :
                    ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="AC Taverne Logo" class="site-logo" style="max-height: 120px;">

                    </a>
                <?php endif; ?>
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
				if ( has_nav_menu( 'menu-1' ) ) {
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
						<li class="menu-item-has-children <?php if (is_page_template('template-rosa.php') || is_page_template('template-staff.php') || is_page('rosa') || is_page('staff') || is_singular('giocatore')) echo 'current-menu-item'; ?>">
							<a href="#">Team</a>
							<ul class="sub-menu">
								<li><a href="<?php echo esc_url( site_url('/rosa') ); ?>">Rosa</a></li>
								<li><a href="<?php echo esc_url( site_url('/staff') ); ?>">Staff</a></li>
							</ul>
						</li>
						<li class="<?php if (is_page_template('template-stagione.php') || is_page('stagione')) echo 'current-menu-item'; ?>"><a href="<?php echo esc_url( site_url('/stagione') ); ?>">Stagione</a></li>
						<li class="menu-item-has-children <?php if (is_page_template('template-organigramma.php') || is_page_template('template-storia.php') || is_page_template('template-club-page.php') || is_page('organigramma') || is_page('storia') || is_page('progetto-sportivo')) echo 'current-menu-item'; ?>">
							<a href="#">Club</a>
							<ul class="sub-menu">
								<li><a href="<?php echo esc_url( site_url('/organigramma') ); ?>">Organigramma</a></li>
								<li><a href="<?php echo esc_url( site_url('/storia') ); ?>">Storia del Club</a></li>
								<li><a href="<?php echo esc_url( site_url('/progetto-sportivo') ); ?>">Progetto</a></li>
							</ul>
						</li>
						<li class="<?php if (is_page_template('template-partner.php') || is_page('partner') || is_page('sponsor')) echo 'current-menu-item'; ?>"><a href="<?php echo esc_url( site_url('/partner') ); ?>">Partner</a></li>
						<li class="<?php if (is_page_template('template-contatti.php') || is_page('contatti')) echo 'current-menu-item'; ?>"><a href="<?php echo esc_url( site_url('/contatti') ); ?>">Contatti</a></li>
						<li><a href="https://actaverneshop.com/" target="_blank">Shop</a></li>
						<li class="menu-item-ac-taverne menu-item-has-children <?php if (is_page('ac-taverne') || is_page('societa') || is_page('scuola-calcio') || is_page('infrastruttura') || is_page_template('template-home-societa.php') || is_page_template('template-scuola-calcio.php') || is_page_template('template-infrastruttura.php') || is_page_template('template-la-societa.php') || is_page_template('template-comitato-societa.php') || is_page_template('template-club-dei-100.php') || is_page_template('template-sezioni.php') || is_page_template('template-news-societa.php') || is_page_template('template-contatti-societa.php') || is_page_template('template-iscritti.php')) echo 'current-menu-item'; ?>">
							<a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>">AC Taverne</a>
							<ul class="sub-menu">
								<li><a href="<?php echo esc_url( site_url('/societa') ); ?>">Società</a></li>
								<li><a href="<?php echo esc_url( site_url('/scuola-calcio') ); ?>">Scuola Calcio</a></li>
								<li><a href="<?php echo esc_url( site_url('/infrastruttura') ); ?>">Infrastruttura</a></li>
							</ul>
						</li>
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
    var openBtn  = document.getElementById('mobile-menu-open');
    var closeBtn = document.getElementById('mobile-menu-close');
    var overlay  = document.getElementById('mobile-menu-overlay');

    if (!openBtn || !closeBtn || !overlay) return;

    openBtn.addEventListener('click', function(){
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
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

    // Assicura che la voce di menu "AC TAVERNE" sia sempre colorata in giallo
    var menuLinks = document.querySelectorAll('.main-navigation a, #mobile-menu-overlay a');
    menuLinks.forEach(function(link) {
        if (link.textContent.trim().toUpperCase() === 'AC TAVERNE') {
            link.style.setProperty('color', 'var(--c-primary)', 'important');
        }
    });
})();
</script>
