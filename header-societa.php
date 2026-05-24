<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
	<?php wp_head(); ?>
</head>

<body <?php body_class('societa-site'); ?>>
<?php wp_body_open(); ?>

<!-- ===================== MOBILE MENU OVERLAY (Società) ===================== -->
<div id="hs-mobile-overlay" style="
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
        <button id="hs-mob-close" style="background:none; border:none; color:white; font-size:26px; cursor:pointer; padding:0;">✕</button>
        <a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>">
            <?php if ( has_custom_logo() ): the_custom_logo(); else: ?>
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="AC Taverne" style="max-height:70px;">
            <?php endif; ?>
        </a>
        <div style="width:40px;"></div>
    </div>

    <hr style="border-color: rgba(255,255,255,0.15); margin-bottom: 10px;">

    <nav style="flex: 1;">
        <ul style="list-style:none; margin:0; padding:0;">

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">HOME</a>
            </li>

            <!-- Società con sottomenu -->
            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div class="hs-mob-toggle" style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; padding:18px 0;">
                    <span style="color:white; font-size:20px; font-weight:700; text-transform:uppercase; letter-spacing:1px;">SOCIETÀ</span>
                    <i class="fa-solid fa-chevron-down" style="color:white; font-size:14px; transition: transform 0.3s;"></i>
                </div>
                <ul class="hs-mob-submenu" style="display:none; list-style:none; padding: 0 0 10px 20px; margin:0;">
                    <li><a href="<?php echo esc_url( site_url('/la-societa') ); ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">La Società</a></li>
                    <li><a href="<?php echo esc_url( site_url('/comitato') ); ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Comitato</a></li>
                    <li><a href="<?php echo esc_url( site_url('/club-dei-100') ); ?>" style="display:block; color:white; font-size:16px; font-weight:700; text-transform:uppercase; padding:10px 0; text-decoration:none; letter-spacing:1px;">Club dei 100</a></li>
                </ul>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/sezioni') ); ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">SEZIONI</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/scuola-calcio') ); ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">SCUOLA CALCIO</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/infrastruttura') ); ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">INFRASTRUTTURA</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/news-societa') ); ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">NEWS</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/iscritti') ); ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">ISCRIVITI</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/contatti-societa') ); ?>" style="display:block; color:white; font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">CONTATTI</a>
            </li>

            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo esc_url( site_url('/prima-squadra') ); ?>" style="display:block; color:var(--c-primary); font-size:20px; font-weight:700; text-transform:uppercase; padding:18px 0; text-decoration:none; letter-spacing:1px;">PRIMA SQUADRA</a>
            </li>

        </ul>
    </nav>

    <hr style="border-color: rgba(255,255,255,0.15); margin: 20px 0;">

    <div style="display:flex; gap:18px; justify-content:flex-start; align-items:center;">
        <a href="https://www.instagram.com/ac_taverne/" target="_blank" style="color:white; font-size:22px;"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" style="color:white; font-size:22px;"><i class="fa-brands fa-facebook-f"></i></a>
    </div>
</div>
<!-- ===================== END MOBILE MENU ===================== -->

