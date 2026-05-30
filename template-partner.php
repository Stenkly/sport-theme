<?php
/* Template Name: Sponsor / Partner */
get_header(); ?>

<main class="site-main" style="background-color: #000;">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center 20%;" alt="<?php echo esc_attr(get_the_title()); ?>">
            
            <!-- Overlay nero graduato per far leggere il testo -->
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">Partner</h1>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION GIALLA -->
    <section class="container" style="padding-top: 0; padding-bottom: 30px;">
        <div class="sponsor-cta">
            <div class="cta-container">
                <h2>Diventa partner<br>della prima squadra</h2>
                <p>Gli sponsor della prima squadra contribuiscono<br>allo sviluppo sportivo e organizzativo del progetto.</p>
                <a href="<?php echo site_url('/contatti'); ?>" class="cta-btn">SCOPRI</a>
            </div>
        </div>
    </section>

    <!-- Linea Decorativa -->
    <div class="container container-hr"><hr class="sponsor-hr"></div>

    <!-- MAIN SPONSOR -->
    <section class="container" style="padding-top: 20px; padding-bottom: 20px;">
        <h2 class="sponsor-section-title">MAIN SPONSOR</h2>
        
        <div class="sponsor-grid main-sponsor-grid">
            <?php
            $main_query = new WP_Query([
                'post_type' => 'sponsor',
                'posts_per_page' => -1,
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => '_livello_sponsor',
                        'value' => 'main',
                        'compare' => '='
                    ],
                    [
                        'relation' => 'OR',
                        [
                            'key' => '_destinazione_sponsor',
                            'value' => 'prima_squadra',
                            'compare' => '='
                        ],
                        [
                            'key' => '_destinazione_sponsor',
                            'value' => 'entrambi',
                            'compare' => '='
                        ],
                        [
                            'key' => '_destinazione_sponsor',
                            'compare' => 'NOT EXISTS'
                        ]
                    ]
                ]
            ]);
            
            if ($main_query->have_posts()):
                while($main_query->have_posts()): $main_query->the_post();
                    $sito = get_post_meta(get_the_ID(), '_sito_url', true);
                    $logo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : '';
                    ?>
                    <div class="sponsor-item">
                        <?php if($sito): ?><a href="<?php echo esc_url($sito); ?>" target="_blank" style="text-decoration:none; display:block; height:100%;"><?php endif; ?>
                            <?php if($logo): ?>
                                <img src="<?php echo esc_url($logo); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php else: ?>
                                <div class="sponsor-placeholder"><?php the_title(); ?></div>
                            <?php endif; ?>
                        <?php if($sito): ?></a><?php endif; ?>
                    </div>
                    <?php
                endwhile; wp_reset_postdata();
            else:
                echo '<p style="color:#666; grid-column:1/-1;">(Nessun Main Sponsor inserito. Aggiungi i loghi dal pannello WordPress sotto "Sponsor")</p>';
            endif;
            ?>
        </div>
    </section>

    <!-- Linea Decorativa -->
    <div class="container container-hr"><hr class="sponsor-hr"></div>

    <!-- PARTNER -->
    <section class="container" style="padding-top: 20px; padding-bottom: 60px;">
        <h2 class="sponsor-section-title">PARTNER</h2>
        
        <div class="sponsor-grid partner-grid">
            <?php
            $partner_query = new WP_Query([
                'post_type' => 'sponsor',
                'posts_per_page' => -1,
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => '_livello_sponsor',
                        'value' => 'partner',
                        'compare' => '='
                    ],
                    [
                        'relation' => 'OR',
                        [
                            'key' => '_destinazione_sponsor',
                            'value' => 'prima_squadra',
                            'compare' => '='
                        ],
                        [
                            'key' => '_destinazione_sponsor',
                            'value' => 'entrambi',
                            'compare' => '='
                        ],
                        [
                            'key' => '_destinazione_sponsor',
                            'compare' => 'NOT EXISTS'
                        ]
                    ]
                ]
            ]);
            
            if ($partner_query->have_posts()):
                while($partner_query->have_posts()): $partner_query->the_post();
                    $sito = get_post_meta(get_the_ID(), '_sito_url', true);
                    $logo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : '';
                    ?>
                    <div class="sponsor-item">
                        <?php if($sito): ?><a href="<?php echo esc_url($sito); ?>" target="_blank" style="text-decoration:none; display:block; height:100%;"><?php endif; ?>
                            <?php if($logo): ?>
                                <img src="<?php echo esc_url($logo); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php else: ?>
                                <div class="sponsor-placeholder"><?php the_title(); ?></div>
                            <?php endif; ?>
                        <?php if($sito): ?></a><?php endif; ?>
                    </div>
                    <?php
                endwhile; wp_reset_postdata();
            else:
                echo '<p style="color:#666; grid-column:1/-1;">(Nessun Partner inserito. Aggiungi i loghi dal pannello WordPress sotto "Sponsor")</p>';
            endif;
            ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>
