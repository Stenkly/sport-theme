<?php
/**
 * Theme functions and definitions
 *
 * @package Sport_Theme
 */

if ( ! defined( 'SPORT_THEME_VERSION' ) ) {
	// Definisci la versione del tema basata sul timestamp di ultima modifica dello style.css
	// Questo FORZA il browser a scaricare il CSS nuovo ad ogni modifica (Cache Busting)!
	define( 'SPORT_THEME_VERSION', filemtime( get_stylesheet_directory() . '/style.css' ) );
}

/**
 * Setup del tema
 */
function sport_theme_setup() {
	// Aggiungi i link ai feed RSS di default nell'header.
	add_theme_support( 'automatic-feed-links' );

	// Lascia a WordPress la gestione del tag <title>.
	add_theme_support( 'title-tag' );

	// Abilita le immagini in evidenza per gli articoli e le pagine.
	add_theme_support( 'post-thumbnails' );

	// Registra i menu di navigazione
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary Menu', 'sport-theme' ),
			'menu-footer' => esc_html__( 'Footer Menu', 'sport-theme' ),
		)
	);

	// Supporto Logo Personalizzato
	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );

	// Supporto HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
}
add_action( 'after_setup_theme', 'sport_theme_setup' );

/**
 * Enqueue scripts and styles.
 */
// Funzione di supporto per convertire i nomi dei ruoli da plurale a singolare
function sport_theme_get_singular_role($role_name) {
    $role_lower = strtolower(trim($role_name));
    switch ($role_lower) {
        case 'portieri': return 'Portiere';
        case 'difensori': return 'Difensore';
        case 'centrocampisti': return 'Centrocampista';
        case 'attaccanti': return 'Attaccante';
        default: return $role_name;
    }
}

