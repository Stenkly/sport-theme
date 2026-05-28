<?php
/**
 * Footer per la sezione Società (secondo sito autonomo)
 * Pixel-perfect dal mockup.
 *
 * @package Sport_Theme
 */
?>
	<footer id="colophon" class="hs-footer">

		<!-- Fascia principale con info -->
		<div class="hs-footer-main">
			<div class="hs-container hs-footer-inner">
				<div class="hs-footer-logo">
					<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
						<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo.png"
						     alt="AC Taverne" style="max-height:80px;"
						     onerror="this.src='https://via.placeholder.com/60x80.png?text=AC';">
					<?php endif; ?>
				</div>

				<div class="hs-footer-info">
					<p><strong>AC TAVERNE</strong></p>
					<p>Via Taverne 2, CP 703 - 6807 Taverne</p>
					<p><a href="mailto:info@actaverne.com">info@actaverne.com</a></p>
					<p><a href="mailto:scoutingactaverne@gmail.com">scoutingactaverne@gmail.com</a></p>
				</div>

				<div class="hs-footer-social">
					<a href="https://www.instagram.com/ac_taverne?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
					<a href="https://www.facebook.com/share/1BZrVQUTfb/?mibextid=wwXIfr" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
					<a href="https://www.linkedin.com/company/actaverne/" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
					<a href="https://whatsapp.com/channel/0029VbBqO0G7YSd4VsRANF2G" target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
					<a href="https://www.tiktok.com/@actaverne?_r=1&_t=ZN-96cub3rtWfm" target="_blank" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
				</div>
			</div>
		</div>

		<!-- Fascia gialla copyright -->
		<div class="hs-footer-bar">
			<p>Copyright &copy; AC TAVERNE &middot; <a href="<?php echo esc_url( site_url('/privacy-policy') ); ?>">Privacy Policy</a></p>
		</div>

	</footer><!-- #colophon -->
</div><!-- #page -->

<script>
// Hamburger menu toggle
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('hsHamburger');
    var menu = document.getElementById('hsMenu');
    if (btn && menu) {
        btn.addEventListener('click', function() {
            btn.classList.toggle('is-active');
            menu.classList.toggle('is-open');
        });
    }

    // Dropdown toggle on mobile
    var dropdownLinks = document.querySelectorAll('.hs-menu .menu-item-has-children > a');
    dropdownLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Se siamo su schermi piccoli (mobile)
            if (window.innerWidth <= 600) {
                e.preventDefault();
                var parent = this.parentElement;
                parent.classList.toggle('is-open');
                
                var submenu = parent.querySelector('.sub-menu');
                if (submenu) {
                    if (submenu.style.display === 'block') {
                        submenu.style.display = 'none';
                    } else {
                        submenu.style.display = 'block';
                    }
                }
            }
        });
    });
});
</script>

<?php wp_footer(); ?>

</body>
</html>
