<?php
/**
 * Template Name: Pagina Club Condivisa
 *
 * @package Sport_Theme
 */

get_header();

// Setup active states for the submenu
$current_page_title = strtolower(get_the_title());
$is_storia = ($current_page_title === 'storia');
$is_progetto = ($current_page_title === 'progetto sportivo' || $current_page_title === 'presente e futuro');

// Style helpers
$btn_active = "padding: 8px 40px; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none; border: 2px solid var(--c-primary); background-color: var(--c-primary); color: var(--c-black);";
$btn_inactive = "padding: 8px 40px; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none; border: 2px solid white; background-color: transparent; color: white; transition: all 0.3s;";
?>

<main id="primary" class="site-main page-club">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        $page_id = get_queried_object_id();
        if ( has_post_thumbnail( $page_id ) ) {
            $hero_image_url = get_the_post_thumbnail_url( $page_id, 'full' );
        } else {
            $club_page = get_page_by_path( 'club' );
            if ( $club_page && has_post_thumbnail( $club_page->ID ) ) {
                $hero_image_url = get_the_post_thumbnail_url( $club_page->ID, 'full' );
            } else {
                $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
            }
        }
        ?>
        <div class="club-hero-wrapper">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="club-hero-title"><?php the_title(); ?></h1>
                
                <?php if (has_excerpt()) : ?>
                <p class="hero-subtitle text-white" style="font-size: 22px; font-weight: 700; text-transform: uppercase; max-width: 800px; margin-top: 15px; line-height: 1.4;"><?php echo get_the_excerpt(); ?></p>
                <?php endif; ?>

                <hr style="border: 0; border-top: 2px solid white; margin: 20px 0;">

                <div class="page-submenu" style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <a href="<?php echo esc_url( site_url('/organigramma') ); ?>" class="btn-outline-hover" style="<?php echo $btn_inactive; ?>">ORGANIGRAMMA</a>
                    <a href="<?php echo esc_url( site_url('/storia') ); ?>" class="<?php echo $is_storia ? '' : 'btn-outline-hover'; ?>" style="<?php echo $is_storia ? $btn_active : $btn_inactive; ?>">STORIA</a>
                    <a href="<?php echo esc_url( site_url('/presente-e-futuro') ); ?>" class="<?php echo $is_progetto ? '' : 'btn-outline-hover'; ?>" style="<?php echo $is_progetto ? $btn_active : $btn_inactive; ?>">PRESENTE E FUTURO</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="container club-content" style="padding-top: 16px; padding-bottom: 30px;">
        <?php
        while ( have_posts() ) :
            the_post();
            
            $raw_content = get_the_content();
            $clean_content = trim(strip_tags($raw_content));
            $current_slug = get_post_field('post_name', get_the_ID());
            $titolo_check = strtolower(get_the_title());
            $is_storia_page = ($current_slug === 'storia' || strpos($titolo_check, 'storia') !== false);
            $is_progetto_page = ($current_slug === 'progetto-sportivo' || $current_slug === 'presente-e-futuro' || strpos($titolo_check, 'progetto') !== false || strpos($titolo_check, 'presente') !== false);
            
            // Finto testo se la pagina non è stata ancora scritta
            if ($is_storia_page) {
                if (!empty($clean_content)) {
                    the_content();
                } else {
                    echo sport_theme_storia_content_html();
                }
            } else if ($is_progetto_page) {
                if (!empty($clean_content)) {
                    the_content();
                } else {
                    echo sport_theme_presente_futuro_content_html();
                }
            } else if ( empty($clean_content) ) {
                $lorem_p = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>";
                $lorem_h3 = "<h3>LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.</h3>";
                echo $lorem_h3 . $lorem_p;
            } else {
                the_content();
            }

        endwhile;
        ?>
    </div>

    <!-- DYNAMIC FOTOGALLERY (dal Custom Post Type Foto Gallery) -->
    <?php
    $gallery_args = array(
        'post_type' => 'fotogallery',
    );
    
    if ($current_slug === 'storia') {
        $gallery_args['posts_per_page'] = -1;
        $gallery_args['tax_query'] = array(
            array(
                'taxonomy' => 'categoria_galleria',
                'field'    => 'slug',
                'terms'    => 'storia',
            ),
        );
    } else {
        // Presente e Futuro: stessa fotogallery della home Prima Squadra.
        $gallery_args['posts_per_page'] = -1;
        $gallery_args['tax_query'] = array(
            array(
                'taxonomy' => 'categoria_galleria',
                'field'    => 'slug',
                'terms'    => 'storia',
                'operator' => 'NOT IN',
            ),
        );
    }
    
    $gallery_query = new WP_Query($gallery_args);
    ?>
    <section class="container <?php echo $current_slug === 'storia' ? 'club-content' : ''; ?> ps-section" style="padding-bottom: 60px;">
        <h2 class="section-title text-white" style="margin-bottom: 30px;">FOTOGALLERY</h2>
        
        <?php if ($current_slug === 'storia') : ?>
            <!-- Layout MASONRY per Storia -->
            <div class="custom-cpt-gallery wp-block-gallery">
                <?php 
                if ($gallery_query->have_posts()) :
                    while ($gallery_query->have_posts()) : $gallery_query->the_post(); 
                        if (has_post_thumbnail()) :
                            $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    ?>
                        <figure class="wp-block-image">
                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                        </figure>
                    <?php 
                        endif;
                    endwhile; wp_reset_postdata(); 
                else :
                    // Mockup Segnaposto per STORIA
                ?>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-1.jpg'); ?>" alt="Storia 1"></figure>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-2.jpg'); ?>" alt="Storia 2"></figure>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-3.jpg'); ?>" alt="Storia 3"></figure>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-4.jpg'); ?>" alt="Storia 4"></figure>
                    <figure class="wp-block-image"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/storia/storia-5.jpg'); ?>" alt="Storia 5"></figure>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <!-- Layout CAROUSEL 4-GRID per Progetto Sportivo (uguale a Prima Squadra) -->
            <div id="progetto-gallery-carousel" style="display:flex; gap:20px; align-items:center; overflow:hidden; scroll-behavior:smooth;">
                <?php
                $foto_count = 0;
                if ( $gallery_query->have_posts() ) :
                    while ( $gallery_query->have_posts() ) : $gallery_query->the_post();
                        $img_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=800';
                        $active_class = $foto_count === 0 ? ' active' : '';
                        echo '<a data-fancybox="gallery" href="' . esc_url($img_url) . '" class="gallery-slide' . esc_attr($active_class) . '"><div class="gallery-item cover-bg" style="background-image: url(\'' . esc_url($img_url) . '\')"></div></a>';
                        $foto_count++;
                    endwhile;
                    wp_reset_postdata();
                else :
                    $foto_count = 4;
                    for ( $i = 0; $i < 4; $i++ ) {
                        $demo_img = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=800';
                        $active_class = $i === 0 ? ' active' : '';
                        echo '<a data-fancybox="gallery" href="' . esc_url($demo_img) . '" class="gallery-slide' . esc_attr($active_class) . '"><div class="gallery-item cover-bg" style="background-image: url(\'' . esc_url($demo_img) . '\')"></div></a>';
                    }
                endif;
                ?>
            </div>

            <div class="carousel-nav gallery-nav" style="margin-top:15px;">
                <span class="nav-arrow text-primary" id="progetto-gallery-prev" style="cursor:pointer;"><i class="fa-solid fa-chevron-left"></i></span>
                <span class="nav-dots" id="progetto-gallery-dots">
                    <?php for ( $i = 0; $i < $foto_count; $i++ ) : ?>
                        <i class="<?php echo $i === 0 ? 'fa-solid' : 'fa-regular'; ?> fa-circle<?php echo $i === 0 ? ' active' : ''; ?>" data-page="<?php echo esc_attr($i); ?>"></i>
                    <?php endfor; ?>
                </span>
                <span class="gallery-counter" id="progetto-gallery-counter">1 / <?php echo esc_html( $foto_count ); ?></span>
                <span class="nav-arrow text-primary" id="progetto-gallery-next" style="cursor:pointer;"><i class="fa-solid fa-chevron-right"></i></span>
            </div>

            <script>
            (function(){
                var car = document.getElementById('progetto-gallery-carousel');
                if (!car) return;

                var prev = document.getElementById('progetto-gallery-prev');
                var next = document.getElementById('progetto-gallery-next');
                var counter = document.getElementById('progetto-gallery-counter');
                var dots = document.querySelectorAll('#progetto-gallery-dots .fa-circle');
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
        <?php endif; ?>
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