function sport_theme_scripts() {
	wp_enqueue_style( 'sport-theme-style', get_stylesheet_uri(), array(), SPORT_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'sport_theme_scripts' );

/**
 * Area Riservata: Registriamo un ruolo fittizio se non esiste e gestiamo i redirect.
 * In questa prima fase gettiamo le basi.
 */
function sport_theme_add_roles() {
	if ( ! get_role( 'allenatore' ) ) {
		// Crea il ruolo 'allenatore' copiando le capabilities del sottoscrittore
		add_role( 'allenatore', 'Allenatore', array( 'read' => true ) );
	}
}
add_action( 'init', 'sport_theme_add_roles' );

// Nascondiamo l'admin bar per gli allenatori.
add_action('after_setup_theme', 'sport_theme_remove_admin_bar');
function sport_theme_remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

/**
 * Integrazione Form Tally tramite shortcode [tally_form]
 */
function sport_theme_tally_shortcode($atts) {
    // Permette di passare l'URL del form via attributo, es: [tally_form url="https://tally.so/r/tuoformID"]
    $atts = shortcode_atts(
        array(
            'url' => 'https://tally.so/r/INSERISCI_QUI_TALLY_ID', // Default se non fornito
        ), 
        $atts, 'tally_form'
    );
    
    // Script embed di Tally
    wp_enqueue_script('tally-embed', 'https://tally.so/widgets/embed.js', array(), null, true);
    
    // Ritorna l'iframe responsive previsto da Tally
    ob_start();
    ?>
    <iframe data-tally-src="<?php echo esc_url($atts['url']); ?>&transparentBackground=1&dynamicHeight=1" loading="lazy" width="100%" height="200" frameborder="0" marginheight="0" marginwidth="0" title="Modulo d'iscrizione"></iframe>
    <?php
    return ob_get_clean();
}
add_shortcode('tally_form', 'sport_theme_tally_shortcode');

/**
 * Auto-creazione Pagine e Menu!
 * (Questa funzione farà tutto da sola al primo caricamento del sito)
 */
function sport_theme_auto_provision() {
    // Se lo abbiamo già fatto, saltiamo per non appesantire il sito
    if ( get_option( 'sport_theme_provisioned' ) ) {
        return;
    }

    $pages = array( 'News', 'Team', 'Stagione', 'Club', 'Partner', 'Contatti', 'AC Taverne' );
    $page_ids = array();

    // 1. Crea le pagine vuote
    foreach ( $pages as $page_title ) {
        $page = get_page_by_title( $page_title );
        if ( ! $page ) {
            $page_id = wp_insert_post( array(
                'post_title'   => $page_title,
                'post_type'    => 'page',
                'post_status'  => 'publish',
            ) );
            $page_ids[$page_title] = $page_id;
        } else {
            $page_ids[$page_title] = $page->ID;
        }
    }

    // 2. Crea il menu chiamato "Menu Principale" se non esiste
    $menu_name = 'Menu Principale';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    if ( ! $menu_exists ) {
        $menu_id = wp_create_nav_menu( $menu_name );

        // 3. Aggiungi tutte le pagine dentro il menu nell'ordine corretto
        foreach ( $pages as $page_title ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'   => $page_title,
                'menu-item-object-id' => $page_ids[$page_title],
                'menu-item-object'  => 'page',
                'menu-item-status'  => 'publish',
                'menu-item-type'    => 'post_type',
            ) );
        }

        // 4. Assegna magicamente il menu alla barra orizzontale in alto nel tema
        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['menu-1'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    // Ricorda che il lavoro è stato fatto
    update_option( 'sport_theme_provisioned', true );
}
// Lo agganciamo a 'init' così parte non appena ricarichi la pagina
add_action( 'init', 'sport_theme_auto_provision' );

/**
 * Auto-creazione sottomenu "Rosa" e "Staff" sotto "Team"
 */
function sport_theme_auto_provision_v2() {
    if ( get_option( 'sport_theme_provisioned_v2' ) ) {
        return;
    }

    $pages_to_add = array( 'Rosa', 'Staff' );
    $page_ids = array();

    // 1. Assicuriamoci che le pagine esistano
    foreach ( $pages_to_add as $page_title ) {
        $page = get_page_by_title( $page_title );
        if ( ! $page ) {
            $page_id = wp_insert_post( array(
                'post_title'   => $page_title,
                'post_type'    => 'page',
                'post_status'  => 'publish',
            ) );
            $page_ids[$page_title] = $page_id;
        } else {
            $page_ids[$page_title] = $page->ID;
        }
    }

    // 2. Troviamo il menu
    $menu_name = 'Menu Principale';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    if ( $menu_exists ) {
        $menu_id = $menu_exists->term_id;
        
        // 3. Troviamo il menu item di "Team"
        $menu_items = wp_get_nav_menu_items( $menu_id );
        $team_item_id = 0;
        
        if ( $menu_items ) {
            foreach ( $menu_items as $item ) {
                if ( $item->title === 'Team' ) {
                    $team_item_id = $item->ID;
                    break;
                }
            }
        }
        
        // 4. Aggiungiamo i sottomenu assegnando il parent_id = $team_item_id
        if ( $team_item_id ) {
            foreach ( $pages_to_add as $page_title ) {
                // Controlla per sicurezza che il sottomenu non esista già per non duplicarlo
                $already_exists = false;
                foreach ( $menu_items as $item ) {
                    if ( $item->title === $page_title && $item->menu_item_parent == $team_item_id ) {
                        $already_exists = true;
                        break;
                    }
                }

                if ( ! $already_exists ) {
                    wp_update_nav_menu_item( $menu_id, 0, array(
                        'menu-item-title'   => $page_title,
                        'menu-item-object-id' => $page_ids[$page_title],
                        'menu-item-object'  => 'page',
                        'menu-item-status'  => 'publish',
                        'menu-item-type'    => 'post_type',
                        'menu-item-parent-id' => $team_item_id,
                    ) );
                }
            }
        }
    }

    // Segna come completato in modo che non venga rieseguito in futuro
    update_option( 'sport_theme_provisioned_v2', true );
}
add_action( 'init', 'sport_theme_auto_provision_v2' );

/**
 * ----------------------------------------------------
 * CUSTOM POST TYPE: PARTITE (Dinamicizzazione Calendario)
 * ----------------------------------------------------
 */
function sport_theme_cpt_partita() {
    register_post_type('partita', array(
        'labels' => array(
            'name' => 'Partite',
            'singular_name' => 'Partita',
            'add_new_item' => 'Aggiungi Partita',
            'edit_item' => 'Modifica Partita'
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title'), // Il titolo servirà come promemoria, es "Derby di ritorno"
        'menu_icon' => 'dashicons-calendar-alt',
    ));
}
add_action('init', 'sport_theme_cpt_partita');

/**
 * ----------------------------------------------------
 * CUSTOM POST TYPE: FOTO GALLERY (Dinamicizzazione Immagini)
 * ----------------------------------------------------
 */
function sport_theme_cpt_fotogallery() {
    // Tassonomia Categoria Galleria (es. Storia, Progetto...)
    register_taxonomy('categoria_galleria', array('fotogallery'), array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Categorie Foto',
            'singular_name' => 'Categoria Foto',
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'categoria-galleria'),
    ));

    register_post_type('fotogallery', array(
        'labels' => array(
            'name' => 'Foto Gallery',
            'singular_name' => 'Foto',
            'add_new_item' => 'Aggiungi Nuova Foto',
            'edit_item' => 'Modifica Foto'
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail'), // Usa "Immagine in evidenza" per la foto
        'menu_icon' => 'dashicons-camera-alt',
    ));
}
add_action('init', 'sport_theme_cpt_fotogallery');

/**
 * ----------------------------------------------------
 * CUSTOM POST TYPE: GIOCATORI DELLA ROSA
 * ----------------------------------------------------
 */
function sport_theme_cpt_giocatore() {
    register_post_type('giocatore', array(
        'labels' => array(
            'name' => 'Giocatori',
            'singular_name' => 'Giocatore',
            'add_new_item' => 'Aggiungi Giocatore',
            'edit_item' => 'Modifica Giocatore'
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail'), // Titolo = Nome Cognome; Thumb = Ritratto
        'menu_icon' => 'dashicons-groups',
    ));

    register_taxonomy('ruolo_giocatore', 'giocatore', array(
        'labels' => array(
            'name' => 'Ruolo in Campo',
            'singular_name' => 'Ruolo'
        ),
        'hierarchical' => true,
        'public' => true,
        'show_admin_column' => true,
    ));
}
add_action('init', 'sport_theme_cpt_giocatore');

function sport_theme_admin_scripts($hook) {
    global $post_type;
    if ('giocatore' === $post_type || 'page' === $post_type) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'sport_theme_admin_scripts');

function sport_theme_giocatore_metabox() {
    add_meta_box('giocatore_meta', 'Dettagli Calciatore', 'sport_theme_giocatore_meta_html', 'giocatore', 'normal', 'high');
}
add_action('add_meta_boxes', 'sport_theme_giocatore_metabox');

function sport_theme_giocatore_meta_html($post) {
    $nome           = get_post_meta($post->ID, '_nome_calciatore', true);
    $cognome        = get_post_meta($post->ID, '_cognome_calciatore', true);
    $foto_esultanza = get_post_meta($post->ID, '_foto_esultanza', true);
    $numero         = get_post_meta($post->ID, '_numero_maglia', true);
    $data           = get_post_meta($post->ID, '_data_nascita', true);
    $altezza        = get_post_meta($post->ID, '_altezza', true);
    $peso           = get_post_meta($post->ID, '_peso', true);
    $nazionalita    = get_post_meta($post->ID, '_nazionalita', true);
    $htp            = get_post_meta($post->ID, '_htp', true);
    $shop_url       = get_post_meta($post->ID, '_shop_url', true);
    
    wp_nonce_field('salva_giocatore_meta', 'giocatore_meta_nonce');
    ?>
    <style>.g-meta input { width:100%; max-width:400px; margin-bottom:10px; }</style>
    <div class="g-meta">
        <label><b>Nome/i (es. Mario Achille):</b></label><br>
        <input type="text" name="_nome_calciatore" value="<?php echo esc_attr($nome); ?>" placeholder="Lascia vuoto per rilevare dal titolo"><br>
        
        <label><b>Cognome/i (es. Casanova):</b></label><br>
        <input type="text" name="_cognome_calciatore" value="<?php echo esc_attr($cognome); ?>" placeholder="Lascia vuoto per rilevare dal titolo"><br>

        <label><b>Foto Esultanza / Azione (mostrata nella card della Rosa):</b></label><br>
        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 15px;">
            <input type="text" id="foto_esultanza_url" name="_foto_esultanza" value="<?php echo esc_url($foto_esultanza); ?>" style="width:100%; max-width:300px; margin-bottom:0;" placeholder="Seleziona o carica un'immagine...">
            <button type="button" id="upload_esultanza_btn" class="button button-secondary">Scegli Immagine</button>
        </div>
        <div id="esultanza_preview" style="margin-top: 5px; margin-bottom: 15px;">
            <?php if (!empty($foto_esultanza)) : ?>
                <img src="<?php echo esc_url($foto_esultanza); ?>" style="max-width: 150px; height: auto; border: 1px solid #ccc; display: block;">
            <?php endif; ?>
        </div>

        <label><b>Numero Maglia (es. 10):</b></label><br>
        <input type="number" name="_numero_maglia" value="<?php echo esc_attr($numero); ?>">
        
        <br><label><b>Data di Nascita (es. 01.01.2000):</b></label><br>
        <input type="text" name="_data_nascita" value="<?php echo esc_attr($data); ?>">
        
        <br><label><b>Altezza (es. 185 cm):</b></label><br>
        <input type="text" name="_altezza" value="<?php echo esc_attr($altezza); ?>">
        
        <br><label><b>Peso (es. 78 kg):</b></label><br>
        <input type="text" name="_peso" value="<?php echo esc_attr($peso); ?>">
        
        <br><label><b>Nazionalità (es. Svizzera):</b></label><br>
        <input type="text" name="_nazionalita" value="<?php echo esc_attr($nazionalita); ?>">
        
        <br><label><b>HTP (es. SI/NO):</b></label><br>
        <input type="text" name="_htp" value="<?php echo esc_attr($htp); ?>">
        
        <br><label><b>URL Shop Personale (opzionale):</b></label><br>
        <input type="text" name="_shop_url" value="<?php echo esc_attr($shop_url); ?>" placeholder="https://...">

        <br><label><b>Zoom Foto in Evidenza (es. cover, 110%, 120%, 150% - default: cover):</b></label><br>
        <input type="text" name="_zoom_foto" value="<?php echo esc_attr(get_post_meta($post->ID, '_zoom_foto', true) ?: 'cover'); ?>" style="width:100%; max-width:400px; margin-bottom:10px;"><br>
        
        <br><label><b>Allineamento Foto (es. center top, center 10%, center 20% - default: center top):</b></label><br>
        <input type="text" name="_allineamento_foto" value="<?php echo esc_attr(get_post_meta($post->ID, '_allineamento_foto', true) ?: 'center top'); ?>" style="width:100%; max-width:400px; margin-bottom:10px;">
    </div>
    
    <script>
    jQuery(document).ready(function($){
        $('#upload_esultanza_btn').click(function(e) {
            e.preventDefault();
            var imageFrame = wp.media({
                title: 'Seleziona Foto Esultanza',
                multiple: false,
                library: { type: 'image' }
            }).on('select', function() {
                var attachment = imageFrame.state().get('selection').first().toJSON();
                $('#foto_esultanza_url').val(attachment.url);
                
                var preview = $('#esultanza_preview');
                preview.html('<img src="' + attachment.url + '" style="max-width:150px; height:auto; border:1px solid #ccc; display:block;">');
            }).open();
        });
    });
    </script>
    <?php
}

function sport_theme_salva_giocatore_meta($post_id) {
    if (!isset($_POST['giocatore_meta_nonce']) || !wp_verify_nonce($_POST['giocatore_meta_nonce'], 'salva_giocatore_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    $fields = ['_nome_calciatore', '_cognome_calciatore', '_foto_esultanza', '_numero_maglia', '_data_nascita', '_altezza', '_peso', '_nazionalita', '_htp', '_shop_url', '_zoom_foto', '_allineamento_foto'];
    foreach($fields as $field) {
        if(isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_giocatore', 'sport_theme_salva_giocatore_meta');

/**
 * Ritorna nome e cognome separati per il giocatore, supportando i campi personalizzati
 * per distinguere nomi e cognomi composti.
 */
function sport_theme_get_giocatore_split_name($post_id) {
    $nome_custom = get_post_meta($post_id, '_nome_calciatore', true);
    $cognome_custom = get_post_meta($post_id, '_cognome_calciatore', true);
    
    if (!empty($nome_custom) || !empty($cognome_custom)) {
        return array(
            'nome' => $nome_custom,
            'cognome' => $cognome_custom
        );
    }
    
    $title = get_the_title($post_id);
    $parti = explode(' ', $title, 2);
    return array(
        'nome' => $parti[0],
        'cognome' => isset($parti[1]) ? $parti[1] : ''
    );
}

/**
 * ----------------------------------------------------
 * CUSTOM POST TYPE: MEMBRI DELLO STAFF
 * ----------------------------------------------------
 */
function sport_theme_cpt_staff() {
    register_post_type('membro_staff', array(
        'labels' => array(
            'name' => 'Staff',
            'singular_name' => 'Membro Staff',
            'add_new_item' => 'Aggiungi Membro Staff',
            'edit_item' => 'Modifica Membro'
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail', 'page-attributes'), // Titolo = Nome Cognome; Thumb = Ritratto; Attributes = Ordinamento
        'menu_icon' => 'dashicons-businessman',
    ));
}
add_action('init', 'sport_theme_cpt_staff');

/**
 * ----------------------------------------------------
 * CUSTOM POST TYPE: DIRIGENTE (Organigramma)
 * ----------------------------------------------------
 */
function sport_theme_cpt_dirigente() {
    register_post_type('dirigente', array(
        'labels' => array(
            'name' => 'Dirigenza',
            'singular_name' => 'Dirigente',
            'add_new_item' => 'Aggiungi Dirigente',
            'edit_item' => 'Modifica Dirigente'
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail', 'editor', 'page-attributes'),
        'menu_icon' => 'dashicons-groups',
    ));
}
add_action('init', 'sport_theme_cpt_dirigente');



function sport_theme_dirigente_metabox() {
    add_meta_box('dirigente_meta', 'Dettagli Dirigente', 'sport_theme_dirigente_meta_html', 'dirigente', 'normal', 'high');
}
add_action('add_meta_boxes', 'sport_theme_dirigente_metabox');

function sport_theme_dirigente_meta_html($post) {
    $ruolo_spec = get_post_meta($post->ID, '_ruolo_specifico', true);
    $sezione = get_post_meta($post->ID, '_sezione_comitato', true);
    $area = get_post_meta($post->ID, '_area_organigramma', true);
    $zoom_foto = get_post_meta($post->ID, '_zoom_foto', true) ?: 'cover';
    $allineamento_foto = get_post_meta($post->ID, '_allineamento_foto', true) ?: 'center top';
    
    if(empty($sezione)) $sezione = 'prima-squadra'; // Default

    wp_nonce_field('salva_dirigente_meta', 'dirigente_meta_nonce');
    ?>
    <p><label><b>Qualifica/Ruolo (es. Presidente Onorario):</b></label><br>
    <input type="text" name="_ruolo_specifico" value="<?php echo esc_attr($ruolo_spec); ?>" style="width:100%; max-width:400px;"></p>
    
    <p><label><b>Area (es. AREA CORPORATE) [Opzionale, serve per raggrupparli]:</b></label><br>
    <input type="text" name="_area_organigramma" value="<?php echo esc_attr($area); ?>" style="width:100%; max-width:400px;"></p>
    
    <p><label><b>Sezione Comitato:</b></label><br>
    <select name="_sezione_comitato" style="width:100%; max-width:400px;">
        <option value="prima-squadra" <?php selected($sezione, 'prima-squadra'); ?>>Comitato Prima Squadra</option>
        <option value="settore-giovanile" <?php selected($sezione, 'settore-giovanile'); ?>>Comitato Settore Giovanile</option>
    </select>
    </p>

    <p><label><b>Zoom Foto in Evidenza (es. cover, 110%, 120%, 150% - default: cover):</b></label><br>
    <input type="text" name="_zoom_foto" value="<?php echo esc_attr($zoom_foto); ?>" style="width:100%; max-width:400px;"></p>
    
    <p><label><b>Allineamento Foto (es. center top, center 10%, center 20% - default: center top):</b></label><br>
    <input type="text" name="_allineamento_foto" value="<?php echo esc_attr($allineamento_foto); ?>" style="width:100%; max-width:400px;"></p>
    <?php
}

function sport_theme_salva_dirigente_meta($post_id) {
    if (!isset($_POST['dirigente_meta_nonce']) || !wp_verify_nonce($_POST['dirigente_meta_nonce'], 'salva_dirigente_meta')) return;
    
    if (isset($_POST['_ruolo_specifico'])) update_post_meta($post_id, '_ruolo_specifico', sanitize_text_field($_POST['_ruolo_specifico']));
    if (isset($_POST['_sezione_comitato'])) update_post_meta($post_id, '_sezione_comitato', sanitize_text_field($_POST['_sezione_comitato']));
    if (isset($_POST['_area_organigramma'])) update_post_meta($post_id, '_area_organigramma', sanitize_text_field($_POST['_area_organigramma']));
    if (isset($_POST['_zoom_foto'])) update_post_meta($post_id, '_zoom_foto', sanitize_text_field($_POST['_zoom_foto']));
    if (isset($_POST['_allineamento_foto'])) update_post_meta($post_id, '_allineamento_foto', sanitize_text_field($_POST['_allineamento_foto']));
}
add_action('save_post_dirigente', 'sport_theme_salva_dirigente_meta');
add_action('init', 'sport_theme_cpt_staff');

function sport_theme_staff_metabox() {
    add_meta_box('staff_meta', 'Dettagli Staff', 'sport_theme_staff_meta_html', 'membro_staff', 'normal', 'high');
}
add_action('add_meta_boxes', 'sport_theme_staff_metabox');

function sport_theme_staff_meta_html($post) {
    $ruolo_spec = get_post_meta($post->ID, '_ruolo_specifico', true);
    wp_nonce_field('salva_staff_meta', 'staff_meta_nonce');
    ?>
    <p><label><b>Qualifica/Ruolo (es. Allenatore, Preparatore):</b></label><br>
    <input type="text" name="_ruolo_specifico" value="<?php echo esc_attr($ruolo_spec); ?>" style="width:100%; max-width:400px;"></p>

    <p><label><b>Zoom Foto in Evidenza (es. cover, 110%, 120%, 150% - default: cover):</b></label><br>
    <input type="text" name="_zoom_foto" value="<?php echo esc_attr(get_post_meta($post->ID, '_zoom_foto', true) ?: 'cover'); ?>" style="width:100%; max-width:400px;"></p>
    
    <p><label><b>Allineamento Foto (es. center top, center 10%, center 20% - default: center top):</b></label><br>
    <input type="text" name="_allineamento_foto" value="<?php echo esc_attr(get_post_meta($post->ID, '_allineamento_foto', true) ?: 'center top'); ?>" style="width:100%; max-width:400px;"></p>
    <?php
}

function sport_theme_salva_staff_meta($post_id) {
    if (!isset($_POST['staff_meta_nonce']) || !wp_verify_nonce($_POST['staff_meta_nonce'], 'salva_staff_meta')) return;
    if (isset($_POST['_ruolo_specifico'])) update_post_meta($post_id, '_ruolo_specifico', sanitize_text_field($_POST['_ruolo_specifico']));
    if (isset($_POST['_zoom_foto'])) update_post_meta($post_id, '_zoom_foto', sanitize_text_field($_POST['_zoom_foto']));
    if (isset($_POST['_allineamento_foto'])) update_post_meta($post_id, '_allineamento_foto', sanitize_text_field($_POST['_allineamento_foto']));
}
add_action('save_post_membro_staff', 'sport_theme_salva_staff_meta');

function sport_theme_partita_metabox() {
    add_meta_box('partita_meta', 'Dettagli Partita', 'sport_theme_partita_meta_html', 'partita', 'normal', 'high');
}
add_action('add_meta_boxes', 'sport_theme_partita_metabox');

function sport_theme_partita_meta_html($post) {
    $data_p = get_post_meta($post->ID, '_data_partita', true);
    $ora_p = get_post_meta($post->ID, '_ora_partita', true);
    $stadio = get_post_meta($post->ID, '_stadio', true);
    $avversario = get_post_meta($post->ID, '_avversario', true);
    $logo_avversario = get_post_meta($post->ID, '_logo_avversario', true);
    $in_casa = get_post_meta($post->ID, '_in_casa', true);
    $risultato = get_post_meta($post->ID, '_risultato', true);
    
    wp_nonce_field('salva_partita_meta', 'partita_meta_nonce');
    ?>
    <style>.p-meta input, .p-meta select { width: 100%; max-width: 400px; margin-bottom: 15px; }</style>
    <div class="p-meta">
        <label><b>Data Partita (es. SAB. 10.02.2024):</b></label><br>
        <input type="text" name="_data_partita" value="<?php echo esc_attr($data_p); ?>">
        
        <br><label><b>Ora (es. 19:00):</b></label><br>
        <input type="text" name="_ora_partita" value="<?php echo esc_attr($ora_p); ?>">
        
        <br><label><b>Stadio/Campo:</b></label><br>
        <input type="text" name="_stadio" value="<?php echo esc_attr($stadio); ?>">

        <br><label><b>DOVE SI GIOCA?</b></label><br>
        <select name="_in_casa">
            <option value="1" <?php selected($in_casa, '1'); ?>>In Casa (Taverne sulla sinistra)</option>
            <option value="0" <?php selected($in_casa, '0'); ?>>In Trasferta (Taverne sulla destra)</option>
        </select>
        
        <br><label><b>Nome Avversario (es. AC Bellinzona):</b></label><br>
        <input type="text" name="_avversario" value="<?php echo esc_attr($avversario); ?>">
        
        <br><label><b>URL Logo Avversario (copia il link da "Media" - opzionale ma consigliato):</b></label><br>
        <input type="text" name="_logo_avversario" value="<?php echo esc_attr($logo_avversario); ?>" placeholder="https://tuosito.com/wp-content/uploads/.../logo.png">
        
        <br><label><b>RISULTATO:</b></label><br>
        <input type="text" name="_risultato" value="<?php echo esc_attr($risultato); ?>" placeholder="es. 1 - 1" style="background:#fefed4; font-weight:bold;">
        <p style="font-size:12px; color:#666; margin-top:-10px;">SE LASCI VUOTO apparirà nei "Prossimi Incontri". Se lo COMPILI andrà automaticamente in "Risultati".</p>
    </div>
    <?php
}

function sport_theme_salva_partita_meta($post_id) {
    if (!isset($_POST['partita_meta_nonce']) || !wp_verify_nonce($_POST['partita_meta_nonce'], 'salva_partita_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    $fields = ['_data_partita', '_ora_partita', '_stadio', '_avversario', '_logo_avversario', '_in_casa', '_risultato'];
    foreach($fields as $field) {
        if(isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_partita', 'sport_theme_salva_partita_meta');

/**
 * ----------------------------------------------------
 * CUSTOM POST TYPE: SPONSOR
 * ----------------------------------------------------
 */
function sport_theme_cpt_sponsor() {
    register_post_type('sponsor', array(
        'labels' => array(
            'name' => 'Sponsor',
            'singular_name' => 'Sponsor',
            'add_new_item' => 'Aggiungi Sponsor',
            'edit_item' => 'Modifica Sponsor'
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail'),
        'menu_icon' => 'dashicons-money-alt',
    ));
}
add_action('init', 'sport_theme_cpt_sponsor');

function sport_theme_sponsor_metabox() {
    add_meta_box('sponsor_meta', 'Dati Sponsor', 'sport_theme_sponsor_meta_html', 'sponsor', 'normal', 'high');
}
add_action('add_meta_boxes', 'sport_theme_sponsor_metabox');

function sport_theme_sponsor_meta_html($post) {
    $livello = get_post_meta($post->ID, '_livello_sponsor', true) ?: 'partner';
    $destinazione = get_post_meta($post->ID, '_destinazione_sponsor', true) ?: 'entrambi';
    $sito_url = get_post_meta($post->ID, '_sito_url', true);
    wp_nonce_field('salva_sponsor_meta', 'sponsor_meta_nonce');
    ?>
    <p><label><b>Livello Sponsor:</b></label><br>
    <select name="_livello_sponsor" style="width:100%; max-width:400px;">
        <option value="main" <?php selected($livello, 'main'); ?>>Main Sponsor</option>
        <option value="partner" <?php selected($livello, 'partner'); ?>>Partner</option>
    </select></p>
    <p><label><b>Destinazione Sponsor:</b></label><br>
    <select name="_destinazione_sponsor" style="width:100%; max-width:400px;">
        <option value="entrambi" <?php selected($destinazione, 'entrambi'); ?>>Entrambi</option>
        <option value="prima_squadra" <?php selected($destinazione, 'prima_squadra'); ?>>Prima Squadra</option>
        <option value="societa" <?php selected($destinazione, 'societa'); ?>>Società / Club</option>
    </select></p>
    <p><label><b>URL Sito Web (opzionale):</b></label><br>
    <input type="text" name="_sito_url" value="<?php echo esc_attr($sito_url); ?>" placeholder="https://..." style="width:100%; max-width:400px;"></p>
    <?php
}

function sport_theme_salva_sponsor_meta($post_id) {
    if (!isset($_POST['sponsor_meta_nonce']) || !wp_verify_nonce($_POST['sponsor_meta_nonce'], 'salva_sponsor_meta')) return;
    if (isset($_POST['_livello_sponsor'])) update_post_meta($post_id, '_livello_sponsor', sanitize_text_field($_POST['_livello_sponsor']));
    if (isset($_POST['_destinazione_sponsor'])) update_post_meta($post_id, '_destinazione_sponsor', sanitize_text_field($_POST['_destinazione_sponsor']));
    if (isset($_POST['_sito_url'])) update_post_meta($post_id, '_sito_url', esc_url_raw($_POST['_sito_url']));
}
add_action('save_post_sponsor', 'sport_theme_salva_sponsor_meta');

/**
 * ----------------------------------------------------
 * CUSTOM POST TYPE: EVENTI (Sezione AC Taverne)
 * ----------------------------------------------------
 */
function sport_theme_cpt_evento() {
    register_post_type('evento', array(
        'labels' => array(
            'name'          => 'Eventi',
            'singular_name' => 'Evento',
            'add_new_item'  => 'Aggiungi Evento',
            'edit_item'     => 'Modifica Evento',
            'all_items'     => 'Tutti gli Eventi',
            'search_items'  => 'Cerca Eventi',
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'eventi'),
        'supports'     => array('title', 'thumbnail', 'editor', 'excerpt'),
        'menu_icon'    => 'dashicons-calendar',
        'show_in_rest' => true,
        'taxonomies'   => array('category'),
    ));
    // Forza il refresh dei permalink temporaneamente per evitare 404
    if ( get_option('sport_theme_flush_rewrite_rules') !== 'yes' ) {
        flush_rewrite_rules();
        update_option('sport_theme_flush_rewrite_rules', 'yes');
    }
}
add_action('init', 'sport_theme_cpt_evento');

/*
 * ----------------------------------------------------
 * CUSTOM POST TYPE: SEZIONI (SQUADRE SOC. TAVERNE)
 * ----------------------------------------------------
 */
function sport_theme_cpt_squadra_sezione() {
    // Tassonomia Categoria (Attivi, Allievi, Femminile)
    register_taxonomy('categoria_sezione', array('squadra_sezione'), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => 'Categorie Sezioni',
            'singular_name'     => 'Categoria Sezione',
            'search_items'      => 'Cerca Categorie',
            'all_items'         => 'Tutte le Categorie',
            'parent_item'       => 'Categoria Genitore',
            'parent_item_colon' => 'Categoria Genitore:',
            'edit_item'         => 'Modifica Categoria',
            'update_item'       => 'Aggiorna Categoria',
            'add_new_item'      => 'Aggiungi Nuova Categoria',
            'new_item_name'     => 'Nuovo Nome Categoria',
            'menu_name'         => 'Categorie Sezioni',
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'categoria-sezione' ),
        'show_in_rest'      => true,
    ));

    // Custom Post Type
    register_post_type('squadra_sezione', array(
        'labels' => array(
            'name'          => 'Sezioni (Squadre)',
            'singular_name' => 'Squadra Sezione',
            'add_new_item'  => 'Aggiungi Squadra',
            'edit_item'     => 'Modifica Squadra',
            'all_items'     => 'Tutte le Squadre',
            'search_items'  => 'Cerca Squadre',
        ),
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => array('slug' => 'sezione-squadra'),
        'supports'     => array('title', 'editor', 'thumbnail', 'page-attributes'), // page-attributes per l'ordinamento (menu_order)
        'menu_icon'    => 'dashicons-groups',
        'show_in_rest' => true,
        'taxonomies'   => array('categoria_sezione'),
    ));
}
add_action('init', 'sport_theme_cpt_squadra_sezione');

function sport_theme_evento_metabox() {
    add_meta_box('evento_meta', 'Dettagli Evento', 'sport_theme_evento_meta_html', 'evento', 'normal', 'high');
}
add_action('add_meta_boxes', 'sport_theme_evento_metabox');

function sport_theme_evento_meta_html($post) {
    $data_evento = get_post_meta($post->ID, '_data_evento', true);
    $ora_evento  = get_post_meta($post->ID, '_ora_evento', true);
    $luogo       = get_post_meta($post->ID, '_luogo_evento', true);
    $tipo        = get_post_meta($post->ID, '_tipo_evento', true);
    wp_nonce_field('salva_evento_meta', 'evento_meta_nonce');
    ?>
    <style>.ev-meta label { display: block; margin-top: 12px; font-weight: bold; } .ev-meta input, .ev-meta select { width: 100%; max-width: 400px; }</style>
    <div class="ev-meta">
        <label>Data Evento (es. 28.02.2025):</label>
        <input type="date" name="_data_evento" value="<?php echo esc_attr($data_evento); ?>">

        <label>Ora (es. 19:00):</label>
        <input type="time" name="_ora_evento" value="<?php echo esc_attr($ora_evento); ?>">

        <label>Luogo:</label>
        <input type="text" name="_luogo_evento" value="<?php echo esc_attr($luogo); ?>" placeholder="es. Centro Sportivo Taverne">

        <label>Tipo Evento:</label>
        <select name="_tipo_evento">
            <option value="torneo" <?php selected($tipo, 'torneo'); ?>>Torneo</option>
            <option value="cena" <?php selected($tipo, 'cena'); ?>>Cena Sociale</option>
            <option value="festa" <?php selected($tipo, 'festa'); ?>>Festa</option>
            <option value="assemblea" <?php selected($tipo, 'assemblea'); ?>>Assemblea</option>
            <option value="altro" <?php selected($tipo, 'altro'); ?>>Altro</option>
        </select>
    </div>
    <?php
}

function sport_theme_salva_evento_meta($post_id) {
    if (!isset($_POST['evento_meta_nonce']) || !wp_verify_nonce($_POST['evento_meta_nonce'], 'salva_evento_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    $fields = ['_data_evento', '_ora_evento', '_luogo_evento', '_tipo_evento'];
    foreach ($fields as $f) {
        if (isset($_POST[$f])) {
            update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
        }
    }
}
add_action('save_post_evento', 'sport_theme_salva_evento_meta');

/**
 * ----------------------------------------------------
 * FUNZIONE GLOBALE: RENDER SPONSOR
 * ----------------------------------------------------
 */
function sport_theme_render_global_sponsors() {
    $is_societa_page = is_page('ac-taverne') || is_page('societa') || is_page('la-societa') || is_page('comitato') || is_page('club-dei-100') || is_page('area-allenatori') || is_page('scuola-calcio') || is_page('infrastruttura') || is_page('news-societa') || is_page('iscritti') || is_page('contatti-societa') || is_page('sezioni') ||
        is_page_template('template-home-societa.php') || 
        is_page_template('template-la-societa.php') || 
        is_page_template('template-comitato-societa.php') || 
        is_page_template('template-club-dei-100.php') || 
        is_page_template('template-allenatori.php') || 
        is_page_template('template-scuola-calcio.php') || 
        is_page_template('template-infrastruttura.php') || 
        is_page_template('template-news-societa.php') || 
        is_page_template('template-iscritti.php') || 
        is_page_template('template-contatti-societa.php') || 
        is_page_template('template-sezioni.php') ||
        (is_singular('evento') && has_category('settore-giovanile')) ||
        (is_singular('post') && has_category('settore-giovanile'));

    if ( $is_societa_page ) {
        $sponsor_query = new WP_Query([
            'post_type' => 'sponsor',
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_destinazione_sponsor',
                    'value' => 'societa',
                    'compare' => '='
                ],
                [
                    'key' => '_destinazione_sponsor',
                    'value' => 'entrambi',
                    'compare' => '='
                ]
            ]
        ]);
        
        if ($sponsor_query->have_posts()) {
            echo '<div class="hs-sponsor-row">';
            while ($sponsor_query->have_posts()) {
                $sponsor_query->the_post();
                $sito = get_post_meta(get_the_ID(), '_sito_url', true);
                $logo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
                if ($logo) {
                    echo '<a href="' . ($sito ? esc_url($sito) : '#') . '" target="_blank">';
                    echo '<img src="' . esc_url($logo) . '" alt="' . esc_attr(get_the_title()) . '">';
                    echo '</a>';
                }
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p style="color:#666;">Nessun partner inserito per la Società.</p>';
        }
    } else {
        $sponsor_query = new WP_Query([
            'post_type' => 'sponsor',
            'posts_per_page' => -1,
            'meta_query' => [
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
        ]);

        if ($sponsor_query->have_posts()) {
            echo '<div class="ps-sponsors">';
            while ($sponsor_query->have_posts()) {
                $sponsor_query->the_post();
                $sito = get_post_meta(get_the_ID(), '_sito_url', true);
                $logo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
                if ($logo) {
                    if ($sito) {
                        echo '<a href="' . esc_url($sito) . '" target="_blank">';
                    }
                    echo '<img src="' . esc_url($logo) . '" alt="' . esc_attr(get_the_title()) . '">';
                    if ($sito) {
                        echo '</a>';
                    }
                }
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p style="color:#666;">Nessun partner inserito.</p>';
        }
    }
}

/**
 * Auto-assign templates to pages to ensure custom designs are used!
 */
function sport_theme_auto_assign_templates() {
    if ( get_option( 'sport_theme_templates_assigned_v5' ) ) {
        return;
    }

    $map = array(
        'Prima Squadra' => 'template-prima-squadra.php',
        'Rosa'          => 'template-rosa.php',
        'Staff'         => 'template-staff.php',
        'News'          => 'template-news.php',
        'Stagione'      => 'template-stagione.php',
        'Partner'       => 'template-partner.php',
        'Sponsor'       => 'template-partner.php',
        'Organigramma'  => 'template-organigramma.php',
        'Storia'        => 'template-club-page.php',
        'Progetto sportivo' => 'template-club-page.php',
        'Contatti'      => 'template-contatti.php',
        'Home Società'  => 'template-home-societa.php',
        // Pagine segnaposto sezione AC Taverne (usano page.php fino a sviluppo)
        'Società'       => '',
        'Scuola Calcio' => 'template-scuola-calcio.php',
        'Allievi'       => '',
        'Femminile'     => '',
        'Infrastruttura'=> '',
        'Iscritti'      => '',
    );

    foreach ( $map as $page_title => $template_file ) {
        // Se la pagina non esiste, la creiamo!
        $page = get_page_by_title( $page_title );
        if ( ! $page ) {
            $page_id = wp_insert_post( array(
                'post_title'     => $page_title,
                'post_name'      => sanitize_title($page_title),
                'post_type'      => 'page',
                'post_status'    => 'publish',
            ) );
            if ( $page_id && ! is_wp_error( $page_id ) ) {
                update_post_meta( $page_id, '_wp_page_template', $template_file );
            }
        } else {
            update_post_meta( $page->ID, '_wp_page_template', $template_file );
        }
    }

    update_option( 'sport_theme_templates_assigned_v5', true );
}
add_action( 'init', 'sport_theme_auto_assign_templates' );

/**
 * Forza l'assegnazione del template Scuola Calcio.
 */
function sport_theme_force_scuola_calcio_template() {
    if ( get_option( 'sport_theme_scuola_calcio_template_v1' ) ) {
        return;
    }
    
    $page = get_page_by_title( 'Scuola Calcio' );
    if ( $page ) {
        update_post_meta( $page->ID, '_wp_page_template', 'template-scuola-calcio.php' );
    }
    
    update_option( 'sport_theme_scuola_calcio_template_v1', true );
}
add_action( 'init', 'sport_theme_force_scuola_calcio_template' );

/**
 * Auto-cleanup del menu "Team" per rimuovere i dropdown e puntare alla pagina Rosa
 */
function sport_theme_auto_provision_v3() {
    if ( get_option( 'sport_theme_provisioned_v4' ) ) {
        return;
    }

    $menu_name = 'Menu Principale';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    if ( $menu_exists ) {
        $menu_id = $menu_exists->term_id;
        $menu_items = wp_get_nav_menu_items( $menu_id );
        
        $team_item_id = 0;
        $club_item_id = 0;
        
        // 1. Trova l'elemento "Team" e "Club"
        if ( $menu_items ) {
            foreach ( $menu_items as $item ) {
                if ( $item->title === 'Team' || strtolower($item->title) === 'team' || strtolower($item->post_title) === 'team' ) {
                    $team_item_id = $item->ID;
                }
                if ( $item->title === 'Club' || strtolower($item->title) === 'club' || strtolower($item->post_title) === 'club' ) {
                    $club_item_id = $item->ID;
                }
            }
        }
        
        // 2. Rimuovi i sotto-menu di "Team" scansionando tutti gli elementi
        if ( $team_item_id ) {
            foreach ( $menu_items as $item ) {
                if ( $item->menu_item_parent == $team_item_id ) {
                    wp_delete_post( $item->ID, true );
                }
            }
            
            // 3. Fai puntare l'elemento "Team" alla pagina "Rosa"
            $rosa_page = get_page_by_title( 'Rosa' );
            if ( $rosa_page ) {
                wp_update_nav_menu_item( $menu_id, $team_item_id, array(
                    'menu-item-title'   => 'Team',
                    'menu-item-object-id' => $rosa_page->ID,
                    'menu-item-object'  => 'page',
                    'menu-item-status'  => 'publish',
                    'menu-item-type'    => 'post_type',
                    'menu-item-parent-id' => 0, 
                ) );
            }
        }

        // 4. Stessa cosa per "Club" -> punta a "Organigramma"
        if ( $club_item_id ) {
            foreach ( $menu_items as $item ) {
                if ( $item->menu_item_parent == $club_item_id ) {
                    wp_delete_post( $item->ID, true );
                }
            }
            $orga_page = get_page_by_title( 'Organigramma' );
            if ( $orga_page ) {
                wp_update_nav_menu_item( $menu_id, $club_item_id, array(
                    'menu-item-title'   => 'Club',
                    'menu-item-object-id' => $orga_page->ID,
                    'menu-item-object'  => 'page',
                    'menu-item-status'  => 'publish',
                    'menu-item-type'    => 'post_type',
                    'menu-item-parent-id' => 0, 
                ) );
            }
        }
    }

    // Segna come completato in modo che non venga rieseguito
    update_option( 'sport_theme_provisioned_v4', true );
}
add_action( 'init', 'sport_theme_auto_provision_v3' );

/**
 * V5 - Ripristina i menu a tendina per Club nel caso l'utente abbia un WP Menu reale
 */
function sport_theme_auto_provision_v5() {
    if ( get_option( 'sport_theme_provisioned_v5' ) ) {
        return;
    }

    $menu_name = 'Menu Principale';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    if ( $menu_exists ) {
        $menu_id = $menu_exists->term_id;
        $menu_items = wp_get_nav_menu_items( $menu_id );
        
        $club_item_id = 0;
        
        if ( $menu_items ) {
            foreach ( $menu_items as $item ) {
                if ( $item->title === 'Club' || strtolower($item->title) === 'club' || strtolower($item->post_title) === 'club' ) {
                    $club_item_id = $item->ID;
                }
            }
        }

        if ( $club_item_id ) {
            // Eliminiamo eventuali figli rimasti per fare pulizia
            foreach ( $menu_items as $item ) {
                if ( $item->menu_item_parent == $club_item_id ) {
                    wp_delete_post( $item->ID, true );
                }
            }
            
            // Inserisci Organigramma come figlio
            $orga = get_page_by_title( 'Organigramma' );
            if($orga) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'   => 'Organigramma',
                    'menu-item-object-id' => $orga->ID,
                    'menu-item-object'  => 'page',
                    'menu-item-status'  => 'publish',
                    'menu-item-type'    => 'post_type',
                    'menu-item-parent-id' => $club_item_id, 
                ) );
            }

            // Inserisci Storia come figlio
            $storia = get_page_by_title( 'Storia' );
            if($storia) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'   => 'Storia del Club',
                    'menu-item-object-id' => $storia->ID,
                    'menu-item-object'  => 'page',
                    'menu-item-status'  => 'publish',
                    'menu-item-type'    => 'post_type',
                    'menu-item-parent-id' => $club_item_id, 
                ) );
            }

            // Inserisci Progetto sportivo come figlio
            $prog = get_page_by_title( 'Progetto sportivo' );
            if($prog) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'   => 'Progetto sportivo',
                    'menu-item-object-id' => $prog->ID,
                    'menu-item-object'  => 'page',
                    'menu-item-status'  => 'publish',
                    'menu-item-type'    => 'post_type',
                    'menu-item-parent-id' => $club_item_id, 
                ) );
            }
        }
    }

    update_option( 'sport_theme_provisioned_v5', true );
}
add_action( 'init', 'sport_theme_auto_provision_v5' );

/**
 * V6 - Rimuove il sotto-menu da Club nel menu di WordPress (solo per desktop, responsive è gestito staticamente)
 */
function sport_theme_auto_provision_v6() {
    if ( get_option( 'sport_theme_provisioned_v6' ) ) {
        return;
    }

    $menu_name = 'Menu Principale';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    if ( $menu_exists ) {
        $menu_id = $menu_exists->term_id;
        $menu_items = wp_get_nav_menu_items( $menu_id );
        
        $club_item_id = 0;
        
        if ( $menu_items ) {
            foreach ( $menu_items as $item ) {
                if ( $item->title === 'Club' || strtolower($item->title) === 'club' || strtolower($item->post_title) === 'club' ) {
                    $club_item_id = $item->ID;
                }
            }
        }

        if ( $club_item_id ) {
            // Eliminiamo tutti i figli di Club (Organigramma, Storia del Club, Progetto sportivo)
            foreach ( $menu_items as $item ) {
                if ( $item->menu_item_parent == $club_item_id ) {
                    wp_delete_post( $item->ID, true );
                }
            }
            
            // Fai puntare l'elemento "Club" alla pagina "Organigramma"
            $orga_page = get_page_by_title( 'Organigramma' );
            if ( $orga_page ) {
                wp_update_nav_menu_item( $menu_id, $club_item_id, array(
                    'menu-item-title'   => 'Club',
                    'menu-item-object-id' => $orga_page->ID,
                    'menu-item-object'  => 'page',
                    'menu-item-status'  => 'publish',
                    'menu-item-type'    => 'post_type',
                    'menu-item-parent-id' => 0, 
                ) );
            }
        }
    }

    update_option( 'sport_theme_provisioned_v6', true );
}
add_action( 'init', 'sport_theme_auto_provision_v6' );

/**
 * Inserisce Dummy Content per far vedere subito il mockup "Storia" e "Progetto sportivo"
 */
function sport_theme_populate_dummy_content() {
    if ( get_option( 'sport_theme_dummy_content_v1' ) ) {
        return;
    }
    
    $lorem = '
<h2>IDENTITÀ DEL CLUB</h2>
<h3>LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.</h3>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<h2>RUOLO DELLA PRIMA SQUADRA</h2>
<h3>LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.</h3>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

<h2>EVOLUZIONE NEL TEMPO</h2>
<h3>LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.</h3>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p>
';

    $pages_to_update = ['Storia', 'Progetto sportivo'];
    foreach ($pages_to_update as $title) {
        $p = get_page_by_title($title);
        // Aggiorniamo solo se è vuoto per non schiacciare quello che magari hai già scritto!
        if ($p && empty($p->post_content)) {
            wp_update_post(array(
                'ID' => $p->ID,
                'post_content' => $lorem,
                'post_excerpt' => 'LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA.'
            ));
        }
    }
    
    update_option( 'sport_theme_dummy_content_v1', true );
}
add_action('init', 'sport_theme_populate_dummy_content');

// FIX DB CONTENT PER PROGETTO SPORTIVO
function sport_theme_fix_progetto_content() {
    if ( get_option( 'sport_theme_fix_progetto_v2' ) ) {
        return;
    }
    
    $p = get_page_by_path('progetto-sportivo');
    if (!$p) $p = get_page_by_title('Progetto sportivo'); // Fallback
    if (!$p) $p = get_page_by_title('Progetto Sportivo');
    
    if ($p) {
        $lorem_p = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>";
        $lorem_h3 = "<h3>LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.</h3>";
        
        $correct_content = '<h2>VISIONE, OBIETTIVI E VALORI SPORTIVI</h2>' . $lorem_h3 . $lorem_p . 
                           '<h2>FILOSOFIA DI GIOCO</h2>' . $lorem_h3 . $lorem_p . 
                           '<h2>OBIETTIVI SPORTIVI</h2>' . $lorem_h3 . $lorem_p . 
                           '<h2>VALORI</h2>' . $lorem_h3 . $lorem_p;
                           
        wp_update_post(array(
            'ID' => $p->ID,
            'post_content' => $correct_content
        ));
        update_option('sport_theme_fix_progetto_v2', true);
    }
}
add_action('init', 'sport_theme_fix_progetto_content');

/**
 * Gestione Form Contatti - Invio email
 */
function sport_theme_handle_contatti_form() {
    if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['contatti_nonce']) ) {
        return;
    }
    
    if ( ! wp_verify_nonce($_POST['contatti_nonce'], 'contatti_form_nonce') ) {
        return;
    }
    
    $nome     = sanitize_text_field($_POST['contatti_nome'] ?? '');
    $telefono = sanitize_text_field($_POST['contatti_telefono'] ?? '');
    $email    = sanitize_email($_POST['contatti_email'] ?? '');
    $oggetto  = sanitize_text_field($_POST['contatti_oggetto'] ?? '');
    $domanda  = sanitize_textarea_field($_POST['contatti_domanda'] ?? '');
    
    if ( empty($nome) || empty($email) || empty($oggetto) || empty($domanda) ) {
        return;
    }
    
    $to      = get_option('admin_email');
    $subject = '[AC Taverne - Contatti] ' . $oggetto;
    $body    = "Nome: {$nome}\n";
    $body   .= "Email: {$email}\n";
    $body   .= "Telefono: {$telefono}\n\n";
    $body   .= "Messaggio:\n{$domanda}";
    $headers = array('Reply-To: ' . $nome . ' <' . $email . '>');
    
    wp_mail($to, $subject, $body, $headers);
    
    // Redirect con flag di successo
    wp_redirect( add_query_arg('contatti_inviato', '1', wp_get_referer() ?: home_url('/contatti/')) );
    exit;
}
add_action('template_redirect', 'sport_theme_handle_contatti_form');

/**
 * Auto-assign Contatti template (esegui una volta)
 */
function sport_theme_auto_assign_contatti_v1() {
    if ( get_option('sport_theme_contatti_assigned_v1') ) {
        return;
    }
    
    $page = get_page_by_title('Contatti');
    if (!$page) {
        $page_id = wp_insert_post(array(
            'post_title'  => 'Contatti',
            'post_status' => 'publish',
            'post_type'   => 'page',
        ));
    } else {
        $page_id = $page->ID;
    }
    
    if ($page_id && !is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', 'template-contatti.php');
    }
    
    update_option('sport_theme_contatti_assigned_v1', true);
}
add_action('init', 'sport_theme_auto_assign_contatti_v1');

/**
 * Crea e assegna la pagina AC Taverne con slug fisso 'ac-taverne'
 * Così il link "AC Taverne" nel menu Prima Squadra funziona correttamente.
 */
function sport_theme_create_home_societa() {
    if ( get_option('sport_theme_ac_taverne_v3') ) {
        return;
    }

    $page = get_page_by_path('ac-taverne');
    
    if (!$page) {
        $page = get_page_by_title('AC Taverne');
    }
    
    if (!$page) {
        $page = get_page_by_title('Home Società');
    }

    if ( ! $page ) {
        $page_id = wp_insert_post(array(
            'post_title'   => 'AC Taverne',
            'post_name'    => 'ac-taverne',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ));
    } else {
        $page_id = $page->ID;
        // Forza lo slug e titolo corretto se necessario
        if ( $page->post_name !== 'ac-taverne' || $page->post_title !== 'AC Taverne' ) {
            wp_update_post(array(
                'ID'        => $page_id,
                'post_name' => 'ac-taverne',
                'post_title' => 'AC Taverne',
            ));
        }
    }

    if ( $page_id && ! is_wp_error($page_id) ) {
        update_post_meta($page_id, '_wp_page_template', 'template-home-societa.php');
    }

    update_option('sport_theme_ac_taverne_v3', true);
}
add_action('init', 'sport_theme_create_home_societa');



/**
 * ----------------------------------------------------
 * METABOX PER PAGINA SCUOLA CALCIO
 * ----------------------------------------------------
 */
function sport_theme_scuola_calcio_metabox() {
    global $post;
    if ( ! $post ) return;
    
    // Mostra se il template è impostato, OPPURE se lo slug è scuola-calcio
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( $template === 'template-scuola-calcio.php' || $post->post_name === 'scuola-calcio' || strtolower($post->post_title) === 'scuola calcio' ) {
        add_meta_box( 'scuola_calcio_meta', 'Informazioni Scuola Calcio (Compila questi campi per modificare la pagina)', 'sport_theme_scuola_calcio_meta_html', 'page', 'normal', 'high' );
    }
}
add_action( 'add_meta_boxes', 'sport_theme_scuola_calcio_metabox' );

function sport_theme_scuola_calcio_meta_html( $post ) {
    wp_nonce_field( 'salva_scuola_calcio_meta', 'scuola_calcio_meta_nonce' );
    
    // Campi
    $email = get_post_meta( $post->ID, '_sc_email', true );
    $orario_prova = get_post_meta( $post->ID, '_sc_orario_prova', true );
    $testo_prova = get_post_meta( $post->ID, '_sc_testo_prova', true );
    $inizio_stagione = get_post_meta( $post->ID, '_sc_inizio_stagione', true );
    $giorni_allenamento = get_post_meta( $post->ID, '_sc_giorni_allenamento', true );
    $responsabile = get_post_meta( $post->ID, '_sc_responsabile', true );
    
    $anno_1 = get_post_meta( $post->ID, '_sc_anno_1', true ) ?: '2017';
    $anno_2 = get_post_meta( $post->ID, '_sc_anno_2', true ) ?: '2018';
    $anno_3 = get_post_meta( $post->ID, '_sc_anno_3', true ) ?: '2019';
    $anno_4 = get_post_meta( $post->ID, '_sc_anno_4', true ) ?: '2020';

    $formatori_2017 = get_post_meta( $post->ID, '_sc_formatori_2017', true );
    $formatori_2018 = get_post_meta( $post->ID, '_sc_formatori_2018', true );
    $formatori_2019 = get_post_meta( $post->ID, '_sc_formatori_2019', true );
    $formatori_2020 = get_post_meta( $post->ID, '_sc_formatori_2020', true );
    $formatori_portieri = get_post_meta( $post->ID, '_sc_formatori_portieri', true );

    ?>
    <style>.sc-meta label { font-weight: bold; display: block; margin-top: 15px; } .sc-meta input[type="text"], .sc-meta textarea { width: 100%; max-width: 600px; padding: 5px; } .sc-meta textarea { height: 80px; } .sc-year-input { width: 120px !important; margin-bottom: 5px; }</style>
    <div class="sc-meta">
        <h3>Sezione: Vuoi Provare?</h3>
        <label>E-mail di contatto:</label>
        <input type="text" name="_sc_email" value="<?php echo esc_attr( $email ?: 'INFO@ACTAVERNE.COM' ); ?>">
        
        <label>Orario presentazione sul campo (es. 09:45):</label>
        <input type="text" name="_sc_orario_prova" value="<?php echo esc_attr( $orario_prova ?: '09:45' ); ?>">
        
        <label>Testo prova gratuita:</label>
        <input type="text" name="_sc_testo_prova" value="<?php echo esc_attr( $testo_prova ?: 'LA PRIMA PROVA È GRATUITA, TI ASPETTIAMO!' ); ?>">
        
        <label>Testo inizio stagione:</label>
        <input type="text" name="_sc_inizio_stagione" value="<?php echo esc_attr( $inizio_stagione ?: '1° ALLENAMENTO DELLA STAGIONE 2025/2026 SABATO 30 AGOSTO 2025' ); ?>">
        
        <hr>
        <h3>Sezione: Giorni di Allenamento ed Educatori</h3>
        <label>Giorni e orari (es. Sabato 10:00 - 11:30):</label>
        <input type="text" name="_sc_giorni_allenamento" value="<?php echo esc_attr( $giorni_allenamento ?: 'Sabato 10:00 - 11:30' ); ?>">
        
        <label>Responsabile Tecnico Scuola Calcio:</label>
        <input type="text" name="_sc_responsabile" value="<?php echo esc_attr( $responsabile ?: 'Angelo Clemente' ); ?>">
        
        <hr>
        <h3>Sezione: Formatori</h3>
        
        <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 15px; max-width: 600px;">
            <label style="margin-top:0;">Anno Gruppo 1 (es. 2017):</label>
            <input type="text" class="sc-year-input" name="_sc_anno_1" value="<?php echo esc_attr( $anno_1 ); ?>">
            <label>Formatori Gruppo 1 (uno per riga):</label>
            <textarea name="_sc_formatori_2017"><?php echo esc_textarea( $formatori_2017 ?: "Mario Mesquita\nMario Mengoni\nCiro Bove" ); ?></textarea>
        </div>

        <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 15px; max-width: 600px;">
            <label style="margin-top:0;">Anno Gruppo 2 (es. 2018):</label>
            <input type="text" class="sc-year-input" name="_sc_anno_2" value="<?php echo esc_attr( $anno_2 ); ?>">
            <label>Formatori Gruppo 2 (uno per riga):</label>
            <textarea name="_sc_formatori_2018"><?php echo esc_textarea( $formatori_2018 ?: "Ignazio Gatto\nMarcello Clemente\nLino Mazzei" ); ?></textarea>
        </div>

        <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 15px; max-width: 600px;">
            <label style="margin-top:0;">Anno Gruppo 3 (es. 2019):</label>
            <input type="text" class="sc-year-input" name="_sc_anno_3" value="<?php echo esc_attr( $anno_3 ); ?>">
            <label>Formatori Gruppo 3 (uno per riga):</label>
            <textarea name="_sc_formatori_2019"><?php echo esc_textarea( $formatori_2019 ?: "Moritz Roth\nLorenzo Pignatiello\nDomenico Criniti" ); ?></textarea>
        </div>

        <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 15px; max-width: 600px;">
            <label style="margin-top:0;">Anno Gruppo 4 (es. 2020):</label>
            <input type="text" class="sc-year-input" name="_sc_anno_4" value="<?php echo esc_attr( $anno_4 ); ?>">
            <label>Formatori Gruppo 4 (uno per riga):</label>
            <textarea name="_sc_formatori_2020"><?php echo esc_textarea( $formatori_2020 ?: "Marco Tognola\nFrancesco Foresta" ); ?></textarea>
        </div>

        <label>Portieri (uno per riga):</label>
        <textarea name="_sc_formatori_portieri"><?php echo esc_textarea( $formatori_portieri ?: "Marcello Clemente" ); ?></textarea>
    </div>
    <?php
}

function sport_theme_salva_scuola_calcio_meta( $post_id ) {
    if ( ! isset( $_POST['scuola_calcio_meta_nonce'] ) || ! wp_verify_nonce( $_POST['scuola_calcio_meta_nonce'], 'salva_scuola_calcio_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    $fields = array(
        '_sc_email', '_sc_orario_prova', '_sc_testo_prova', '_sc_inizio_stagione',
        '_sc_giorni_allenamento', '_sc_responsabile', 
        '_sc_anno_1', '_sc_anno_2', '_sc_anno_3', '_sc_anno_4',
        '_sc_formatori_2017', '_sc_formatori_2018', '_sc_formatori_2019', '_sc_formatori_2020', 
        '_sc_formatori_portieri'
    );
    
    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field] ) ) {
            update_post_meta( $post_id, $field, sanitize_textarea_field( $_POST[$field] ) );
        }
    }
}
add_action( 'save_post', 'sport_theme_salva_scuola_calcio_meta' );

function sport_theme_ultimate_fix_scuola_calcio() {
    $page_title = 'Scuola Calcio';
    $page_slug = 'scuola-calcio';
    $template_file = 'template-scuola-calcio.php';

    // Cerca la pagina per slug
    $page = get_page_by_path( $page_slug );

    if ( ! $page ) {
        // Cerca per titolo
        $page = get_page_by_title( $page_title );
    }

    if ( ! $page ) {
        // Crea la pagina se non esiste
        $page_id = wp_insert_post( array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
        ) );
    } else {
        $page_id = $page->ID;
        // Aggiorna lo slug se è sbagliato
        if ( $page->post_name !== $page_slug || $page->post_status !== 'publish' ) {
            wp_update_post( array(
                'ID' => $page_id,
                'post_name' => $page_slug,
                'post_status' => 'publish'
            ) );
        }
    }

    // Forza il template
    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_ultimate_fix_scuola_calcio' );

function sport_theme_create_comitato_societa() {
    $page_title = 'Comitato';
    $page_slug = 'comitato';
    $template_file = 'template-comitato-societa.php';

    $page = get_page_by_path( $page_slug );
    if ( ! $page ) $page = get_page_by_title( $page_title );

    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
        ) );
    } else {
        $page_id = $page->ID;
        if ( $page->post_name !== $page_slug || $page->post_status !== 'publish' ) {
            wp_update_post( array(
                'ID' => $page_id,
                'post_name' => $page_slug,
                'post_status' => 'publish'
            ) );
        }
    }

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_create_comitato_societa' );

