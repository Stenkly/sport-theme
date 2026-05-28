<?php
/**
 * Template Name: Home Prima Squadra
 *
 * @package Sport_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-prima-squadra">

    <!-- HERO IMMAGINE -->
    <section class="news-hero" style="overflow: hidden; background: #000; position: relative;">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="container" style="overflow: hidden; position: relative; line-height: 0;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" 
                 style="width: 100%; height: auto; display: block; animation: heroFadeIn 1.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;" 
                 alt="<?php echo esc_attr(get_the_title()); ?>">
            <!-- Sfumatura nera in basso per fondersi con il resto della pagina -->
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 30%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
        </div>
        <style>
        @keyframes heroFadeIn {
            from { opacity: 0; transform: scale(1.02); }
            to { opacity: 1; transform: scale(1); }
        }
        </style>
    </section>

    <!-- FOTOGALLERY -->
    <section class="ps-section container">
        <h2 class="section-title text-white" style="margin-bottom:30px;">FOTOGALLERY</h2>

        <!-- Gallery carousel -->
        <div id="gallery-carousel" style="display:flex; gap:12px; align-items:center; overflow:hidden; scroll-behavior:smooth;">
            <?php
            $gallery_query = new WP_Query(array(
                'post_type'      => 'fotogallery',
                'posts_per_page' => -1,
                'tax_query'      => array(array(
                    'taxonomy' => 'categoria_galleria',
                    'field'    => 'slug',
                    'terms'    => 'storia',
                    'operator' => 'NOT IN',
                )),
            ));

            $foto_count = 0;
            if ( $gallery_query->have_posts() ) {
                while ( $gallery_query->have_posts() ) {
                    $gallery_query->the_post();
                    $img_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=800';
                    $active_class = ($foto_count === 0) ? ' active' : '';
                    echo '<a data-fancybox="gallery" href="' . esc_url($img_url) . '" class="gallery-slide' . $active_class . '"><div class="gallery-item cover-bg" style="background-image: url(\'' . esc_url($img_url) . '\')"></div></a>';
                    $foto_count++;
                }
                wp_reset_postdata();
            } else {
                $foto_count = 4;
                for($i=0; $i<4; $i++) {
                    $demo_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=800';
                    $active_class = ($i === 0) ? ' active' : '';
                    echo '<a data-fancybox="gallery" href="' . esc_url($demo_url) . '" class="gallery-slide' . $active_class . '"><div class="gallery-item cover-bg" style="background-image: url(\'' . esc_url($demo_url) . '\')"></div></a>';
                }
            }
            ?>
        </div>

        <!-- Navigation arrows + dots -->
        <div class="carousel-nav gallery-nav" style="margin-top:15px;">
            <span class="nav-arrow text-primary" id="gallery-prev" style="cursor:pointer;"><i class="fa-solid fa-chevron-left"></i></span>
            <span class="nav-dots" id="gallery-dots">
                <?php for($i=0; $i<$foto_count; $i++): ?>
                <i class="<?php echo $i===0 ? 'fa-solid' : 'fa-regular'; ?> fa-circle<?php echo $i===0 ? ' active' : ''; ?>" data-page="<?php echo $i; ?>"></i>
                <?php endfor; ?>
            </span>
            <span class="nav-arrow text-primary" id="gallery-next" style="cursor:pointer;"><i class="fa-solid fa-chevron-right"></i></span>
        </div>

        <script>
        (function(){
            var car   = document.getElementById('gallery-carousel');
            var prev  = document.getElementById('gallery-prev');
            var next  = document.getElementById('gallery-next');
            var dots  = document.querySelectorAll('#gallery-dots .fa-circle');
            var slides = car.querySelectorAll('.gallery-slide');
            var cur   = 0;
            var isAnimating = false;

            function getScrollPosition(index) {
                var pos = 0;
                for (var i = 0; i < index; i++) {
                    pos += slides[i].offsetWidth + 12; // width + gap
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
                slides.forEach(function(s,i){ s.classList.toggle('active', i===cur); });
            }

            function go(n) {
                var max = slides.length - 1;
                cur = Math.max(0, Math.min(n, max));
                
                var maxScroll = car.scrollWidth - car.clientWidth;
                var targetScroll = Math.min(getScrollPosition(cur), maxScroll);
                
                isAnimating = true;
                car.scrollTo({
                    left: targetScroll,
                    behavior: 'smooth'
                });
                
                updateActiveState(cur);
                
                setTimeout(function() {
                    isAnimating = false;
                }, 400);
            }

            prev.addEventListener('click', function(){ go(cur - 1); });
            next.addEventListener('click', function(){ go(cur + 1); });
            dots.forEach(function(d,i){ d.addEventListener('click', function(){ go(i); }); });

            car.addEventListener('scroll', function() {
                if (isAnimating) return;
                var scrollLeft = car.scrollLeft;
                var closestIndex = 0;
                var minDiff = Infinity;
                for (var i = 0; i < slides.length; i++) {
                    var slidePos = getScrollPosition(i);
                    var diff = Math.abs(slidePos - scrollLeft);
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

    <!-- PROSSIMI INCONTRI -->
    <section class="ps-section container">
        <div class="section-header">
            <h2 class="section-title text-primary">PROSSIMI INCONTRI</h2>
            <?php $stagione_page = get_page_by_title('Stagione'); ?>
            <a href="<?php echo $stagione_page ? esc_url(get_permalink($stagione_page->ID)) : '#'; ?>" class="btn-sm btn-primary" style="display:inline-block; font-size: 22px; font-weight: 700;">CALENDARIO</a>
        </div>
        <div class="ps-grid grid-2">
            <?php
            if (!function_exists('get_it_day_of_week')) {
                function get_it_day_of_week($date_str) {
                    $date_str = str_replace(array('-', '/'), '.', $date_str);
                    $parts = explode('.', $date_str);
                    if (count($parts) === 3) {
                        if (strlen($parts[0]) === 4) {
                            $year = intval($parts[0]);
                            $month = intval($parts[1]);
                            $day = intval($parts[2]);
                        } else {
                            $day = intval($parts[0]);
                            $month = intval($parts[1]);
                            $year = intval($parts[2]);
                        }
                        $timestamp = mktime(0, 0, 0, $month, $day, $year);
                        if ($timestamp !== false) {
                            $w = date('w', $timestamp);
                            $days = array('DOM', 'LUN', 'MAR', 'MER', 'GIO', 'VEN', 'SAB');
                            return $days[$w];
                        }
                    }
                    return '';
                }
            }

            // Ottieni dinamicamente le prossime 2 partite future (senza risultato)
            $all_hp_matches = new WP_Query(array('post_type' => 'partita', 'posts_per_page' => -1, 'order' => 'ASC'));
            $hp_calendario = [];
            while($all_hp_matches->have_posts()) {
                $all_hp_matches->the_post();
                if (get_post_meta(get_the_ID(), '_risultato', true) == '') {
                    $hp_calendario[] = get_post();
                }
            }
            wp_reset_postdata();
            
            $hp_calendario = array_slice($hp_calendario, 0, 2);
            $taverne_logo = has_custom_logo() ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : 'https://via.placeholder.com/40';
            
            if(count($hp_calendario) === 0) {
                echo '<p class="text-white">Nessuna partita futura in programma.</p>';
            }
            
            foreach($hp_calendario as $post) : setup_postdata($post);
                $data_p = get_post_meta($post->ID, '_data_partita', true);
                $ora_p = get_post_meta($post->ID, '_ora_partita', true);
                $stadio = sport_theme_get_match_stadium($post->ID);
                $avversario = get_post_meta($post->ID, '_avversario', true) ? get_post_meta($post->ID, '_avversario', true) : 'Sfidante';
                $logo_avversario = sport_theme_get_opponent_logo($post->ID);
                $in_casa = get_post_meta($post->ID, '_in_casa', true);

                $t1_name = $in_casa == '1' ? 'AC Taverne' : $avversario;
                $t1_logo = $in_casa == '1' ? $taverne_logo : $logo_avversario;
                $t2_name = $in_casa == '1' ? $avversario : 'AC Taverne';
                $t2_logo = $in_casa == '1' ? $logo_avversario : $taverne_logo;

                $giorno = get_it_day_of_week($data_p);
                $formatted_date = ($giorno ? $giorno . ', ' : '') . $data_p;
            ?>
            <div class="match-card">
                <div class="match-info">
                    <p class="match-date text-white"><span class="match-date-day"><?php echo esc_html($formatted_date); ?></span><span class="match-date-time"><?php echo esc_html($ora_p); ?></span></p>
                    <p class="match-venue"><?php echo esc_html($stadio); ?></p>
                </div>
                <div class="match-teams">
                    <span class="team-name team-1-name"><?php echo esc_html($t1_name); ?></span>
                    <div class="team-logo-container team-1-logo-container">
                        <img class="team-logo" src="<?php echo esc_url($t1_logo); ?>" alt="<?php echo esc_attr($t1_name); ?>">
                    </div>
                    <span class="vs">VS</span>
                    <div class="team-logo-container team-2-logo-container">
                        <img class="team-logo" src="<?php echo esc_url($t2_logo); ?>" alt="<?php echo esc_attr($t2_name); ?>">
                    </div>
                    <span class="team-name team-2-name"><?php echo esc_html($t2_name); ?></span>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    </section>

    <!-- NEWS -->
    <section class="ps-section container">
        <div class="section-header">
            <h2 class="section-title text-white">NEWS</h2>
            <?php $news_page = get_page_by_title('News'); ?>
            <a href="<?php echo $news_page ? esc_url(get_permalink($news_page->ID)) : '#'; ?>" class="btn-sm btn-outline">SCOPRI</a>
        </div>

        <div id="news-carousel" style="display:flex; gap:16px; overflow:hidden; scroll-behavior:smooth;">
            <?php
            $args_news = array(
                'post_type'      => 'post',
                'posts_per_page' => 9,
            );
            $latest_news  = new WP_Query( $args_news );
            $news_count   = 0;

            if ( $latest_news->have_posts() ) :
                while ( $latest_news->have_posts() ) : $latest_news->the_post();
                    $news_count++;
                    $thumb_url = has_post_thumbnail()
                        ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' )
                        : 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=600';
            ?>
            <div class="news-slide">
                <div class="news-card cover-bg" style="background-image: url('<?php echo esc_url( $thumb_url ); ?>'); height: 350px;">
                    <div class="news-date"><?php echo get_the_date('d.m'); ?></div>
                    <div class="news-content">
                        <h3 class="news-title text-white"><?php echo wp_trim_words( get_the_title(), 7, '...' ); ?></h3>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn-sm btn-primary" style="display:inline-block;">LEGGI ARTICOLO</a>
                    </div>
                </div>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="text-white">Nessun articolo recente.</p>';
            endif;
            ?>
        </div>

        <div class="carousel-nav" style="margin-top:15px;">
            <span class="nav-arrow text-primary" id="news-prev" style="cursor:pointer;"><i class="fa-solid fa-chevron-left"></i></span>
            <span class="nav-dots" id="news-dots">
                <?php for($i=0; $i<$news_count; $i++): ?>
                <i class="fa-solid fa-circle<?php echo $i===0 ? ' active' : ''; ?>"></i>
                <?php endfor; ?>
            </span>
            <span class="nav-arrow text-primary" id="news-next" style="cursor:pointer;"><i class="fa-solid fa-chevron-right"></i></span>
        </div>

        <script>
        (function(){
            var car  = document.getElementById('news-carousel');
            var prev = document.getElementById('news-prev');
            var next = document.getElementById('news-next');
            var dots = document.querySelectorAll('#news-dots .fa-circle');
            var cur  = 0;

            function slideWidth() {
                var s = car.querySelector('.news-slide');
                return s ? s.offsetWidth + 16 : 0;
            }
            function go(n) {
                var max = car.querySelectorAll('.news-slide').length - 1;
                cur = Math.max(0, Math.min(n, max));
                car.scrollLeft = cur * slideWidth();
                dots.forEach(function(d, i){ d.classList.toggle('active', i === cur); });
            }
            prev.addEventListener('click', function(){ go(cur - 1); });
            next.addEventListener('click', function(){ go(cur + 1); });
            dots.forEach(function(d, i){ d.addEventListener('click', function(){ go(i); }); });
        })();
        </script>
    </section>


    <!-- TEAM -->
    <section class="ps-section container team-section">
        <div class="section-header">
            <h2 class="section-title text-white">TEAM</h2>
            <?php $rosa_page = get_page_by_title('Rosa'); ?>
            <a href="<?php echo $rosa_page ? esc_url(get_permalink($rosa_page->ID)) : '#'; ?>" class="btn-sm btn-outline">SCOPRI</a>
        </div>

        <!-- Carousel wrapper -->
        <div style="position: relative;">
            <div id="team-carousel" style="display: flex; gap: 20px; overflow: hidden; scroll-behavior: smooth;">
                <?php
                $hp_team_query = new WP_Query(array(
                    'post_type'      => 'giocatore',
                    'posts_per_page' => -1, // Tutti i giocatori
                    'orderby'        => 'meta_value_num',
                    'meta_key'       => '_numero_maglia',
                    'order'          => 'ASC',
                ));

                $total_players = 0;
                if($hp_team_query->have_posts()):
                    while($hp_team_query->have_posts()): $hp_team_query->the_post();
                        $total_players++;
                        $numero    = get_post_meta(get_the_ID(), '_numero_maglia', true);
                        $ruolo_spec= get_post_meta(get_the_ID(), '_ruolo_specifico', true);
                        $foto_ritratto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://via.placeholder.com/300x400/222222/FFFFFF?text=' . get_the_title();
                        $foto_esultanza = get_post_meta(get_the_ID(), '_foto_esultanza', true);
                        $foto_dettaglio = !empty($foto_esultanza) ? $foto_esultanza : $foto_ritratto;
                        $data      = get_post_meta(get_the_ID(), '_data_nascita', true) ?: '-';
                        $altezza   = get_post_meta(get_the_ID(), '_altezza', true) ?: '-';
                        $peso      = get_post_meta(get_the_ID(), '_peso', true) ?: '-';
                        $nazionalita = get_post_meta(get_the_ID(), '_nazionalita', true) ?: '-';
                        $htp       = get_post_meta(get_the_ID(), '_htp', true) ?: '-';
                        $shop_url  = get_post_meta(get_the_ID(), '_shop_url', true);
                        $ruoli     = get_the_terms(get_the_ID(), 'ruolo_giocatore');
                        $ruolo_str = $ruoli && !is_wp_error($ruoli) ? sport_theme_get_singular_role($ruoli[0]->name) : '-';
                        $split_name = sport_theme_get_giocatore_split_name(get_the_ID());
                        $nome_riga1 = $split_name['nome'];
                        $nome_riga2 = $split_name['cognome'];
                        $zoom_foto   = get_post_meta(get_the_ID(), '_zoom_foto', true) ?: 'cover';
                        $allineamento_foto = get_post_meta(get_the_ID(), '_allineamento_foto', true) ?: 'center top';
                ?>
                <a href="#" class="open-player-modal team-slide"
                   data-foto="<?php echo esc_attr($foto_dettaglio); ?>"
                   data-numero="<?php echo esc_attr($numero); ?>"
                   data-nome1="<?php echo esc_attr($nome_riga1); ?>"
                   data-nome2="<?php echo esc_attr($nome_riga2); ?>"
                   data-nascita="<?php echo esc_attr($data); ?>"
                   data-altezza="<?php echo esc_attr($altezza); ?>"
                   data-peso="<?php echo esc_attr($peso); ?>"
                   data-nazionalita="<?php echo esc_attr($nazionalita); ?>"
                   data-htp="<?php echo esc_attr($htp); ?>"
                   data-shop="<?php echo esc_attr($shop_url); ?>"
                   data-ruolo="<?php echo esc_attr($ruolo_str); ?>"
                   style="text-decoration: none; flex: 0 0 calc(25% - 15px); min-width: 0; display: block; overflow: hidden; transition: transform 0.3s;"
                   onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="player-card" style="position: relative; aspect-ratio: 3/4; overflow: hidden; background-color: #111;">
                        <div class="player-photo cover-bg" style="background-image: url('<?php echo esc_url($foto_ritratto); ?>'); background-size: <?php echo esc_attr($zoom_foto); ?>; background-position: <?php echo esc_attr($allineamento_foto); ?>; width: 100%; height: 100%; border: none; transition: transform 0.5s ease;"></div>
                        <div class="player-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 90%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, rgba(0,0,0,0.8) 25%, transparent 100%); pointer-events: none;"></div>
                        <div class="player-info" style="position: absolute; bottom: 2px; left: 12px; z-index: 2; flex-direction: column; gap: 0; padding: 0;">
                            <?php if(!empty($numero)): ?>
                            <span class="player-number" style="display: block; font-size: 50px; font-weight: 700; margin-bottom: 20px; line-height: 1; color: #F2E302;"><?php echo esc_html($numero); ?></span>
                            <?php elseif(!empty($ruolo_spec)): ?>
                            <span class="staff-role text-primary" style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 20px; letter-spacing: 1px;"><?php echo esc_html($ruolo_spec); ?></span>
                            <?php endif; ?>
                            <span class="player-name text-white" style="display: block; font-size: 34px; font-weight: 700; line-height: 1.2; margin-top: 0px;">
                                <?php echo esc_html($nome_riga1); ?><br>
                                <span style="color: #F9EA86; display:block; margin-top:-3px;"><?php echo esc_html($nome_riga2); ?></span>
                            </span>
                        </div>
                    </div>
                </a>
                <?php
                    endwhile; wp_reset_postdata();
                else:
                    echo '<p class="text-white">(Nessun giocatore inserito. Vai su "Team" in WordPress per aggiungere i primi membri)</p>';
                endif;
                ?>
            </div><!-- end carousel -->
        </div>

        <!-- Navigation arrows + dots -->
        <div class="carousel-nav" style="margin-top: 20px;">
            <span class="nav-arrow text-primary" id="team-prev" style="cursor:pointer;"><i class="fa-solid fa-chevron-left"></i></span>
            <span class="nav-dots" id="team-dots">
                <?php
                $pages = ceil($total_players / 4);
                for($i = 0; $i < $pages; $i++):
                    $active = $i === 0 ? ' active' : '';
                ?>
                <i class="fa-solid fa-circle<?php echo $active; ?>" data-page="<?php echo $i; ?>"></i>
                <?php endfor; ?>
            </span>
            <span class="nav-arrow text-primary" id="team-next" style="cursor:pointer;"><i class="fa-solid fa-chevron-right"></i></span>
        </div>

        <script>
        (function() {
            var carousel = document.getElementById('team-carousel');
            var prevBtn  = document.getElementById('team-prev');
            var nextBtn  = document.getElementById('team-next');
            var dots     = document.querySelectorAll('#team-dots .fa-circle');
            var cur      = 0;

            function slideWidth() {
                var card = carousel.querySelector('.team-slide');
                return card ? card.offsetWidth + 20 : 0;
            }
            function go(n) {
                var total = carousel.querySelectorAll('.team-slide').length;
                cur = Math.max(0, Math.min(n, total - 1));
                carousel.scrollLeft = cur * slideWidth();
                dots.forEach(function(d, i) {
                    // Dot attivo: uno ogni 4 giocatori
                    d.classList.toggle('active', Math.floor(cur / 4) === i);
                });
            }
            prevBtn.addEventListener('click', function() { go(cur - 1); });
            nextBtn.addEventListener('click', function() { go(cur + 1); });
            dots.forEach(function(d, i) {
                d.addEventListener('click', function() { go(i * 4); });
            });
        })();
        </script>
    </section>


    <!-- PARTNER E SPONSOR -->
    <section class="ps-section container">
        <h2 class="section-title text-white">PARTNER E SPONSOR</h2>
        <?php sport_theme_render_global_sponsors(); ?>
    </section>

    <!-- INSTAGRAM -->
    <section class="ps-section container text-center" style="padding-top: 10px; padding-bottom: 50px;">
        <?php echo do_shortcode('[instagram-feed]'); ?>
    </section>

</main>

<?php get_footer(); ?>
