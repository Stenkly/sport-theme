<?php
/**
 * Template Name: Pagina Club dei 100
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-club100">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        $hero_image_url = sport_theme_get_societa_home_hero_url();
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">CLUB DEI 100</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid white; margin: 20px 0;">
                <?php sport_theme_render_societa_submenu(); ?>
                <p class="text-white" style="font-size: 24px; font-weight: 700; text-transform: uppercase; margin: 20px 0 0 0; line-height: 1.3;">
                    IL CLUB SOSTENITORE DELL’AC TAVERNE.<br>SOSTIENI LA NOSTRA SOCIETÀ PER I NOSTRI ALLIEVI.
                </p>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <div class="container" style="padding-top: 10px; padding-bottom: 60px;">
        
        <div class="text-white club100-content" style="font-size: 16px; line-height: 1.8; margin-bottom: 50px;">
            <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; color: var(--c-primary); border-bottom: 2px solid white; padding-bottom: 10px;">Cos’è il Club dei 100?</h3>
            <p style="font-size: 18px; font-weight: 600; margin-bottom: 30px;">E’ semplicemente un Club sostenitore dell’AC Taverne.</p>

            <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; color: var(--c-primary); border-bottom: 2px solid white; padding-bottom: 10px;">Come funziona?</h3>
            <p>Chi aderisce all’iniziativa, sarà invitato a una splendida e ottima cena dove sarete nostri graditi ospiti. Durante la cena verranno estratti dei numeri che vanno da 1 a 100, a questo numero sarà abbinato un socio (i numeri verranno assegnati in base all’ordine d’iscrizione) ed un valore compreso da 2.-CHF a 200.- CHF, questo valore sarà il costo da pagare quale tassa d’appartenenza al club.</p>
            <p>Se siete fortunati pagherete CHF 2.-, se lo siete un po’ meno pagherete al massimo CHF 200.-.</p>
            <p>Se qualcuno non potrà partecipare alla cena, verrà estratto il suo nome da qualcuno dei presenti e di conseguenza dovrà pagare il rispettivo in CHF.</p>
            <p style="margin-bottom: 30px;">Durante la cena verranno estratti anche 3 biglietti come lotteria AC Taverne con i seguenti premi:</p>

            <ul style="list-style: none; padding-left: 0; margin-bottom: 30px; font-size: 18px; font-weight: 700;">
                <li style="margin-bottom: 8px;">1° Buono AC Taverne del valore di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CHF 200.-</li>
                <li style="margin-bottom: 8px;">2° Buono AC Taverne del valore di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CHF 100.-</li>
                <li style="margin-bottom: 15px;">3° Buono AC Taverne del valore di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CHF 50.-</li>
            </ul>

            <p>I buoni avranno validità di 1 anno e potrete usarli solo presso la società AC Taverne e per qualsiasi cosa che riguarda l'acquisto in società, buvette, acquisto materiale per i ragazzi. (escluso tassa sociale)</p>
            <p>Verranno offerti inoltre 2 aperitivi in stagione, durante una partita della prima squadra.</p>
            <p style="margin-bottom: 30px;">Tutto questo lo si fa al sostegno della nostra società AC Taverne per i nostri allievi.</p>

            <p style="margin-bottom: 30px;">Il vostro sostegno è la nostra miglior soddisfazione e al momento della vostra iscrizione ve lo dimostreremo inviandovi la tessera socio club dei 100 che vi permetterà di partecipare ai diversi eventi.</p>

            <p>In caso foste propensi ad aderire all’iniziativa, vi chiediamo gentilmente di compilare al più presto possibile il contratto che trovate in buvette e ritornarlo per e-mail o via posta all’indirizzo indicato nel contratto. e-mail: <a href="mailto:marketing@actaverne.com" style="color: var(--c-primary); text-decoration: none; font-weight: 700;">marketing@actaverne.com</a></p>
            <p style="margin-bottom: 40px;">Potete anche consegnarlo in buvette.</p>

            <div style="text-align: right; margin-top: 10px; line-height: 1.4; font-size: 16px;">
                Sportivi Saluti<br>
                <strong>Antonio Londino</strong><br>
                <span style="color: var(--c-primary); font-weight: 700;">Responsabile Club dei 100</span>
            </div>
        </div>

    </div>

    <?php
    $club100_page_id = get_queried_object_id();
    $club100_gallery_ids = get_post_meta( $club100_page_id, '_club100_gallery_ids', true );
    $club100_gallery_ids = array_filter( array_map( 'absint', explode( ',', (string) $club100_gallery_ids ) ) );
    $club100_gallery_items = array();

    foreach ( $club100_gallery_ids as $attachment_id ) {
        $large_url = wp_get_attachment_image_url( $attachment_id, 'large' );
        $full_url  = wp_get_attachment_image_url( $attachment_id, 'full' );
        if ( $large_url ) {
            $club100_gallery_items[] = array(
                'large' => $large_url,
                'full'  => $full_url ? $full_url : $large_url,
                'alt'   => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
            );
        }
    }
    ?>
    <?php if ( ! empty( $club100_gallery_items ) ) : ?>
        <section class="container ps-section club100-gallery-section" style="padding-top: 0;">
            <h2 class="section-title text-white" style="margin-bottom:30px;">FOTOGALLERY</h2>

            <div id="club100-gallery-carousel" class="club100-gallery-carousel" style="display:flex; gap:20px; align-items:center; overflow:hidden; scroll-behavior:smooth;">
                <?php foreach ( $club100_gallery_items as $index => $gallery_item ) : ?>
                    <a data-fancybox="club100-gallery" href="<?php echo esc_url( $gallery_item['full'] ); ?>" class="gallery-slide<?php echo $index === 0 ? ' active' : ''; ?>">
                        <div class="gallery-item cover-bg" style="background-image: url('<?php echo esc_url( $gallery_item['large'] ); ?>')" role="img" aria-label="<?php echo esc_attr( $gallery_item['alt'] ? $gallery_item['alt'] : 'Foto Club dei 100 AC Taverne' ); ?>"></div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="carousel-nav gallery-nav club100-gallery-nav" style="margin-top:15px;">
                <span class="nav-arrow text-primary" id="club100-gallery-prev" style="cursor:pointer;"><i class="fa-solid fa-chevron-left"></i></span>
                <span class="nav-dots" id="club100-gallery-dots">
                    <?php foreach ( $club100_gallery_items as $index => $gallery_item ) : ?>
                        <i class="<?php echo $index === 0 ? 'fa-solid' : 'fa-regular'; ?> fa-circle<?php echo $index === 0 ? ' active' : ''; ?>" data-page="<?php echo esc_attr( $index ); ?>"></i>
                    <?php endforeach; ?>
                </span>
                <span class="gallery-counter" id="club100-gallery-counter">1 / <?php echo esc_html( count( $club100_gallery_items ) ); ?></span>
                <span class="nav-arrow text-primary" id="club100-gallery-next" style="cursor:pointer;"><i class="fa-solid fa-chevron-right"></i></span>
            </div>

            <script>
            (function(){
                var car = document.getElementById('club100-gallery-carousel');
                if (!car) return;

                var prev = document.getElementById('club100-gallery-prev');
                var next = document.getElementById('club100-gallery-next');
                var counter = document.getElementById('club100-gallery-counter');
                var dots = document.querySelectorAll('#club100-gallery-dots .fa-circle');
                var slides = car.querySelectorAll('.gallery-slide');
                var cur = 0;
                var isAnimating = false;

                function getGap() {
                    var styles = window.getComputedStyle(car);
                    return parseFloat(styles.columnGap || styles.gap || 20) || 20;
                }

                function getScrollPosition(index) {
                    var pos = 0;
                    var gap = getGap();
                    for (var i = 0; i < index; i++) {
                        pos += slides[i].offsetWidth + gap;
                    }
                    return pos;
                }

                function updateActiveState(index) {
                    cur = index;
                    dots.forEach(function(d,i){
                        if (i === cur) {
                            d.classList.remove('fa-regular');
                            d.classList.add('fa-solid', 'active');
                        } else {
                            d.classList.remove('fa-solid', 'active');
                            d.classList.add('fa-regular');
                        }
                    });
                    if (counter) {
                        counter.textContent = (cur + 1) + ' / ' + slides.length;
                    }
                    slides.forEach(function(s,i){ s.classList.toggle('active', i === cur); });
                }

                function go(n) {
                    var max = slides.length - 1;
                    cur = Math.max(0, Math.min(n, max));
                    var maxScroll = car.scrollWidth - car.clientWidth;
                    var targetScroll = Math.min(getScrollPosition(cur), maxScroll);

                    isAnimating = true;
                    car.scrollTo({ left: targetScroll, behavior: 'smooth' });
                    updateActiveState(cur);

                    setTimeout(function(){ isAnimating = false; }, 400);
                }

                if (prev) prev.addEventListener('click', function(){ go(cur - 1); });
                if (next) next.addEventListener('click', function(){ go(cur + 1); });
                dots.forEach(function(d,i){ d.addEventListener('click', function(){ go(i); }); });

                car.addEventListener('scroll', function() {
                    if (isAnimating) return;
                    var scrollLeft = car.scrollLeft;
                    var closestIndex = 0;
                    var minDiff = Infinity;
                    for (var i = 0; i < slides.length; i++) {
                        var diff = Math.abs(getScrollPosition(i) - scrollLeft);
                        if (diff < minDiff) {
                            minDiff = diff;
                            closestIndex = i;
                        }
                    }
                    if (closestIndex !== cur && closestIndex >= 0 && closestIndex < slides.length) {
                        updateActiveState(closestIndex);
                    }
                });
            })();
            </script>
        </section>
    <?php endif; ?>

    <!-- FORM SEZIONE (Larghezza allineata al testo del container superiore) -->
    <div class="container" style="padding-top: 0; padding-bottom: 40px; margin-top: <?php echo ! empty( $club100_gallery_items ) ? '0' : '-55px'; ?>;">
        <div class="club100-form-band" style="border-top: 2px solid #555; border-bottom: 2px solid #555; background-color: #000; display: flex; justify-content: center;">
            <!-- Form -->
            <div class="club100-form-left" style="width: 100%; max-width: 900px; padding: 40px 0 40px 0; display: flex; flex-direction: column; justify-content: center;">
                <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px; text-align: left;">ISCRIVITI</h2>
                
                <?php if ( isset($_GET['iscritto']) && $_GET['iscritto'] == '1' ) : ?>
                    <div style="background-color: var(--c-primary); color: #000; padding: 15px; margin-bottom: 20px; font-weight: bold;">
                        Grazie! La tua richiesta è stata inviata con successo.
                    </div>
                <?php endif; ?>
 
                <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="hs-contact-form">
                    <input type="hidden" name="action" value="club100_subscribe">
                    <?php wp_nonce_field('club100_form_nonce', 'club100_nonce'); ?>
                    
                    <div style="display: flex; gap: 30px; margin-bottom: 25px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 16px; font-weight: 600;">Nome*</label>
                            <input type="text" name="c100_nome" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 14px; font-size: 17px;" placeholder="Nome">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 16px; font-weight: 600;">Cognome*</label>
                            <input type="text" name="c100_cognome" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 14px; font-size: 17px;" placeholder="Cognome">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 16px; font-weight: 600;">Numero di telefono*</label>
                            <input type="text" name="c100_telefono" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 14px; font-size: 17px;" placeholder="Numero di telefono">
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 30px; margin-bottom: 25px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 16px; font-weight: 600;">La tua e-mail*</label>
                            <input type="email" name="c100_email" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 14px; font-size: 17px;" placeholder="E-mail">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 16px; font-weight: 600;">Indirizzo*</label>
                            <input type="text" name="c100_indirizzo" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 14px; font-size: 17px;" placeholder="Indirizzo">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 16px; font-weight: 600;">Luogo*</label>
                            <input type="text" name="c100_luogo" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 14px; font-size: 17px;" placeholder="Luogo">
                        </div>
                    </div>
 
                    <div style="margin-bottom: 40px;">
                        <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 16px; font-weight: 600;">Testo</label>
                        <input type="text" name="c100_testo" style="width: 100%; background: transparent; border: none; border-bottom: 2px solid white; color: white; padding: 14px 0; font-size: 17px;" placeholder="Testo">
                    </div>
 
                    <div style="text-align: right;">
                        <button type="submit" style="background-color: var(--c-primary); color: #000; border: none; padding: 16px 48px; font-weight: bold; text-transform: uppercase; cursor: pointer; letter-spacing: 1px; font-size: 16px; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">INVIA RICHIESTA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</main>

<style>
.club100-content h3 {
    color: var(--c-primary) !important;
    font-size: 18px !important;
    margin-bottom: 20px !important;
}
.club100-content p {
    margin-bottom: 20px;
}
</style>

<?php get_footer('societa'); ?>
