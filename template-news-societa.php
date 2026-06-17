<?php
/**
 * Template Name: Pagina News Società
 *
 * @package Sport_Theme
 */

get_header('societa');

// Recupero dati per la paginazione e filtri
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$ordine = isset($_GET['ordine']) && $_GET['ordine'] === 'asc' ? 'asc' : 'desc';
$ricerca = isset($_GET['ricerca']) ? sanitize_text_field($_GET['ricerca']) : '';

function sport_theme_render_news_societa_filters( $action, $ordine, $ricerca, $options, $search_label ) {
    ?>
    <div class="news-filters news-societa-filters">
        <form method="GET" action="<?php echo esc_url( $action ); ?>" class="news-filter-form">
            <div class="news-filter-dropdown">
                <button type="button" class="news-filter-control news-filter-order" aria-haspopup="listbox" aria-expanded="false">
                    ORDINA PER
                </button>
                <input type="hidden" name="ordine" value="<?php echo esc_attr( $ordine ); ?>">
                <div class="news-filter-menu" role="listbox">
                    <?php foreach ( $options as $value => $label ) : ?>
                        <button type="button" data-order="<?php echo esc_attr( $value ); ?>" class="<?php echo $ordine === $value ? 'is-active' : ''; ?>">
                            <?php echo esc_html( $label ); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <label class="news-filter-control news-filter-search">
                <span><?php echo esc_html( $search_label ); ?></span>
                <input type="text" name="ricerca" value="<?php echo esc_attr( $ricerca ); ?>" placeholder=" " aria-label="<?php echo esc_attr( $search_label ); ?>">
            </label>
        </form>
    </div>
    <?php
}

function sport_theme_news_societa_event_timestamp( $post_id ) {
    $data_evento = get_post_meta( $post_id, '_data_evento', true );
    if ( ! empty( $data_evento ) ) {
        $timestamp = strtotime( $data_evento . ' 00:00:00' );
        if ( $timestamp ) {
            return $timestamp;
        }
    }

    return (int) get_post_time( 'U', false, $post_id );
}

function sport_theme_news_societa_get_events( $period, $ordine, $ricerca, $limit = 3 ) {
    $args = array(
        'post_type'      => 'evento',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    if ( ! empty( $ricerca ) ) {
        $args['s'] = $ricerca;
    }

    $query = new WP_Query( $args );
    $today_start = strtotime( current_time( 'Y-m-d' ) . ' 00:00:00' );
    $events = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $event_timestamp = sport_theme_news_societa_event_timestamp( get_the_ID() );
            $is_future = $event_timestamp >= $today_start;

            if ( ( 'future' === $period && $is_future ) || ( 'past' === $period && ! $is_future ) ) {
                $events[] = array(
                    'post'      => get_post(),
                    'timestamp' => $event_timestamp,
                );
            }
        }
        wp_reset_postdata();
    }

    usort( $events, function( $a, $b ) use ( $ordine ) {
        if ( $a['timestamp'] === $b['timestamp'] ) {
            return 0;
        }
        return 'asc' === $ordine ? $a['timestamp'] <=> $b['timestamp'] : $b['timestamp'] <=> $a['timestamp'];
    } );

    return array_slice( $events, 0, $limit );
}

function sport_theme_news_societa_render_event_card( $event, $link_label ) {
    $post = $event['post'];
    $thumb = has_post_thumbnail( $post->ID )
        ? get_the_post_thumbnail_url( $post->ID, 'medium_large' )
        : 'https://images.unsplash.com/photo-1508344928928-7137b29de218?q=80&w=900&auto=format&fit=crop';
    $data_format = date_i18n( 'd.m', $event['timestamp'] );
    ?>
    <div class="event-card news-card cover-bg" style="position: relative; height: 350px; overflow: hidden; background-image: url('<?php echo esc_url( $thumb ); ?>'); background-size: cover; background-position: center;">
        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 70%);"></div>
        <div style="position: absolute; top: 20px; left: 20px; color: white; font-weight: bold; font-size: 32px; z-index: 2;"><?php echo esc_html( $data_format ); ?></div>
        <div style="position: absolute; bottom: 20px; left: 20px; right: 20px; z-index: 2;">
            <h3 style="color: white; font-size: 32px; font-weight: bold; margin-bottom: 10px; line-height: 1.4; text-transform: uppercase;">
                <?php echo esc_html( get_the_title( $post ) ); ?>
            </h3>
            <a href="<?php echo esc_url( get_permalink( $post ) ); ?>" style="color: white; font-size: 13px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html( $link_label ); ?> <span style="margin-left: 5px; opacity: 0.7;">|</span></a>
        </div>
    </div>
    <?php
}
?>

