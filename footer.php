<?php
/**
 * The template for displaying the footer
 *
 * @package Sport_Theme
 */

?>
	<footer id="colophon" class="site-footer">

        <div class="footer-mega container">
            <div class="fm-col fm-col-1">
                <h4>AC TAVERNE</h4>
                <p>Via Traversone 2<br>
                CP 703 - 6807 Taverne<br>
                <a href="mailto:info@actaverne.com">info@actaverne.com</a></p>
                <div class="fm-social">
                    <a href="https://www.instagram.com/ac_taverne?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.facebook.com/share/1BZrVQUTfb/?mibextid=wwXIfr" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.linkedin.com/company/actaverne/" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://whatsapp.com/channel/0029VbBqO0G7YSd4VsRANF2G" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="https://www.tiktok.com/@actaverne?_r=1&_t=ZN-96cub3rtWfm" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
            <div class="fm-col">
                <h4>HOMEPAGE</h4>
                <a href="<?php echo site_url('/news'); ?>">News</a>
                <a href="<?php echo site_url('/stagione'); ?>">Stagione</a>
                <a href="<?php echo esc_url( sport_theme_get_page_url('partner', 'Partner') ); ?>">Partner</a>
            </div>
            <div class="fm-col">
                <h4>TEAM</h4>
                <a href="<?php echo site_url('/giocatori'); ?>">Giocatori</a>
                <a href="<?php echo site_url('/staff'); ?>">Staff</a>
            </div>
            <div class="fm-col">
                <h4>CLUB</h4>
                <a href="<?php echo site_url('/organigramma'); ?>">Organigramma</a>
                <a href="<?php echo site_url('/storia'); ?>">Storia</a>
                <a href="<?php echo site_url('/progetto-sportivo'); ?>">Progetto sportivo</a>
            </div>
            <div class="fm-col fm-col-links">
                <a href="<?php echo site_url('/contatti'); ?>"><b>Contatti</b></a>
                <a href="<?php echo site_url('/shop'); ?>"><b>Shop</b></a>
                <a href="<?php echo site_url('/'); ?>" style="color: var(--c-primary);"><b>AC Taverne</b></a>
            </div>
        </div>

		<!-- Sezione Bottom Copyright -->
		<div class="footer-bottom-bar">
			<p>Copyright &copy; AC TAVERNE &middot; <a href="<?php echo esc_url( site_url('/privacy-policy') ); ?>">Privacy Policy</a></p>
		</div>

	</footer><!-- #colophon -->
</div><!-- #page -->

<!-- Script per aprire lo Shop in una nuova scheda automaticamente -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    var menuLinks = document.querySelectorAll('#site-navigation a, .footer-mega a');
    menuLinks.forEach(function(link) {
        if (link.textContent.trim().toUpperCase() === 'SHOP' || link.href.indexOf('actaverneshop.com') !== -1 || link.href.indexOf('/shop') !== -1) {
            link.setAttribute('target', '_blank');
        }
    });
});
</script>

<?php wp_footer(); ?>

