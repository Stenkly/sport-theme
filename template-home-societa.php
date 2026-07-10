<?php
/**
 * Template Name: Home Società
 * @package Sport_Theme
 */
get_header('societa');
?>

<style>
/* ===== HOME SOCIETÀ ===== */
.hs-hero-wrapper {
    position: relative;
    width: 100%;
    height: 55vh;
    overflow: hidden;
    background: #000;
}
.hs-hero-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
}
.hs-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,1) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
}
.hs-hero-content {
    position: absolute;
    bottom: 80px;
    left: 0; right: 0;
    text-align: center;
}
.hs-hero-title {
    font-size: 42px;
    font-weight: 700;
    text-transform: uppercase;
    color: #fff;
    line-height: 1.1;
    letter-spacing: 2px;
    margin: 0 0 30px 0;
}
.hs-hero-cta {
    display: inline-block;
    background: var(--c-primary);
    color: var(--c-black);
    font-size: 22px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 14px 40px;
    text-decoration: none;
    transition: background 0.3s, color 0.3s;
}
.hs-hero-cta:hover { background: #fff; color: #000; }

/* Sezioni banner */
.hs-sezioni-band {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    margin-top: 20px;
    margin-bottom: 20px;
}
.hs-sez-item {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
    display: block;
    text-decoration: none;
    border: 2px solid #fff;
    box-sizing: border-box;
}
.hs-sez-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.hs-sez-item:hover img { transform: scale(1.05); }
.hs-sez-item-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, transparent 60%);
}
.hs-sez-label {
    position: absolute;
    bottom: 25px;
    left: 0;
    right: 0;
    text-align: center;
    color: #fff;
    font-size: 30px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.hs-youth-sponsor-section {
    padding-top: 10px;
    padding-bottom: 50px;
}

.hs-youth-sponsor-marquee .sponsor-item {
    flex: 0 0 240px;
    min-height: 120px;
    padding: 18px;
    background: #fff;
    border-radius: 6px;
    box-sizing: border-box;
}

.hs-youth-sponsor-marquee .sponsor-item img {
    width: 100%;
    height: 82px;
    max-width: 200px;
    object-fit: contain;
}

.hs-youth-sponsor-marquee .sponsor-placeholder {
    background: transparent;
    color: #111;
    font-weight: 800;
}

.hs-youth-sponsor-marquee .sponsor-marquee-track {
    animation-duration: 58s;
}

/* Section header */
.hs-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255,255,255,0.12);
    padding-bottom: 15px;
    margin-bottom: 30px;
}
.hs-section-header h2 {
    font-size: 28px;
    font-weight: 800;
    color: #fff;
    text-transform: uppercase;
    margin: 0;
}
.hs-section-header .sep {
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.15);
    margin: 0 20px;
}
.hs-section-header a {
    color: var(--c-primary);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-decoration: none;
}

/* News cards */
.hs-news-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.hs-news-card {
    background: #1a1a1a;
    overflow: hidden;
}
.hs-news-card-img {
    position: relative;
    width: 100%;
    aspect-ratio: 16/10;
    overflow: hidden;
}
.hs-news-card-img img {
    width: 100%; height: 100%;
    object-fit: cover;
}
.hs-news-date {
    position: absolute;
    top: 14px; left: 14px;
    background: rgba(0,0,0,0.75);
    color: #fff;
    font-size: 32px;
    font-weight: 700;
    padding: 5px 12px;
    z-index: 2;
}
.hs-news-body { padding: 20px; }
.hs-news-body h3 {
    color: #fff;
    font-size: 32px;
    font-weight: 700;
    text-transform: uppercase;
    line-height: 1.4;
    margin: 0 0 16px 0;
}
.btn-leggi {
    display: inline-block;
    background: var(--c-primary);
    color: var(--c-black);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 9px 18px;
    text-decoration: none;
    letter-spacing: 0.5px;
}

