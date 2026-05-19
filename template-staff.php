<?php
/**
 * Template Name: Pagina Staff
 *
 * @package Sport_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-staff">

    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">TEAM</h1>
                
                <hr style="border: 0; border-top: 2px solid white; margin: 20px 0;">
                
                <div class="page-submenu" style="display: flex; gap: 20px;">
                    <a href="<?php echo esc_url( site_url('/rosa') ); ?>" class="btn-outline-hover" style="padding: 10px 40px; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none; border: 2px solid white; background-color: transparent; color: white; transition: all 0.3s;">ROSA</a>
                    <a href="<?php echo esc_url( site_url('/staff') ); ?>" style="padding: 10px 40px; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none; border: 2px solid var(--c-primary); background-color: var(--c-primary); color: var(--c-black);">STAFF</a>
                </div>
            </div>
        </div>
    </section>

    <div style="padding-top: 40px;">
    <?php
    $team_query = new WP_Query(array(
        'post_type' => 'membro_staff',
        'posts_per_page' => -1,
        'order' => 'ASC',
    ));

    if($team_query->have_posts()):
    ?>
    <section class="ps-section container" style="padding-top: 40px; padding-bottom: 40px;">
        <div class="ps-grid grid-4 ps-team">
            <?php while($team_query->have_posts()): $team_query->the_post(); 
                $ruolo_spec = get_post_meta(get_the_ID(), '_ruolo_specifico', true);
                $foto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://via.placeholder.com/300x400/222222/FFFFFF?text=' . get_the_title();
                
                $parti_nome = explode(' ', get_the_title(), 2);
                $nome_riga1 = $parti_nome[0];
                $nome_riga2 = isset($parti_nome[1]) ? $parti_nome[1] : '';
            ?>
            <a href="#" class="open-player-modal" 
               data-foto="<?php echo esc_attr($foto); ?>" 
               data-numero="" 
               data-nome1="<?php echo esc_attr($nome_riga1); ?>" 
               data-nome2="<?php echo esc_attr($nome_riga2); ?>" 
               data-nascita="<?php echo esc_attr(get_post_meta(get_the_ID(), '_data_nascita', true) ?: '-'); ?>" 
               data-altezza="-" 
               data-peso="-" 
               data-nazionalita="<?php echo esc_attr(get_post_meta(get_the_ID(), '_nazionalita', true) ?: '-'); ?>" 
               data-htp="-" 
               data-shop=""
               data-ruolo="<?php echo esc_attr($ruolo_spec ?: '-'); ?>"
               style="text-decoration: none; display: block; overflow: hidden; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <div class="player-card" style="position: relative; aspect-ratio: 3/4; overflow: hidden; background-color: #111; border: 1px solid rgba(255,255,255,0.5);">
                    <div class="player-photo cover-bg" style="background-image: url('<?php echo esc_url($foto); ?>'); width: 100%; height: 100%; border: none; transition: transform 0.5s ease;"></div>
                    <div class="player-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, transparent 100%); pointer-events: none;"></div>
                    
                    <div class="player-info" style="position: absolute; bottom: 20px; left: 20px; z-index: 2; flex-direction: column; gap: 0;">
                        <?php if(!empty($ruolo_spec)): ?>
                        <span class="staff-role text-primary" style="display: block; font-size: 13px; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; letter-spacing: 1px;"><?php echo esc_html($ruolo_spec); ?></span>
                        <?php endif; ?>
                        <span class="player-name text-white" style="display: block; font-size: 22px; font-weight: 700; line-height: 1.1; margin-top: 0;">
                            <?php echo esc_html($nome_riga1); ?><br>
                            <?php echo esc_html($nome_riga2); ?>
                        </span>
                    </div>
                </div>
            </a>
            
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php else:
        echo '<div class="container text-center" style="padding: 100px 0;"><h3 class="text-white">Nessun membro inserito. (Aggiungine uno dal menu "Staff")</h3></div>';
    endif;
    ?>
    </div>

    <!-- PARTNER E SPONSOR -->
    <section class="ps-section container">
        <h2 class="section-title text-white">PARTNER E SPONSOR</h2>
        <?php sport_theme_render_global_sponsors(); ?>
    </section>

    <!-- INSTAGRAM MOCKUP -->
    <section class="ps-section container text-center" style="padding-top: 10px; padding-bottom: 50px;">
        <?php echo do_shortcode('[instagram-feed]'); ?>
        <p style="font-size: 15px; color: #aaaaaa; margin-top: 20px;">[ Sezione Instagram: Installa il plugin "Smash Balloon" per attivare questa area ]</p>
    </section>

</main>

<?php get_footer(); ?>