/**
 * Crea e assegna la pagina Club dei 100
 */
function sport_theme_create_club100_societa() {
    $page_title = 'Club dei 100';
    $page_slug = 'club-dei-100';
    $template_file = 'template-club-dei-100.php';

    $page = get_page_by_path( $page_slug );
    if ( ! $page ) $page = get_page_by_title( $page_title );

    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
        ) );
    } else {
        $page_id = $page->ID;
        if ( $page->post_name !== $page_slug || $page->post_status !== 'publish' ) {
            wp_update_post( array(
                'ID' => $page_id,
                'post_name' => $page_slug,
                'post_status' => 'publish'
            ) );
        }
    }

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_create_club100_societa' );

/**
 * Gestione Form Iscrizione Club dei 100
 */
function sport_theme_handle_club100_form() {
    if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['club100_nonce']) ) {
        return;
    }
    
    if ( ! wp_verify_nonce($_POST['club100_nonce'], 'club100_form_nonce') ) {
        return;
    }
    
    $nome     = sanitize_text_field($_POST['c100_nome'] ?? '');
    $telefono = sanitize_text_field($_POST['c100_telefono'] ?? '');
    $email    = sanitize_email($_POST['c100_email'] ?? '');
    $oggetto  = sanitize_text_field($_POST['c100_oggetto'] ?? '');
    $testo    = sanitize_textarea_field($_POST['c100_testo'] ?? '');
    
    if ( empty($nome) || empty($email) ) {
        return;
    }
    
    $to      = get_option('admin_email');
    $subject = '[AC Taverne - Club dei 100] ' . $oggetto;
    $body    = "Richiesta iscrizione al Club dei 100:\n\n";
    $body   .= "Nome: {$nome}\n";
    $body   .= "Email: {$email}\n";
    $body   .= "Telefono: {$telefono}\n\n";
    $body   .= "Messaggio:\n{$testo}";
    $headers = array('Reply-To: ' . $nome . ' <' . $email . '>');
    
    wp_mail($to, $subject, $body, $headers);
    
    wp_redirect( add_query_arg('iscritto', '1', wp_get_referer() ?: home_url('/club-dei-100/')) );
    exit;
}
add_action('admin_post_nopriv_club100_subscribe', 'sport_theme_handle_club100_form');
add_action('admin_post_club100_subscribe', 'sport_theme_handle_club100_form');

