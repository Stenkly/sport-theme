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

<div id="page" class="site">

	<header id="masthead" class="site-header hs-header">
		<div class="hs-header-top hs-container">
			<!-- Spazio vuoto sinistra per bilanciamento -->
			<div class="hs-header-left"></div>

			<!-- Logo centrato -->
			<div class="hs-header-logo">
				<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
					<a href="<?php echo esc_url( site_url('/home-societa') ); ?>">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="AC Taverne 1950">
					</a>
				<?php endif; ?>
			</div>
				
				<!-- Sezione Centro (Testo AC TAVERNE SETTORE GIOVANILE) -->
				<div class="header-center-text" style="flex: 1; text-align: center; color: white;">
					<!-- Da design mockup, il testo centrale della società -->
				</div>

				<!-- Icone in alto a destra (Instagram e Facebook) -->
				<div class="header-right-icons">
					<a href="https://www.instagram.com/ac_taverne/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
					<a href="#"><i class="fa-brands fa-facebook-f"></i></a>
				</div>
			</div>

			<!-- Navigazione Inferiore -->
			<nav id="site-navigation" class="main-navigation" style="background-color: #f7ca18;">
				<ul style="display: flex; justify-content: center; margin: 0; padding: 0; list-style: none;">
					<li><a href="<?php echo esc_url( site_url('/ac-taverne') ); ?>" class="<?php echo is_page('ac-taverne') ? 'hs-active' : ''; ?>">HOME</a></li>
				<li class="menu-item-has-children">
					<a href="#" class="<?php echo is_page(array('societa', 'la-societa', 'comitato', 'club-dei-100', 'area-allenatori')) ? 'hs-active' : ''; ?>">SOCIETÀ</a>
					<ul class="sub-menu">
						<li><a href="<?php echo esc_url( site_url('/la-societa') ); ?>">La Società</a></li>
						<li><a href="<?php echo esc_url( site_url('/comitato') ); ?>">Comitato</a></li>
						<li><a href="<?php echo esc_url( site_url('/club-dei-100') ); ?>">Club dei 100</a></li>
						<li><a href="<?php echo esc_url( site_url('/area-allenatori') ); ?>">Area Allenatori</a></li>
					</ul>
				</li>
				<li><a href="<?php echo esc_url( site_url('/sezioni') ); ?>" class="<?php echo is_page('sezioni') ? 'hs-active' : ''; ?>">SEZIONI</a></li>
				<li><a href="<?php echo esc_url( site_url('/scuola-calcio') ); ?>" class="<?php echo is_page('scuola-calcio') ? 'hs-active' : ''; ?>">SCUOLA CALCIO</a></li>
				<li><a href="<?php echo esc_url( site_url('/infrastruttura') ); ?>" class="<?php echo is_page('infrastruttura') ? 'hs-active' : ''; ?>">INFRASTRUTTURA</a></li>
				<li><a href="<?php echo esc_url( site_url('/news-societa') ); ?>" class="<?php echo is_page('news-societa') ? 'hs-active' : ''; ?>">NEWS</a></li>
				<li><a href="<?php echo esc_url( site_url('/iscritti') ); ?>" class="<?php echo is_page('iscritti') ? 'hs-active' : ''; ?>">ISCRITTI</a></li>
				<li><a href="<?php echo esc_url( site_url('/contatti-societa') ); ?>" class="<?php echo is_page('contatti-societa') ? 'hs-active' : ''; ?>">CONTATTI</a></li>
				<li class="hs-menu-switch"><a href="<?php echo esc_url( site_url('/prima-squadra') ); ?>">PRIMA SQUADRA</a></li>
			</ul>
		</nav>
	</header><!-- #masthead -->
