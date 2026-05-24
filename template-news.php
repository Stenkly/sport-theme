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
            
            <!-- Overlay nero graduato per far leggere il testo -->
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">NEWS</h1>
                <div class="news-filters" style="margin-top: 15px; display: flex; align-items: center; gap: 20px;">
                    <?php
                    $ordine = isset($_GET['ordine']) && $_GET['ordine'] === 'asc' ? 'asc' : 'desc';
                    $ricerca = isset($_GET['ricerca']) ? sanitize_text_field($_GET['ricerca']) : '';
                    ?>
                    <form method="GET" action="<?php echo esc_url( get_permalink() ); ?>" style="display:flex; gap:15px; align-items:center; margin:0;" class="news-filter-form">
                        <select name="ordine" onchange="this.form.submit()" style="background:transparent; color:white; border:none; font-size:11px; font-weight:700; letter-spacing: 2px; text-transform:uppercase; outline:none; cursor:pointer; width:auto; -webkit-appearance: none; appearance: none; padding-right:15px; border-bottom: 1px solid transparent;">
                            <option value="desc" style="color:black;" <?php selected($ordine, 'desc'); ?>>ORDINA PER: RECENTI</option>
                            <option value="asc" style="color:black;" <?php selected($ordine, 'asc'); ?>>ORDINA PER: MENO RECENTI</option>
                        </select>
                        <i class="fa fa-chevron-down" style="color:white; font-size:9px; margin-left:-18px; pointer-events:none;"></i>
                        
                        <span style="color: #666; margin-left:10px;">|</span>
                        
                        <div style="position:relative; display:flex; align-items:center;">
                            <input type="text" name="ricerca" value="<?php echo esc_attr($ricerca); ?>" placeholder="CERCA..." style="background:transparent; border:none; border-bottom:1px solid var(--c-primary); color:var(--c-primary); font-size:11px; padding:5px 0; outline:none; text-transform:uppercase; font-weight:700; letter-spacing: 2px; width:120px;">
                            <button type="submit" style="background:transparent; border:none; color:var(--c-primary); cursor:pointer; outline:none; padding-left:10px;"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- GRIGLIA NEWS (DINAMICA CON WORDPRESS LOOP) -->
    <section class="ps-section container" style="padding-top: 60px;">
        <div class="ps-grid grid-3">
            <?php
            // Setup della query di WordPress per caricare tutti gli articoli del blog nativi 
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $args = array(
                'post_type'      => 'post',
                'category_name'  => 'prima-squadra',
                'posts_per_page' => 12, // Nella grafica sono 12
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
                    <h3 class="news-title text-white"><?php echo wp_trim_words( get_the_title(), 7, '...' ); ?></h3>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn-sm btn-primary" style="display:inline-block;">LEGGI ARTICOLO</a>
                </div>
            </div>
            
            <?php
                endwhile;
            ?>
        </div>

        <!-- PAGINAZIONE (Frecce centrali + Numeri pag.) -->
        <div class="pagination-container" style="display: flex; justify-content: space-between; align-items: center; padding: 60px 0 20px 0; border-bottom: 1px solid #333;">
            <div class="nav-arrow text-primary">
                <?php previous_posts_link('<i class="fa-solid fa-chevron-left"></i>'); ?>
            </div>
            
            <div class="pagination-numbers" style="display: flex; gap: 8px;">
                <?php
                echo paginate_links( array(
                    'total'        => $news_query->max_num_pages,
                    'current'      => max( 1, get_query_var( 'paged' ) ),
                    'prev_next'    => false, // Le frecce le abbiamo messe a lato
                    'type'         => 'plain',
                    'end_size'     => 1,
                    'mid_size'     => 1,
                ) );
                ?>
            </div>
            
            <div class="nav-arrow text-primary">
                <?php
                $next_page = get_next_posts_link('<i class="fa-solid fa-chevron-right"></i>', $news_query->max_num_pages);
                if($next_page) echo $next_page; else echo '<i class="fa-solid fa-chevron-right" style="opacity:0.3;"></i>';
                ?>
            </div>
        </div>

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