/**
 * Crea e assegna la pagina La Società
 */
function sport_theme_create_la_societa() {
    $page_title = 'La Società';
    $page_slug = 'la-societa';
    $template_file = 'template-la-societa.php';

    $page = get_page_by_path( $page_slug );
    if ( ! $page ) $page = get_page_by_title( $page_title );

    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
        ) );
    } else {
        $page_id = $page->ID;
        if ( $page->post_name !== $page_slug || $page->post_status !== 'publish' ) {
            wp_update_post( array(
                'ID' => $page_id,
                'post_name' => $page_slug,
                'post_status' => 'publish'
            ) );
        }
    }

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_create_la_societa' );

/**
 * Metabox per La Società
 */
function sport_theme_la_societa_metabox() {
    global $post;
    if ( ! $post ) return;
    
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( $template === 'template-la-societa.php' || $post->post_name === 'la-societa' ) {
        add_meta_box( 'la_societa_meta', 'Sezioni Pagina', 'sport_theme_la_societa_meta_html', 'page', 'normal', 'high' );
    }
}
add_action( 'add_meta_boxes', 'sport_theme_la_societa_metabox' );

function sport_theme_la_societa_meta_html( $post ) {
    wp_nonce_field( 'salva_la_societa_meta', 'la_societa_meta_nonce' );
    
    for($i=1; $i<=4; $i++) {
        $titolo = get_post_meta( $post->ID, "_soc_titolo_$i", true );
        $sottotitolo = get_post_meta( $post->ID, "_soc_sottotitolo_$i", true );
        $testo = get_post_meta( $post->ID, "_soc_testo_$i", true );
        
        echo "<hr><h3>Sezione $i</h3>";
        echo "<label style='display:block;margin-top:10px;font-weight:bold;'>Titolo:</label>";
        echo "<input type='text' name='_soc_titolo_$i' value='" . esc_attr($titolo) . "' style='width:100%;max-width:600px;'>";
        
        echo "<label style='display:block;margin-top:10px;font-weight:bold;'>Sottotitolo:</label>";
        echo "<textarea name='_soc_sottotitolo_$i' style='width:100%;max-width:600px;height:50px;'>" . esc_textarea($sottotitolo) . "</textarea>";
        
        if($i < 4) {
            echo "<label style='display:block;margin-top:10px;font-weight:bold;'>Testo Paragrafo:</label>";
            echo "<textarea name='_soc_testo_$i' style='width:100%;max-width:600px;height:100px;'>" . esc_textarea($testo) . "</textarea>";
        } else {
            $file = get_post_meta( $post->ID, "_soc_file_statuto", true );
            echo "<label style='display:block;margin-top:10px;font-weight:bold;'>URL File Statuto (es. PDF):</label>";
            echo "<input type='text' name='_soc_file_statuto' value='" . esc_attr($file) . "' style='width:100%;max-width:600px;' placeholder='https://.../statuto.pdf'>";
        }
    }
}

function sport_theme_salva_la_societa_meta( $post_id ) {
    if ( ! isset( $_POST['la_societa_meta_nonce'] ) || ! wp_verify_nonce( $_POST['la_societa_meta_nonce'], 'salva_la_societa_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    
    for($i=1; $i<=4; $i++) {
        if ( isset( $_POST["_soc_titolo_$i"] ) ) update_post_meta( $post_id, "_soc_titolo_$i", sanitize_text_field( $_POST["_soc_titolo_$i"] ) );
        if ( isset( $_POST["_soc_sottotitolo_$i"] ) ) update_post_meta( $post_id, "_soc_sottotitolo_$i", sanitize_textarea_field( $_POST["_soc_sottotitolo_$i"] ) );
        if ( isset( $_POST["_soc_testo_$i"] ) ) update_post_meta( $post_id, "_soc_testo_$i", sanitize_textarea_field( $_POST["_soc_testo_$i"] ) );
    }
    if ( isset( $_POST["_soc_file_statuto"] ) ) update_post_meta( $post_id, "_soc_file_statuto", esc_url_raw( $_POST["_soc_file_statuto"] ) );
}
add_action( 'save_post', 'sport_theme_salva_la_societa_meta' );
add_action('save_post', 'sport_theme_salva_evento_meta');

/**
 * Metabox per Squadra Sezione
 */
function sport_theme_squadra_sezione_metabox_register() {
    add_meta_box('squadra_sezione_meta', 'Dettagli Squadra', 'sport_theme_squadra_sezione_meta_html', 'squadra_sezione', 'normal', 'high');
}
add_action('add_meta_boxes', 'sport_theme_squadra_sezione_metabox_register');

function sport_theme_squadra_sezione_meta_html($post) {
    wp_nonce_field('salva_squadra_sezione_meta', 'squadra_sezione_meta_nonce');

    $giorni = get_post_meta($post->ID, '_ss_giorni', true);
    $allenatore = get_post_meta($post->ID, '_ss_allenatore', true);
    $assistente = get_post_meta($post->ID, '_ss_assistente', true);
    $iframe = get_post_meta($post->ID, '_ss_iframe', true);

    echo "<label style='display:block;margin-top:10px;'>Giorni di Allenamento (es. Martedì: 19:30 - 21:00):</label>";
    echo "<textarea name='_ss_giorni' style='width:100%;height:80px;'>" . esc_textarea($giorni) . "</textarea>";

    echo "<label style='display:block;margin-top:10px;'>Allenatore:</label>";
    echo "<input type='text' name='_ss_allenatore' value='" . esc_attr($allenatore) . "' style='width:100%;max-width:400px;'>";

    echo "<label style='display:block;margin-top:10px;'>Assistente (opzionale):</label>";
    echo "<input type='text' name='_ss_assistente' value='" . esc_attr($assistente) . "' style='width:100%;max-width:400px;'>";

    echo "<hr><label style='display:block;margin-top:10px;font-weight:bold;'>Codice Iframe Classifica (ftc.football.ch):</label>";
    echo "<p>Incolla qui l'iframe per mostrare la classifica. Se vuoto, mostreremo una tabella finta di design.</p>";
    echo "<textarea name='_ss_iframe' style='width:100%;height:100px;font-family:monospace;'>" . esc_textarea($iframe) . "</textarea>";
}

function sport_theme_salva_squadra_sezione_meta($post_id) {
    if (!isset($_POST['squadra_sezione_meta_nonce']) || !wp_verify_nonce($_POST['squadra_sezione_meta_nonce'], 'salva_squadra_sezione_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['_ss_giorni'])) update_post_meta($post_id, '_ss_giorni', sanitize_textarea_field($_POST['_ss_giorni']));
    if (isset($_POST['_ss_allenatore'])) update_post_meta($post_id, '_ss_allenatore', sanitize_text_field($_POST['_ss_allenatore']));
    if (isset($_POST['_ss_assistente'])) update_post_meta($post_id, '_ss_assistente', sanitize_text_field($_POST['_ss_assistente']));
    
    if (isset($_POST['_ss_iframe'])) {
        $iframe_rules = array(
            'iframe' => array(
                'src'             => true,
                'height'          => true,
                'width'           => true,
                'frameborder'     => true,
                'allowfullscreen' => true,
                'style'           => true,
                'scrolling'       => true
            )
        );
        update_post_meta($post_id, '_ss_iframe', wp_kses(wp_unslash($_POST['_ss_iframe']), $iframe_rules));
    }
}
add_action('save_post', 'sport_theme_salva_squadra_sezione_meta');

/**
 * Funzione per creare automaticamente pagina Area Riservata
 */
function sport_theme_create_allenatori_societa() {
    $page_title = 'Area Allenatori';
    $page_slug = 'area-allenatori';
    $template_file = 'template-allenatori.php';

    $page = get_page_by_path( $page_slug );
    if ( ! $page ) $page = get_page_by_title( $page_title );

    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
        ) );
    } else {
        $page_id = $page->ID;
        if ( $page->post_name !== $page_slug || $page->post_status !== 'publish' ) {
            wp_update_post( array(
                'ID' => $page_id,
                'post_name' => $page_slug,
                'post_status' => 'publish'
            ) );
        }
    }

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_create_allenatori_societa' );

/**
 * Metabox per Area Allenatori
 */
function sport_theme_allenatori_metabox() {
    global $post;
    if ( ! $post ) return;
    
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( $template === 'template-allenatori.php' || $post->post_name === 'area-allenatori' ) {
        add_meta_box( 'allenatori_meta', 'Documenti da Scaricare', 'sport_theme_allenatori_meta_html', 'page', 'normal', 'high' );
    }
}
add_action( 'add_meta_boxes', 'sport_theme_allenatori_metabox' );

function sport_theme_allenatori_meta_html( $post ) {
    wp_nonce_field( 'salva_allenatori_meta', 'allenatori_meta_nonce' );
    
    echo "<p>Compila i campi qui sotto per aggiungere documenti scaricabili nella pagina. Puoi inserirne fino a 8. Se l'URL del file è vuoto, il documento non verrà mostrato.</p>";
    
    for($i=1; $i<=8; $i++) {
        $titolo = get_post_meta( $post->ID, "_all_titolo_$i", true );
        $desc = get_post_meta( $post->ID, "_all_desc_$i", true );
        $url = get_post_meta( $post->ID, "_all_url_$i", true );
        
        echo "<div style='border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background: #f9f9f9;'>";
        echo "<h4>Documento $i</h4>";
        
        echo "<label style='display:block;margin-top:10px;font-weight:bold;'>Titolo Documento:</label>";
        echo "<input type='text' name='_all_titolo_$i' value='" . esc_attr($titolo) . "' style='width:100%;max-width:600px;' placeholder='Es: Linee guida allenamenti'>";
        
        echo "<label style='display:block;margin-top:10px;font-weight:bold;'>Breve Descrizione:</label>";
        echo "<textarea name='_all_desc_$i' style='width:100%;max-width:600px;height:50px;' placeholder='Breve descrizione o note per l\'allenatore'>" . esc_textarea($desc) . "</textarea>";
        
        echo "<label style='display:block;margin-top:10px;font-weight:bold;'>URL File (carica il file sui Media di WordPress e incolla qui l'URL):</label>";
        echo "<input type='text' name='_all_url_$i' value='" . esc_attr($url) . "' style='width:100%;max-width:600px;' placeholder='https://.../documento.pdf'>";
        echo "</div>";
    }
}

function sport_theme_salva_allenatori_meta( $post_id ) {
    if ( ! isset( $_POST['allenatori_meta_nonce'] ) || ! wp_verify_nonce( $_POST['allenatori_meta_nonce'], 'salva_allenatori_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    
    for($i=1; $i<=8; $i++) {
        if ( isset( $_POST["_all_titolo_$i"] ) ) update_post_meta( $post_id, "_all_titolo_$i", sanitize_text_field( $_POST["_all_titolo_$i"] ) );
        if ( isset( $_POST["_all_desc_$i"] ) ) update_post_meta( $post_id, "_all_desc_$i", sanitize_textarea_field( $_POST["_all_desc_$i"] ) );
        if ( isset( $_POST["_all_url_$i"] ) ) update_post_meta( $post_id, "_all_url_$i", esc_url_raw( $_POST["_all_url_$i"] ) );
    }
}
add_action( 'save_post', 'sport_theme_salva_allenatori_meta' );

/**
 * Gestione Login Fallito per Area Allenatori
 */
add_action( 'wp_login_failed', 'sport_theme_allenatori_login_failed' );
function sport_theme_allenatori_login_failed( $username ) {
    $referrer = wp_get_referer();
    // Se il login è fallito ed è stato fatto dalla pagina Area Allenatori
    if ( $referrer && ! strpos($referrer, 'wp-login') && ! strpos($referrer, 'wp-admin') ) {
        if ( strpos($referrer, 'area-allenatori') !== false ) {
            wp_redirect( add_query_arg( 'login', 'failed', $referrer ) );
            exit;
        }
    }
}

/**
 * Evita di far vedere i campi vuoti in wp-login.php in caso di errore
 */
add_action( 'authenticate', 'sport_theme_allenatori_authenticate_empty', 1, 3 );
function sport_theme_allenatori_authenticate_empty( $user, $username, $password ) {
    if ( isset($_POST['redirect_to']) && strpos($_POST['redirect_to'], 'area-allenatori') !== false ) {
        if ( empty($username) || empty($password) ) {
            wp_redirect( add_query_arg( 'login', 'failed', esc_url_raw($_POST['redirect_to']) ) );
            exit;
        }
    }
    return $user;
}

/**
 * Crea e assegna la pagina Contatti Società
 */
function sport_theme_create_contatti_societa() {
    $page_title = 'Contatti Società';
    $page_slug = 'contatti-societa';
    $template_file = 'template-contatti-societa.php';

    $page = get_page_by_path( $page_slug );
    if ( ! $page ) $page = get_page_by_title( $page_title );

    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
        ) );
    } else {
        $page_id = $page->ID;
        if ( $page->post_name !== $page_slug || $page->post_status !== 'publish' ) {
            wp_update_post( array(
                'ID' => $page_id,
                'post_name' => $page_slug,
                'post_status' => 'publish'
            ) );
        }
    }

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_create_contatti_societa' );

/**
 * Metabox per Contatti Società
 */
function sport_theme_contatti_societa_metabox() {
    global $post;
    if ( ! $post ) return;
    
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( $template === 'template-contatti-societa.php' || $post->post_name === 'contatti-societa' ) {
        add_meta_box( 'contatti_societa_meta', 'Informazioni di Contatto', 'sport_theme_contatti_societa_meta_html', 'page', 'normal', 'high' );
    }
}
add_action( 'add_meta_boxes', 'sport_theme_contatti_societa_metabox' );

function sport_theme_contatti_societa_meta_html( $post ) {
    wp_nonce_field( 'salva_contatti_societa_meta', 'contatti_societa_meta_nonce' );
    
    echo "<h3>Informazioni Generali</h3>";
    $gen_email = get_post_meta( $post->ID, "_cont_email", true );
    $gen_tel = get_post_meta( $post->ID, "_cont_tel", true );
    $gen_ind = get_post_meta( $post->ID, "_cont_ind", true );
    
    echo "<label style='display:block;margin-top:10px;'>Email Principale:</label>";
    echo "<input type='text' name='_cont_email' value='" . esc_attr($gen_email) . "' style='width:100%;max-width:400px;' placeholder='info@actaverne.com'>";
    
    echo "<label style='display:block;margin-top:10px;'>Telefono Principale:</label>";
    echo "<input type='text' name='_cont_tel' value='" . esc_attr($gen_tel) . "' style='width:100%;max-width:400px;' placeholder='+41 91 945 22 95'>";
    
    echo "<label style='display:block;margin-top:10px;'>Indirizzo Principale:</label>";
    echo "<textarea name='_cont_ind' style='width:100%;max-width:400px;height:60px;'>" . esc_textarea($gen_ind) . "</textarea>";
    
    echo "<hr><h3>Contatti Responsabili (fino a 9 blocchi)</h3>";
    
    for($i=1; $i<=9; $i++) {
        $ruolo = get_post_meta( $post->ID, "_cont_ruolo_$i", true );
        $info = get_post_meta( $post->ID, "_cont_info_$i", true );
        
        echo "<div style='border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background: #f9f9f9;'>";
        echo "<h4>Blocco $i</h4>";
        echo "<label style='display:block;margin-top:10px;font-weight:bold;'>Ruolo (es: RESPONSABILE ALLIEVI):</label>";
        echo "<input type='text' name='_cont_ruolo_$i' value='" . esc_attr($ruolo) . "' style='width:100%;max-width:600px;'>";
        
        echo "<label style='display:block;margin-top:10px;font-weight:bold;'>Nome e Recapiti:</label>";
        echo "<textarea name='_cont_info_$i' style='width:100%;max-width:600px;height:70px;' placeholder='Mario Rossi&#10;E-mail: mario@email.com'>" . esc_textarea($info) . "</textarea>";
        echo "</div>";
    }
}

function sport_theme_salva_contatti_societa_meta( $post_id ) {
    if ( ! isset( $_POST['contatti_societa_meta_nonce'] ) || ! wp_verify_nonce( $_POST['contatti_societa_meta_nonce'], 'salva_contatti_societa_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    
    if ( isset( $_POST["_cont_email"] ) ) update_post_meta( $post_id, "_cont_email", sanitize_text_field( $_POST["_cont_email"] ) );
    if ( isset( $_POST["_cont_tel"] ) ) update_post_meta( $post_id, "_cont_tel", sanitize_text_field( $_POST["_cont_tel"] ) );
    if ( isset( $_POST["_cont_ind"] ) ) update_post_meta( $post_id, "_cont_ind", sanitize_textarea_field( $_POST["_cont_ind"] ) );
    
    for($i=1; $i<=9; $i++) {
        if ( isset( $_POST["_cont_ruolo_$i"] ) ) update_post_meta( $post_id, "_cont_ruolo_$i", sanitize_text_field( $_POST["_cont_ruolo_$i"] ) );
        if ( isset( $_POST["_cont_info_$i"] ) ) update_post_meta( $post_id, "_cont_info_$i", sanitize_textarea_field( $_POST["_cont_info_$i"] ) );
    }
}
add_action( 'save_post', 'sport_theme_salva_contatti_societa_meta' );

/**
 * Handler per il form di Contatti Società
 */
function sport_theme_handle_contatti_societa_form() {
    if ( ! isset($_POST['contatti_soc_nonce']) || ! wp_verify_nonce($_POST['contatti_soc_nonce'], 'contatti_soc_form_nonce') ) {
        wp_die('Richiesta non valida. Riprova.');
    }

    $nome     = sanitize_text_field($_POST['cs_nome'] ?? '');
    $telefono = sanitize_text_field($_POST['cs_telefono'] ?? '');
    $email    = sanitize_email($_POST['cs_email'] ?? '');
    $oggetto  = sanitize_text_field($_POST['cs_oggetto'] ?? '');
    $domanda  = sanitize_textarea_field($_POST['cs_domanda'] ?? '');

    if ( empty($nome) || empty($email) || empty($oggetto) || empty($domanda) ) {
        wp_die('Compila tutti i campi obbligatori.');
    }

    $to = get_option('admin_email');
    $subject = '[AC Taverne - Sito Società] ' . $oggetto;
    
    $body  = "Nuova richiesta di contatto dalla pagina Contatti Società:\n\n";
    $body .= "Nome: {$nome}\n";
    $body .= "Email: {$email}\n";
    if(!empty($telefono)) $body .= "Telefono: {$telefono}\n";
    $body .= "\nMessaggio:\n{$domanda}\n";

    $headers = array('Reply-To: ' . $nome . ' <' . $email . '>');
    
    wp_mail($to, $subject, $body, $headers);
    
    wp_redirect( add_query_arg('inviato', '1', wp_get_referer() ?: home_url('/contatti-societa/')) );
    exit;
}
add_action('admin_post_nopriv_contatti_societa_submit', 'sport_theme_handle_contatti_societa_form');
add_action('admin_post_contatti_societa_submit', 'sport_theme_handle_contatti_societa_form');

/**
 * Crea e assegna la pagina News Società
 */
function sport_theme_create_news_societa() {
    $page_title = 'News Società';
    $page_slug = 'news-societa';
    $template_file = 'template-news-societa.php';

    $page = get_page_by_path( $page_slug );
    if ( ! $page ) $page = get_page_by_title( $page_title );

    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
        ) );
    } else {
        $page_id = $page->ID;
        if ( $page->post_name !== $page_slug || $page->post_status !== 'publish' ) {
            wp_update_post( array(
                'ID' => $page_id,
                'post_name' => $page_slug,
                'post_status' => 'publish'
            ) );
        }
    }

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_create_news_societa' );

/**
 * Crea e assegna la pagina Infrastruttura
 */
function sport_theme_create_infrastruttura() {
    $page_title = 'Infrastruttura';
    $page_slug = 'infrastruttura';
    $template_file = 'template-infrastruttura.php';

    $page = get_page_by_path( $page_slug );
    if ( ! $page ) $page = get_page_by_title( $page_title );

    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
        ) );
    } else {
        $page_id = $page->ID;
        if ( $page->post_name !== $page_slug || $page->post_status !== 'publish' ) {
            wp_update_post( array(
                'ID' => $page_id,
                'post_name' => $page_slug,
                'post_status' => 'publish'
            ) );
        }
    }

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_create_infrastruttura' );