<!-- GLOBAL PLAYER MODAL POPUP -->
<div id="playerModal" class="player-modal-overlay">
    <div class="player-modal-content">
        <button id="closeModalBtn" class="player-modal-close">X</button>
        
        <div class="player-modal-img-col">
            <img id="modalFoto" src="" alt="">
        </div>
        
        <div class="player-modal-data-col">
            <div id="modalNumero" class="player-modal-numero"></div>
            <h2 class="player-modal-name">
                <span id="modalNome1"></span><br>
                <span id="modalNome2" style="display:block; margin-top:5px; color: #F9EA86;"></span>
            </h2>
            
            <div class="player-modal-grid">
                <div><div class="player-modal-label">DATA DI NASCITA</div><div id="modalNascita" class="player-modal-value"></div></div>
                <div><div class="player-modal-label">ALTEZZA</div><div id="modalAltezza" class="player-modal-value"></div></div>
                <div><div class="player-modal-label">NAZIONALITÀ</div><div id="modalNazionalita" class="player-modal-value"></div></div>
                <div><div class="player-modal-label">PESO</div><div id="modalPeso" class="player-modal-value"></div></div>
                <div><div class="player-modal-label">HTP</div><div id="modalHtp" class="player-modal-value"></div></div>
                <div><div class="player-modal-label">RUOLO</div><div id="modalRuolo" class="player-modal-value"></div></div>
            </div>
            
            <div>
                <a id="modalShopBtn" href="#" class="btn-primary player-modal-shop-btn" style="display:none;" target="_blank">SHOP</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('playerModal');
    if (!modal) return;
    
    var closeBtn = document.getElementById('closeModalBtn');
    var modalImage = document.getElementById('modalFoto');
    var modalImgCol = document.querySelector('.player-modal-img-col');

    function setModalEdgeStrip(img) {
        if (!modalImgCol || !img || !img.naturalWidth || !img.naturalHeight) return;

        try {
            var stripWidth = Math.min(3, img.naturalWidth);
            var canvas = document.createElement('canvas');
            canvas.width = stripWidth;
            canvas.height = img.naturalHeight;

            var ctx = canvas.getContext('2d');
            ctx.drawImage(
                img,
                img.naturalWidth - stripWidth,
                0,
                stripWidth,
                img.naturalHeight,
                0,
                0,
                stripWidth,
                img.naturalHeight
            );

            modalImgCol.style.setProperty('--modal-edge-strip', 'url("' + canvas.toDataURL('image/png') + '")');
        } catch (err) {
            modalImgCol.style.removeProperty('--modal-edge-strip');
        }
    }

    if (modalImage) {
        modalImage.addEventListener('load', function() {
            setModalEdgeStrip(modalImage);
        });
    }
    
    // Use event delegation for dynamically added links or just standard if they are present
    document.body.addEventListener('click', function(e) {
        var link = e.target.closest('.open-player-modal');
        if (link) {
            e.preventDefault();
            var modalFotoUrl = link.getAttribute('data-foto') || '';
            modalImage.src = modalFotoUrl;
            if (modalImgCol) {
                modalImgCol.style.setProperty('--modal-player-photo', modalFotoUrl ? 'url("' + modalFotoUrl + '")' : 'none');
                modalImgCol.style.removeProperty('--modal-edge-strip');
            }
            
            var num = link.getAttribute('data-numero');
            var numEl = document.getElementById('modalNumero');
            if(num) { numEl.style.display = 'block'; numEl.textContent = num; } else { numEl.style.display = 'none'; }
            
            document.getElementById('modalNome1').textContent = link.getAttribute('data-nome1') || '';
            document.getElementById('modalNome2').textContent = link.getAttribute('data-nome2') || '';
            
            function setModalField(id, val) {
                var el = document.getElementById(id);
                if (el && el.parentNode) {
                    if (!val || val === '-') {
                        el.parentNode.style.display = 'none';
                    } else {
                        el.parentNode.style.display = 'block';
                        el.textContent = val;
                    }
                }
            }
            
            setModalField('modalNascita', link.getAttribute('data-nascita'));
            setModalField('modalAltezza', link.getAttribute('data-altezza'));
            setModalField('modalNazionalita', link.getAttribute('data-nazionalita'));
            setModalField('modalPeso', link.getAttribute('data-peso'));
            setModalField('modalHtp', link.getAttribute('data-htp'));
            setModalField('modalRuolo', link.getAttribute('data-ruolo'));
            
            var shopUrl = link.getAttribute('data-shop');
            var shopBtn = document.getElementById('modalShopBtn');
            if (shopUrl && shopUrl !== '-' && shopUrl !== '') {
                shopBtn.href = shopUrl;
                shopBtn.style.display = 'inline-block';
            } else {
                shopBtn.href = 'https://actaverneshop.com/';
                shopBtn.style.display = 'inline-block';
            }
            
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; 
        }
    });
    
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
    
    modal.addEventListener('click', function(e) {
        if(e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.ps-sponsors').forEach(function(row) {
        if (row.dataset.marqueeReady === 'true') return;

        var items = Array.prototype.slice.call(row.children);
        if (!items.length) return;

        var track = document.createElement('div');
        track.className = 'ps-sponsors-track';

        items.forEach(function(item) {
            track.appendChild(item);
        });

        items.forEach(function(item) {
            var clone = item.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            track.appendChild(clone);
        });

        row.appendChild(track);
        row.classList.add('is-marquee');
        row.dataset.marqueeReady = 'true';
    });

});
</script>

<!-- Fancybox JS per Lightbox -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

</body>
</html>
