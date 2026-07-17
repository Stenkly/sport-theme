<?php
/**
 * Template Name: Pagina News
 *
 * @package Sport_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-news">

    <!-- HERO IMMAGINE NEWS -->
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 500px;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="News">
            
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">NEWS</h1>
                <hr style="border: 0; border-top: 2px solid white; margin: 20px 0;">
                <div class="news-filters">
                    <?php
                    $ordine = isset($_GET['ordine']) && $_GET['ordine'] === 'asc' ? 'asc' : 'desc';
                    $ricerca = isset($_GET['ricerca']) ? sanitize_text_field($_GET['ricerca']) : '';
                    ?>
                    <form method="GET" action="<?php echo esc_url( get_permalink() ); ?>" class="news-filter-form">
                        <div class="news-filter-dropdown">
                            <button type="button" class="news-filter-control news-filter-order" aria-haspopup="listbox" aria-expanded="false">
                                ORDINA PER
                            </button>
                            <input type="hidden" name="ordine" value="<?php echo esc_attr($ordine); ?>">
                            <div class="news-filter-menu" role="listbox">
                                <button type="button" data-order="desc" class="<?php echo $ordine === 'desc' ? 'is-active' : ''; ?>">RECENTI</button>
                                <button type="button" data-order="asc" class="<?php echo $ordine === 'asc' ? 'is-active' : ''; ?>">MENO RECENTI</button>
                            </div>
                        </div>

                        <label class="news-filter-control news-filter-search">
                            <span>CERCA</span>
                            <input type="text" name="ricerca" value="<?php echo esc_attr($ricerca); ?>" placeholder=" " aria-label="Cerca news">
                        </label>
                    </form>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        function clampNewsTitles() {
                            document.querySelectorAll('.news-title-clamp').forEach(function(title) {
                                var fullText = title.dataset.fullTitle || title.textContent.trim();
                                title.dataset.fullTitle = fullText;
                                title.textContent = fullText;

                                var lineHeight = parseFloat(window.getComputedStyle(title).lineHeight);
                                var maxHeight = lineHeight * 2;

                                if (title.scrollHeight <= maxHeight + 1) {
                                    return;
                                }

                                var words = fullText.split(/\s+/);
                                var low = 0;
                                var high = words.length;
                                var best = '';

                                while (low <= high) {
                                    var mid = Math.floor((low + high) / 2);
                                    var candidate = words.slice(0, mid).join(' ') + '...';
                                    title.textContent = candidate;

                                    if (title.scrollHeight <= maxHeight + 1) {
                                        best = candidate;
                                        low = mid + 1;
                                    } else {
                                        high = mid - 1;
                                    }
                                }

                                title.textContent = best || words[0] + '...';
                            });
                        }

                        clampNewsTitles();
                        window.addEventListener('resize', clampNewsTitles);

                        document.querySelectorAll('.news-filter-search input').forEach(function(input) {
                            input.addEventListener('keydown', function(event) {
                                if (event.key === 'Enter') {
                                    event.preventDefault();
                                    input.form.submit();
                                }
                            });
                        });

                        document.querySelectorAll('.news-filter-dropdown').forEach(function(dropdown) {
                            var trigger = dropdown.querySelector('.news-filter-order');
                            var hidden = dropdown.querySelector('input[name="ordine"]');
                            var menu = dropdown.querySelector('.news-filter-menu');

                            function positionMenu() {
                                var rect = trigger.getBoundingClientRect();
                                menu.style.left = rect.left + 'px';
                                menu.style.top = (rect.bottom + 8) + 'px';
                                menu.style.width = rect.width + 'px';
                            }

                            function openMenu() {
                                positionMenu();
                                document.body.appendChild(menu);
                                menu.classList.add('is-floating');
                                dropdown.classList.add('is-open');
                                trigger.setAttribute('aria-expanded', 'true');
                            }

                            function closeMenu() {
                                dropdown.appendChild(menu);
                                menu.classList.remove('is-floating');
                                menu.removeAttribute('style');
                                dropdown.classList.remove('is-open');
                                trigger.setAttribute('aria-expanded', 'false');
                            }

                            trigger.addEventListener('click', function() {
                                if (dropdown.classList.contains('is-open')) {
                                    closeMenu();
                                } else {
                                    openMenu();
                                }
                            });

                            menu.querySelectorAll('button').forEach(function(option) {
                                option.addEventListener('click', function() {
                                    hidden.value = option.dataset.order;
                                    hidden.form.submit();
                                });
                            });

                            window.addEventListener('resize', function() {
                                if (dropdown.classList.contains('is-open')) {
                                    positionMenu();
                                }
                            });

                            window.addEventListener('scroll', function() {
                                if (dropdown.classList.contains('is-open')) {
                                    positionMenu();
                                }
                            }, true);
                        });

                        document.addEventListener('click', function(event) {
                            document.querySelectorAll('.news-filter-dropdown.is-open').forEach(function(dropdown) {
                                var menu = document.querySelector('.news-filter-menu.is-floating');
                                if (!dropdown.contains(event.target) && !menu.contains(event.target)) {
                                    dropdown.appendChild(menu);
                                    menu.classList.remove('is-floating');
                                    menu.removeAttribute('style');
                                    dropdown.classList.remove('is-open');
                                    dropdown.querySelector('.news-filter-order').setAttribute('aria-expanded', 'false');
                                }
                            });
                        });
                    });
                    </script>
                </div>
            </div>
        </div>
    </section>

    <!-- GRIGLIA NEWS (DINAMICA CON WORDPRESS LOOP) -->
    <section class="ps-section container news-list-section">
        <div class="ps-grid grid-3">
            <?php
            // Setup della query di WordPress per caricare tutti gli articoli del blog nativi 
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $args = array(
                'post_type'      => 'post',
                'category_name'  => 'prima-squadra',
                'posts_per_page' => 6,
                'paged'          => $paged,
                'order'          => strtoupper($ordine),
            );

            if ( !empty($ricerca) ) {
                $args['s'] = $ricerca;
            }

            $news_query = new WP_Query( $args );

            if ( $news_query->have_posts() ) :
                while ( $news_query->have_posts() ) : $news_query->the_post();
                    // Prendi l'immagine dell'articolo o usa un segnaposto se assente
                    $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) : 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=600';
            ?>
            
            <div class="news-card cover-bg" style="background-image: url('<?php echo esc_url( $thumb_url ); ?>');">
                <div class="news-date"><?php echo get_the_date('d.m'); ?></div>
                <div class="news-content">
                    <h3 class="news-title news-title-clamp text-white"><?php echo esc_html( get_the_title() ); ?></h3>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn-sm btn-primary">LEGGI ARTICOLO</a>
                </div>
            </div>
            
            <?php
                endwhile;
            ?>
        </div>

        <?php if ( $news_query->max_num_pages > 1 ) : ?>
            <!-- PAGINAZIONE (Frecce centrali + Numeri pag.) -->
            <div class="pagination-container news-pagination">
                <div class="nav-arrow text-primary">
                    <?php
                    $prev_page = get_previous_posts_link('<i class="fa-solid fa-chevron-left"></i>');
                    echo $prev_page ? $prev_page : '<i class="fa-solid fa-chevron-left" style="opacity:0.3;"></i>';
                    ?>
                </div>
                
                <div class="pagination-numbers">
                    <?php
                    echo paginate_links( array(
                        'total'        => $news_query->max_num_pages,
                        'current'      => max( 1, get_query_var( 'paged' ) ),
                        'prev_next'    => false,
                        'type'         => 'plain',
                        'end_size'     => 1,
                        'mid_size'     => 1,
                    ) );
                    ?>
                </div>
                
                <div class="nav-arrow text-primary">
                    <?php
                    $next_page = get_next_posts_link('<i class="fa-solid fa-chevron-right"></i>', $news_query->max_num_pages);
                    echo $next_page ? $next_page : '<i class="fa-solid fa-chevron-right" style="opacity:0.3;"></i>';
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <?php
            wp_reset_postdata();
        else :
            ?>
            <div style="text-align: center; color: white; padding: 40px;">
                <h2>Non ci sono ancora News.</h2>
                <p>Scrivi e pubblica qualche articolo dal pannello di WordPress e appariranno qui magicamente in griglia!</p>
            </div>
            <?php
        endif;
        ?>
    </section>

    <!-- PARTNER E SPONSOR (Identico alla Prima Squadra) -->
    <section class="ps-section container">
        <h2 class="section-title text-white">PARTNER E SPONSOR</h2>
        <?php sport_theme_render_global_sponsors(); ?>
    </section>

    <!-- INSTAGRAM PLUGIN -->
    <section class="ps-section container text-center" style="padding-top: 10px; padding-bottom: 50px;">
        <?php echo do_shortcode('[instagram-feed]'); ?>
    </section>

</main>

<?php
get_footer();