/**
 * Crea e assegna la pagina Sezioni
 */
function sport_theme_create_sezioni() {
    $page_title = 'Sezioni';
    $page_slug = 'sezioni';
    $template_file = 'template-sezioni.php';

    $page = get_page_by_path( $page_slug );
    if ( ! $page ) $page = get_page_by_title( $page_title );

    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
        ) );
    } else {
        $page_id = $page->ID;
        if ( $page->post_name !== $page_slug || $page->post_status !== 'publish' ) {
            wp_update_post( array(
                'ID' => $page_id,
                'post_name' => $page_slug,
                'post_status' => 'publish'
            ) );
        }
    }

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_create_sezioni' );

// ATTIVAZIONE AUTOMATICA PLUGIN INSTAGRAM FEED
add_action('admin_init', function() {
    if ( ! function_exists( 'activate_plugin' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    if ( ! is_plugin_active( 'instagram-feed/instagram-feed.php' ) ) {
        activate_plugin( 'instagram-feed/instagram-feed.php' );
    }
});

// REDIRECT PAGINA SHOP
function sport_theme_redirect_shop() {
    // Se la pagina corrente si chiama "Shop" o ha lo slug "shop"
    if ( is_page('shop') ) {
        wp_redirect('https://actaverneshop.com/');
        exit;
    }
}
add_action('template_redirect', 'sport_theme_redirect_shop');



/**
 * Metabox per Infrastruttura
 */
function sport_theme_infrastruttura_metabox() {
    global $post;
    if ( ! $post ) return;
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( $template === 'template-infrastruttura.php' || $post->post_name === 'infrastruttura' ) {
        add_meta_box( 'infrastruttura_meta', 'Contenuti Infrastruttura', 'sport_theme_infrastruttura_meta_html', 'page', 'normal', 'high' );
    }
}
add_action( 'add_meta_boxes', 'sport_theme_infrastruttura_metabox' );

function sport_theme_infrastruttura_meta_html( $post ) {
    wp_nonce_field( 'salva_infrastruttura_meta', 'infrastruttura_meta_nonce' );
    
    echo "<h3>Testi dei Tab</h3>";
    $testo_campo = get_post_meta( $post->ID, "_infra_testo_campo", true );
    $testo_buvette = get_post_meta( $post->ID, "_infra_testo_buvette", true );
    $testo_occupazione = get_post_meta( $post->ID, "_infra_testo_occupazione", true );
    
    echo "<label style='display:block;margin-top:10px;'>Testo Campo Sportivo:</label>";
    echo "<textarea name='_infra_testo_campo' style='width:100%;height:80px;'>" . esc_textarea($testo_campo) . "</textarea>";
    echo "<label style='display:block;margin-top:10px;'>Testo Buvette:</label>";
    echo "<textarea name='_infra_testo_buvette' style='width:100%;height:80px;'>" . esc_textarea($testo_buvette) . "</textarea>";
    echo "<label style='display:block;margin-top:10px;'>Testo Occupazione:</label>";
    echo "<textarea name='_infra_testo_occupazione' style='width:100%;height:80px;'>" . esc_textarea($testo_occupazione) . "</textarea>";
    
    echo "<hr><h3>Galleria Immagini (Campo Sportivo)</h3>";
    echo "<p>Inserisci gli URL delle immagini o selezionale dalla libreria media.</p>";
    for($i=1; $i<=6; $i++) {
        $img = get_post_meta( $post->ID, "_infra_img_$i", true );
        echo "<label style='display:block;margin-top:10px;'>Immagine $i (URL):</label>";
        echo "<input type='text' id='infra_img_$i' name='_infra_img_$i' value='" . esc_attr($img) . "' style='width:100%;max-width:500px;vertical-align:middle;'>";
        echo "<button type='button' class='button upload_file_button' data-input-id='infra_img_$i' style='margin-left: 5px; vertical-align: middle;'>Seleziona</button>";
    }

    echo "<hr><h3>Galleria Immagini (Buvette)</h3>";
    for($i=1; $i<=6; $i++) {
        $img = get_post_meta( $post->ID, "_infra_buvette_img_$i", true );
        echo "<label style='display:block;margin-top:10px;'>Immagine Buvette $i (URL):</label>";
        echo "<input type='text' id='infra_buvette_img_$i' name='_infra_buvette_img_$i' value='" . esc_attr($img) . "' style='width:100%;max-width:500px;vertical-align:middle;'>";
        echo "<button type='button' class='button upload_file_button' data-input-id='infra_buvette_img_$i' style='margin-left: 5px; vertical-align: middle;'>Seleziona</button>";
    }

    echo "<hr><h3>Calendario Google (Campo Sportivo)</h3>";
    $calendar = get_post_meta( $post->ID, "_infra_calendar_iframe", true );
    echo "<p>Incolla qui il codice HTML (Iframe) del calendario Google per il Campo Sportivo.</p>";
    echo "<textarea name='_infra_calendar_iframe' style='width:100%;height:100px;font-family:monospace;'>" . esc_textarea($calendar) . "</textarea>";

    echo "<hr><h3>Calendario Google (Infrastruttura)</h3>";
    $calendar_infra = get_post_meta( $post->ID, "_infra_calendar_infra_iframe", true );
    echo "<p>Incolla qui il codice HTML (Iframe) del calendario Google per l'Infrastruttura.</p>";
    echo "<textarea name='_infra_calendar_infra_iframe' style='width:100%;height:100px;font-family:monospace;'>" . esc_textarea($calendar_infra) . "</textarea>";

    echo "<hr><h3>Calendario Google (Buvette)</h3>";
    $calendar_buvette = get_post_meta( $post->ID, "_infra_calendar_buvette_iframe", true );
    echo "<p>Incolla qui il codice HTML (Iframe) del calendario Google per la Buvette.</p>";
    echo "<textarea name='_infra_calendar_buvette_iframe' style='width:100%;height:100px;font-family:monospace;'>" . esc_textarea($calendar_buvette) . "</textarea>";

    echo "<hr><h3>Contatti Prenotazione (Colonna Sinistra)</h3>";
    $email = get_post_meta( $post->ID, "_infra_email", true );
    $tel = get_post_meta( $post->ID, "_infra_tel", true );
    $ind = get_post_meta( $post->ID, "_infra_ind", true );
    echo "<label style='display:block;margin-top:10px;'>Email:</label>";
    echo "<input type='text' name='_infra_email' value='" . esc_attr($email) . "' style='width:100%;max-width:400px;'>";
    echo "<label style='display:block;margin-top:10px;'>Telefono:</label>";
    echo "<input type='text' name='_infra_tel' value='" . esc_attr($tel) . "' style='width:100%;max-width:400px;'>";
    echo "<label style='display:block;margin-top:10px;'>Indirizzo:</label>";
    echo "<textarea name='_infra_ind' style='width:100%;height:60px;max-width:400px;'>" . esc_textarea($ind) . "</textarea>";

    echo "<hr><h3>Documenti</h3>";
    $regolamento = get_post_meta( $post->ID, "_infra_pdf_regolamento", true );
    echo "<label style='display:block;margin-top:10px;'>URL Regolamento (PDF):</label>";
    echo "<input type='text' id='infra_pdf_regolamento' name='_infra_pdf_regolamento' value='" . esc_attr($regolamento) . "' style='width:100%;max-width:500px;vertical-align:middle;'>";
    echo "<button type='button' class='button upload_file_button' data-input-id='infra_pdf_regolamento' style='margin-left: 5px; vertical-align: middle;'>Seleziona</button>";
    ?>
    <script>
    jQuery(document).ready(function($){
        $('.upload_file_button').click(function(e) {
            e.preventDefault();
            var button = $(this);
            var input_id = button.data('input-id');
            var input_field = $('#' + input_id);
            
            var custom_uploader = wp.media({
                title: 'Seleziona File o Immagine',
                button: {
                    text: 'Usa questo file'
                },
                multiple: false
            })
            .on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                input_field.val(attachment.url);
            })
            .open();
        });
    });
    </script>
    <?php
}