<main id="primary" class="site-main page-news-societa">

    <!-- SEZIONE NEWS -->
    <section class="news-hero">
        <?php
        $hero_image_url = sport_theme_get_societa_home_hero_url();
        $hero_sottotitolo = get_post_meta( get_the_ID(), '_news_societa_hero_sottotitolo', true ) ?: 'AGGIORNAMENTI, EVENTI E MOMENTI DELLA VITA GIALLONERA.';
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 732px; min-height: 350px;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="News AC Taverne">
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 30px;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">NEWS</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 20px 0;">
                <p class="text-white hero-subtitle"><?php echo esc_html( $hero_sottotitolo ); ?></p>
            </div>
        </div>
    </section>

    <div class="container" style="padding-top: 30px; padding-bottom: 60px;">
        
        <?php sport_theme_render_news_societa_filters( get_permalink(), $ordine, $ricerca, array( 'desc' => 'RECENTI', 'asc' => 'MENO RECENTI' ), 'CERCA' ); ?>

        <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
            <?php
            $args_news = array(
                'post_type'      => 'post',
                'category_name'  => 'settore-giovanile',
                'posts_per_page' => 6,
                'paged'          => $paged,
                'order'          => strtoupper($ordine)
            );
            if(!empty($ricerca)) $args_news['s'] = $ricerca;
            $news_query = new WP_Query($args_news);

            if ( $news_query->have_posts() ) :
                while ( $news_query->have_posts() ) : $news_query->the_post();
                    $img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=800';
                    $data_format = get_the_date('d.m');
                    ?>
                    <div class="news-card" style="position: relative; height: 350px; overflow: hidden; background-color: #111;">
                        <img src="<?php echo esc_url($img); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="<?php echo esc_attr(get_the_title()); ?>">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 60%);"></div>
                        
                        <div style="position: absolute; top: 20px; left: 20px; color: white; font-weight: bold; font-size: 32px;"><?php echo $data_format; ?></div>
                        
                        <div style="position: absolute; bottom: 20px; left: 20px; right: 20px;">
                            <h3 style="color: white; font-size: 32px; font-weight: bold; margin-bottom: 15px; line-height: 1.4; text-transform: uppercase;">
                                <?php echo wp_trim_words( get_the_title(), 8, '...' ); ?>
                            </h3>
                            <a href="<?php the_permalink(); ?>" style="display: inline-block; background-color: var(--c-primary); color: #000; font-weight: bold; font-size: 12px; padding: 8px 15px; text-decoration: none; text-transform: uppercase;">LEGGI ARTICOLO</a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                echo '<p class="text-white">Nessuna news trovata.</p>';
            endif;
            ?>
        </div>
        
        <?php if ( $news_query->max_num_pages > 1 ) : ?>
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
                        'total'     => $news_query->max_num_pages,
                        'current'   => max( 1, get_query_var( 'paged' ) ),
                        'prev_next' => false,
                        'type'      => 'plain',
                        'end_size'  => 1,
                        'mid_size'  => 1,
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

    </div>

    <!-- SEZIONE PROSSIMI EVENTI -->
    <div class="container news-societa-section" style="padding-top: 30px; padding-bottom: 60px;">
        <h2 class="news-societa-section-title">PROSSIMI EVENTI</h2>
        
        <?php sport_theme_render_news_societa_filters( get_permalink() . '#prossimi-eventi', $ordine, $ricerca, array( 'asc' => 'VICINI', 'desc' => 'LONTANI' ), 'CERCA' ); ?>

        <div id="prossimi-eventi" class="eventi-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
            <?php
            $prossimi_eventi = sport_theme_news_societa_get_events( 'future', $ordine, $ricerca );
            if ( ! empty( $prossimi_eventi ) ) :
                foreach ( $prossimi_eventi as $evento ) :
                    sport_theme_news_societa_render_event_card( $evento, 'SCOPRI' );
                endforeach;
            else :
                if(!empty($ricerca)) {
                    echo '<p class="text-white">Nessun evento futuro trovato con la ricerca: '.esc_html($ricerca).'</p>';
                } else {
                    echo '<p class="text-white">Nessun evento futuro disponibile.</p>';
                }
            endif;
            ?>
        </div>

    </div>

    <!-- SEZIONE EVENTI PASSATI -->
    <div class="container news-societa-section" style="padding-top: 30px; padding-bottom: 60px;">
        <h2 class="news-societa-section-title">EVENTI PASSATI</h2>
        
        <?php sport_theme_render_news_societa_filters( get_permalink() . '#eventi-passati', $ordine, $ricerca, array( 'desc' => 'RECENTI', 'asc' => 'MENO RECENTI' ), 'CERCA' ); ?>

        <div id="eventi-passati" class="eventi-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
            <?php
            $eventi_passati = sport_theme_news_societa_get_events( 'past', $ordine, $ricerca );
            if ( ! empty( $eventi_passati ) ) :
                foreach ( $eventi_passati as $evento ) :
                    sport_theme_news_societa_render_event_card( $evento, 'GALLERY' );
                endforeach;
            else :
                if(!empty($ricerca)) {
                    echo '<p class="text-white">Nessun evento passato trovato con la ricerca: '.esc_html($ricerca).'</p>';
                } else {
                    echo '<p class="text-white">Nessun evento passato disponibile.</p>';
                }
            endif;
            ?>
        </div>

    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.page-news-societa .news-filter-search input').forEach(function(input) {
            input.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    input.form.submit();
                }
            });
        });

        document.querySelectorAll('.page-news-societa .news-filter-dropdown').forEach(function(dropdown) {
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
            document.querySelectorAll('.page-news-societa .news-filter-dropdown.is-open').forEach(function(dropdown) {
                var menu = document.querySelector('.news-filter-menu.is-floating');
                if (menu && !dropdown.contains(event.target) && !menu.contains(event.target)) {
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
</main>

<?php get_footer('societa'); ?>
