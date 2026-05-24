<?php
/**
 * The template for displaying single giocatore
 *
 * @package Sport_Theme
 */

get_header();

while ( have_posts() ) : the_post();

    // Recupera i dati
    $numero      = get_post_meta(get_the_ID(), '_numero_maglia', true);
    $data        = get_post_meta(get_the_ID(), '_data_nascita', true) ?: '-';
    $altezza     = get_post_meta(get_the_ID(), '_altezza', true) ?: '-';
    $peso        = get_post_meta(get_the_ID(), '_peso', true) ?: '-';
    $nazionalita = get_post_meta(get_the_ID(), '_nazionalita', true) ?: '-';
    $eta         = get_post_meta(get_the_ID(), '_eta', true) ?: '-';
    
    // Ruolo in campo (Tassonomia)
    $ruoli = get_the_terms(get_the_ID(), 'ruolo_giocatore');
    $ruolo_str = '-';
    if($ruoli && !is_wp_error($ruoli)) {
        $ruolo_str = sport_theme_get_singular_role($ruoli[0]->name);
    }

    $split_name = sport_theme_get_giocatore_split_name(get_the_ID());
    $nome_riga1 = $split_name['nome'];
    $nome_riga2 = $split_name['cognome'];
    
    $foto_ritratto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : 'https://via.placeholder.com/600x800/111111/FFFFFF?text=' . get_the_title();
    $foto_esultanza = get_post_meta(get_the_ID(), '_foto_esultanza', true);
    $foto = !empty($foto_esultanza) ? $foto_esultanza : $foto_ritratto;

?>

<main id="primary" class="site-main" style="background-color: #000; padding-bottom: 50px;">
    
    <div class="container" style="padding-top: 40px; text-align: right;">
        <a href="<?php echo esc_url(site_url('/rosa')); ?>" style="color: var(--c-primary); text-decoration: none; font-size: 20px; font-weight: 700;"><i class="fa-solid fa-arrow-left"></i> TORNA ALLA ROSA</a>
    </div>

    <!-- Hero Player -->
    <div class="container player-single-layout" style="display: flex; flex-wrap: wrap; margin-top: 40px; background: linear-gradient(to right, #111, #000); border-radius: 4px; overflow: hidden; border: 1px solid #333;">
        
        <!-- Left: Image -->
        <div class="player-single-img" style="flex: 1; min-width: 300px;">
            <img src="<?php echo esc_url($foto); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top center; min-height: 500px; display: block;">
        </div>

        <!-- Right: Dati -->
        <div class="player-single-data" style="flex: 1; min-width: 300px; padding: 50px; display: flex; flex-direction: column; justify-content: center;">
            
            <?php if(!empty($numero)): ?>
            <div style="font-size: 60px; font-weight: 900; color: var(--c-primary); line-height: 1; margin-bottom: 20px;"><?php echo esc_html($numero); ?></div>
            <?php endif; ?>
            
            <h1 style="color: var(--c-white); font-size: 45px; font-weight: 700; line-height: 1.1; margin: 0 0 40px 0; text-transform: capitalize;">
                <?php echo esc_html($nome_riga1); ?><br>
                <span style="color: var(--c-primary); display: block; margin-top: 5px;"><?php echo esc_html($nome_riga2); ?></span>
            </h1>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; font-size: 14px; text-transform: uppercase;">
                <div>
                    <div style="color: #888; margin-bottom: 5px; font-weight: 700; font-size: 11px; letter-spacing: 1px;">DATA DI NASCITA</div>
                    <div style="color: var(--c-white); font-weight: 700; font-size: 16px;"><?php echo esc_html($data); ?></div>
                </div>
                <div>
                    <div style="color: #888; margin-bottom: 5px; font-weight: 700; font-size: 11px; letter-spacing: 1px;">ALTEZZA</div>
                    <div style="color: var(--c-white); font-weight: 700; font-size: 16px;"><?php echo esc_html($altezza); ?></div>
                </div>
                <div>
                    <div style="color: #888; margin-bottom: 5px; font-weight: 700; font-size: 11px; letter-spacing: 1px;">NAZIONALITÀ</div>
                    <div style="color: var(--c-white); font-weight: 700; font-size: 16px;"><?php echo esc_html($nazionalita); ?></div>
                </div>
                <div>
                    <div style="color: #888; margin-bottom: 5px; font-weight: 700; font-size: 11px; letter-spacing: 1px;">PESO</div>
                    <div style="color: var(--c-white); font-weight: 700; font-size: 16px;"><?php echo esc_html($peso); ?></div>
                </div>
                <div>
                    <div style="color: #888; margin-bottom: 5px; font-weight: 700; font-size: 11px; letter-spacing: 1px;">ETÀ</div>
                    <div style="color: var(--c-white); font-weight: 700; font-size: 16px;"><?php echo esc_html($eta); ?></div>
                </div>
                <div>
                    <div style="color: #888; margin-bottom: 5px; font-weight: 700; font-size: 11px; letter-spacing: 1px;">RUOLO</div>
                    <div style="color: var(--c-white); font-weight: 700; font-size: 16px;"><?php echo esc_html($ruolo_str); ?></div>
                </div>
            </div>
            
            <div style="margin-top: 50px;">
                <a href="<?php echo esc_url(site_url('/rosa')); ?>" class="btn-sm btn-primary" style="display: inline-block;">TORNA ALLA ROSA</a>
            </div>

        </div>
    </div>
    
</main>

<?php
endwhile;

get_footer();