function sport_theme_salva_infrastruttura_meta( $post_id ) {
    if ( ! isset( $_POST['infrastruttura_meta_nonce'] ) || ! wp_verify_nonce( $_POST['infrastruttura_meta_nonce'], 'salva_infrastruttura_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    
    $fields = ['_infra_testo_campo', '_infra_testo_buvette', '_infra_testo_occupazione', '_infra_email', '_infra_tel', '_infra_ind', '_infra_pdf_regolamento'];
    for($i=1; $i<=6; $i++) {
        $fields[] = "_infra_img_$i";
        $fields[] = "_infra_buvette_img_$i";
    }

    foreach($fields as $field) {
        if ( isset( $_POST[$field] ) ) {
            update_post_meta( $post_id, $field, wp_kses_post( $_POST[$field] ) );
        }
    }

    // Permetti iframe per il calendario Google
    $iframe_rules = array(
        'iframe' => array(
            'src'             => true,
            'height'          => true,
            'width'           => true,
            'frameborder'     => true,
            'allowfullscreen' => true,
            'style'           => true,
            'scrolling'       => true
        )
    );

    if ( isset( $_POST['_infra_calendar_iframe'] ) ) {
        update_post_meta( $post_id, '_infra_calendar_iframe', wp_kses( wp_unslash($_POST['_infra_calendar_iframe']), $iframe_rules ) );
    }
    if ( isset( $_POST['_infra_calendar_infra_iframe'] ) ) {
        update_post_meta( $post_id, '_infra_calendar_infra_iframe', wp_kses( wp_unslash($_POST['_infra_calendar_infra_iframe']), $iframe_rules ) );
    }
    if ( isset( $_POST['_infra_calendar_buvette_iframe'] ) ) {
        update_post_meta( $post_id, '_infra_calendar_buvette_iframe', wp_kses( wp_unslash($_POST['_infra_calendar_buvette_iframe']), $iframe_rules ) );
    }

    // Clear calendar transient caches so changes apply immediately
    global $wpdb;
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_cal_ics_%' OR option_name LIKE '_transient_timeout_cal_ics_%'");
}
add_action( 'save_post', 'sport_theme_salva_infrastruttura_meta' );

/**
 * Handler form prenotazioni
 */
function sport_theme_handle_prenotazioni_form() {
    if ( ! isset($_POST['prenotazioni_nonce']) || ! wp_verify_nonce($_POST['prenotazioni_nonce'], 'prenotazioni_form_nonce') ) {
        wp_die('Richiesta non valida. Riprova.');
    }

    $nome     = sanitize_text_field($_POST['pr_nome'] ?? '');
    $telefono = sanitize_text_field($_POST['pr_telefono'] ?? '');
    $email    = sanitize_email($_POST['pr_email'] ?? '');
    $azienda  = sanitize_text_field($_POST['pr_azienda'] ?? '');
    $oggetto  = sanitize_text_field($_POST['pr_oggetto'] ?? '');
    $periodo  = sanitize_text_field($_POST['pr_periodo'] ?? '');
    $domanda  = sanitize_textarea_field($_POST['pr_domanda'] ?? '');
    
    $infras = isset($_POST['pr_infra']) && is_array($_POST['pr_infra']) ? array_map('sanitize_text_field', $_POST['pr_infra']) : [];
    $infra_list = implode(', ', $infras);

    $to = get_option('admin_email');
    $subject = '[AC Taverne - Prenotazioni] ' . $oggetto;
    
    $body  = "Nuova richiesta di prenotazione infrastrutture:\n\n";
    $body .= "Nome: {$nome}\n";
    $body .= "Azienda: {$azienda}\n";
    $body .= "Email: {$email}\n";
    $body .= "Telefono: {$telefono}\n";
    $body .= "Periodo richiesto: {$periodo}\n";
    $body .= "Infrastrutture d'interesse: {$infra_list}\n";
    $body .= "\nMessaggio/Domanda:\n{$domanda}\n";

    $headers = array('Reply-To: ' . $nome . ' <' . $email . '>');
    
    wp_mail($to, $subject, $body, $headers);
    
    wp_redirect( add_query_arg('prenotazione', '1', wp_get_referer() ?: home_url('/infrastruttura/')) );
    exit;
}
add_action('admin_post_nopriv_prenotazioni_submit', 'sport_theme_handle_prenotazioni_form');
add_action('admin_post_prenotazioni_submit', 'sport_theme_handle_prenotazioni_form');

// TEMPORARY SCRIPT TO IMPORT PLAYERS
add_action('init', 'sport_theme_import_players_once');
function sport_theme_import_players_once() {
    if (!isset($_GET['import_players_now'])) return;
    if (!current_user_can('manage_options')) return;

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $players_data = [
        ['ruolo' => 'POR', 'nome' => 'Nikola', 'cognome' => 'PEEV', 'data' => '18.02.2004', 'numero' => '1', 'naz' => 'Bulgaria', 'alt' => '185 cm', 'peso' => '87 kg', 'htp' => 'SI'],
        ['ruolo' => 'POR', 'nome' => 'Federico', 'cognome' => 'CECCHINATO', 'data' => '15.09.2007', 'numero' => '18', 'naz' => 'Italia', 'alt' => '181 cm', 'peso' => '72 kg', 'htp' => 'SI'],
        ['ruolo' => 'POR', 'nome' => 'Mario Achille', 'cognome' => 'CASANOVA', 'data' => '20/5/2007', 'numero' => '98', 'naz' => 'Svizzera', 'alt' => '174 cm', 'peso' => '70 kg', 'htp' => 'SI'],
        ['ruolo' => 'DIF', 'nome' => 'Matthais', 'cognome' => 'SOLERIO', 'data' => '01.11.1992', 'numero' => '3', 'naz' => 'Italia', 'alt' => '188 cm', 'peso' => '85 kg', 'htp' => 'NO'],
        ['ruolo' => 'DIF', 'nome' => 'Gabriele', 'cognome' => 'STIVAL', 'data' => '10.09.2007', 'numero' => '5', 'naz' => 'Italia', 'alt' => '187 cm', 'peso' => '77 kg', 'htp' => 'NO'],
        ['ruolo' => 'DIF', 'nome' => 'Federico', 'cognome' => 'IVANAJ', 'data' => '05.02.2006', 'numero' => '13', 'naz' => 'Italia/Albania', 'alt' => '186 cm', 'peso' => '77 kg', 'htp' => 'SI'],
        ['ruolo' => 'DIF', 'nome' => 'Fabio', 'cognome' => 'NOTARESCHI', 'data' => '16.08.2006', 'numero' => '20', 'naz' => 'Italia', 'alt' => '178 cm', 'peso' => '72 kg', 'htp' => 'SI'],
        ['ruolo' => 'DIF', 'nome' => 'Fabio', 'cognome' => 'FESTA', 'data' => '08.01.2007', 'numero' => '27', 'naz' => 'Italia', 'alt' => '178 cm', 'peso' => '66 kg', 'htp' => 'SI'],
        ['ruolo' => 'DIF', 'nome' => 'Edoardo', 'cognome' => 'BORGHI', 'data' => '10/3/2006', 'numero' => '45', 'naz' => 'Italia', 'alt' => '191 cm', 'peso' => '78 kg', 'htp' => 'NO'],
        ['ruolo' => 'CEN', 'nome' => 'Vladimir', 'cognome' => 'PECHERSKYY', 'data' => '19.07.2006', 'numero' => '4', 'naz' => 'Ucraina', 'alt' => '176 cm', 'peso' => '63 kg', 'htp' => 'SI'],
        ['ruolo' => 'CEN', 'nome' => 'Diego', 'cognome' => 'TORRICINI', 'data' => '07.02.2007', 'numero' => '6', 'naz' => 'Italia', 'alt' => '172 cm', 'peso' => '67 kg', 'htp' => 'NO'],
        ['ruolo' => 'CEN', 'nome' => 'Maksim', 'cognome' => 'NOVOSELSKIY', 'data' => '26.07.2006', 'numero' => '8', 'naz' => 'Russia', 'alt' => '183 cm', 'peso' => '77 kg', 'htp' => 'SI'],
        ['ruolo' => 'CEN', 'nome' => 'Alessandro', 'cognome' => 'BIZZARRI', 'data' => '03.10.2007', 'numero' => '10', 'naz' => 'Italia', 'alt' => '179 cm', 'peso' => '71 kg', 'htp' => 'SI'],
        ['ruolo' => 'CEN', 'nome' => 'Jonathan Maximiliano', 'cognome' => 'SABBATINI PERFECTO', 'data' => '31.03.1988', 'numero' => '14', 'naz' => 'Uruguay', 'alt' => '176 cm', 'peso' => '72 kg', 'htp' => 'NO'],
        ['ruolo' => 'CEN', 'nome' => 'Luca', 'cognome' => 'SOLZI', 'data' => '06.06.2007', 'numero' => '21', 'naz' => 'Italia', 'alt' => '174 cm', 'peso' => '62 kg', 'htp' => 'SI'],
        ['ruolo' => 'CEN', 'nome' => 'Ivan', 'cognome' => 'IVANAJ', 'data' => '31.01.2006', 'numero' => '23', 'naz' => 'Italia/Albania', 'alt' => '174 cm', 'peso' => '66 kg', 'htp' => 'SI'],
        ['ruolo' => 'CEN', 'nome' => 'Filippo', 'cognome' => 'GRASSI', 'data' => '23.06.2003', 'numero' => '25', 'naz' => 'Italia', 'alt' => '181 cm', 'peso' => '79 kg', 'htp' => 'NO'],
        ['ruolo' => 'CEN', 'nome' => 'Christian', 'cognome' => 'BIANCHI', 'data' => '27.04.2005', 'numero' => '30', 'naz' => 'Italia', 'alt' => '178 cm', 'peso' => '67 kg', 'htp' => 'SI'],
        ['ruolo' => 'CEN', 'nome' => 'Arthur', 'cognome' => 'BANDEIRA DINIZ', 'data' => '06.05.2003', 'numero' => '77', 'naz' => 'Brasile/Svizzera', 'alt' => '179 cm', 'peso' => '75 kg', 'htp' => 'NO'],
        ['ruolo' => 'ATT', 'nome' => 'Luca', 'cognome' => 'ROSSETTO', 'data' => '14.05.2006', 'numero' => '7', 'naz' => 'Italia', 'alt' => '175 cm', 'peso' => '76 kg', 'htp' => 'SI'],
        ['ruolo' => 'ATT', 'nome' => 'Lorenzo', 'cognome' => 'ROSA', 'data' => '26.08.2005', 'numero' => '9', 'naz' => 'Svizzera', 'alt' => '181 cm', 'peso' => '80 kg', 'htp' => 'SI'],
        ['ruolo' => 'ATT', 'nome' => 'Armando', 'cognome' => 'REXHEPAJ', 'data' => '18.05.2002', 'numero' => '11', 'naz' => 'Italia/Albania', 'alt' => '180 cm', 'peso' => '69 kg', 'htp' => 'NO'],
        ['ruolo' => 'ATT', 'nome' => 'Issouf', 'cognome' => 'DIARRA', 'data' => '08.08.2005', 'numero' => '17', 'naz' => 'Italia', 'alt' => '183 cm', 'peso' => '81 kg', 'htp' => 'NO'],
        ['ruolo' => 'ATT', 'nome' => 'Pietro', 'cognome' => 'TORRICINI', 'data' => '07.02.2007', 'numero' => '29', 'naz' => 'Italia', 'alt' => '173 cm', 'peso' => '66 kg', 'htp' => 'NO'],
        ['ruolo' => 'ATT', 'nome' => 'Ismael', 'cognome' => 'ADEJUMO', 'data' => '5/12/2003', 'numero' => '78', 'naz' => 'Spagna', 'alt' => '193 cm', 'peso' => '89 kg', 'htp' => 'SI'],
    ];

    $ruoli_map = [
        'POR' => 'Portieri',
        'DIF' => 'Difensori',
        'CEN' => 'Centrocampisti',
        'ATT' => 'Attaccanti'
    ];

    $image_dir = '/Users/stanoje/Downloads/braccia conserte edit';
    $files = file_exists($image_dir) ? scandir($image_dir) : [];
    
    echo "<div style='background:#fff; padding:30px; position:absolute; z-index:9999; top:0; left:0; right:0; bottom:0; overflow:auto;'>";
    echo "<h1>Importazione Giocatori</h1><ul>";

    foreach ($players_data as $p) {
        $title = $p['nome'] . ' ' . ucfirst(strtolower($p['cognome']));
        $existing = get_page_by_title($title, OBJECT, 'giocatore');
        
        if ($existing) {
            $post_id = $existing->ID;
            echo "<li><strong>Aggiorno:</strong> {$title} (ID: {$post_id})</li>";
        } else {
            $post_data = array(
                'post_title'    => $title,
                'post_status'   => 'publish',
                'post_type'     => 'giocatore'
            );
            $post_id = wp_insert_post($post_data);
            echo "<li><strong>Creato:</strong> {$title} (ID: {$post_id})</li>";
        }

        // Taxonomies
        $term_name = isset($ruoli_map[$p['ruolo']]) ? $ruoli_map[$p['ruolo']] : 'Giocatori';
        if (!term_exists($term_name, 'ruolo_giocatore')) {
            wp_insert_term($term_name, 'ruolo_giocatore');
        }
        wp_set_object_terms($post_id, $term_name, 'ruolo_giocatore');

        // Meta Data
        update_post_meta($post_id, '_numero_maglia', $p['numero']);
        update_post_meta($post_id, '_data_nascita', $p['data']);
        update_post_meta($post_id, '_altezza', $p['alt']);
        update_post_meta($post_id, '_peso', $p['peso']);
        update_post_meta($post_id, '_nazionalita', $p['naz']);
        update_post_meta($post_id, '_htp', $p['htp']);
        
        // Attach image
        if (!has_post_thumbnail($post_id) && !empty($files)) {
            $found_img = false;
            $search_name = strtolower($p['nome'] . ' ' . $p['cognome']);
            
            $custom_searches = [
                'matthais solerio' => 'matthias solerio',
                'sabbatini perfecto' => 'sabbatini',
                'bandeira diniz' => 'bandeira',
                'mario achille' => 'mario-achille'
            ];
            
            foreach ($custom_searches as $k => $v) {
                if (strpos($search_name, $k) !== false) {
                    $search_name = str_replace($k, $v, $search_name);
                }
            }

            foreach ($files as $file) {
                if ($file == '.' || $file == '..') continue;
                $file_lower = strtolower($file);
                $n1 = explode(' ', strtolower($p['nome']))[0];
                $n2 = strtolower($p['cognome']);
                
                if (strpos($file_lower, $n1) !== false && strpos($file_lower, $n2) !== false) {
                    $found_img = $file;
                    break;
                }
            }

            if ($found_img) {
                $file_path = $image_dir . '/' . $found_img;
                $wp_filetype = wp_check_filetype(basename($file_path), null );
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_path ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );
                
                $upload_dir = wp_upload_dir();
                $file_content = file_get_contents($file_path);
                $new_file_path = $upload_dir['path'] . '/' . basename($file_path);
                file_put_contents($new_file_path, $file_content);

                $attach_id = wp_insert_attachment( $attachment, $new_file_path, $post_id );
                $attach_data = wp_generate_attachment_metadata( $attach_id, $new_file_path );
                wp_update_attachment_metadata( $attach_id, $attach_data );
                
                set_post_thumbnail( $post_id, $attach_id );
                echo "<ul><li>Foto caricata: {$found_img}</li></ul>";
            }
        }
    }
    
    // STAFF
    $staff_data = [
        ['nome' => 'Dagoberto', 'cognome' => 'Carbone', 'ruolo' => 'Allenatore', 'filename_hint' => 'carbone', 'data' => '18/2/1988', 'naz' => 'Italia'],
        ['nome' => 'Simone', 'cognome' => 'Zullo', 'ruolo' => 'Vice Allenatore', 'filename_hint' => 'zullo', 'data' => '20/9/1986', 'naz' => 'Italia'],
        ['nome' => 'Oscar', 'cognome' => 'Verderame', 'ruolo' => 'Allenatore dei portieri', 'filename_hint' => 'verderame', 'data' => '4/8/1971', 'naz' => 'Italia'],
        ['nome' => 'Matteo', 'cognome' => 'Arienti', 'ruolo' => 'Preparatore Atletico', 'filename_hint' => 'arienti', 'data' => '1/2/1993', 'naz' => 'Italia'],
        ['nome' => 'Tommaso', 'cognome' => 'Restelli', 'ruolo' => 'Fisioterapista', 'filename_hint' => 'restelli', 'data' => '6/6/2000', 'naz' => 'Italia'],
        ['nome' => 'Alessandro', 'cognome' => 'Biscotti', 'ruolo' => 'Direttore Generale', 'filename_hint' => 'biscotti', 'data' => '', 'naz' => ''],
        ['nome' => 'Alessandro', 'cognome' => 'Spoggi', 'ruolo' => 'Responsabile Markerting ed Eventi', 'filename_hint' => 'spoggi', 'data' => '', 'naz' => ''],
        ['nome' => 'Kubilay', 'cognome' => 'Türkyılmaz', 'ruolo' => 'Brand Ambassador', 'filename_hint' => 'kubilay', 'data' => '', 'naz' => ''],
        ['nome' => 'Milo', 'cognome' => 'Delorenzi', 'ruolo' => 'Membro del Comitato', 'filename_hint' => 'delorenzi', 'data' => '', 'naz' => ''],
        ['nome' => 'Riccardo', 'cognome' => 'Bonavetti', 'ruolo' => 'Segretario Generale', 'filename_hint' => 'bonavetti', 'data' => '', 'naz' => ''],
    ];

    echo "</ul><br><h1>Importazione Staff</h1><ul>";
    
    foreach ($staff_data as $index => $s) {
        $title = $s['nome'] . ' ' . $s['cognome'];
        
        // Find if they exist as 'giocatore' (from the previous run) or 'membro_staff'
        $existing = get_page_by_title($title, OBJECT, 'membro_staff');
        if (!$existing) {
            $existing = get_page_by_title($title, OBJECT, 'giocatore');
        }
        
        if ($existing) {
            $post_id = $existing->ID;
            // Force change to 'membro_staff' if it was incorrectly 'giocatore' and set menu_order
            wp_update_post(array(
                'ID' => $post_id,
                'post_type' => 'membro_staff',
                'menu_order' => $index,
            ));
            echo "<li><strong>Aggiorno/Correggo Staff:</strong> {$title} (ID: {$post_id})</li>";
        } else {
            $post_data = array(
                'post_title'    => $title,
                'post_status'   => 'publish',
                'post_type'     => 'membro_staff',
                'menu_order'    => $index,
            );
            $post_id = wp_insert_post($post_data);
            echo "<li><strong>Creato Staff:</strong> {$title} (ID: {$post_id})</li>";
        }

        // Meta Data (Ruolo Specifico, Data Nascita, Nazionalita)
        update_post_meta($post_id, '_ruolo_specifico', $s['ruolo']);
        update_post_meta($post_id, '_data_nascita', $s['data']);
        update_post_meta($post_id, '_nazionalita', $s['naz']);
        
        // Attach image
        if (!has_post_thumbnail($post_id) && !empty($files)) {
            $found_img = false;
            foreach ($files as $file) {
                if ($file == '.' || $file == '..') continue;
                if (strpos(strtolower($file), strtolower($s['filename_hint'])) !== false) {
                    $found_img = $file;
                    break;
                }
            }

            if ($found_img) {
                $file_path = $image_dir . '/' . $found_img;
                $wp_filetype = wp_check_filetype(basename($file_path), null );
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_path ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );
                
                $upload_dir = wp_upload_dir();
                $file_content = file_get_contents($file_path);
                $new_file_path = $upload_dir['path'] . '/' . basename($file_path);
                file_put_contents($new_file_path, $file_content);

                $attach_id = wp_insert_attachment( $attachment, $new_file_path, $post_id );
                $attach_data = wp_generate_attachment_metadata( $attach_id, $new_file_path );
                wp_update_attachment_metadata( $attach_id, $attach_data );
                
                set_post_thumbnail( $post_id, $attach_id );
                echo "<ul><li>Foto caricata: {$found_img}</li></ul>";
            }
        }
    }

    echo "</ul><h2>Importazione completata con successo! Rimuovi '?import_players_now=true' dall'URL per vedere il sito.</h2></div>";
    die();
}

// TEMPORARY SCRIPT TO IMPORT MATCHES
add_action('init', 'sport_theme_import_matches_once');
function sport_theme_import_matches_once() {
    if (!isset($_GET['import_matches'])) return;
    if (!current_user_can('manage_options')) return;

    $partite = [
        [
            'title' => 'SC YF Juventus vs AC Taverne',
            'data' => '23.05.2026',
            'ora' => '16:00',
            'avversario' => 'SC YF Juventus',
            'in_casa' => '0', // 0 = away
            'stadio' => 'Trasferta',
            'risultato' => '',
            'link' => ''
        ],
        [
            'title' => 'FC Mendrisio vs AC Taverne',
            'data' => '30.05.2026',
            'ora' => '16:00',
            'avversario' => 'FC Mendrisio',
            'in_casa' => '0',
            'stadio' => 'Trasferta',
            'risultato' => '',
            'link' => ''
        ],
        [
            'title' => 'FC Widnau vs AC Taverne',
            'data' => '10.05.2026',
            'ora' => '14:00',
            'avversario' => 'FC Widnau',
            'in_casa' => '0',
            'stadio' => 'Trasferta',
            'risultato' => '0 : 2',
            'link' => 'https://matchcenter.el-pl.ch/default.aspx?oid=3&lng=1&v=617&t=32750&sg=67666&ls=24469&tg=4157715'
        ],
        [
            'title' => 'AC Taverne vs SV Höngg',
            'data' => '16.05.2026',
            'ora' => '16:00',
            'avversario' => 'SV Höngg',
            'in_casa' => '1',
            'stadio' => 'Stadio Comunale Taverne',
            'risultato' => '3 : 1',
            'link' => 'https://matchcenter.el-pl.ch/default.aspx?oid=3&lng=1&v=617&t=32750&sg=67666&ls=24469&tg=4157725'
        ]
    ];

    echo "<div style='background:#fff; padding:30px; position:absolute; z-index:9999; top:0; left:0; right:0; bottom:0;'>";
    echo "<h1>Importazione Partite</h1><ul>";
    
    foreach ($partite as $p) {
        $existing = get_page_by_title($p['title'], OBJECT, 'partita');
        if (!$existing) {
            $post_id = wp_insert_post([
                'post_title' => $p['title'],
                'post_status' => 'publish',
                'post_type' => 'partita'
            ]);
        } else {
            $post_id = $existing->ID;
            echo "<li>Partita già esistente, aggiorno dati: " . $p['title'] . "</li>";
        }
        
        update_post_meta($post_id, '_data_partita', $p['data']);
        update_post_meta($post_id, '_ora_partita', $p['ora']);
        update_post_meta($post_id, '_avversario', $p['avversario']);
        update_post_meta($post_id, '_in_casa', $p['in_casa']);
        update_post_meta($post_id, '_stadio', $p['stadio']);
        update_post_meta($post_id, '_risultato', $p['risultato']);
        update_post_meta($post_id, '_match_link', $p['link']);
        
        if (!$existing) echo "<li>Partita aggiunta: " . $p['title'] . "</li>";
    }
    
    echo "</ul><h2>Importazione completata con successo! Rimuovi '?import_matches=true' dall'URL.</h2></div>";
    die();
}