/* Carousel nav */
.hs-carousel-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 25px;
}
.hs-carousel-nav .nav-arrow {
    color: var(--c-primary);
    font-size: 18px;
    cursor: pointer;
    padding: 5px 10px;
    transition: opacity 0.2s;
}
.hs-carousel-nav .nav-arrow:hover { opacity: 0.7; }
.hs-nav-dots { display: flex; gap: 6px; }
.hs-nav-dots i { font-size: 7px; color: rgba(255,255,255,0.3); }
.hs-nav-dots i.active { color: var(--c-primary); }

/* Instagram */
.hs-insta-bar {
    background: #111;
    padding: 18px 0;
}
.hs-insta-bar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}
.hs-insta-profile {
    display: flex;
    align-items: center;
    gap: 14px;
}
.hs-insta-avatar {
    width: 52px; height: 52px;
    border-radius: 50%;
    border: 2px solid var(--c-primary);
    object-fit: cover;
}
.hs-insta-name { color: #fff; font-size: 15px; font-weight: 700; margin: 0; }
.hs-insta-handle { color: #888; font-size: 12px; margin: 0; }
.hs-insta-stats {
    display: flex;
    gap: 25px;
    text-align: center;
}
.hs-insta-stats strong {
    display: block;
    color: #fff;
    font-size: 15px;
    font-weight: 700;
}
.hs-insta-stats span { color: #888; font-size: 11px; }
.hs-insta-follow {
    background: var(--c-primary);
    color: #000;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 9px 22px;
    text-decoration: none;
    letter-spacing: 0.5px;
}
.hs-insta-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 3px;
}
.hs-insta-grid-item {
    aspect-ratio: 1/1;
    overflow: hidden;
}
.hs-insta-grid-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}
.hs-insta-grid-item:hover img { transform: scale(1.07); }

/* Responsive */
@media (max-width: 768px) {
    .hs-hero-title { font-size: 34px; }
    .hs-hero-content { bottom: 40px; }
    .hs-sezioni-band { grid-template-columns: 1fr; }
    .hs-news-grid {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        gap: 15px;
        scrollbar-width: none;
    }
    .hs-news-grid::-webkit-scrollbar { display: none; }
    .hs-news-card {
        flex: 0 0 80%;
        scroll-snap-align: start;
    }

    .hs-insta-grid { grid-template-columns: repeat(3, 1fr); }
    .hs-insta-stats { gap: 15px; }
}
@media (max-width: 480px) {
    .hs-hero-title { font-size: 26px; }
    .hs-news-card { flex: 0 0 90%; }
    .hs-insta-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<main id="primary" class="site-main page-home-societa">

    <!-- ═══ 1. HERO ═══ -->
    <section>
        <div class="hs-hero-wrapper container">
            <?php
            $hero_img = has_post_thumbnail()
                ? get_the_post_thumbnail_url(get_the_ID(), 'full')
                : get_template_directory_uri() . '/assets/images/campo-taverne-aereo.jpg';
            ?>
            <img src="<?php echo esc_url($hero_img); ?>" alt="AC Taverne" loading="eager">
            <div class="hs-hero-overlay"></div>
            <div class="hs-hero-content container">
                <h1 class="hs-hero-title">
                    AC Taverne:<br>Passione, Valori e<br>Futuro dal 1950
                </h1>
                <a href="<?php echo esc_url(site_url('/iscritti')); ?>" class="hs-hero-cta">ISCRIVITI ORA</a>
            </div>
        </div>
    </section>

    <!-- ═══ 2. SEZIONI ═══ -->
    <section class="hs-sezioni-band container">
        <?php
        $sezioni = [
            ['label' => 'SCUOLA CALCIO', 'url' => '/scuola-calcio',          'img' => get_template_directory_uri() . '/assets/images/scuola-calcio.jpg'],
            ['label' => 'ATTIVI',        'url' => '/sezioni?cat=attivi',     'img' => get_template_directory_uri() . '/assets/images/attivi-home.jpg'],
            ['label' => 'ALLIEVI',       'url' => '/sezioni?cat=allievi',    'img' => get_template_directory_uri() . '/assets/images/allievi-home.jpg'],
            ['label' => 'FEMMINILE',     'url' => '/sezioni?cat=femminile',  'img' => get_template_directory_uri() . '/assets/images/femminile-home.jpg'],
        ];
        foreach ($sezioni as $s):
        ?>
        <a href="<?php echo esc_url(site_url($s['url'])); ?>" class="hs-sez-item">
            <img src="<?php echo esc_url($s['img']); ?>" alt="<?php echo esc_attr($s['label']); ?>" loading="lazy">
            <div class="hs-sez-item-overlay"></div>
            <span class="hs-sez-label"><?php echo esc_html($s['label']); ?></span>
        </a>
        <?php endforeach; ?>
    </section>

    <!-- ═══ 3. NEWS ═══ -->
    <section class="ps-section container">
        <div class="section-header">
            <h2 class="section-title text-white">NEWS</h2>
            <a href="<?php echo esc_url(site_url('/news-societa')); ?>" class="btn-sm btn-outline">SCOPRI</a>
        </div>

        <?php
        $news_q = new WP_Query([
            'post_type'      => 'post',
            'category_name'  => 'settore-giovanile',
            'posts_per_page' => 6,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        $news_count = 0;
        ob_start();
        if ($news_q->have_posts()):
            while ($news_q->have_posts()): $news_q->the_post();
                $news_count++;
                $thumb = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium_large') : 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=600';
        ?>
            <div class="news-slide">
                <div class="news-card cover-bg" style="background-image: url('<?php echo esc_url($thumb); ?>'); height: 350px;">
                    <div class="news-date"><?php echo get_the_date('d.m'); ?></div>
                    <div class="news-content">
                        <h3 class="news-title text-white"><?php echo wp_trim_words(get_the_title(), 7, '...'); ?></h3>
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="btn-sm btn-primary" style="display:inline-block;">LEGGI ARTICOLO</a>
                    </div>
                </div>
            </div>
        <?php
            endwhile;
            wp_reset_postdata();
        else:
        ?>
            <p class="text-white hs-empty-news">Nessuna news del settore giovanile disponibile.</p>
        <?php endif;
        $news_html = ob_get_clean();
        ?>

        <div id="hs-news-carousel" style="display:flex; gap:16px; overflow:hidden; scroll-behavior:smooth;">
            <?php echo $news_html; ?>
        </div>

        <div class="hs-carousel-nav">
            <span class="nav-arrow" id="hs-news-prev"><i class="fa-solid fa-chevron-left"></i></span>
            <div class="hs-nav-dots" id="hs-news-dots">
                <?php for ($i = 0; $i < max(1, ceil($news_count/3)); $i++): ?>
                <i class="<?php echo $i === 0 ? 'fa-solid' : 'fa-regular'; ?> fa-circle<?php echo $i === 0 ? ' active' : ''; ?>"></i>
                <?php endfor; ?>
            </div>
            <span class="nav-arrow" id="hs-news-next"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
    </section>

    <!-- ═══ 4. SPONSOR ═══ -->
    <section class="hs-youth-sponsor-section container">
        <div class="section-header">
            <h2 class="section-title text-white">SPONSOR</h2>
        </div>

        <?php
        $sponsor_q = new WP_Query([
            'post_type'      => 'sponsor',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'relation' => 'OR',
                    [
                        'key'     => '_destinazione_sponsor',
                        'value'   => 'societa',
                        'compare' => '=',
                    ],
                    [
                        'key'     => '_destinazione_sponsor',
                        'value'   => 'entrambi',
                        'compare' => '=',
                    ],
                    [
                        'key'     => '_destinazione_sponsor',
                        'compare' => 'NOT EXISTS',
                    ],
                ],
            ],
        ]);

        $sponsor_items = [];
        if ($sponsor_q->have_posts()):
            while ($sponsor_q->have_posts()): $sponsor_q->the_post();
                $site = get_post_meta(get_the_ID(), '_sito_url', true);
                $logo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : '';
                ob_start();
                ?>
                <div class="sponsor-item">
                    <?php if ($site): ?><a href="<?php echo esc_url($site); ?>" target="_blank" rel="noopener"><?php endif; ?>
                        <?php if ($logo): ?>
                            <img src="<?php echo esc_url($logo); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                        <?php else: ?>
                            <div class="sponsor-placeholder"><?php the_title(); ?></div>
                        <?php endif; ?>
                    <?php if ($site): ?></a><?php endif; ?>
                </div>
                <?php
                $sponsor_items[] = ob_get_clean();
            endwhile;
            wp_reset_postdata();
        endif;

        if (!empty($sponsor_items)):
            $marquee_items = $sponsor_items;
            while (count($marquee_items) < 8) {
                $marquee_items = array_merge($marquee_items, $sponsor_items);
            }
        ?>
            <div class="sponsor-marquee sponsor-marquee-network hs-youth-sponsor-marquee">
                <div class="sponsor-marquee-track">
                    <div class="sponsor-marquee-group">
                        <?php echo implode('', $marquee_items); ?>
                    </div>
                    <div class="sponsor-marquee-group" aria-hidden="true">
                        <?php echo implode('', $marquee_items); ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p class="sponsor-empty-message">Nessuno sponsor del settore giovanile inserito.</p>
        <?php endif; ?>
    </section>

    <!-- ═══ 5. INSTAGRAM ═══ -->
    <section class="hs-instagram-strip">
        <div class="container">
            <?php echo do_shortcode('[instagram-feed feed=2]'); ?>
        </div>
    </section>

</main>

<script>
(function(){
    function makeCarousel(gridId, prevId, nextId, dotsId, perPage) {
        var grid = document.getElementById(gridId);
        var prev = document.getElementById(prevId);
        var next = document.getElementById(nextId);
        var dots = document.getElementById(dotsId);
        if (!grid) return;

        var isMobile = window.innerWidth <= 768;
        var current = 0;

        function getCards() { return grid.children; }
        function total() { return Math.ceil(getCards().length / (isMobile ? 1 : perPage)); }

        function updateDots() {
            if (!dots) return;
            var ds = dots.querySelectorAll('i');
            ds.forEach(function(d, i){
                if (i === current) {
                    d.classList.remove('fa-regular');
                    d.classList.add('fa-solid', 'active');
                } else {
                    d.classList.remove('fa-solid', 'active');
                    d.classList.add('fa-regular');
                }
            });
        }

        function scrollTo(idx) {
            var cards = getCards();
            if (!cards.length) return;
            current = Math.max(0, Math.min(idx, total() - 1));
            if (isMobile) {
                var cardWidth = cards[0].offsetWidth + 15;
                grid.scrollTo({ left: current * cardWidth, behavior: 'smooth' });
            } else {
                var perP = perPage;
                var cardW = cards[0].offsetWidth + 20;
                grid.scrollTo({ left: current * perP * cardW, behavior: 'smooth' });
            }
            updateDots();
        }

        if (prev) prev.addEventListener('click', function(){ scrollTo(current - 1); });
        if (next) next.addEventListener('click', function(){ scrollTo(current + 1); });
        if (dots) {
            var ds = dots.querySelectorAll('i');
            ds.forEach(function(d, i){
                d.style.cursor = 'pointer';
                d.addEventListener('click', function(){ scrollTo(i); });
            });
        }
        updateDots();
    }

    makeCarousel('hs-news-carousel',   'hs-news-prev',  'hs-news-next',  'hs-news-dots',  3);
})();
</script>

<?php get_footer('societa'); ?>
