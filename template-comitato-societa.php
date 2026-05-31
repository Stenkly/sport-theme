<?php
/**
 * Template Name: Pagina Comitato Società
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-comitato">

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
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 30px;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">COMITATO</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 20px 0;">
                <?php sport_theme_render_societa_submenu(); ?>
            </div>
        </div>
    </section>

    <div style="padding-top: 60px;">
    <?php
    $dirigenti_query = new WP_Query(array(
        'post_type' => 'dirigente',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'menu_order title',
        'meta_query' => array(
            array(
                'key' => '_sezione_comitato',
                'value' => 'settore-giovanile',
                'compare' => '='
            )
        )
    ));

    if($dirigenti_query->have_posts()):
        $presidents = array();
        $leaders = array();
        $others = array();
        
        while($dirigenti_query->have_posts()) {
            $dirigenti_query->the_post();
            $ruolo = get_post_meta(get_the_ID(), '_ruolo_specifico', true);
            $foto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://via.placeholder.com/300x400/222222/FFFFFF?text=' . get_the_title();
            
            $parti_nome = explode(' ', get_the_title(), 2);
            $nome_riga1 = $parti_nome[0];
            $nome_riga2 = isset($parti_nome[1]) ? $parti_nome[1] : '';
            
            $is_pres_onorario = (stripos($ruolo, 'presidente onorario') !== false);
            $is_leader = !$is_pres_onorario && (stripos($ruolo, 'vice presidente') !== false || strcasecmp(trim($ruolo), 'presidente') === 0);
            
            $foto_pos_y = get_post_meta(get_the_ID(), '_foto_position_y', true);
            $bg_pos = ($foto_pos_y !== '') ? ' background-position: center ' . esc_attr($foto_pos_y) . '%;' : '';
            
            $item = array(
                'nome_riga1' => $nome_riga1,
                'nome_riga2' => $nome_riga2,
                'ruolo'      => $ruolo,
                'foto'       => $foto,
                'content'    => get_the_content(),
                'bg_pos'     => $bg_pos,
            );
            
            if ($is_pres_onorario) {
                $presidents[] = $item;
            } elseif ($is_leader) {
                $leaders[] = $item;
            } else {
                $others[] = $item;
            }
        }
        wp_reset_postdata();

        // Ordina leaders: Presidente prima di Vice Presidente
        usort($leaders, function($a, $b) {
            $is_a_vice = (stripos($a['ruolo'], 'vice') !== false);
            $is_b_vice = (stripos($b['ruolo'], 'vice') !== false);
            if ($is_a_vice && !$is_b_vice) return 1;
            if (!$is_a_vice && $is_b_vice) return -1;
            return 0;
        });
    ?>
    <section class="ps-section container" style="padding-top: 20px; padding-bottom: 60px;">
        <!-- Sezione Presidente Onorario (in cima, centrato) -->
        <?php if (!empty($presidents)): ?>
            <div class="dirigenti-grid" style="grid-template-columns: 1fr; margin-bottom: 40px; display: grid;">
                <?php foreach ($presidents as $p): ?>
                    <div class="dirigente-card" style="justify-self: center; width: 100%; max-width: 800px;">
                        <div class="dirigente-photo cover-bg" style="background-image: url('<?php echo esc_url($p['foto']); ?>');<?php echo $p['bg_pos']; ?>">
                            <div class="dirigente-photo-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent); pointer-events: none;"></div>
                        </div>
                        <div class="dirigente-info">
                            <?php if(!empty($p['ruolo'])): 
                                $ruolo_formattato = nl2br(esc_html($p['ruolo']));
                                $ruolo_formattato = str_ireplace(array(' / ', ' + ', ' - ', ' e '), '<br>', $ruolo_formattato);
                                $ruolo_formattato = str_replace(array('/', '+'), '<br>', $ruolo_formattato);
                            ?>
                            <div class="dirigente-role" style="line-height: 1.3; margin-bottom: 10px;"><?php echo $ruolo_formattato; ?></div>
                            <?php endif; ?>
                            <div class="dirigente-name" style="margin-top: 5px; margin-bottom: 15px;">
                                <?php echo esc_html($p['nome_riga1']); ?><br>
                                <span style="color: var(--c-primary);"><?php echo esc_html($p['nome_riga2']); ?></span>
                            </div>
                            <div class="dirigente-desc text-white" style="font-size: 16px; line-height: 1.6;">
                                <?php echo wpautop($p['content']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Sezione Presidente e Vice Presidente (affiancati) -->
        <?php if (!empty($leaders)): ?>
            <div class="dirigenti-grid" style="margin-bottom: 40px;">
                <?php foreach ($leaders as $l): ?>
                    <div class="dirigente-card">
                        <div class="dirigente-photo cover-bg" style="background-image: url('<?php echo esc_url($l['foto']); ?>');<?php echo $l['bg_pos']; ?>">
                            <div class="dirigente-photo-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent); pointer-events: none;"></div>
                        </div>
                        <div class="dirigente-info">
                            <?php if(!empty($l['ruolo'])): 
                                $ruolo_formattato = nl2br(esc_html($l['ruolo']));
                                $ruolo_formattato = str_ireplace(array(' / ', ' + ', ' - ', ' e '), '<br>', $ruolo_formattato);
                                $ruolo_formattato = str_replace(array('/', '+'), '<br>', $ruolo_formattato);
                            ?>
                            <div class="dirigente-role" style="line-height: 1.3; margin-bottom: 10px;"><?php echo $ruolo_formattato; ?></div>
                            <?php endif; ?>
                            <div class="dirigente-name" style="margin-top: 5px; margin-bottom: 15px;">
                                <?php echo esc_html($l['nome_riga1']); ?><br>
                                <span style="color: var(--c-primary);"><?php echo esc_html($l['nome_riga2']); ?></span>
                            </div>
                            <div class="dirigente-desc text-white" style="font-size: 16px; line-height: 1.6;">
                                <?php echo wpautop($l['content']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Resto del Comitato (altri membri) -->
        <?php if (!empty($others)): ?>
            <div class="dirigenti-grid">
                <?php foreach ($others as $o): ?>
                    <div class="dirigente-card">
                        <div class="dirigente-photo cover-bg" style="background-image: url('<?php echo esc_url($o['foto']); ?>');<?php echo $o['bg_pos']; ?>">
                            <div class="dirigente-photo-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent); pointer-events: none;"></div>
                        </div>
                        <div class="dirigente-info">
                            <?php if(!empty($o['ruolo'])): 
                                $ruolo_formattato = nl2br(esc_html($o['ruolo']));
                                $ruolo_formattato = str_ireplace(array(' / ', ' + ', ' - ', ' e '), '<br>', $ruolo_formattato);
                                $ruolo_formattato = str_replace(array('/', '+'), '<br>', $ruolo_formattato);
                            ?>
                            <div class="dirigente-role" style="line-height: 1.3; margin-bottom: 10px;"><?php echo $ruolo_formattato; ?></div>
                            <?php endif; ?>
                            <div class="dirigente-name" style="margin-top: 5px; margin-bottom: 15px;">
                                <?php echo esc_html($o['nome_riga1']); ?><br>
                                <span style="color: var(--c-primary);"><?php echo esc_html($o['nome_riga2']); ?></span>
                            </div>
                            <div class="dirigente-desc text-white" style="font-size: 16px; line-height: 1.6;">
                                <?php echo wpautop($o['content']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
    <?php else:
        echo '<div class="container text-center" style="padding: 100px 0;"><h3 class="text-white">Nessun dirigente inserito.</h3></div>';
    endif;
    ?>
    </div>

    <!-- PARTNER E SPONSOR -->
    <section class="ps-section container">
        <h2 class="section-title text-white">SPONSOR</h2>
        <?php sport_theme_render_global_sponsors(); ?>
    </section>

</main>

<?php get_footer('societa'); ?>