// TEMPORARY SCRIPT TO INSERT TABLE IN STAGIONE
add_action('init', 'sport_theme_insert_table_once');
function sport_theme_insert_table_once() {
    if (!isset($_GET['insert_table'])) return;
    if (!current_user_can('manage_options')) return;

    $page = get_page_by_path('stagione');
    if (!$page) $page = get_page_by_title('Stagione');

    if ($page) {
        $table_content = '
<!-- wp:table {"className":"is-style-regular"} -->
<figure class="wp-block-table is-style-regular"><table>
<thead><tr><th>POS</th><th>SQUADRA</th><th>G</th><th>V</th><th>N</th><th>P</th><th>Pti</th></tr></thead>
<tbody>
<tr><td>1</td><td>SC YF Juventus</td><td>28</td><td>22</td><td>3</td><td>3</td><td>69</td></tr>
<tr><td>2</td><td>AC Taverne</td><td>28</td><td>18</td><td>4</td><td>6</td><td>58</td></tr>
<tr><td>3</td><td>FC Tuggen</td><td>28</td><td>18</td><td>4</td><td>6</td><td>58</td></tr>
<tr><td>4</td><td>FC Wettswil-Bonstetten</td><td>28</td><td>17</td><td>5</td><td>6</td><td>56</td></tr>
<tr><td>5</td><td>FC Dietikon</td><td>28</td><td>11</td><td>8</td><td>9</td><td>41</td></tr>
<tr><td>6</td><td>FC Baden 1897</td><td>28</td><td>11</td><td>7</td><td>10</td><td>40</td></tr>
<tr><td>7</td><td>FC Winterthur U-21</td><td>28</td><td>10</td><td>9</td><td>9</td><td>39</td></tr>
<tr><td>8</td><td>FC Freienbach</td><td>28</td><td>10</td><td>7</td><td>11</td><td>37</td></tr>
<tr><td>9</td><td>FC Kosova</td><td>28</td><td>9</td><td>9</td><td>10</td><td>36</td></tr>
<tr><td>10</td><td>FC Mendrisio</td><td>28</td><td>10</td><td>6</td><td>12</td><td>36</td></tr>
<tr><td>11</td><td>FC Collina d\'Oro</td><td>28</td><td>9</td><td>8</td><td>11</td><td>35</td></tr>
<tr><td>12</td><td>FC St. Gallen 1879 U-21</td><td>28</td><td>9</td><td>4</td><td>15</td><td>31</td></tr>
<tr><td>13</td><td>USV Eschen/Mauren</td><td>28</td><td>6</td><td>11</td><td>11</td><td>29</td></tr>
<tr><td>14</td><td>SV Höngg</td><td>28</td><td>5</td><td>6</td><td>17</td><td>21</td></tr>
<tr><td>15</td><td>FC Widnau</td><td>28</td><td>5</td><td>6</td><td>17</td><td>21</td></tr>
<tr><td>16</td><td>SV Schaffhausen</td><td>28</td><td>3</td><td>5</td><td>20</td><td>14</td></tr>
</tbody>
</table></figure>
<!-- /wp:table -->';

        wp_update_post([
            'ID' => $page->ID,
            'post_content' => $table_content
        ]);
        
        echo "<div style='background:#fff; padding:30px; position:absolute; z-index:9999; top:0; left:0; right:0; bottom:0;'>";
        echo "<h1>Classifica inserita!</h1><p>Tabella inserita correttamente nella pagina Stagione.</p><h2>Rimuovi '?insert_table=true' dall'URL.</h2></div>";
    } else {
        echo "<div style='background:#fff; padding:30px; position:absolute; z-index:9999; top:0; left:0; right:0; bottom:0;'>";
        echo "<h1>Errore!</h1><p>Pagina Stagione non trovata.</p></div>";
    }
    die();
}

// TEMPORARY SCRIPT TO IMPORT ORGANIGRAMMA
add_action('init', 'sport_theme_import_organigramma_finale');
function sport_theme_import_organigramma_finale() {
    if (!isset($_GET['import_organigramma'])) return;
    if (!current_user_can('manage_options')) return;

    $people = [
        ['name' => 'Alessandro Biscotti', 'role' => 'DIRETTORE GENERALE', 'area' => 'DIREZIONE'],
        ['name' => 'Alessandro Biscotti', 'role' => 'DIRETTORE SPORTIVO', 'area' => 'AREA MANAGEMENT SPORTIVO'],
        ['name' => 'Kubilay Türkyılmaz', 'role' => 'BRAND AMBASSADOR', 'area' => 'AREA MANAGEMENT SPORTIVO'],
        ['name' => 'Luca Defranceschi', 'role' => 'SCOUTING', 'area' => 'AREA MANAGEMENT SPORTIVO'],
        
        ['name' => 'Riccardo Bonavetti', 'role' => 'SEGRETARIO GENERALE', 'area' => 'AREA CORPORATE'],
        ['name' => 'Alessandro Spoggi', 'role' => 'RESPONSABILE MARKETING', 'area' => 'AREA CORPORATE'],
        ['name' => 'Alessandro Spoggi', 'role' => 'RESPONSABILE EVENTI', 'area' => 'AREA CORPORATE'],
        ['name' => 'Niccolò Crespi', 'role' => 'RESPONSABILE COMUNICAZIONE', 'area' => 'AREA CORPORATE'],
        ['name' => 'Milo Delorenzi', 'role' => 'MEMBRO DEL COMITATO', 'area' => 'AREA CORPORATE'],
        
        ['name' => 'Dagoberto Carbone', 'role' => 'ALLENATORE', 'area' => 'STAFF TECNICO'],
        ['name' => 'Simone Zullo', 'role' => 'VICE ALLENATORE', 'area' => 'STAFF TECNICO'],
        ['name' => 'Oscar Verderame', 'role' => 'ALLENATORE DEI PORTIERI', 'area' => 'STAFF TECNICO'],
        ['name' => 'Matteo Arienti', 'role' => 'RESPONSABILE AREA ATLETICA', 'area' => 'STAFF TECNICO'],
        ['name' => 'Domenico Pallone', 'role' => 'MAGAZZINIERE', 'area' => 'STAFF TECNICO'],
        
        ['name' => 'FOURFISIO', 'role' => 'AREA MEDICA', 'area' => 'AREA MEDICA']
    ];

    echo "<div style='background:#fff; padding:30px; position:absolute; z-index:9999; top:0; left:0; right:0; bottom:0;'>";
    echo "<h1>Importazione Organigramma Finale</h1><ul>";

    $menu_order = 1;
    foreach ($people as $p) {
        $post_data = array(
            'post_title'    => $p['name'],
            'post_status'   => 'publish',
            'post_type'     => 'dirigente',
            'menu_order'    => $menu_order
        );
        
        $post_id = wp_insert_post($post_data);
        
        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, '_ruolo_specifico', $p['role']);
            update_post_meta($post_id, '_sezione_comitato', 'prima-squadra');
            update_post_meta($post_id, '_area_organigramma', $p['area']);
            
            global $wpdb;
            $attachment = $wpdb->get_row($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_title LIKE %s LIMIT 1", '%' . $p['name'] . '%'));
            if ($attachment) {
                set_post_thumbnail($post_id, $attachment->ID);
                echo "<li>Aggiunto: " . $p['name'] . " (" . $p['role'] . ") - CON FOTO</li>";
            } else {
                echo "<li>Aggiunto: " . $p['name'] . " (" . $p['role'] . ") - FOTO NON TROVATA</li>";
            }
        }
        $menu_order++;
    }

    echo "</ul><h2>Completato. Vai alla pagina Organigramma.</h2></div>";
    die();
}

// TEMPORARY SCRIPT TO IMPORT STORIA
add_action('init', 'sport_theme_import_storia');
function sport_theme_import_storia() {
    if (!isset($_GET['import_storia'])) return;
    if (!current_user_can('manage_options')) return;

    $content = "La storia della prima squadra di Taverne affonda le sue radici negli anni Venti, quando nacque il Football Club Stella Taverne, prima vera formazione locale, seppur con notizie frammentarie e denominazioni variabili. Inizialmente si giocava nella zona di Livorno, lungo il corso del Vedeggio, con una caratteristica maglia nera impreziosita da una stella bianca sul petto. Successivamente il campo si spostò a Taverne Superiore, nel Comune di Sigirino, e negli anni Quaranta, con il F.C. Taverne, nell’area della stazione, tra il fiume e la ferrovia.

Nel secondo dopoguerra nacque l’Associazione Calcio Taverne, sostenuta da grande entusiasmo popolare. La società divenne un punto di riferimento sportivo per tutta la valle, grazie a un ambiente favorevole, buoni allenatori e una solida comunità. A questo periodo, definito “eroico”, sono legati nomi importanti come Mario Banfi, Flaminio Petrocchi e Gino Gova, mentre già negli anni Trenta si distinsero a livello regionale i fratelli Zambelli, in particolare il portiere Emilio, soprannominato “Zamorra”.

Un momento chiave arrivò nel 1950, quando l’AC Taverne entrò ufficialmente nella Federazione calcistica ticinese partecipando al campionato di Quarta Divisione, ottenendo subito la promozione in Terza. La crescita proseguì fino alla stagione 1956-57, in cui, sotto la guida di Bruno Passardi, la squadra conquistò il titolo di campione di Terza Divisione. L’anno successivo, con Dino Leoni alla guida, il Taverne si confermò vincendo sia il campionato sia il Trofeo Ticino, segnando una delle pagine più significative della sua storia.

Nel corso della sua storia, il Taverne ha costruito un percorso solido e coerente, caratterizzato da tappe significative e da una crescita costante nel panorama calcistico regionale e nazionale.

Tra gli anni Ottanta e Novanta, la prima squadra ha partecipato con regolarità ai campionati di Seconda e Terza Lega, consolidando la propria presenza e gettando le basi per i successi futuri. Un primo importante salto di qualità si registra nella stagione 2009-2010, quando il Taverne conquista il terzo rango in Seconda Lega regionale ottenendo la promozione in Seconda Lega Interregionale. Si tratta di un traguardo storico, che segna l’ingresso del club, a partire dal 2010, in un contesto di competizione nazionale.

Nel campionato 2011-2012, il Taverne tenta la scalata alla Seconda Lega élite, categoria già raggiunta con merito due anni prima e sfortunatamente persa nella stagione successiva. L’annata seguente, 2012-2013, si apre con segnali positivi: la squadra si presenta briosa e promettente, mantenendo il primo posto in classifica al termine del girone d’andata.

Parallelamente ai risultati della prima squadra, il club ha sempre attribuito grande importanza al settore giovanile. I ragazzi delle categorie Allievi, sempre più numerosi, rappresentano una risorsa fondamentale e una prospettiva concreta per il futuro. Il loro sviluppo è affidato a dirigenti, allenatori e preparatori che privilegiano una crescita progressiva e duratura rispetto ai risultati immediati.

A partire dalla stagione 2019-2020, il Taverne milita stabilmente in Prima Lega Classic, raggiungendo il livello più alto nella storia della società dopo alcune stagioni di consolidamento nei campionati interregionali.

Nel corso degli anni, il club ha collezionato numerosi successi, tra cui:
<ul>
<li>Campione ticinese di Terza Divisione e promozione in Seconda Lega (stagione 1956-1957)</li>
<li>Campione ticinese di Seconda Divisione (stagione 1958-1959)</li>
<li>Vincitore di gruppo di Terza Divisione e promozione in Seconda Lega (stagione 1979-1980)</li>
<li>Vincitore di gruppo di Terza Lega e promozione in Seconda Lega (stagioni 1992-1993 e 2004-2005)</li>
<li>Terzo posto in Seconda Lega regionale e promozione in Seconda Lega Interregionale (stagione 2009-2010)</li>
</ul>

A questi si aggiungono i risultati del settore giovanile e della seconda squadra:
<ul>
<li>Campione ticinese Allievi A e promozione nella categoria Interregionale (stagione 1986-1987)</li>
<li>Seconda squadra campione di gruppo in Quinta Lega e promossa in Quarta Lega (stagione 2007-2008)</li>
</ul>

Di particolare rilievo anche il percorso nelle competizioni regionali: il Taverne ha conquistato sei Coppe Ticino, stabilendo un record prestigioso, e ha ottenuto un primo e un secondo posto nella Coppa Campioni del calcio regionale ticinese.

Dalla stagione attuale, la prima squadra si presenta con un nuovo assetto societario, segnando l’inizio di una nuova fase nel percorso di sviluppo del club, nel segno della continuità e dell’attenzione alla propria storia.";

    $page = get_page_by_path('storia');
    if ($page) {
        wp_update_post([
            'ID' => $page->ID,
            'post_content' => wpautop($content)
        ]);
        update_post_meta($page->ID, '_wp_page_template', 'template-storia.php');
    } else {
        $post_id = wp_insert_post([
            'post_title' => 'Storia',
            'post_name' => 'storia',
            'post_content' => wpautop($content),
            'post_status' => 'publish',
            'post_type' => 'page'
        ]);
        update_post_meta($post_id, '_wp_page_template', 'template-storia.php');
    }

    echo "<div style='background:#fff; padding:30px; position:absolute; z-index:9999; top:0; left:0; right:0; bottom:0;'>";
    echo "<h1>Storia inserita!</h1><p>Pagina Storia creata con il testo fornito.</p><h2>Rimuovi '?import_storia=true' dall'URL.</h2></div>";
    die();
}

// TEMPORARY SCRIPT TO UPDATE STORIA
add_action('init', 'sport_theme_update_storia_format');
function sport_theme_update_storia_format() {
    if (!isset($_GET['update_storia'])) return;
    if (!current_user_can('manage_options')) return;

    $page = get_page_by_path('storia');
    if (!$page) return;

    $subtitle = "La storia della prima squadra di Taverne affonda le sue radici negli anni Venti, quando nacque il Football Club Stella Taverne, prima vera formazione locale, seppur con notizie frammentarie e denominazioni variabili. Inizialmente si giocava nella zona di Livorno, lungo il corso del Vedeggio, con una caratteristica maglia nera impreziosita da una stella bianca sul petto. Successivamente il campo si spostò a Taverne Superiore, nel Comune di Sigirino, e negli anni Quaranta, con il F.C. Taverne, nell’area della stazione, tra il fiume e la ferrovia.";

    $content = <<<HTML
<h3>IDENTITÀ DEL CLUB</h3>
<h4>Nel secondo dopoguerra nacque l’Associazione Calcio Taverne, sostenuta da grande entusiasmo popolare.</h4>
<p>La società divenne un punto di riferimento sportivo per tutta la valle, grazie a un ambiente favorevole, buoni allenatori e una solida comunità. A questo periodo, definito “eroico”, sono legati nomi importanti come Mario Banfi, Flaminio Petrocchi e Gino Gova, mentre già negli anni Trenta si distinsero a livello regionale i fratelli Zambelli, in particolare il portiere Emilio, soprannominato “Zamorra”.</p>
<p>Un momento chiave arrivò nel 1950, quando l’AC Taverne entrò ufficialmente nella Federazione calcistica ticinese partecipando al campionato di Quarta Divisione, ottenendo subito la promozione in Terza. La crescita proseguì fino alla stagione 1956-57, in cui, sotto la guida di Bruno Passardi, la squadra conquistò il titolo di campione di Terza Divisione. L’anno successivo, con Dino Leoni alla guida, il Taverne si confermò vincendo sia il campionato sia il Trofeo Ticino, segnando una delle pagine più significative della sua storia.</p>

<h3>EVOLUZIONE NEL TEMPO</h3>
<h4>Nel corso della sua storia, il Taverne ha costruito un percorso solido e coerente, caratterizzato da tappe significative e da una crescita costante nel panorama calcistico regionale e nazionale.</h4>
<p>Tra gli anni Ottanta e Novanta, la prima squadra ha partecipato con regolarità ai campionati di Seconda e Terza Lega, consolidando la propria presenza e gettando le basi per i successi futuri. Un primo importante salto di qualità si registra nella stagione 2009-2010, quando il Taverne conquista il terzo rango in Seconda Lega regionale ottenendo la promozione in Seconda Lega Interregionale. Si tratta di un traguardo storico, che segna l’ingresso del club, a partire dal 2010, in un contesto di competizione nazionale.</p>
<p>Nel campionato 2011-2012, il Taverne tenta la scalata alla Seconda Lega élite, categoria già raggiunta con merito due anni prima e sfortunatamente persa nella stagione successiva. L’annata seguente, 2012-2013, si apre con segnali positivi: la squadra si presenta briosa e promettente, mantenendo il primo posto in classifica al termine del girone d’andata.</p>
<p>A partire dalla stagione 2019-2020, il Taverne milita stabilmente in Prima Lega Classic, raggiungendo il livello più alto nella storia della società dopo alcune stagioni di consolidamento nei campionati interregionali.</p>

<h3>IL SETTORE GIOVANILE E I SUCCESSI</h3>
<h4>Parallelamente ai risultati della prima squadra, il club ha sempre attribuito grande importanza al settore giovanile. I ragazzi delle categorie Allievi, sempre più numerosi, rappresentano una risorsa fondamentale e una prospettiva concreta per il futuro.</h4>
<p>Il loro sviluppo è affidato a dirigenti, allenatori e preparatori che privilegiano una crescita progressiva e duratura rispetto ai risultati immediati.</p>
<p>Nel corso degli anni, il club ha collezionato numerosi successi, tra cui:</p>
<ul>
<li>Campione ticinese di Terza Divisione e promozione in Seconda Lega (stagione 1956-1957)</li>
<li>Campione ticinese di Seconda Divisione (stagione 1958-1959)</li>
<li>Vincitore di gruppo di Terza Divisione e promozione in Seconda Lega (stagione 1979-1980)</li>
<li>Vincitore di gruppo di Terza Lega e promozione in Seconda Lega (stagioni 1992-1993 e 2004-2005)</li>
<li>Terzo posto in Seconda Lega regionale e promozione in Seconda Lega Interregionale (stagione 2009-2010)</li>
</ul>
<p>A questi si aggiungono i risultati del settore giovanile e della seconda squadra:</p>
<ul>
<li>Campione ticinese Allievi A e promozione nella categoria Interregionale (stagione 1986-1987)</li>
<li>Seconda squadra campione di gruppo in Quinta Lega e promossa in Quarta Lega (stagione 2007-2008)</li>
</ul>
<p>Di particolare rilievo anche il percorso nelle competizioni regionali: il Taverne ha conquistato sei Coppe Ticino, stabilendo un record prestigioso, e ha ottenuto un primo e un secondo posto nella Coppa Campioni del calcio regionale ticinese.</p>
<p>Dalla stagione attuale, la prima squadra si presenta con un nuovo assetto societario, segnando l’inizio di una nuova fase nel percorso di sviluppo del club, nel segno della continuità e dell’attenzione alla propria storia.</p>
HTML;

    wp_update_post([
        'ID' => $page->ID,
        'post_content' => $content,
        'post_excerpt' => $subtitle
    ]);

    echo "<div style='background:#fff; padding:30px; position:absolute; z-index:9999; top:0; left:0; right:0; bottom:0;'>";
    echo "<h1>Storia Aggiornata!</h1><p>Testo formattato inserito con successo.</p><h2>Rimuovi '?update_storia=true' dall'URL.</h2></div>";
    die();
}