<div id="page" class="site">

	<header id="masthead" class="site-header">
        <div class="site-header-inner container" style="position: relative; text-align: center; padding: 20px 0 10px;">

            <!-- Hamburger (solo mobile) -->
            <button id="hs-mob-open" style="
                display: none;
                position: absolute;
                top: 50%; left: 15px;
                transform: translateY(-50%);
                background: none; border: none;
                color: white; font-size: 24px;
                cursor: pointer; padding: 5px;
                z-index: 100;
            " aria-label="Apri menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Logo centrato -->
            <div class="site-branding" style="display: inline-block;">
                <?php if ( has_custom_logo() ): the_custom_logo(); else: ?>
                <a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="AC Taverne Logo" class="site-logo" style="max-height: 120px;">
                </a>
                <?php endif; ?>
            </div>

            <!-- Icone social top-right -->
            <div class="header-right-icons" style="position: absolute; top: 30px; right: 15px; display: flex; gap: 15px;">
                <a href="https://www.instagram.com/ac_taverne/" target="_blank" style="color: var(--c-primary); font-size: 18px; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid rgba(255,255,255,0.2);"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" style="color: var(--c-primary); font-size: 18px; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid rgba(255,255,255,0.2);"><i class="fa-brands fa-facebook-f"></i></a>
            </div>
        </div>

        <!-- Navigazione desktop -->
        <nav id="hs-nav" class="main-navigation">
            <ul>
                <li><a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>" <?php if(is_front_page() || is_page('ac-taverne')) echo 'class="current-menu-item"'; ?>>HOME</a></li>
                <li class="menu-item-has-children <?php if(is_page('la-societa') || is_page('comitato') || is_page('club-dei-100')) echo 'current-menu-item'; ?>">
                    <a href="<?php echo esc_url( site_url('/la-societa') ); ?>">SOCIETÀ</a>
                    <ul class="sub-menu">
                        <li><a href="<?php echo esc_url( site_url('/la-societa') ); ?>">La Società</a></li>
                        <li><a href="<?php echo esc_url( site_url('/comitato') ); ?>">Comitato</a></li>
                        <li><a href="<?php echo esc_url( site_url('/club-dei-100') ); ?>">Club dei 100</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo esc_url( site_url('/sezioni') ); ?>" <?php if(is_page('sezioni')) echo 'class="current-menu-item"'; ?>>SEZIONI</a></li>
                <li><a href="<?php echo esc_url( site_url('/scuola-calcio') ); ?>" <?php if(is_page('scuola-calcio')) echo 'class="current-menu-item"'; ?>>SCUOLA CALCIO</a></li>
                <li><a href="<?php echo esc_url( site_url('/infrastruttura') ); ?>" <?php if(is_page('infrastruttura')) echo 'class="current-menu-item"'; ?>>INFRASTRUTTURA</a></li>
                <li><a href="<?php echo esc_url( site_url('/news-societa') ); ?>" <?php if(is_page('news-societa')) echo 'class="current-menu-item"'; ?>>NEWS</a></li>
                <li><a href="<?php echo esc_url( site_url('/iscritti') ); ?>" <?php if(is_page('iscritti')) echo 'class="current-menu-item"'; ?>>ISCRIVITI</a></li>

                <li><a href="<?php echo esc_url( site_url('/contatti-societa') ); ?>" <?php if(is_page('contatti-societa')) echo 'class="current-menu-item"'; ?>>CONTATTI</a></li>
                <li class="hs-menu-switch"><a href="<?php echo esc_url( site_url('/prima-squadra') ); ?>">PRIMA SQUADRA</a></li>
            </ul>
        </nav>
	</header><!-- #masthead -->

<style>
/* Header Società — stesso stile Prima Squadra */
@media (max-width: 768px) {
    #hs-mob-open       { display: block !important; }
    #hs-nav            { display: none !important; }
    .header-right-icons{ display: none !important; }
    .site-header-inner { padding: 15px 0 !important; }
    .custom-logo-link img, .site-logo { max-height: 70px !important; }
}
</style>

<script>
(function(){
    var openBtn  = document.getElementById('hs-mob-open');
    var closeBtn = document.getElementById('hs-mob-close');
    var overlay  = document.getElementById('hs-mobile-overlay');
    if (!openBtn || !closeBtn || !overlay) return;

    openBtn.addEventListener('click', function(){
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });
    closeBtn.addEventListener('click', function(){
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    });

    document.querySelectorAll('.hs-mob-toggle').forEach(function(toggle){
        toggle.addEventListener('click', function(e){
            if (e.target.tagName === 'A') return;
            var li   = toggle.parentElement;
            var sub  = li.querySelector('.hs-mob-submenu');
            var icon = toggle.querySelector('i');
            if (!sub) return;
            var open = sub.style.display === 'block';
            sub.style.display = open ? 'none' : 'block';
            if (icon) icon.style.transform = open ? '' : 'rotate(180deg)';
        });
    });
})();
</script>

