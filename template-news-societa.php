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
?>

<main id="primary" class="site-main page-news-societa">

    <!-- SEZIONE NEWS -->
    <section class="news-hero">
        <?php
        $hero_image_url = 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=2000&auto=format&fit=crop';
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh; min-height: 350px;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="News AC Taverne">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 30px;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">NEWS</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 20px 0;">
            </div>
        </div>
    </section>

    <div class="container" style="padding-top: 30px; padding-bottom: 60px;">
        
        <!-- BARRA FILTRI NEWS -->
        <div class="filters-bar" style="display: flex; align-items: center; margin-bottom: 40px;">
            <form method="GET" action="<?php echo esc_url( get_permalink() ); ?>" style="display:flex; gap:15px; align-items:center; margin:0; width:100%;">
                <select name="ordine" onchange="this.form.submit()" style="background:transparent; color:white; border:none; font-size:14px; font-weight:bold; outline:none; cursor:pointer; -webkit-appearance: none; appearance: none; padding-right:15px; text-transform:uppercase;">
                    <option value="desc" style="color:black;" <?php selected($ordine, 'desc'); ?>>ORDINA PER (PIÙ RECENTI)</option>
                    <option value="asc" style="color:black;" <?php selected($ordine, 'asc'); ?>>ORDINA PER (MENO RECENTI)</option>
                </select>
                <i class="fa fa-chevron-down" style="color:white; font-size:10px; margin-left:-20px; pointer-events:none;"></i>
                
                <div style="width: 2px; height: 15px; background-color: var(--c-primary); margin: 0 10px;"></div>
                
                <div style="display:flex; align-items:center;">
                    <input type="text" name="ricerca" value="<?php echo esc_attr($ricerca); ?>" placeholder="CERCA..." style="background:transparent; border:none; border-bottom:1px solid var(--c-primary); color:var(--c-primary); font-size:14px; padding:5px 0; outline:none; font-weight:bold; width:150px; text-transform:uppercase;">
                    <button type="submit" style="background:transparent; border:none; color:var(--c-primary); cursor:pointer; outline:none; padding-left:10px;"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>

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
                        
                        <div style="position: absolute; top: 20px; left: 20px; color: white; font-weight: bold; font-size: 20px;"><?php echo $data_format; ?></div>
                        
                        <div style="position: absolute; bottom: 20px; left: 20px; right: 20px;">
                            <h3 style="color: white; font-size: 16px; font-weight: bold; margin-bottom: 15px; line-height: 1.4; text-transform: uppercase;">
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
        
        <div class="mock-pagination" style="display: flex; justify-content: space-between; align-items: center; color: var(--c-primary); font-size: 20px; font-weight: bold; margin-bottom: 60px;">
            <i class="fas fa-chevron-left" style="cursor: pointer;"></i>
            <div style="display: flex; gap: 10px;">
                <i class="fa-solid fa-circle" style="font-size: 10px;"></i>
                <i class="fa-regular fa-circle" style="font-size: 10px; color: white;"></i>
                <i class="fa-regular fa-circle" style="font-size: 10px; color: white;"></i>
            </div>
            <i class="fas fa-chevron-right" style="cursor: pointer;"></i>
        </div>

    </div>

    <!-- SEZIONE PROSSIMI EVENTI -->
    <section class="news-hero" style="margin-top: 40px;">
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 35vh; min-height: 250px;">
            <img src="https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=2000&auto=format&fit=crop" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center; filter: brightness(0.6);" alt="Prossimi Eventi">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 20px;">
                <h1 class="text-white" style="font-size: 45px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">PROSSIMI EVENTI</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 15px 0;">
            </div>
        </div>
    </section>

    <div class="container" style="padding-top: 30px; padding-bottom: 60px;">
        
        <!-- BARRA FILTRI PROSSIMI EVENTI -->
        <div class="filters-bar" style="display: flex; align-items: center; margin-bottom: 40px;">
            <form method="GET" action="<?php echo esc_url( get_permalink() ); ?>#prossimi-eventi" style="display:flex; gap:15px; align-items:center; margin:0; width:100%;">
                <select name="ordine" onchange="this.form.submit()" style="background:transparent; color:white; border:none; font-size:14px; font-weight:bold; outline:none; cursor:pointer; -webkit-appearance: none; appearance: none; padding-right:15px; text-transform:uppercase;">
                    <option value="asc" style="color:black;" <?php selected($ordine, 'asc'); ?>>ORDINA PER (PIÙ VICINI)</option>
                    <option value="desc" style="color:black;" <?php selected($ordine, 'desc'); ?>>ORDINA PER (PIÙ LONTANI)</option>
                </select>
                <i class="fa fa-chevron-down" style="color:white; font-size:10px; margin-left:-20px; pointer-events:none;"></i>
                
                <div style="width: 2px; height: 15px; background-color: var(--c-primary); margin: 0 10px;"></div>
                
                <div style="display:flex; align-items:center;">
                    <input type="text" name="ricerca" value="<?php echo esc_attr($ricerca); ?>" placeholder="CERCA EVENTO..." style="background:transparent; border:none; border-bottom:1px solid var(--c-primary); color:var(--c-primary); font-size:14px; padding:5px 0; outline:none; font-weight:bold; width:150px; text-transform:uppercase;">
                    <button type="submit" style="background:transparent; border:none; color:var(--c-primary); cursor:pointer; outline:none; padding-left:10px;"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>

        <div id="prossimi-eventi" class="eventi-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
            <?php
            $today = date('Y-m-d');
            $args_prossimi = array(
                'post_type'      => 'evento',
                'category_name'  => 'settore-giovanile',
                'posts_per_page' => 3,
                'meta_key'       => '_data_evento',
                'orderby'        => 'meta_value',
                'order'          => strtoupper($ordine) === 'DESC' ? 'DESC' : 'ASC',
                'meta_query'     => array(
                    array(
                        'key'     => '_data_evento',
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'DATE'
                    )
                )
            );
            if(!empty($ricerca)) $args_prossimi['s'] = $ricerca;
            $prossimi_query = new WP_Query($args_prossimi);

            if ( $prossimi_query->have_posts() ) :
                while ( $prossimi_query->have_posts() ) : $prossimi_query->the_post();
                    $data_raw = get_post_meta(get_the_ID(), '_data_evento', true);
                    $data_format = $data_raw ? date('d.m', strtotime($data_raw)) : '';
                    ?>
                    <div class="event-card" style="position: relative; height: 350px; background-color: var(--c-primary); overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; padding: 20px;">
                        <div style="position: absolute; inset: 0; background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>'); background-size: 150%; background-position: center; background-repeat: no-repeat; opacity: 0.15;"></div>
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 70%);"></div>
                        
                        <div style="position: absolute; top: 20px; left: 20px; color: white; font-weight: bold; font-size: 20px; z-index: 2;"><?php echo esc_html($data_format); ?></div>
                        
                        <div style="position: relative; z-index: 2;">
                            <h3 style="color: white; font-size: 16px; font-weight: bold; margin-bottom: 10px; line-height: 1.4; text-transform: uppercase;">
                                <?php echo esc_html(get_the_title()); ?>
                            </h3>
                            <a href="<?php the_permalink(); ?>" style="color: white; font-size: 13px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">SCOPRI <span style="margin-left: 5px; opacity: 0.7;">|</span></a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                // Se c'è una ricerca attiva e non trova nulla
                if(!empty($ricerca)) {
                    echo '<p class="text-white">Nessun evento futuro trovato con la ricerca: '.esc_html($ricerca).'</p>';
                } else {
                    // Fallback dummies
                    for($i=1; $i<=3; $i++) {
                        ?>
                        <div class="event-card" style="position: relative; height: 350px; background-color: var(--c-primary); overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; padding: 20px;">
                            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 70%);"></div>
                            <div style="position: absolute; top: 20px; left: 20px; color: white; font-weight: bold; font-size: 20px; z-index: 2;">28.02</div>
                            <div style="position: relative; z-index: 2;">
                                <h3 style="color: white; font-size: 16px; font-weight: bold; margin-bottom: 10px; line-height: 1.4; text-transform: uppercase;">DESCRIZIONE EVENTO DI ESEMPIO</h3>
                                <a href="#" onclick="alert('Questo è solo un evento finto di prova per mostrarti la grafica! Crea un evento VERO su WordPress, assegnagli la categoria Settore Giovanile e la Data, e poi cliccaci per vedere la bellissima pagina che ho progettato per te!'); return false;" style="color: white; font-size: 13px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">SCOPRI <span style="margin-left: 5px; opacity: 0.7;">|</span></a>
                            </div>
                        </div>
                        <?php
                    }
                }
            endif;
            ?>
        </div>

        <div class="mock-pagination" style="display: flex; justify-content: space-between; align-items: center; color: var(--c-primary); font-size: 20px; font-weight: bold; margin-bottom: 60px;">
            <i class="fas fa-chevron-left" style="cursor: pointer;"></i>
            <div style="display: flex; gap: 10px;">
                <i class="fa-solid fa-circle" style="font-size: 10px;"></i>
                <i class="fa-regular fa-circle" style="font-size: 10px; color: white;"></i>
                <i class="fa-regular fa-circle" style="font-size: 10px; color: white;"></i>
            </div>
            <i class="fas fa-chevron-right" style="cursor: pointer;"></i>
        </div>

    </div>

    <!-- SEZIONE EVENTI PASSATI -->
    <section class="news-hero" style="margin-top: 40px;">
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 35vh; min-height: 250px;">
            <img src="https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=2000&auto=format&fit=crop" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center; filter: brightness(0.6);" alt="Eventi Passati">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 20px;">
                <h1 class="text-white" style="font-size: 45px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">EVENTI PASSATI</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 15px 0;">
            </div>
        </div>
    </section>

    <div class="container" style="padding-top: 30px; padding-bottom: 60px;">
        
        <!-- BARRA FILTRI EVENTI PASSATI -->
        <div class="filters-bar" style="display: flex; align-items: center; margin-bottom: 40px;">
            <form method="GET" action="<?php echo esc_url( get_permalink() ); ?>#eventi-passati" style="display:flex; gap:15px; align-items:center; margin:0; width:100%;">
                <select name="ordine" onchange="this.form.submit()" style="background:transparent; color:white; border:none; font-size:14px; font-weight:bold; outline:none; cursor:pointer; -webkit-appearance: none; appearance: none; padding-right:15px; text-transform:uppercase;">
                    <option value="desc" style="color:black;" <?php selected($ordine, 'desc'); ?>>ORDINA PER (PIÙ RECENTI)</option>
                    <option value="asc" style="color:black;" <?php selected($ordine, 'asc'); ?>>ORDINA PER (MENO RECENTI)</option>
                </select>
                <i class="fa fa-chevron-down" style="color:white; font-size:10px; margin-left:-20px; pointer-events:none;"></i>
                
                <div style="width: 2px; height: 15px; background-color: var(--c-primary); margin: 0 10px;"></div>
                
                <div style="display:flex; align-items:center;">
                    <input type="text" name="ricerca" value="<?php echo esc_attr($ricerca); ?>" placeholder="CERCA EVENTO..." style="background:transparent; border:none; border-bottom:1px solid var(--c-primary); color:var(--c-primary); font-size:14px; padding:5px 0; outline:none; font-weight:bold; width:150px; text-transform:uppercase;">
                    <button type="submit" style="background:transparent; border:none; color:var(--c-primary); cursor:pointer; outline:none; padding-left:10px;"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>

        <div id="eventi-passati" class="eventi-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
            <?php
            $args_passati = array(
                'post_type'      => 'evento',
                'category_name'  => 'settore-giovanile',
                'posts_per_page' => 3,
                'meta_key'       => '_data_evento',
                'orderby'        => 'meta_value',
                'order'          => strtoupper($ordine) === 'ASC' ? 'ASC' : 'DESC',
                'meta_query'     => array(
                    array(
                        'key'     => '_data_evento',
                        'value'   => $today,
                        'compare' => '<',
                        'type'    => 'DATE'
                    )
                )
            );
            if(!empty($ricerca)) $args_passati['s'] = $ricerca;
            $passati_query = new WP_Query($args_passati);

            if ( $passati_query->have_posts() ) :
                while ( $passati_query->have_posts() ) : $passati_query->the_post();
                    $data_raw = get_post_meta(get_the_ID(), '_data_evento', true);
                    $data_format = $data_raw ? date('d.m', strtotime($data_raw)) : '';
                    ?>
                    <div class="event-card" style="position: relative; height: 350px; background-color: var(--c-primary); overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; padding: 20px;">
                        <div style="position: absolute; inset: 0; background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>'); background-size: 150%; background-position: center; background-repeat: no-repeat; opacity: 0.15;"></div>
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 70%);"></div>
                        
                        <div style="position: absolute; top: 20px; left: 20px; color: white; font-weight: bold; font-size: 20px; z-index: 2;"><?php echo esc_html($data_format); ?></div>
                        
                        <div style="position: relative; z-index: 2;">
                            <h3 style="color: white; font-size: 16px; font-weight: bold; margin-bottom: 10px; line-height: 1.4; text-transform: uppercase;">
                                <?php echo esc_html(get_the_title()); ?>
                            </h3>
                            <a href="<?php the_permalink(); ?>" style="color: white; font-size: 13px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">GALLERY <span style="margin-left: 5px; opacity: 0.7;">|</span></a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                if(!empty($ricerca)) {
                    echo '<p class="text-white">Nessun evento passato trovato con la ricerca: '.esc_html($ricerca).'</p>';
                } else {
                    // Fallback dummies
                    for($i=1; $i<=3; $i++) {
                        ?>
                        <div class="event-card" style="position: relative; height: 350px; background-color: var(--c-primary); overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; padding: 20px;">
                            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 70%);"></div>
                            <div style="position: absolute; top: 20px; left: 20px; color: white; font-weight: bold; font-size: 20px; z-index: 2;">28.02</div>
                            <div style="position: relative; z-index: 2;">
                                <h3 style="color: white; font-size: 16px; font-weight: bold; margin-bottom: 10px; line-height: 1.4; text-transform: uppercase;">DESCRIZIONE EVENTO DI ESEMPIO</h3>
                                <a href="#" onclick="alert('Questo è solo un evento finto di prova per mostrarti la grafica! Crea un evento VERO su WordPress, assegnagli la categoria Settore Giovanile e la Data, e poi cliccaci per vedere la bellissima pagina che ho progettato per te!'); return false;" style="color: white; font-size: 13px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">GALLERY <span style="margin-left: 5px; opacity: 0.7;">|</span></a>
                            </div>
                        </div>
                        <?php
                    }
                }
            endif;
            ?>
        </div>

        <div class="mock-pagination" style="display: flex; justify-content: space-between; align-items: center; color: var(--c-primary); font-size: 20px; font-weight: bold; margin-bottom: 60px;">
            <i class="fas fa-chevron-left" style="cursor: pointer;"></i>
            <div style="display: flex; gap: 10px;">
                <i class="fa-solid fa-circle" style="font-size: 10px;"></i>
                <i class="fa-regular fa-circle" style="font-size: 10px; color: white;"></i>
                <i class="fa-regular fa-circle" style="font-size: 10px; color: white;"></i>
            </div>
            <i class="fas fa-chevron-right" style="cursor: pointer;"></i>
        </div>

        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.4); margin-bottom: 40px;">

        <!-- SPONSOR -->
        <h3 class="text-white" style="font-size: 26px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px;">SPONSOR</h3>
        <?php sport_theme_render_global_sponsors(); ?>

    </div>
</main>

<?php get_footer('societa'); ?>