/**
 * Ottiene il nome dello stadio per una partita.
 * Se lo stadio è impostato su 'Trasferta' o è vuoto, effettua un mapping dinamico in base all'avversario.
 */
function sport_theme_get_match_stadium($post_id) {
    $stadio = get_post_meta($post_id, '_stadio', true);
    $avversario = get_post_meta($post_id, '_avversario', true);
    $in_casa = get_post_meta($post_id, '_in_casa', true);

    if ($in_casa == '1') {
        if ($stadio === 'Trasferta' || empty($stadio)) {
            return 'Stadio Comunale Taverne';
        }
    } else {
        if ($stadio === 'Trasferta' || empty($stadio)) {
            if (stripos($avversario, 'Juventus') !== false) {
                return 'Utogrund';
            } elseif (stripos($avversario, 'Mendrisio') !== false) {
                return 'Campo Comunale Mendrisio';
            } elseif (stripos($avversario, 'Widnau') !== false) {
                return 'Sportanlage Aegeten';
            }
        }
    }
    return $stadio;
}

/**
 * Ottiene l'URL del logo dell'avversario.
 * Se non è impostato, tenta di mappare loghi locali se disponibili.
 */
function sport_theme_get_opponent_logo($post_id) {
    $logo = get_post_meta($post_id, '_logo_avversario', true);
    $avversario = get_post_meta($post_id, '_avversario', true);

    if (empty($logo) || stripos($logo, 'placeholder') !== false || stripos($logo, 'via.placeholder.com') !== false) {
        if (stripos($avversario, 'Mendrisio') !== false) {
            return get_stylesheet_directory_uri() . '/assets/images/mendrisio-logo.png';
        }
    }
    return $logo ? $logo : 'https://via.placeholder.com/40';
}

// TEMPORARY SCRIPT TO FIX STADIUMS IN DB
add_action('init', 'sport_theme_fix_stadiums_in_db');
function sport_theme_fix_stadiums_in_db() {
    if (!isset($_GET['fix_stadiums'])) return;
    if (!current_user_can('manage_options')) return;

    $args = [
        'post_type' => 'partita',
        'posts_per_page' => -1
    ];
    $query = new WP_Query($args);
    
    echo "<div style='background:#fff; padding:30px; position:absolute; z-index:9999; top:0; left:0; right:0; bottom:0;'>";
    echo "<h1>Aggiornamento Stadi in corso...</h1><ul>";
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $stadio = get_post_meta($id, '_stadio', true);
            $avversario = get_post_meta($id, '_avversario', true);
            
            if ($stadio === 'Trasferta' || empty($stadio)) {
                $new_stadio = '';
                if (stripos($avversario, 'Juventus') !== false) {
                    $new_stadio = 'Utogrund';
                } elseif (stripos($avversario, 'Mendrisio') !== false) {
                    $new_stadio = 'Campo Comunale Mendrisio';
                } elseif (stripos($avversario, 'Widnau') !== false) {
                    $new_stadio = 'Sportanlage Aegeten';
                }
                
                if ($new_stadio) {
                    update_post_meta($id, '_stadio', $new_stadio);
                    echo "<li>Partita: <b>" . get_the_title() . "</b> - Stadio aggiornato da 'Trasferta' a: <b>" . $new_stadio . "</b></li>";
                } else {
                    echo "<li>Partita: <b>" . get_the_title() . "</b> - Stadio è 'Trasferta', nessun mapping trovato per avversario: <b>" . $avversario . "</b></li>";
                }
            } else {
                echo "<li>Partita: <b>" . get_the_title() . "</b> - Stadio già impostato a: " . $stadio . "</li>";
            }
        }
        wp_reset_postdata();
    }
    
    echo "</ul><h2>Finito! Rimuovi '?fix_stadiums=true' dall'URL.</h2></div>";
    die();
}

/**
 * Renders the horizontal sub-menu for the AC Taverne (La Societa) section.
 */
function sport_theme_render_societa_submenu() {
    $menu_items = [
        'La Società'     => site_url('/la-societa'),
        'Comitato'       => site_url('/comitato'),
        'Club dei 100'   => site_url('/club-dei-100'),
        'Area Riservata' => site_url('/area-allenatori'),
    ];

    echo '<div class="page-submenu" style="display: flex; gap: 20px; margin-top: 30px; margin-bottom: 10px; flex-wrap: wrap; justify-content: flex-start; z-index: 10; position: relative;">';
    foreach ( $menu_items as $label => $url ) {
        $is_active = false;
        
        if ( $label === 'La Società' && (is_page('la-societa') || is_page_template('template-la-societa.php')) ) {
            $is_active = true;
        } elseif ( $label === 'Comitato' && (is_page('comitato') || is_page_template('template-comitato-societa.php')) ) {
            $is_active = true;
        } elseif ( $label === 'Club dei 100' && (is_page('club-dei-100') || is_page_template('template-club-dei-100.php')) ) {
            $is_active = true;
        } elseif ( $label === 'Area Riservata' && (is_page('area-allenatori') || is_page_template('template-allenatori.php')) ) {
            $is_active = true;
        }

        $bg_color     = $is_active ? 'var(--c-primary)' : 'transparent';
        $text_color   = $is_active ? '#000' : 'white';
        $border_color = $is_active ? 'var(--c-primary)' : 'white';
        $hover_class  = $is_active ? '' : 'btn-outline-hover';

        echo '<h4 style="margin: 0; display: inline-block;">';
        echo '<a href="' . esc_url($url) . '" class="' . $hover_class . '" style="padding: 10px 30px; font-weight: 700; text-transform: uppercase; font-size: 13px; text-decoration: none; border: 2px solid ' . $border_color . '; background-color: ' . $bg_color . '; color: ' . $text_color . '; transition: all 0.3s; display: inline-block; text-align: center; min-width: 150px;">' . esc_html($label) . '</a>';
        echo '</h4>';
    }
    echo '</div>';
}

/**
 * Helper to filter out past events (older than 60 days) to optimize size and parse times (Option B)
 */
function sport_theme_filter_old_ics_events( $ics_content ) {
    if ( empty( $ics_content ) ) {
        return '';
    }
    
    // Normalize newlines
    $ics_content = str_replace( array("\r\n", "\r"), "\n", $ics_content );
    // Handle folded lines
    $ics_content = preg_replace( "/\n[ \t]/", "", $ics_content );
    
    $parts = explode( "BEGIN:VEVENT", $ics_content );
    $header = $parts[0];
    $footer = '';
    
    $filtered_events = array();
    // Keep events from up to 60 days (2 months) ago
    $start_cutoff = time() - ( 60 * 24 * 60 * 60 );
    
    for ( $i = 1; $i < count( $parts ); $i++ ) {
        $event_part = $parts[$i];
        $subparts = explode( "END:VEVENT", $event_part );
        $vevent_content = $subparts[0];
        $trailing = $subparts[1] ?? '';
        
        if ( $i === count( $parts ) - 1 ) {
            $footer = $trailing;
        }
        
        $keep = true;
        // Keep all recurring events as they might occur within our visible range
        $is_recurring = ( stripos( $vevent_content, 'RRULE:' ) !== false );
        
        if ( ! $is_recurring ) {
            if ( preg_match( '/^DTSTART(?:;[^:]*)?:(\d{8})/m', $vevent_content, $matches ) ) {
                $date_str = $matches[1];
                $event_time = strtotime( substr( $date_str, 0, 4 ) . '-' . substr( $date_str, 4, 2 ) . '-' . substr( $date_str, 6, 2 ) );
                if ( $event_time && $event_time < $start_cutoff ) {
                    $keep = false;
                }
            }
        }
        
        if ( $keep ) {
            $filtered_events[] = "BEGIN:VEVENT" . $vevent_content . "END:VEVENT";
        }
    }
    
    return $header . implode( "\n", $filtered_events ) . "\n" . $footer;
}

/**
 * AJAX Handler to proxy Google Calendar ICS files (Option B)
 */
add_action( 'wp_ajax_get_calendar_ics', 'sport_theme_get_calendar_ics' );
add_action( 'wp_ajax_nopriv_get_calendar_ics', 'sport_theme_get_calendar_ics' );

function sport_theme_get_calendar_ics() {
    $post_id = intval( $_GET['post_id'] ?? 0 );
    $field = sanitize_text_field( $_GET['field'] ?? '' );
    
    $valid_fields = array(
        'campo' => '_infra_calendar_iframe',
        'buvette' => '_infra_calendar_buvette_iframe',
        'infra' => '_infra_calendar_infra_iframe'
    );
    
    if ( ! $post_id || ! isset( $valid_fields[$field] ) ) {
        wp_die( 'Richiesta non valida', 400 );
    }
    
    $iframe_code = get_post_meta( $post_id, $valid_fields[$field], true );
    
    // Default fallback calendar IDs if meta is empty
    $default_ids = array(
        'campo' => 'q5annq4orol4ue2pipv70hlmsc@group.calendar.google.com',
        'buvette' => 'f7b2100de53n0cp2a4nc700i9s@group.calendar.google.com',
        'infra' => 'i9i8o8n999k36rfllaua5aoes0@group.calendar.google.com'
    );
    
    $calendar_id = $default_ids[$field];
    
    // If iframe is specified, extract the calendar ID from it
    if ( ! empty( $iframe_code ) ) {
        if ( preg_match( '/src=["\']([^"\']+)["\']/', $iframe_code, $matches ) ) {
            $url = html_entity_decode( $matches[1] );
            $parts = parse_url( $url );
            if ( isset( $parts['query'] ) ) {
                parse_str( $parts['query'], $query );
                if ( isset( $query['src'] ) ) {
                    $calendar_id = $query['src'];
                }
            }
        }
    }
    
    $ics_url = 'https://calendar.google.com/calendar/ical/' . urlencode( $calendar_id ) . '/public/basic.ics';
    
    // Fetch using WordPress HTTP API with caching
    $transient_key = 'cal_ics_' . md5( $ics_url );
    
    // Only bypass cache if explicitly requested via ?nocache=1 or ?nocache=true
    $bypass_cache = isset( $_GET['nocache'] ) && ( $_GET['nocache'] === '1' || $_GET['nocache'] === 'true' );
    $body = $bypass_cache ? false : get_transient( $transient_key );
    
    if ( false === $body ) {
        $response = wp_remote_get( $ics_url, array( 'timeout' => 12 ) );
        if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
            $raw_body = wp_remote_retrieve_body( $response );
            // Filter out old events on the server side to speed up downloads and browser rendering
            $body = sport_theme_filter_old_ics_events( $raw_body );
            // Cache for 12 hours
            set_transient( $transient_key, $body, 12 * HOUR_IN_SECONDS );
        }
    }
    
    if ( empty( $body ) ) {
        wp_die( 'Errore nel recupero del calendario', 500 );
    }
    
    if ( ob_get_length() ) {
        ob_clean();
    }
    header( 'Content-Type: text/calendar; charset=utf-8' );
    header( 'Access-Control-Allow-Origin: *' );
    echo trim( $body );
    exit;
}

/**
 * ----------------------------------------------------
 * METABOX PER POSIZIONE E ZOOM DELL'IMMAGINE HERO
 * ----------------------------------------------------
 */
function sport_theme_hero_settings_metabox() {
    $post_types = array( 'page', 'post', 'evento', 'squadra_sezione' );
    foreach ( $post_types as $post_type ) {
        add_meta_box(
            'sport_theme_hero_settings',
            'Impostazioni Immagine Hero',
            'sport_theme_hero_settings_html',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'sport_theme_hero_settings_metabox' );

function sport_theme_hero_settings_html( $post ) {
    wp_nonce_field( 'salva_hero_settings_meta', 'hero_settings_meta_nonce' );

    $position_y = get_post_meta( $post->ID, '_hero_position_y', true );
    $zoom = get_post_meta( $post->ID, '_hero_zoom', true );

    // Defaults
    if ( $position_y === '' ) {
        $position_y = 50; // Default to center
    }
    if ( $zoom === '' ) {
        $zoom = 100; // Default to 100%
    }
    ?>
    <div class="hero-settings-meta-box">
        <style>
            .hero-meta-field { margin-bottom: 20px; }
            .hero-meta-field label { display: block; font-weight: bold; margin-bottom: 8px; font-size: 14px; }
            .hero-meta-field input[type="range"] { width: 100%; max-width: 400px; vertical-align: middle; }
            .hero-meta-field .val-display { font-weight: bold; font-size: 14px; color: #007cba; margin-left: 8px; }
        </style>
        
        <div class="hero-meta-field">
            <label for="hero_position_y">Posizionamento Verticale (Y): <span id="hero_pos_y_val" class="val-display"><?php echo esc_html( $position_y ); ?>%</span></label>
            <input type="range" id="hero_position_y" name="_hero_position_y" min="0" max="100" value="<?php echo esc_attr( $position_y ); ?>" oninput="document.getElementById('hero_pos_y_val').innerText = this.value + '%';">
            <p class="description" style="margin-top: 5px; font-size: 11px;">Muovi lo slider per spostare l'immagine verso l'alto (0% = Alto) o verso il basso (100% = Basso).</p>
        </div>

        <div class="hero-meta-field">
            <label for="hero_zoom">Zoom Immagine: <span id="hero_zoom_val" class="val-display"><?php echo esc_html( $zoom ); ?>%</span></label>
            <input type="range" id="hero_zoom" name="_hero_zoom" min="100" max="300" step="5" value="<?php echo esc_attr( $zoom ); ?>" oninput="document.getElementById('hero_zoom_val').innerText = this.value + '%';">
            <p class="description" style="margin-top: 5px; font-size: 11px;">100% = Dimensione normale, fino a 300% per ingrandire.</p>
        </div>
    </div>
    <?php
}

function sport_theme_save_hero_settings_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Resolve parent ID if this is a revision
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }

    if ( isset( $_POST['_hero_position_y'] ) ) {
        $pos_y = intval( $_POST['_hero_position_y'] );
        update_post_meta( $post_id, '_hero_position_y', $pos_y );
    }

    if ( isset( $_POST['_hero_zoom'] ) ) {
        $zoom = intval( $_POST['_hero_zoom'] );
        update_post_meta( $post_id, '_hero_zoom', $zoom );
    }
}
add_action( 'save_post', 'sport_theme_save_hero_settings_meta' );

/**
 * Output dei CSS dinamici per l'immagine Hero
 */
function sport_theme_render_hero_custom_styles() {
    $post_id = get_queried_object_id();
    if ( ! $post_id ) {
        return;
    }

    $position_y = get_post_meta( $post_id, '_hero_position_y', true );
    $zoom = get_post_meta( $post_id, '_hero_zoom', true );

    // Se entrambi i valori sono vuoti, verifichiamo l'ereditarietà in base al template
    if ( $position_y === '' && $zoom === '' ) {
        $fallback_post = null;
        
        // 1. Pagine Club: fallback alla pagina con slug 'club'
        if ( is_page_template( array( 'template-organigramma.php', 'template-storia.php', 'template-club-page.php', 'template-comitato-societa.php' ) ) || is_page( array( 'organigramma', 'storia', 'progetto-sportivo' ) ) ) {
            $fallback_post = get_page_by_path( 'club' );
            if ( ! $fallback_post ) {
                $fallback_post = get_page_by_title( 'Club' );
            }
        }
        // 2. Pagine Team/Prima Squadra: fallback alla pagina con slug 'team'
        elseif ( is_page_template( array( 'template-staff.php', 'template-rosa.php', 'template-prima-squadra.php' ) ) || is_page( array( 'staff', 'rosa', 'prima-squadra' ) ) ) {
            $fallback_post = get_page_by_path( 'team' );
            if ( ! $fallback_post ) {
                $fallback_post = get_page_by_title( 'Team' );
            }
        }

        if ( $fallback_post ) {
            $position_y = get_post_meta( $fallback_post->ID, '_hero_position_y', true );
            $zoom = get_post_meta( $fallback_post->ID, '_hero_zoom', true );
        }
    }

    if ( $position_y === '' && $zoom === '' ) {
        return;
    }

    $position_y = ( $position_y !== '' ) ? intval( $position_y ) : 50;
    $zoom = ( $zoom !== '' ) ? intval( $zoom ) : 100;
    $scale = $zoom / 100;
    ?>
    <style id="sport-theme-hero-custom-styles">
        /* Attiva il contesto di impilamento (stacking context) sui contenitori padre */
        .club-hero-wrapper,
        .news-hero-wrapper,
        .hs-hero-wrapper,
        [class*="hero-wrapper"] {
            position: relative !important;
            z-index: 10 !important;
            background-color: transparent !important;
        }

        /* Forza l'immagine sullo sfondo in modo assoluto */
        [class*="hero"] img,
        [class*="hero-wrapper"] img,
        .news-hero-wrapper img, 
        .hs-hero-wrapper img, 
        .club-hero-wrapper img,
        .news-hero-wrapper .hero-image,
        .hs-hero-wrapper .hero-image,
        img.hero-image {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 1 !important;
            object-position: center <?php echo $position_y; ?>% !important;
            transform: scale(<?php echo $scale; ?>) !important;
            transform-origin: center <?php echo $position_y; ?>% !important;
        }

        /* Forza tutti gli altri elementi del contenitore ad avere un z-index superiore rispetto all'immagine */
        .news-hero-overlay,
        .hs-hero-overlay,
        .news-hero-content,
        .hs-hero-content,
        .club-hero-wrapper > div,
        .news-hero-wrapper > div,
        .hs-hero-wrapper > div,
        [class*="hero-wrapper"] > div,
        [class*="hero"] > div {
            z-index: 20 !important;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'sport_theme_render_hero_custom_styles' );
add_action( 'wp_footer', 'sport_theme_render_hero_custom_styles', 999 );




