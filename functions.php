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

$sport_theme_autoload = get_stylesheet_directory() . '/vendor/autoload.php';
if ( file_exists( $sport_theme_autoload ) ) {
    require_once $sport_theme_autoload;
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

function sport_theme_get_page_url($slug, $title = '') {
    $page = get_page_by_path($slug);

    if (!$page && $title !== '') {
        foreach (get_pages(array('post_status' => 'publish')) as $candidate) {
            if (strtolower(trim($candidate->post_title)) === strtolower(trim($title))) {
                $page = $candidate;
                break;
            }
        }
    }

    return $page ? get_permalink($page->ID) : site_url('/' . trim($slug, '/'));
}

function sport_theme_get_site_logo_url() {
    return get_template_directory_uri() . '/assets/images/logo.png';
}

function sport_theme_get_context_logo_url() {
    $is_societa_page = is_page(array(
        'ac-taverne',
        'societa',
        'la-societa',
        'comitato',
        'club-dei-100',
        'area-allenatori',
        'area-segreteria',
        'scuola-calcio',
        'infrastruttura',
        'news-societa',
        'iscritti',
        'contatti-societa',
        'sezioni',
        'sponsor-ac-taverne',
    )) || is_page_template(array(
        'template-home-societa.php',
        'template-la-societa.php',
        'template-comitato-societa.php',
        'template-club-dei-100.php',
        'template-allenatori.php',
        'template-area-segreteria.php',
        'template-scuola-calcio.php',
        'template-infrastruttura.php',
        'template-news-societa.php',
        'template-iscritti.php',
        'template-contatti-societa.php',
        'template-sezioni.php',
        'template-sponsor-societa.php',
    )) || ( is_singular('evento') && has_category('settore-giovanile') )
       || ( is_singular('post') && has_category('settore-giovanile') );

    if ( $is_societa_page ) {
        return sport_theme_get_page_url('ac-taverne', 'AC Taverne');
    }

    $is_prima_squadra_page = is_page(array(
        'prima-squadra',
        'news',
        'news-prima-squadra',
        'giocatori',
        'staff',
        'stagione',
        'organigramma',
        'storia',
        'presente-e-futuro',
        'partner',
        'sponsor',
        'contatti',
    )) || is_page_template(array(
        'template-prima-squadra.php',
        'template-news.php',
        'template-rosa.php',
        'template-staff.php',
        'template-stagione.php',
        'template-organigramma.php',
        'template-storia.php',
        'template-club-page.php',
        'template-partner.php',
        'template-contatti.php',
    )) || is_singular(array('giocatore', 'partita'))
       || ( is_singular('post') && ! has_category('settore-giovanile') )
       || ( is_singular('evento') && ! has_category('settore-giovanile') );

    if ( $is_prima_squadra_page ) {
        return sport_theme_get_page_url('prima-squadra', 'Prima Squadra');
    }

    return home_url('/');
}

function sport_theme_primary_menu_home_order( $items, $args ) {
    if ( empty( $args->theme_location ) || $args->theme_location !== 'menu-1' || empty( $items ) ) {
        return $items;
    }

    $order = array(
        'news'       => 10,
        'shop'       => 20,
        'team'       => 30,
        'stagione'   => 40,
        'club'       => 50,
        'network'    => 60,
        'partner'    => 60,
        'contatti'   => 70,
        'ac-taverne' => 80,
    );

    $children = array();
    $top      = array();

    foreach ( $items as $item ) {
        if ( (int) $item->menu_item_parent > 0 ) {
            $children[ (int) $item->menu_item_parent ][] = $item;
        } else {
            $top[] = $item;
        }
    }

    $menu_key = function( $item ) {
        $title = strtolower( trim( wp_strip_all_tags( $item->title ) ) );
        $url   = strtolower( (string) $item->url );

        if ( strpos( $url, 'actaverneshop.com' ) !== false || preg_match( '#/shop/?$#', $url ) ) {
            return 'shop';
        }
        if ( strpos( $url, '/ac-taverne' ) !== false || $title === 'ac taverne' ) {
            return 'ac-taverne';
        }
        if ( strpos( $url, '/stagione' ) !== false || $title === 'stagione' ) {
            return 'stagione';
        }
        if ( strpos( $url, '/contatti' ) !== false || $title === 'contatti' ) {
            return 'contatti';
        }
        if ( strpos( $url, '/partner' ) !== false || $title === 'network' || $title === 'partner' ) {
            return 'network';
        }
        if ( strpos( $url, '/organigramma' ) !== false || $title === 'club' ) {
            return 'club';
        }
        if ( strpos( $url, '/giocatori' ) !== false || $title === 'team' ) {
            return 'team';
        }
        if ( strpos( $url, '/news' ) !== false || $title === 'news' ) {
            return 'news';
        }

        return $title;
    };

    usort( $top, function( $a, $b ) use ( $order, $menu_key ) {
        $key_a = $menu_key( $a );
        $key_b = $menu_key( $b );
        $pos_a = $order[ $key_a ] ?? 999;
        $pos_b = $order[ $key_b ] ?? 999;

        if ( $pos_a === $pos_b ) {
            return (int) $a->menu_order <=> (int) $b->menu_order;
        }

        return $pos_a <=> $pos_b;
    } );

    $ordered = array();
    foreach ( $top as $item ) {
        $ordered[] = $item;

        if ( ! empty( $children[ (int) $item->ID ] ) ) {
            usort( $children[ (int) $item->ID ], function( $a, $b ) {
                return (int) $a->menu_order <=> (int) $b->menu_order;
            } );

            foreach ( $children[ (int) $item->ID ] as $child ) {
                $ordered[] = $child;
            }
        }
    }

    return $ordered;
}
add_filter( 'wp_nav_menu_objects', 'sport_theme_primary_menu_home_order', 20, 2 );

function sport_theme_get_societa_home_hero_url() {
    $home_societa = get_page_by_path('ac-taverne');

    if ($home_societa && has_post_thumbnail($home_societa->ID)) {
        return get_the_post_thumbnail_url($home_societa->ID, 'full');
    }

    return get_template_directory_uri() . '/assets/images/campo-taverne-aereo.jpg';
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

	if ( ! get_role( 'segreteria' ) ) {
		add_role( 'segreteria', 'Segreteria', array( 'read' => true, 'access_segreteria' => true ) );
	} else {
		get_role( 'segreteria' )->add_cap( 'access_segreteria' );
	}
}
add_action( 'init', 'sport_theme_add_roles' );

function sport_theme_can_access_segreteria() {
    return current_user_can( 'manage_options' ) || current_user_can( 'edit_pages' ) || current_user_can( 'access_segreteria' );
}

function sport_theme_iscrizioni_default_email_settings() {
    return array(
        'new_registration_recipients' => "info@actaverne.com\nf.ruberto@honegger.ch\nmenegao@hotmail.com",
        'payment_card_recipients'     => "marketing@actaverne.com\ninfo@actaverne.com",
        'invoice_notice_recipients'   => "marketing@actaverne.com",
    );
}

function sport_theme_iscrizioni_email_settings() {
    $defaults = sport_theme_iscrizioni_default_email_settings();
    $settings = get_option( 'sport_theme_iscrizioni_email_settings', array() );

    return wp_parse_args( is_array( $settings ) ? $settings : array(), $defaults );
}

function sport_theme_parse_email_list( $value ) {
    $items  = preg_split( '/[\s,;]+/', (string) $value );
    $emails = array();

    foreach ( $items as $item ) {
        $email = sanitize_email( trim( $item ) );
        if ( $email && is_email( $email ) ) {
            $emails[] = $email;
        }
    }

    return array_values( array_unique( $emails ) );
}

function sport_theme_sanitize_iscrizioni_email_settings( $settings ) {
    $defaults = sport_theme_iscrizioni_default_email_settings();
    $clean    = array();

    foreach ( $defaults as $key => $default_value ) {
        $emails        = sport_theme_parse_email_list( $settings[ $key ] ?? $default_value );
        $clean[ $key ] = implode( "\n", $emails );
    }

    return $clean;
}

function sport_theme_register_iscrizioni_settings() {
    register_setting(
        'sport_theme_iscrizioni_email_settings',
        'sport_theme_iscrizioni_email_settings',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'sport_theme_sanitize_iscrizioni_email_settings',
            'default'           => sport_theme_iscrizioni_default_email_settings(),
        )
    );
}
add_action( 'admin_init', 'sport_theme_register_iscrizioni_settings' );

function sport_theme_add_iscrizioni_settings_page() {
    add_options_page(
        'Iscrizioni AC Taverne',
        'Iscrizioni AC Taverne',
        'manage_options',
        'act-iscrizioni-settings',
        'sport_theme_render_iscrizioni_settings_page'
    );
}
add_action( 'admin_menu', 'sport_theme_add_iscrizioni_settings_page' );

function sport_theme_render_iscrizioni_settings_field( $key, $label, $description ) {
    $settings = sport_theme_iscrizioni_email_settings();
    ?>
    <tr>
        <th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
        <td>
            <textarea id="<?php echo esc_attr( $key ); ?>" name="sport_theme_iscrizioni_email_settings[<?php echo esc_attr( $key ); ?>]" rows="4" class="large-text code"><?php echo esc_textarea( $settings[ $key ] ?? '' ); ?></textarea>
            <p class="description"><?php echo esc_html( $description ); ?></p>
        </td>
    </tr>
    <?php
}

function sport_theme_render_iscrizioni_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>Iscrizioni AC Taverne</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'sport_theme_iscrizioni_email_settings' ); ?>
            <table class="form-table" role="presentation">
                <?php
                sport_theme_render_iscrizioni_settings_field(
                    'new_registration_recipients',
                    'Nuove iscrizioni',
                    'Una email per riga. Ricevono la conferma d’iscrizione PDF quando arriva una nuova pratica.'
                );
                sport_theme_render_iscrizioni_settings_field(
                    'payment_card_recipients',
                    'Pagamenti carta',
                    'Una email per riga. Ricevono l’avviso quando Stripe conferma un pagamento carta.'
                );
                sport_theme_render_iscrizioni_settings_field(
                    'invoice_notice_recipients',
                    'Avvisi fattura',
                    'Una email per riga. Ricevono l’avviso quando una pratica viene gestita con fattura/cedola.'
                );
                ?>
            </table>
            <?php submit_button( 'Salva impostazioni' ); ?>
        </form>
    </div>
    <?php
}

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

    $pages_to_add = array( 'Giocatori', 'Staff' );
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

function sport_theme_get_iscrizioni_classificazione_default_url() {
    return get_template_directory_uri() . '/assets/images/iscrizioni/classificazione-2026-27.png';
}

function sport_theme_get_iscrizioni_classificazione_url( $post_id = 0 ) {
    $post_id = $post_id ? (int) $post_id : get_the_ID();
    $url = $post_id ? get_post_meta( $post_id, '_iscrizioni_classificazione_file', true ) : '';

    return $url ? esc_url_raw( $url ) : sport_theme_get_iscrizioni_classificazione_default_url();
}

function sport_theme_iscritti_metabox( $post_type, $post ) {
    if ( $post_type !== 'page' || ! $post instanceof WP_Post ) {
        return;
    }

    if ( get_page_template_slug( $post->ID ) !== 'template-iscritti.php' ) {
        return;
    }

    add_meta_box(
        'iscritti_classificazione_meta',
        'Iscrizioni - Classificazione categorie',
        'sport_theme_iscritti_classificazione_meta_html',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'sport_theme_iscritti_metabox', 10, 2 );

function sport_theme_iscritti_classificazione_meta_html( $post ) {
    $file_url = get_post_meta( $post->ID, '_iscrizioni_classificazione_file', true );
    $season   = get_post_meta( $post->ID, '_iscrizioni_classificazione_stagione', true );

    wp_nonce_field( 'sport_theme_save_iscritti_classificazione', 'sport_theme_iscritti_classificazione_nonce' );
    ?>
    <p>Carica qui il documento annuale con la classificazione per anno di nascita. Può essere un'immagine o un PDF.</p>
    <p>
        <label for="iscrizioni_classificazione_stagione"><strong>Stagione</strong></label><br>
        <input type="text" id="iscrizioni_classificazione_stagione" name="_iscrizioni_classificazione_stagione" value="<?php echo esc_attr( $season ); ?>" placeholder="2026/2027" style="width:100%;max-width:240px;">
    </p>
    <p>
        <label for="iscrizioni_classificazione_file"><strong>Documento classificazione</strong></label><br>
        <input type="text" id="iscrizioni_classificazione_file" name="_iscrizioni_classificazione_file" value="<?php echo esc_url( $file_url ); ?>" placeholder="Seleziona immagine o PDF..." style="width:100%;max-width:620px;">
        <button type="button" class="button button-secondary" id="iscrizioni_classificazione_upload">Seleziona / carica</button>
        <button type="button" class="button" id="iscrizioni_classificazione_clear">Rimuovi</button>
    </p>
    <div id="iscrizioni_classificazione_preview" style="margin-top:12px;">
        <?php if ( $file_url ) : ?>
            <?php if ( preg_match( '/\.(jpe?g|png|webp|gif)(\?.*)?$/i', $file_url ) ) : ?>
                <img src="<?php echo esc_url( $file_url ); ?>" alt="" style="max-width:420px;height:auto;border:1px solid #ccd0d4;">
            <?php else : ?>
                <a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener">Apri documento caricato</a>
            <?php endif; ?>
        <?php else : ?>
            <em>Se non carichi un file, verrà mostrata l'immagine predefinita inclusa nel tema.</em>
        <?php endif; ?>
    </div>
    <script>
    jQuery(function($){
        var frame;
        $('#iscrizioni_classificazione_upload').on('click', function(e){
            e.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media({
                title: 'Seleziona classificazione categorie',
                button: { text: 'Usa questo documento' },
                multiple: false
            });
            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                $('#iscrizioni_classificazione_file').val(attachment.url);
                if (attachment.type === 'image') {
                    $('#iscrizioni_classificazione_preview').html('<img src="' + attachment.url + '" alt="" style="max-width:420px;height:auto;border:1px solid #ccd0d4;">');
                } else {
                    $('#iscrizioni_classificazione_preview').html('<a href="' + attachment.url + '" target="_blank" rel="noopener">Apri documento caricato</a>');
                }
            });
            frame.open();
        });
        $('#iscrizioni_classificazione_clear').on('click', function(e){
            e.preventDefault();
            $('#iscrizioni_classificazione_file').val('');
            $('#iscrizioni_classificazione_preview').html("<em>Se non carichi un file, verrà mostrata l'immagine predefinita inclusa nel tema.</em>");
        });
    });
    </script>
    <?php
}

function sport_theme_save_iscritti_classificazione_meta( $post_id ) {
    if ( ! isset( $_POST['sport_theme_iscritti_classificazione_nonce'] ) || ! wp_verify_nonce( $_POST['sport_theme_iscritti_classificazione_nonce'], 'sport_theme_save_iscritti_classificazione' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['_iscrizioni_classificazione_file'] ) ) {
        update_post_meta( $post_id, '_iscrizioni_classificazione_file', esc_url_raw( wp_unslash( $_POST['_iscrizioni_classificazione_file'] ) ) );
    }

    if ( isset( $_POST['_iscrizioni_classificazione_stagione'] ) ) {
        update_post_meta( $post_id, '_iscrizioni_classificazione_stagione', sanitize_text_field( wp_unslash( $_POST['_iscrizioni_classificazione_stagione'] ) ) );
    }
}
add_action( 'save_post_page', 'sport_theme_save_iscritti_classificazione_meta' );

function sport_theme_storia_gallery_metabox( $post_type, $post ) {
    if ( $post_type !== 'page' || ! $post instanceof WP_Post ) {
        return;
    }

    $is_storia_page = get_page_template_slug( $post->ID ) === 'template-storia.php' || $post->post_name === 'storia';
    if ( ! $is_storia_page ) {
        return;
    }

    add_meta_box(
        'storia_gallery_meta',
        'Fotogallery Storia',
        'sport_theme_storia_gallery_meta_html',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'sport_theme_storia_gallery_metabox', 10, 2 );

function sport_theme_storia_gallery_meta_html( $post ) {
    $gallery_ids = get_post_meta( $post->ID, '_storia_gallery_ids', true );
    $gallery_ids = is_array( $gallery_ids ) ? $gallery_ids : array_filter( array_map( 'absint', explode( ',', (string) $gallery_ids ) ) );

    wp_nonce_field( 'sport_theme_save_storia_gallery', 'sport_theme_storia_gallery_nonce' );
    ?>
    <p>Seleziona le immagini della fotogallery della pagina Storia. Puoi aggiungerle dalla Media Library e riordinarle con i pulsanti.</p>
    <input type="hidden" id="storia_gallery_ids" name="_storia_gallery_ids" value="<?php echo esc_attr( implode( ',', $gallery_ids ) ); ?>">
    <p>
        <button type="button" class="button button-secondary" id="storia_gallery_select">Seleziona / aggiungi immagini</button>
        <button type="button" class="button" id="storia_gallery_clear">Rimuovi tutte</button>
    </p>
    <div id="storia_gallery_preview" class="storia-gallery-admin-preview">
        <?php foreach ( $gallery_ids as $attachment_id ) : ?>
            <?php $thumb_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' ); ?>
            <?php if ( $thumb_url ) : ?>
                <div class="storia-gallery-admin-item" data-id="<?php echo esc_attr( $attachment_id ); ?>">
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="">
                    <div class="storia-gallery-admin-actions">
                        <button type="button" class="button-link storia-gallery-move-up">Su</button>
                        <button type="button" class="button-link storia-gallery-move-down">Giu</button>
                        <button type="button" class="button-link-delete storia-gallery-remove">Rimuovi</button>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <style>
        .storia-gallery-admin-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 12px;
            max-width: 900px;
            margin-top: 12px;
        }
        .storia-gallery-admin-item {
            padding: 8px;
            background: #fff;
            border: 1px solid #ccd0d4;
        }
        .storia-gallery-admin-item img {
            display: block;
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            margin-bottom: 8px;
        }
        .storia-gallery-admin-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 12px;
        }
    </style>
    <script>
    jQuery(function($){
        var frame;
        var $ids = $('#storia_gallery_ids');
        var $preview = $('#storia_gallery_preview');

        function currentIds() {
            return $ids.val() ? $ids.val().split(',').filter(Boolean) : [];
        }

        function syncIds() {
            var ids = [];
            $preview.find('.storia-gallery-admin-item').each(function(){
                ids.push($(this).data('id'));
            });
            $ids.val(ids.join(','));
        }

        function renderItem(attachment) {
            var imageUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
            return '<div class="storia-gallery-admin-item" data-id="' + attachment.id + '">' +
                '<img src="' + imageUrl + '" alt="">' +
                '<div class="storia-gallery-admin-actions">' +
                '<button type="button" class="button-link storia-gallery-move-up">Su</button>' +
                '<button type="button" class="button-link storia-gallery-move-down">Giu</button>' +
                '<button type="button" class="button-link-delete storia-gallery-remove">Rimuovi</button>' +
                '</div>' +
                '</div>';
        }

        $('#storia_gallery_select').on('click', function(e){
            e.preventDefault();
            frame = wp.media({
                title: 'Seleziona immagini per la Storia',
                button: { text: 'Usa queste immagini' },
                library: { type: 'image' },
                multiple: true
            });
            frame.on('open', function(){
                var selection = frame.state().get('selection');
                currentIds().forEach(function(id){
                    var attachment = wp.media.attachment(id);
                    attachment.fetch();
                    selection.add(attachment ? [attachment] : []);
                });
            });
            frame.on('select', function(){
                var html = '';
                frame.state().get('selection').each(function(attachment){
                    html += renderItem(attachment.toJSON());
                });
                $preview.html(html);
                syncIds();
            });
            frame.open();
        });

        $('#storia_gallery_clear').on('click', function(e){
            e.preventDefault();
            $preview.empty();
            syncIds();
        });

        $preview.on('click', '.storia-gallery-remove', function(e){
            e.preventDefault();
            $(this).closest('.storia-gallery-admin-item').remove();
            syncIds();
        });

        $preview.on('click', '.storia-gallery-move-up', function(e){
            e.preventDefault();
            var $item = $(this).closest('.storia-gallery-admin-item');
            $item.prev('.storia-gallery-admin-item').before($item);
            syncIds();
        });

        $preview.on('click', '.storia-gallery-move-down', function(e){
            e.preventDefault();
            var $item = $(this).closest('.storia-gallery-admin-item');
            $item.next('.storia-gallery-admin-item').after($item);
            syncIds();
        });
    });
    </script>
    <?php
}

function sport_theme_save_storia_gallery_meta( $post_id ) {
    if ( ! isset( $_POST['sport_theme_storia_gallery_nonce'] ) || ! wp_verify_nonce( $_POST['sport_theme_storia_gallery_nonce'], 'sport_theme_save_storia_gallery' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $ids = isset( $_POST['_storia_gallery_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['_storia_gallery_ids'] ) ) : '';
    $ids = array_filter( array_map( 'absint', explode( ',', $ids ) ) );

    update_post_meta( $post_id, '_storia_gallery_ids', implode( ',', $ids ) );
}
add_action( 'save_post_page', 'sport_theme_save_storia_gallery_meta' );

function sport_theme_club100_gallery_metabox( $post_type, $post ) {
    if ( $post_type !== 'page' || ! $post instanceof WP_Post ) {
        return;
    }

    $is_club100_page = get_page_template_slug( $post->ID ) === 'template-club-dei-100.php' || $post->post_name === 'club-dei-100';
    if ( ! $is_club100_page ) {
        return;
    }

    add_meta_box(
        'club100_gallery_meta',
        'Fotogallery Club dei 100',
        'sport_theme_club100_gallery_meta_html',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'sport_theme_club100_gallery_metabox', 10, 2 );

function sport_theme_club100_gallery_meta_html( $post ) {
    $gallery_ids = get_post_meta( $post->ID, '_club100_gallery_ids', true );
    $gallery_ids = is_array( $gallery_ids ) ? $gallery_ids : array_filter( array_map( 'absint', explode( ',', (string) $gallery_ids ) ) );

    wp_nonce_field( 'sport_theme_save_club100_gallery', 'sport_theme_club100_gallery_nonce' );
    ?>
    <p>Seleziona le immagini della fotogallery della pagina Club dei 100. Puoi aggiungerle dalla Media Library e riordinarle con i pulsanti.</p>
    <input type="hidden" id="club100_gallery_ids" name="_club100_gallery_ids" value="<?php echo esc_attr( implode( ',', $gallery_ids ) ); ?>">
    <p>
        <button type="button" class="button button-secondary" id="club100_gallery_select">Seleziona / aggiungi immagini</button>
        <button type="button" class="button" id="club100_gallery_clear">Rimuovi tutte</button>
    </p>
    <div id="club100_gallery_preview" class="storia-gallery-admin-preview">
        <?php foreach ( $gallery_ids as $attachment_id ) : ?>
            <?php $thumb_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' ); ?>
            <?php if ( $thumb_url ) : ?>
                <div class="storia-gallery-admin-item" data-id="<?php echo esc_attr( $attachment_id ); ?>">
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="">
                    <div class="storia-gallery-admin-actions">
                        <button type="button" class="button-link club100-gallery-move-up">Su</button>
                        <button type="button" class="button-link club100-gallery-move-down">Giu</button>
                        <button type="button" class="button-link-delete club100-gallery-remove">Rimuovi</button>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <script>
    jQuery(function($){
        var frame;
        var $ids = $('#club100_gallery_ids');
        var $preview = $('#club100_gallery_preview');

        function currentIds() {
            return $ids.val() ? $ids.val().split(',').filter(Boolean) : [];
        }

        function syncIds() {
            var ids = [];
            $preview.find('.storia-gallery-admin-item').each(function(){
                ids.push($(this).data('id'));
            });
            $ids.val(ids.join(','));
        }

        function renderItem(attachment) {
            var imageUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
            return '<div class="storia-gallery-admin-item" data-id="' + attachment.id + '">' +
                '<img src="' + imageUrl + '" alt="">' +
                '<div class="storia-gallery-admin-actions">' +
                '<button type="button" class="button-link club100-gallery-move-up">Su</button>' +
                '<button type="button" class="button-link club100-gallery-move-down">Giu</button>' +
                '<button type="button" class="button-link-delete club100-gallery-remove">Rimuovi</button>' +
                '</div>' +
                '</div>';
        }

        $('#club100_gallery_select').on('click', function(e){
            e.preventDefault();
            frame = wp.media({
                title: 'Seleziona immagini per il Club dei 100',
                button: { text: 'Usa queste immagini' },
                library: { type: 'image' },
                multiple: true
            });
            frame.on('open', function(){
                var selection = frame.state().get('selection');
                currentIds().forEach(function(id){
                    var attachment = wp.media.attachment(id);
                    attachment.fetch();
                    selection.add(attachment ? [attachment] : []);
                });
            });
            frame.on('select', function(){
                var html = '';
                frame.state().get('selection').each(function(attachment){
                    html += renderItem(attachment.toJSON());
                });
                $preview.html(html);
                syncIds();
            });
            frame.open();
        });

        $('#club100_gallery_clear').on('click', function(e){
            e.preventDefault();
            $preview.empty();
            syncIds();
        });

        $preview.on('click', '.club100-gallery-remove', function(e){
            e.preventDefault();
            $(this).closest('.storia-gallery-admin-item').remove();
            syncIds();
        });

        $preview.on('click', '.club100-gallery-move-up', function(e){
            e.preventDefault();
            var $item = $(this).closest('.storia-gallery-admin-item');
            $item.prev('.storia-gallery-admin-item').before($item);
            syncIds();
        });

        $preview.on('click', '.club100-gallery-move-down', function(e){
            e.preventDefault();
            var $item = $(this).closest('.storia-gallery-admin-item');
            $item.next('.storia-gallery-admin-item').after($item);
            syncIds();
        });
    });
    </script>
    <?php
}

function sport_theme_save_club100_gallery_meta( $post_id ) {
    if ( ! isset( $_POST['sport_theme_club100_gallery_nonce'] ) || ! wp_verify_nonce( $_POST['sport_theme_club100_gallery_nonce'], 'sport_theme_save_club100_gallery' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $ids = isset( $_POST['_club100_gallery_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['_club100_gallery_ids'] ) ) : '';
    $ids = array_filter( array_map( 'absint', explode( ',', $ids ) ) );

    update_post_meta( $post_id, '_club100_gallery_ids', implode( ',', $ids ) );
}
add_action( 'save_post_page', 'sport_theme_save_club100_gallery_meta' );

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

        <label><b>Foto Esultanza / Azione (mostrata nella card dei Giocatori):</b></label><br>
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
    $tipo_evento = get_post_meta($post->ID, '_tipo_evento', true);
    $tipi_evento = array('Campionato', 'Amichevole', 'Coppa', 'Torneo', 'Playoff', 'Altro');
    
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

        <br><label><b>Tipo evento:</b></label><br>
        <select name="_tipo_evento">
            <?php foreach($tipi_evento as $tipo) : ?>
                <option value="<?php echo esc_attr($tipo); ?>" <?php selected($tipo_evento ?: 'Campionato', $tipo); ?>><?php echo esc_html($tipo); ?></option>
            <?php endforeach; ?>
        </select>

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
    
    $fields = ['_data_partita', '_ora_partita', '_stadio', '_tipo_evento', '_avversario', '_logo_avversario', '_in_casa', '_risultato'];
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
        'Giocatori'     => 'template-rosa.php',
        'Staff'         => 'template-staff.php',
        'News'          => 'template-news.php',
        'Stagione'      => 'template-stagione.php',
        'Partner'       => 'template-partner.php',
        'Sponsor'       => 'template-partner.php',
        'Organigramma'  => 'template-organigramma.php',
        'Storia'        => 'template-club-page.php',
        'Presente e Futuro' => 'template-club-page.php',
        'Contatti'      => 'template-contatti.php',
        'Home Società'  => 'template-home-societa.php',
        // Pagine segnaposto sezione AC Taverne (usano page.php fino a sviluppo)
        'Società'       => '',
        'Scuola Calcio' => 'template-scuola-calcio.php',
        'Allievi'       => '',
        'Femminile'     => '',
        'Infrastruttura'=> '',
        'Sponsor AC Taverne' => 'template-sponsor-societa.php',
        'Iscritti'      => 'template-iscritti.php',
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

function sport_theme_force_iscritti_template() {
    $page = get_page_by_title( 'Iscritti' );
    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'  => 'Iscritti',
            'post_name'   => 'iscritti',
            'post_type'   => 'page',
            'post_status' => 'publish',
        ) );
        if ( $page_id && ! is_wp_error( $page_id ) ) {
            update_post_meta( $page_id, '_wp_page_template', 'template-iscritti.php' );
        }
        return;
    }

    update_post_meta( $page->ID, '_wp_page_template', 'template-iscritti.php' );
}
add_action( 'init', 'sport_theme_force_iscritti_template' );

function sport_theme_force_sponsor_societa_template() {
    $page = get_page_by_path( 'sponsor-ac-taverne' );
    if ( ! $page ) {
        $page = get_page_by_title( 'Sponsor AC Taverne' );
    }

    if ( ! $page ) {
        $page_id = wp_insert_post( array(
            'post_title'  => 'Sponsor AC Taverne',
            'post_name'   => 'sponsor-ac-taverne',
            'post_type'   => 'page',
            'post_status' => 'publish',
        ) );
        if ( $page_id && ! is_wp_error( $page_id ) ) {
            update_post_meta( $page_id, '_wp_page_template', 'template-sponsor-societa.php' );
        }
        return;
    }

    update_post_meta( $page->ID, '_wp_page_template', 'template-sponsor-societa.php' );
}
add_action( 'init', 'sport_theme_force_sponsor_societa_template' );

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
            $rosa_page = get_page_by_title( 'Giocatori' );
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

            // Inserisci Presente e Futuro come figlio
            $prog = get_page_by_title( 'Presente e Futuro' );
            if($prog) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'   => 'Presente e Futuro',
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
            // Eliminiamo tutti i figli di Club (Organigramma, Storia del Club, Presente e Futuro)
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

    $pages_to_update = ['Storia', 'Presente e Futuro'];
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

// FIX DB CONTENT PER PRESENTE E FUTURO
function sport_theme_fix_progetto_content() {
    if ( get_option( 'sport_theme_fix_progetto_v2' ) ) {
        return;
    }
    
    $p = get_page_by_path('presente-e-futuro');
    if (!$p) $p = get_page_by_path('progetto-sportivo');
    if (!$p) $p = get_page_by_title('Presente e Futuro');
    if (!$p) $p = get_page_by_title('Progetto sportivo'); // Fallback
    
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
    
    $to      = 'primasquadra@actaverne.com';
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
 * Gestione Form Network - richiesta sponsor/partner
 */
function sport_theme_handle_network_form() {
    if ( ! isset($_POST['network_nonce']) || ! wp_verify_nonce($_POST['network_nonce'], 'network_form_nonce') ) {
        wp_safe_redirect( add_query_arg('network_inviato', '0', home_url('/partner/')) );
        exit;
    }

    if ( ! empty($_POST['network_website']) ) {
        wp_safe_redirect( add_query_arg('network_inviato', '1', home_url('/partner/')) );
        exit;
    }

    $azienda   = sanitize_text_field($_POST['network_azienda'] ?? '');
    $nome      = sanitize_text_field($_POST['network_nome'] ?? '');
    $email     = sanitize_email($_POST['network_email'] ?? '');
    $telefono  = sanitize_text_field($_POST['network_telefono'] ?? '');
    $interesse = sanitize_text_field($_POST['network_interesse'] ?? '');
    $messaggio = sanitize_textarea_field($_POST['network_messaggio'] ?? '');

    if ( empty($azienda) || empty($nome) || ! is_email($email) || empty($messaggio) ) {
        wp_safe_redirect( add_query_arg('network_inviato', '0', wp_get_referer() ?: home_url('/partner/')) );
        exit;
    }

    $to      = 'primasquadra@actaverne.com';
    $subject = '[AC Taverne - Network] Richiesta da ' . $azienda;
    $body    = "Azienda: {$azienda}\n";
    $body   .= "Referente: {$nome}\n";
    $body   .= "Email: {$email}\n";
    $body   .= "Telefono: {$telefono}\n";
    $body   .= "Interesse: {$interesse}\n\n";
    $body   .= "Messaggio:\n{$messaggio}";
    $headers = array('Reply-To: ' . $nome . ' <' . $email . '>');

    $sent = wp_mail($to, $subject, $body, $headers);

    wp_safe_redirect( add_query_arg('network_inviato', $sent ? '1' : '0', wp_get_referer() ?: home_url('/partner/')) );
    exit;
}
add_action('admin_post_nopriv_network_submit', 'sport_theme_handle_network_form');
add_action('admin_post_network_submit', 'sport_theme_handle_network_form');

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
    $cognome  = sanitize_text_field($_POST['c100_cognome'] ?? '');
    $telefono = sanitize_text_field($_POST['c100_telefono'] ?? '');
    $email    = sanitize_email($_POST['c100_email'] ?? '');
    $oggetto  = sanitize_text_field($_POST['c100_oggetto'] ?? '');
    $testo    = sanitize_textarea_field($_POST['c100_testo'] ?? '');
    
    if ( empty($nome) || empty($cognome) || empty($email) ) {
        return;
    }
    
    $to      = get_option('admin_email');
    $subject = '[AC Taverne - Club dei 100] ' . $oggetto;
    $body    = "Richiesta iscrizione al Club dei 100:\n\n";
    $body   .= "Nome: {$nome}\n";
    $body   .= "Cognome: {$cognome}\n";
    $body   .= "Email: {$email}\n";
    $body   .= "Telefono: {$telefono}\n\n";
    $body   .= "Messaggio:\n{$testo}";
    $headers = array('Reply-To: ' . $nome . ' ' . $cognome . ' <' . $email . '>');
    
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
    echo "<textarea name='_ss_allenatore' style='width:100%;max-width:400px;height:70px;' placeholder='Inserisci un nome per riga'>" . esc_textarea($allenatore) . "</textarea>";

    echo "<label style='display:block;margin-top:10px;'>Assistente (opzionale):</label>";
    echo "<textarea name='_ss_assistente' style='width:100%;max-width:400px;height:70px;' placeholder='Inserisci un nome per riga'>" . esc_textarea($assistente) . "</textarea>";

    echo "<hr><label style='display:block;margin-top:10px;font-weight:bold;'>Codice Iframe Classifica (ftc.football.ch):</label>";
    echo "<p>Incolla qui l'iframe per mostrare la classifica. Se vuoto, mostreremo una tabella finta di design.</p>";
    echo "<textarea name='_ss_iframe' style='width:100%;height:100px;font-family:monospace;'>" . esc_textarea($iframe) . "</textarea>";
}

function sport_theme_salva_squadra_sezione_meta($post_id) {
    if (!isset($_POST['squadra_sezione_meta_nonce']) || !wp_verify_nonce($_POST['squadra_sezione_meta_nonce'], 'salva_squadra_sezione_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['_ss_giorni'])) update_post_meta($post_id, '_ss_giorni', sanitize_textarea_field($_POST['_ss_giorni']));
    if (isset($_POST['_ss_allenatore'])) update_post_meta($post_id, '_ss_allenatore', sanitize_textarea_field($_POST['_ss_allenatore']));
    if (isset($_POST['_ss_assistente'])) update_post_meta($post_id, '_ss_assistente', sanitize_textarea_field($_POST['_ss_assistente']));
    
    if (isset($_POST['_ss_iframe'])) {
        $iframe_rules = array(
            'iframe' => array(
                'src'             => true,
                'height'          => true,
                'width'           => true,
                'frameborder'     => true,
                'allowfullscreen' => true,
                'style'           => true,
                'scrolling'       => true,
                'loading'         => true,
                'title'           => true,
                'id'              => true,
                'class'           => true
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
 * Crea automaticamente la pagina Area Segreteria.
 */
function sport_theme_create_area_segreteria() {
    $page_title = 'Area Segreteria';
    $page_slug = 'area-segreteria';
    $template_file = 'template-area-segreteria.php';

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
                'post_status' => 'publish',
            ) );
        }
    }

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', $template_file );
    }
}
add_action( 'init', 'sport_theme_create_area_segreteria' );

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
        ['name' => 'Alessandro Biscotti', 'role' => 'DIRETTORE SPORTIVO', 'area' => 'MANAGEMENT SPORTIVO'],
        ['name' => 'Kubilay Türkyılmaz', 'role' => 'BRAND AMBASSADOR', 'area' => 'MANAGEMENT SPORTIVO'],
        ['name' => 'Luca Defranceschi', 'role' => 'SCOUTING', 'area' => 'MANAGEMENT SPORTIVO'],
        
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
        'Area Allenatori' => site_url('/area-allenatori'),
        'Area Segreteria' => site_url('/area-segreteria'),
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
        } elseif ( $label === 'Area Allenatori' && (is_page('area-allenatori') || is_page_template('template-allenatori.php')) ) {
            $is_active = true;
        } elseif ( $label === 'Area Segreteria' && (is_page('area-segreteria') || is_page_template('template-area-segreteria.php')) ) {
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
        if ( is_page_template( array( 'template-organigramma.php', 'template-storia.php', 'template-club-page.php', 'template-comitato-societa.php', 'template-sezioni.php' ) ) || is_page( array( 'organigramma', 'storia', 'presente-e-futuro', 'sezioni' ) ) ) {
            $fallback_post = get_page_by_path( 'club' );
            if ( ! $fallback_post ) {
                $fallback_post = get_page_by_title( 'Club' );
            }
        }
        // 2. Pagine Team/Prima Squadra/Contatti: fallback alla pagina con slug 'team'
        elseif ( is_page_template( array( 'template-staff.php', 'template-rosa.php', 'template-prima-squadra.php', 'template-contatti.php' ) ) || is_page( array( 'staff', 'giocatori', 'prima-squadra', 'contatti' ) ) ) {
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

function sport_theme_render_single_post_share_meta() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    $post_id = get_queried_object_id();
    if ( ! $post_id ) {
        return;
    }

    $title = get_the_title( $post_id );
    $description = has_excerpt( $post_id )
        ? get_the_excerpt( $post_id )
        : wp_trim_words( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post_id ) ) ), 28, '...' );
    $url = get_permalink( $post_id );
    $image = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'large' ) : '';

    if ( ! $image && has_custom_logo() ) {
        $image = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' );
    }

    ?>
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
    <meta property="og:url" content="<?php echo esc_url( $url ); ?>">
    <?php if ( $image ) : ?>
        <meta property="og:image" content="<?php echo esc_url( $image ); ?>">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="<?php echo esc_url( $image ); ?>">
    <?php endif; ?>
    <meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>">
    <?php
}
add_action( 'wp_head', 'sport_theme_render_single_post_share_meta', 5 );

/**
 * ----------------------------------------------------
 * Iscrizioni AC Taverne: database, upload e salvataggio
 * ----------------------------------------------------
 */
function sport_theme_iscrizioni_table_names() {
    global $wpdb;

    return array(
        'registrations' => $wpdb->prefix . 'act_iscrizioni',
        'children'      => $wpdb->prefix . 'act_iscrizione_bambini',
        'documents'     => $wpdb->prefix . 'act_iscrizione_documenti',
        'logs'          => $wpdb->prefix . 'act_iscrizione_log',
    );
}

function sport_theme_create_iscrizioni_tables() {
    global $wpdb;

    $installed_version = get_option( 'sport_theme_iscrizioni_db_version' );
    $target_version    = '1.7.0';

    if ( $installed_version === $target_version ) {
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $tables          = sport_theme_iscrizioni_table_names();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "
CREATE TABLE {$tables['registrations']} (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  uuid VARCHAR(64) NOT NULL,
  tipo_iscrizione VARCHAR(30) NOT NULL DEFAULT 'allievi',
  stagione_sportiva VARCHAR(20) NOT NULL DEFAULT '',
  stato VARCHAR(30) NOT NULL DEFAULT 'nuova',
  metodo_pagamento VARCHAR(30) NOT NULL DEFAULT '',
  stato_pagamento VARCHAR(30) NOT NULL DEFAULT 'non_pagato',
  importo_totale_chf DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  riduzione_fratelli TINYINT(1) NOT NULL DEFAULT 0,
  sconto_meta_stagione TINYINT(1) NOT NULL DEFAULT 0,
  stripe_customer_id VARCHAR(255) NOT NULL DEFAULT '',
  stripe_checkout_session_id VARCHAR(255) NOT NULL DEFAULT '',
  stripe_payment_intent_id VARCHAR(255) NOT NULL DEFAULT '',
  stripe_invoice_id VARCHAR(255) NOT NULL DEFAULT '',
  stripe_invoice_url TEXT NULL,
  stripe_invoice_pdf TEXT NULL,
  stripe_payment_url TEXT NULL,
  stripe_payment_sent_at DATETIME NULL,
  stripe_paid_at DATETIME NULL,
  responsabilita_genitoriale VARCHAR(30) NOT NULL DEFAULT '',
  responsabile_nome VARCHAR(120) NOT NULL DEFAULT '',
  responsabile_cognome VARCHAR(120) NOT NULL DEFAULT '',
  responsabile_telefono VARCHAR(80) NOT NULL DEFAULT '',
  responsabile_email VARCHAR(190) NOT NULL DEFAULT '',
  certificato_tutela_document_id BIGINT UNSIGNED NULL,
  regolamento_accettato TINYINT(1) NOT NULL DEFAULT 1,
  numero_bambini SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  note_interne TEXT NULL,
  dati_json LONGTEXT NULL,
  ip_hash VARCHAR(128) NOT NULL DEFAULT '',
  user_agent TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY uuid (uuid),
  KEY tipo_iscrizione (tipo_iscrizione),
  KEY stagione_sportiva (stagione_sportiva),
  KEY stato (stato),
  KEY stripe_checkout_session_id (stripe_checkout_session_id),
  KEY responsabile_email (responsabile_email),
  KEY created_at (created_at)
) $charset_collate;

CREATE TABLE {$tables['children']} (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  iscrizione_id BIGINT UNSIGNED NOT NULL,
  child_index SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  nome VARCHAR(120) NOT NULL DEFAULT '',
  cognome VARCHAR(120) NOT NULL DEFAULT '',
  data_nascita DATE NULL,
  nazionalita VARCHAR(120) NOT NULL DEFAULT '',
  avs VARCHAR(32) NOT NULL DEFAULT '',
  indirizzo VARCHAR(255) NOT NULL DEFAULT '',
  cap_citta VARCHAR(120) NOT NULL DEFAULT '',
  email VARCHAR(190) NOT NULL DEFAULT '',
  cellulare VARCHAR(80) NOT NULL DEFAULT '',
  categoria VARCHAR(80) NOT NULL DEFAULT '',
  quota_chf DECIMAL(10,2) NULL,
  salute_allergie_medicinali VARCHAR(10) NOT NULL DEFAULT '',
  salute_dettagli TEXT NULL,
  altro_sport VARCHAR(10) NOT NULL DEFAULT '',
  sport_societa VARCHAR(190) NOT NULL DEFAULT '',
  sport_giorni VARCHAR(190) NOT NULL DEFAULT '',
  tragitto_autonomo VARCHAR(10) NOT NULL DEFAULT '',
  abile_sport VARCHAR(10) NOT NULL DEFAULT '',
  tipo_documento VARCHAR(40) NOT NULL DEFAULT '',
  foto_document_id BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY  (id),
  KEY iscrizione_id (iscrizione_id),
  KEY child_index (child_index),
  KEY categoria (categoria),
  KEY cognome (cognome),
  KEY data_nascita (data_nascita)
) $charset_collate;

CREATE TABLE {$tables['documents']} (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  iscrizione_id BIGINT UNSIGNED NOT NULL,
  bambino_id BIGINT UNSIGNED NULL,
  child_index SMALLINT UNSIGNED NULL,
  tipo_documento VARCHAR(60) NOT NULL DEFAULT '',
  campo_file VARCHAR(120) NOT NULL DEFAULT '',
  ruolo_file VARCHAR(80) NOT NULL DEFAULT '',
  storage VARCHAR(30) NOT NULL DEFAULT 'private',
  attachment_id BIGINT UNSIGNED NULL,
  private_path TEXT NULL,
  private_url TEXT NULL,
  original_name VARCHAR(255) NOT NULL DEFAULT '',
  mime_type VARCHAR(120) NOT NULL DEFAULT '',
  file_size BIGINT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  PRIMARY KEY  (id),
  KEY iscrizione_id (iscrizione_id),
  KEY bambino_id (bambino_id),
  KEY campo_file (campo_file),
  KEY storage (storage)
) $charset_collate;

CREATE TABLE {$tables['logs']} (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  iscrizione_id BIGINT UNSIGNED NOT NULL,
  azione VARCHAR(80) NOT NULL DEFAULT '',
  messaggio TEXT NULL,
  created_by BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY  (id),
  KEY iscrizione_id (iscrizione_id),
  KEY azione (azione),
  KEY created_at (created_at)
) $charset_collate;
";

    dbDelta( $sql );
    $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$tables['registrations']} SET stagione_sportiva = %s WHERE stagione_sportiva = '' OR stagione_sportiva IS NULL",
            sport_theme_current_sport_season()
        )
    );
    $wpdb->query(
        "UPDATE {$tables['registrations']}
         SET importo_totale_chf = CASE
             WHEN tipo_iscrizione = 'scuola_calcio' THEN 150
             WHEN numero_bambini <= 1 THEN 300
             ELSE 300 + ((numero_bambini - 1) * 250)
         END
         WHERE importo_totale_chf = 0 OR importo_totale_chf IS NULL"
    );
    $wpdb->query(
        "UPDATE {$tables['children']} b
         INNER JOIN {$tables['registrations']} i ON i.id = b.iscrizione_id
         SET b.quota_chf = CASE
             WHEN i.tipo_iscrizione = 'scuola_calcio' THEN 150
             WHEN b.child_index <= 1 THEN 300
             ELSE 250
         END
         WHERE b.quota_chf IS NULL"
    );
    update_option( 'sport_theme_iscrizioni_db_version', $target_version );
}
add_action( 'after_switch_theme', 'sport_theme_create_iscrizioni_tables' );
add_action( 'init', 'sport_theme_create_iscrizioni_tables' );

function sport_theme_sanitize_iscrizione_key( $value, $allowed, $default = '' ) {
    $value = sanitize_key( wp_unslash( $value ) );
    return in_array( $value, $allowed, true ) ? $value : $default;
}

function sport_theme_clean_text_field( $key, $source = null ) {
    $source = is_array( $source ) ? $source : $_POST;
    return isset( $source[ $key ] ) ? sanitize_text_field( wp_unslash( $source[ $key ] ) ) : '';
}

function sport_theme_clean_email_field( $key ) {
    return isset( $_POST[ $key ] ) ? sanitize_email( wp_unslash( $_POST[ $key ] ) ) : '';
}

function sport_theme_clean_textarea_field( $key ) {
    return isset( $_POST[ $key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) : '';
}

function sport_theme_clean_date_field( $key ) {
    $value = sport_theme_clean_text_field( $key );
    return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ? $value : null;
}

function sport_theme_get_uploaded_file( $field_name ) {
    if ( empty( $_FILES[ $field_name ] ) || ! is_array( $_FILES[ $field_name ] ) ) {
        return null;
    }

    $file = $_FILES[ $field_name ];
    if ( ! isset( $file['error'] ) || (int) $file['error'] === UPLOAD_ERR_NO_FILE ) {
        return null;
    }

    return $file;
}

function sport_theme_private_iscrizioni_dir( $uuid ) {
    $uploads = wp_upload_dir();
    $base    = trailingslashit( $uploads['basedir'] ) . 'ac-taverne-private/iscrizioni/' . sanitize_file_name( $uuid );

    if ( ! wp_mkdir_p( $base ) ) {
        return new WP_Error( 'private_dir_failed', 'Non è stato possibile creare la cartella privata dei documenti.' );
    }

    $root = trailingslashit( $uploads['basedir'] ) . 'ac-taverne-private';
    if ( wp_mkdir_p( $root ) ) {
        if ( ! file_exists( trailingslashit( $root ) . '.htaccess' ) ) {
            file_put_contents( trailingslashit( $root ) . '.htaccess', "Require all denied\nDeny from all\n" );
        }
        if ( ! file_exists( trailingslashit( $root ) . 'index.html' ) ) {
            file_put_contents( trailingslashit( $root ) . 'index.html', '' );
        }
    }

    if ( ! file_exists( trailingslashit( $base ) . 'index.html' ) ) {
        file_put_contents( trailingslashit( $base ) . 'index.html', '' );
    }

    return $base;
}

function sport_theme_store_private_iscrizione_file( $field_name, $uuid, $prefix ) {
    $file = sport_theme_get_uploaded_file( $field_name );
    if ( ! $file ) {
        return null;
    }

    if ( (int) $file['error'] !== UPLOAD_ERR_OK ) {
        return new WP_Error( 'upload_error', 'Errore durante il caricamento di ' . $field_name . '.' );
    }

    $allowed = array( 'image/jpeg', 'image/png', 'image/webp', 'application/pdf' );
    $check   = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );
    $mime    = ! empty( $check['type'] ) ? $check['type'] : $file['type'];

    if ( ! in_array( $mime, $allowed, true ) ) {
        return new WP_Error( 'invalid_file_type', 'Formato file non consentito per ' . $field_name . '.' );
    }

    $dir = sport_theme_private_iscrizioni_dir( $uuid );
    if ( is_wp_error( $dir ) ) {
        return $dir;
    }

    $extension = pathinfo( sanitize_file_name( $file['name'] ), PATHINFO_EXTENSION );
    $filename  = sanitize_file_name( $prefix . '-' . wp_unique_id() . ( $extension ? '.' . $extension : '' ) );
    $target    = trailingslashit( $dir ) . $filename;

    if ( ! move_uploaded_file( $file['tmp_name'], $target ) ) {
        return new WP_Error( 'move_failed', 'Non è stato possibile salvare il file ' . $field_name . '.' );
    }

    return array(
        'path'          => $target,
        'original_name' => sanitize_file_name( $file['name'] ),
        'mime_type'     => $mime,
        'file_size'     => (int) $file['size'],
    );
}

function sport_theme_store_media_iscrizione_file( $field_name, $parent_id = 0 ) {
    $file = sport_theme_get_uploaded_file( $field_name );
    if ( ! $file ) {
        return null;
    }

    if ( (int) $file['error'] !== UPLOAD_ERR_OK ) {
        return new WP_Error( 'upload_error', 'Errore durante il caricamento della foto.' );
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $attachment_id = media_handle_upload( $field_name, $parent_id );
    if ( is_wp_error( $attachment_id ) ) {
        return $attachment_id;
    }

    return array(
        'attachment_id'  => (int) $attachment_id,
        'original_name'  => sanitize_file_name( $file['name'] ),
        'mime_type'      => get_post_mime_type( $attachment_id ),
        'file_size'      => isset( $file['size'] ) ? (int) $file['size'] : 0,
        'attachment_url' => wp_get_attachment_url( $attachment_id ),
    );
}

function sport_theme_insert_iscrizione_document( $iscrizione_id, $bambino_id, $child_index, $tipo_documento, $field_name, $role, $file_data, $storage ) {
    global $wpdb;

    if ( ! $file_data ) {
        return null;
    }

    $tables = sport_theme_iscrizioni_table_names();
    $now    = current_time( 'mysql' );

    $wpdb->insert(
        $tables['documents'],
        array(
            'iscrizione_id' => (int) $iscrizione_id,
            'bambino_id'    => $bambino_id ? (int) $bambino_id : null,
            'child_index'   => $child_index ? (int) $child_index : null,
            'tipo_documento'=> sanitize_key( $tipo_documento ),
            'campo_file'    => sanitize_key( $field_name ),
            'ruolo_file'    => sanitize_key( $role ),
            'storage'       => sanitize_key( $storage ),
            'attachment_id' => isset( $file_data['attachment_id'] ) ? (int) $file_data['attachment_id'] : null,
            'private_path'  => isset( $file_data['path'] ) ? $file_data['path'] : null,
            'private_url'   => isset( $file_data['attachment_url'] ) ? esc_url_raw( $file_data['attachment_url'] ) : null,
            'original_name' => isset( $file_data['original_name'] ) ? $file_data['original_name'] : '',
            'mime_type'     => isset( $file_data['mime_type'] ) ? $file_data['mime_type'] : '',
            'file_size'     => isset( $file_data['file_size'] ) ? (int) $file_data['file_size'] : 0,
            'created_at'    => $now,
        ),
        array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s' )
    );

    return (int) $wpdb->insert_id;
}

function sport_theme_collect_iscrizione_children( $tipo_iscrizione ) {
    $children = array();

    $children[1] = array(
        'index'                       => 1,
        'nome'                        => sport_theme_clean_text_field( 'giocatore_nome' ),
        'cognome'                     => sport_theme_clean_text_field( 'giocatore_cognome' ),
        'data_nascita'                => sport_theme_clean_date_field( 'giocatore_data_nascita' ),
        'nazionalita'                 => sport_theme_clean_text_field( 'giocatore_nazionalita' ),
        'avs'                         => sport_theme_clean_text_field( 'giocatore_avs' ),
        'indirizzo'                   => sport_theme_clean_text_field( 'giocatore_indirizzo' ),
        'cap_citta'                   => sport_theme_clean_text_field( 'giocatore_cap_citta' ),
        'email'                       => sport_theme_clean_email_field( 'giocatore_email' ),
        'cellulare'                   => sport_theme_clean_text_field( 'giocatore_cellulare' ),
        'salute_allergie_medicinali'  => sport_theme_sanitize_iscrizione_key( $_POST['figlio_1_salute_allergie_medicinali'] ?? '', array( 'si', 'no' ) ),
        'salute_dettagli'             => sport_theme_clean_textarea_field( 'figlio_1_salute_dettagli' ),
        'altro_sport'                 => sport_theme_sanitize_iscrizione_key( $_POST['figlio_1_altro_sport'] ?? '', array( 'si', 'no' ) ),
        'sport_societa'               => sport_theme_clean_text_field( 'figlio_1_sport_societa' ),
        'sport_giorni'                => sport_theme_clean_text_field( 'figlio_1_sport_giorni' ),
        'tragitto_autonomo'           => sport_theme_sanitize_iscrizione_key( $_POST['figlio_1_tragitto_autonomo'] ?? '', array( 'si', 'no' ) ),
        'abile_sport'                 => sport_theme_sanitize_iscrizione_key( $_POST['figlio_1_abile_sport'] ?? '', array( 'si', 'no' ) ),
        'tipo_documento'              => sport_theme_sanitize_iscrizione_key( $_POST['figlio_1_tipo_documento'] ?? '', array( 'carta_identita', 'permesso_soggiorno', 'passaporto' ) ),
    );

    if ( $tipo_iscrizione === 'allievi' ) {
        for ( $i = 2; $i <= 4; $i++ ) {
            if ( empty( $_POST[ "figlio_{$i}_nome" ] ) && empty( $_POST[ "figlio_{$i}_cognome" ] ) ) {
                continue;
            }

            $children[ $i ] = array(
                'index'                       => $i,
                'nome'                        => sport_theme_clean_text_field( "figlio_{$i}_nome" ),
                'cognome'                     => sport_theme_clean_text_field( "figlio_{$i}_cognome" ),
                'data_nascita'                => sport_theme_clean_date_field( "figlio_{$i}_data_nascita" ),
                'nazionalita'                 => sport_theme_clean_text_field( "figlio_{$i}_nazionalita" ),
                'avs'                         => sport_theme_clean_text_field( "figlio_{$i}_avs" ),
                'indirizzo'                   => sport_theme_clean_text_field( "figlio_{$i}_indirizzo" ),
                'cap_citta'                   => sport_theme_clean_text_field( "figlio_{$i}_cap_citta" ),
                'email'                       => sport_theme_clean_email_field( "figlio_{$i}_email" ),
                'cellulare'                   => sport_theme_clean_text_field( "figlio_{$i}_cellulare" ),
                'salute_allergie_medicinali'  => sport_theme_sanitize_iscrizione_key( $_POST[ "figlio_{$i}_salute_allergie_medicinali" ] ?? '', array( 'si', 'no' ) ),
                'salute_dettagli'             => sport_theme_clean_textarea_field( "figlio_{$i}_salute_dettagli" ),
                'altro_sport'                 => sport_theme_sanitize_iscrizione_key( $_POST[ "figlio_{$i}_altro_sport" ] ?? '', array( 'si', 'no' ) ),
                'sport_societa'               => sport_theme_clean_text_field( "figlio_{$i}_sport_societa" ),
                'sport_giorni'                => sport_theme_clean_text_field( "figlio_{$i}_sport_giorni" ),
                'tragitto_autonomo'           => sport_theme_sanitize_iscrizione_key( $_POST[ "figlio_{$i}_tragitto_autonomo" ] ?? '', array( 'si', 'no' ) ),
                'abile_sport'                 => sport_theme_sanitize_iscrizione_key( $_POST[ "figlio_{$i}_abile_sport" ] ?? '', array( 'si', 'no' ) ),
                'tipo_documento'              => sport_theme_sanitize_iscrizione_key( $_POST[ "figlio_{$i}_tipo_documento" ] ?? '', array( 'carta_identita', 'permesso_soggiorno', 'passaporto' ) ),
            );
        }
    }

    return array_values( $children );
}

function sport_theme_validate_iscrizione_payload( $children ) {
    $errors = array();

    foreach ( $children as $child ) {
        $label = trim( $child['nome'] . ' ' . $child['cognome'] );
        $label = $label ? $label : 'Bambino ' . $child['index'];

        foreach ( array( 'nome', 'cognome', 'data_nascita', 'nazionalita', 'avs', 'indirizzo', 'cap_citta', 'salute_allergie_medicinali', 'altro_sport', 'tragitto_autonomo', 'abile_sport', 'tipo_documento' ) as $required ) {
            if ( empty( $child[ $required ] ) ) {
                $errors[] = $label . ': campo obbligatorio mancante (' . $required . ').';
            }
        }

        if ( $child['salute_allergie_medicinali'] === 'si' && empty( $child['salute_dettagli'] ) ) {
            $errors[] = $label . ': indicare i dettagli salute.';
        }

        if ( $child['altro_sport'] === 'si' && ( empty( $child['sport_societa'] ) || empty( $child['sport_giorni'] ) ) ) {
            $errors[] = $label . ': indicare società e giorni dell’altro sport.';
        }

        if ( ! sport_theme_get_uploaded_file( "figlio_{$child['index']}_foto_giocatore" ) ) {
            $errors[] = $label . ': caricare la foto del giocatore.';
        }

        foreach ( sport_theme_document_fields_for_type( $child['index'], $child['tipo_documento'] ) as $field_name => $role ) {
            if ( ! sport_theme_get_uploaded_file( $field_name ) ) {
                $errors[] = $label . ': caricare il documento richiesto (' . str_replace( '_', ' ', $role ) . ').';
            }
        }
    }

    foreach ( array( 'responsabilita_genitoriale', 'responsabile_nome', 'responsabile_cognome', 'responsabile_telefono', 'responsabile_email', 'metodo_pagamento' ) as $required ) {
        if ( empty( $_POST[ $required ] ) ) {
            $errors[] = 'Responsabile: campo obbligatorio mancante (' . $required . ').';
        }
    }

    if ( ! is_email( sport_theme_clean_email_field( 'responsabile_email' ) ) ) {
        $errors[] = 'Inserisci un indirizzo email responsabile valido.';
    }

    $responsabilita = sport_theme_sanitize_iscrizione_key( $_POST['responsabilita_genitoriale'] ?? '', array( 'padre', 'madre', 'tutore_legale' ) );
    if ( $responsabilita === 'tutore_legale' && ! sport_theme_get_uploaded_file( 'certificato_tutela' ) ) {
        $errors[] = 'Caricare il certificato di tutela.';
    }

    return $errors;
}

function sport_theme_document_fields_for_type( $child_index, $tipo_documento ) {
    if ( $tipo_documento === 'carta_identita' ) {
        return array(
            "figlio_{$child_index}_carta_identita_fronte" => 'carta_identita_fronte',
            "figlio_{$child_index}_carta_identita_retro"  => 'carta_identita_retro',
        );
    }

    if ( $tipo_documento === 'permesso_soggiorno' ) {
        return array(
            "figlio_{$child_index}_permesso_soggiorno_fronte" => 'permesso_soggiorno_fronte',
            "figlio_{$child_index}_permesso_soggiorno_retro"  => 'permesso_soggiorno_retro',
        );
    }

    if ( $tipo_documento === 'passaporto' ) {
        return array(
            "figlio_{$child_index}_passaporto_fronte" => 'passaporto_fronte',
        );
    }

    return array();
}

function sport_theme_handle_iscrizione_submit() {
    check_ajax_referer( 'act_iscrizione_submit', 'nonce' );

    global $wpdb;

    sport_theme_create_iscrizioni_tables();

    $tables          = sport_theme_iscrizioni_table_names();
    $tipo_iscrizione = sport_theme_sanitize_iscrizione_key( $_POST['tipo_iscrizione'] ?? 'allievi', array( 'allievi', 'scuola_calcio' ), 'allievi' );
    $children        = sport_theme_collect_iscrizione_children( $tipo_iscrizione );
    $errors          = sport_theme_validate_iscrizione_payload( $children );

    if ( $errors ) {
        wp_send_json_error( array( 'message' => 'Controlla i dati inseriti.', 'errors' => $errors ), 422 );
    }

    $uuid = wp_generate_uuid4();
    $now  = current_time( 'mysql' );

    $registration_data = array(
        'uuid'                         => $uuid,
        'tipo_iscrizione'              => $tipo_iscrizione,
        'stagione_sportiva'            => sport_theme_current_sport_season(),
        'stato'                        => 'nuova',
        'metodo_pagamento'             => sport_theme_sanitize_iscrizione_key( $_POST['metodo_pagamento'] ?? '', array( 'stripe', 'fattura' ) ),
        'stato_pagamento'              => 'non_pagato',
        'importo_totale_chf'           => sport_theme_calculate_iscrizione_amount( $tipo_iscrizione, count( $children ), false ),
        'riduzione_fratelli'           => 0,
        'responsabilita_genitoriale'   => sport_theme_sanitize_iscrizione_key( $_POST['responsabilita_genitoriale'] ?? '', array( 'padre', 'madre', 'tutore_legale' ) ),
        'responsabile_nome'            => sport_theme_clean_text_field( 'responsabile_nome' ),
        'responsabile_cognome'         => sport_theme_clean_text_field( 'responsabile_cognome' ),
        'responsabile_telefono'        => sport_theme_clean_text_field( 'responsabile_telefono' ),
        'responsabile_email'           => sport_theme_clean_email_field( 'responsabile_email' ),
        'regolamento_accettato'        => 1,
        'numero_bambini'               => count( $children ),
        'note_interne'                 => '',
        'dati_json'                    => wp_json_encode( array( 'children' => $children ), JSON_UNESCAPED_UNICODE ),
        'ip_hash'                      => isset( $_SERVER['REMOTE_ADDR'] ) ? hash( 'sha256', sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) . wp_salt() ) : '',
        'user_agent'                   => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
        'created_at'                   => $now,
        'updated_at'                   => $now,
    );

    $inserted = $wpdb->insert(
        $tables['registrations'],
        $registration_data,
        array( '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
    );

    if ( ! $inserted ) {
        wp_send_json_error( array( 'message' => 'Non è stato possibile salvare l’iscrizione.' ), 500 );
    }

    $iscrizione_id = (int) $wpdb->insert_id;

    foreach ( $children as $child ) {
        $child_quota = sport_theme_calculate_iscrizione_child_amount( $tipo_iscrizione, $child['index'] );
        $wpdb->insert(
            $tables['children'],
            array(
                'iscrizione_id'                 => $iscrizione_id,
                'child_index'                   => (int) $child['index'],
                'nome'                          => $child['nome'],
                'cognome'                       => $child['cognome'],
                'data_nascita'                  => $child['data_nascita'],
                'nazionalita'                   => $child['nazionalita'],
                'avs'                           => $child['avs'],
                'indirizzo'                     => $child['indirizzo'],
                'cap_citta'                     => $child['cap_citta'],
                'email'                         => $child['email'],
                'cellulare'                     => $child['cellulare'],
                'categoria'                     => '',
                'quota_chf'                     => $child_quota,
                'salute_allergie_medicinali'    => $child['salute_allergie_medicinali'],
                'salute_dettagli'               => $child['salute_dettagli'],
                'altro_sport'                   => $child['altro_sport'],
                'sport_societa'                 => $child['sport_societa'],
                'sport_giorni'                  => $child['sport_giorni'],
                'tragitto_autonomo'             => $child['tragitto_autonomo'],
                'abile_sport'                   => $child['abile_sport'],
                'tipo_documento'                => $child['tipo_documento'],
                'created_at'                    => $now,
                'updated_at'                    => $now,
            ),
            array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );

        $bambino_id = (int) $wpdb->insert_id;

        $photo_field = "figlio_{$child['index']}_foto_giocatore";
        $photo       = sport_theme_store_media_iscrizione_file( $photo_field );
        if ( is_wp_error( $photo ) ) {
            wp_send_json_error( array( 'message' => $photo->get_error_message() ), 500 );
        }

        $photo_document_id = sport_theme_insert_iscrizione_document( $iscrizione_id, $bambino_id, $child['index'], 'foto', $photo_field, 'foto_giocatore', $photo, 'media' );
        if ( $photo_document_id && ! empty( $photo['attachment_id'] ) ) {
            $wpdb->update( $tables['children'], array( 'foto_document_id' => $photo_document_id ), array( 'id' => $bambino_id ), array( '%d' ), array( '%d' ) );
        }

        foreach ( sport_theme_document_fields_for_type( $child['index'], $child['tipo_documento'] ) as $field_name => $role ) {
            $private_file = sport_theme_store_private_iscrizione_file( $field_name, $uuid, "bambino-{$child['index']}-{$role}" );
            if ( is_wp_error( $private_file ) ) {
                wp_send_json_error( array( 'message' => $private_file->get_error_message() ), 500 );
            }

            sport_theme_insert_iscrizione_document( $iscrizione_id, $bambino_id, $child['index'], $child['tipo_documento'], $field_name, $role, $private_file, 'private' );
        }
    }

    if ( sport_theme_sanitize_iscrizione_key( $_POST['responsabilita_genitoriale'] ?? '', array( 'padre', 'madre', 'tutore_legale' ) ) === 'tutore_legale' ) {
        $certificate = sport_theme_store_private_iscrizione_file( 'certificato_tutela', $uuid, 'certificato-tutela' );
        if ( is_wp_error( $certificate ) ) {
            wp_send_json_error( array( 'message' => $certificate->get_error_message() ), 500 );
        }

        $certificate_document_id = sport_theme_insert_iscrizione_document( $iscrizione_id, null, null, 'certificato_tutela', 'certificato_tutela', 'certificato_tutela', $certificate, 'private' );
        if ( $certificate_document_id ) {
            $wpdb->update( $tables['registrations'], array( 'certificato_tutela_document_id' => $certificate_document_id ), array( 'id' => $iscrizione_id ), array( '%d' ), array( '%d' ) );
        }
    }

    $wpdb->insert(
        $tables['logs'],
        array(
            'iscrizione_id' => $iscrizione_id,
            'azione'        => 'creata',
            'messaggio'     => 'Iscrizione inviata dal sito.',
            'created_by'    => get_current_user_id() ?: null,
            'created_at'    => $now,
        ),
        array( '%d', '%s', '%s', '%d', '%s' )
    );

    sport_theme_send_iscrizione_received_email( $iscrizione_id );

    wp_send_json_success(
        array(
            'message'        => 'Iscrizione ricevuta correttamente.',
            'iscrizione_id'  => $iscrizione_id,
            'uuid'           => $uuid,
            'tipo'           => $tipo_iscrizione,
            'numero_bambini' => count( $children ),
        )
    );
}
add_action( 'wp_ajax_act_submit_iscrizione', 'sport_theme_handle_iscrizione_submit' );
add_action( 'wp_ajax_nopriv_act_submit_iscrizione', 'sport_theme_handle_iscrizione_submit' );

function sport_theme_iscrizioni_require_segreteria_access() {
    if ( ! is_user_logged_in() || ! sport_theme_can_access_segreteria() ) {
        wp_die( 'Accesso non autorizzato.', 403 );
    }
}

function sport_theme_iscrizioni_allowed_statuses() {
    return array( 'nuova', 'in_verifica', 'documenti_mancanti', 'approvata', 'confermata', 'archiviata' );
}

function sport_theme_iscrizioni_status_labels() {
    return array(
        'nuova'              => 'Nuova',
        'in_verifica'        => 'In verifica',
        'documenti_mancanti' => 'Documenti mancanti',
        'approvata'          => 'Approvata',
        'confermata'         => 'Confermata',
        'archiviata'         => 'Archiviata',
    );
}

function sport_theme_iscrizioni_category_options() {
    return array(
        ''              => 'Da assegnare',
        'scuola_calcio' => 'Scuola Calcio',
        'allievi_a'     => 'Allievi A',
        'allievi_b'     => 'Allievi B',
        'allievi_c'     => 'Allievi C',
        'allievi_d'     => 'Allievi D',
        'allievi_e'     => 'Allievi E',
        'allievi_f'     => 'Allievi F',
        'allievi_g'     => 'Allievi G',
    );
}

function sport_theme_current_sport_season() {
    $year  = (int) current_time( 'Y' );
    $month = (int) current_time( 'n' );

    if ( $month >= 5 ) {
        return $year . '/' . ( $year + 1 );
    }

    return ( $year - 1 ) . '/' . $year;
}

function sport_theme_calculate_iscrizione_amount( $tipo_iscrizione, $children_count, $riduzione_fratelli = false ) {
    $children_count = max( 1, (int) $children_count );

    if ( $tipo_iscrizione === 'scuola_calcio' ) {
        return 150.00;
    }

    $amount = 300.00 + ( max( 0, $children_count - 1 ) * 250.00 );

    if ( $riduzione_fratelli ) {
        $amount -= 50.00;
    }

    return max( 0, $amount );
}

function sport_theme_calculate_iscrizione_child_amount( $tipo_iscrizione, $child_index ) {
    if ( $tipo_iscrizione === 'scuola_calcio' ) {
        return 150.00;
    }

    return (int) $child_index <= 1 ? 300.00 : 250.00;
}

function sport_theme_get_iscrizione_child_amount( $child, $tipo_iscrizione = 'allievi' ) {
    $quota = is_array( $child ) ? ( $child['quota_chf'] ?? null ) : ( $child->quota_chf ?? null );
    if ( $quota !== null && $quota !== '' ) {
        return max( 0, (float) $quota );
    }

    $child_index = is_array( $child ) ? ( $child['child_index'] ?? 1 ) : ( $child->child_index ?? 1 );
    return sport_theme_calculate_iscrizione_child_amount( $tipo_iscrizione, $child_index );
}

function sport_theme_sum_iscrizione_children_amounts( $children, $tipo_iscrizione = 'allievi', $riduzione_fratelli = false ) {
    $total = 0.00;

    foreach ( (array) $children as $child ) {
        $total += sport_theme_get_iscrizione_child_amount( $child, $tipo_iscrizione );
    }

    if ( $riduzione_fratelli && $tipo_iscrizione === 'allievi' ) {
        $total -= 50.00;
    }

    return max( 0, $total );
}

function sport_theme_has_riduzione_fratelli( $registration ) {
    return ! empty( $registration->riduzione_fratelli );
}

function sport_theme_iscrizione_discount_lines( $registration ) {
    $lines = array();

    if ( ! empty( $registration->sconto_meta_stagione ) ) {
        $lines[] = 'Sconto meta stagione 50% applicato';
    }

    if ( sport_theme_has_riduzione_fratelli( $registration ) ) {
        $lines[] = 'Riduzione fratello/sorella: - CHF 50.00';
    }

    return $lines;
}

function sport_theme_iscrizione_payment_description() {
    return 'Tassa sociale per attività sportiva';
}

function sport_theme_pdf_escape_text( $text ) {
    $text = wp_strip_all_tags( (string) $text );
    $text = html_entity_decode( $text, ENT_QUOTES, 'UTF-8' );

    if ( function_exists( 'iconv' ) ) {
        $converted = @iconv( 'UTF-8', 'Windows-1252//TRANSLIT', $text );
        if ( $converted !== false ) {
            $text = $converted;
        }
    }

    return str_replace( array( '\\', '(', ')' ), array( '\\\\', '\\(', '\\)' ), $text );
}

function sport_theme_create_simple_a4_pdf( $title, $lines, $filename ) {
    $upload_dir = wp_upload_dir();
    if ( ! empty( $upload_dir['error'] ) ) {
        return new WP_Error( 'pdf_upload_dir', $upload_dir['error'] );
    }

    $dir = trailingslashit( $upload_dir['basedir'] ) . 'act-iscrizioni-pdf';
    if ( ! wp_mkdir_p( $dir ) ) {
        return new WP_Error( 'pdf_dir', 'Impossibile creare la cartella PDF iscrizioni.' );
    }

    $filename = sanitize_file_name( $filename );
    $path     = trailingslashit( $dir ) . $filename;

    $pages       = array_chunk( array_values( $lines ), 42 );
    $page_count  = max( 1, count( $pages ) );
    $objects     = array();
    $content_ids = array();
    $page_ids    = array();

    $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
    $objects[] = null;
    $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';

    for ( $page_index = 0; $page_index < $page_count; $page_index++ ) {
        $content_id     = count( $objects ) + 1;
        $page_id        = $content_id + 1;
        $content_ids[]  = $content_id;
        $page_ids[]     = $page_id;
        $page_lines     = $pages[ $page_index ] ?? array();
        $stream_lines   = array( 'BT', '/F1 20 Tf', '50 790 Td', '(' . sport_theme_pdf_escape_text( $title ) . ') Tj', '/F1 10 Tf', '0 -18 Td', '(AC Taverne) Tj' );

        foreach ( $page_lines as $line ) {
            $line = (string) $line;
            if ( $line === '' ) {
                $stream_lines[] = '0 -14 Td';
                continue;
            }

            if ( strlen( $line ) > 95 ) {
                $chunks = explode( "\n", wordwrap( $line, 95, "\n", true ) );
            } else {
                $chunks = array( $line );
            }

            foreach ( $chunks as $chunk ) {
                $stream_lines[] = '0 -14 Td';
                $stream_lines[] = '(' . sport_theme_pdf_escape_text( $chunk ) . ') Tj';
            }
        }

        $stream_lines[] = '0 -24 Td';
        $stream_lines[] = '(' . sport_theme_pdf_escape_text( 'Pagina ' . ( $page_index + 1 ) . ' di ' . $page_count ) . ') Tj';
        $stream_lines[] = 'ET';
        $stream         = implode( "\n", $stream_lines );

        $objects[] = "<< /Length " . strlen( $stream ) . " >>\nstream\n{$stream}\nendstream";
        $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R >> >> /Contents {$content_id} 0 R >>";
    }

    $kids       = implode( ' ', array_map( static function ( $id ) { return $id . ' 0 R'; }, $page_ids ) );
    $objects[1] = "<< /Type /Pages /Kids [{$kids}] /Count {$page_count} >>";

    $pdf     = "%PDF-1.4\n";
    $offsets = array( 0 );

    foreach ( $objects as $index => $object ) {
        $offsets[] = strlen( $pdf );
        $object_id = $index + 1;
        $pdf      .= "{$object_id} 0 obj\n{$object}\nendobj\n";
    }

    $xref = strlen( $pdf );
    $pdf .= "xref\n0 " . ( count( $objects ) + 1 ) . "\n";
    $pdf .= "0000000000 65535 f \n";

    for ( $i = 1; $i <= count( $objects ); $i++ ) {
        $pdf .= sprintf( "%010d 00000 n \n", $offsets[ $i ] );
    }

    $pdf .= "trailer\n<< /Size " . ( count( $objects ) + 1 ) . " /Root 1 0 R >>\n";
    $pdf .= "startxref\n{$xref}\n%%EOF";

    if ( file_put_contents( $path, $pdf ) === false ) {
        return new WP_Error( 'pdf_write', 'Impossibile scrivere il PDF.' );
    }

    return $path;
}

function sport_theme_create_iscrizione_confirmation_pdf( $registration, $children ) {
    $responsabile = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $type_label   = $registration->tipo_iscrizione === 'scuola_calcio' ? 'Scuola Calcio' : 'Allievi';
    $season       = $registration->stagione_sportiva ?: sport_theme_current_sport_season();
    $upload_dir = wp_upload_dir();
    if ( ! empty( $upload_dir['error'] ) ) {
        return new WP_Error( 'pdf_upload_dir', $upload_dir['error'] );
    }

    $dir = trailingslashit( $upload_dir['basedir'] ) . 'act-iscrizioni-pdf';
    if ( ! wp_mkdir_p( $dir ) ) {
        return new WP_Error( 'pdf_dir', 'Impossibile creare la cartella PDF iscrizioni.' );
    }

    $filename = sanitize_file_name( 'conferma-iscrizione-' . (int) $registration->id . '.pdf' );
    $path     = trailingslashit( $dir ) . $filename;

    $company_profile = function_exists( 'sport_theme_invoice_company_profile' ) ? sport_theme_invoice_company_profile() : array();
    $logo_path       = $company_profile['logo_path'] ?? '';
    $logo_data       = ( $logo_path && file_exists( $logo_path ) ) ? file_get_contents( $logo_path ) : false;
    $logo_size       = ( $logo_path && file_exists( $logo_path ) ) ? getimagesize( $logo_path ) : false;
    if ( $logo_size && ( $logo_size['mime'] ?? '' ) !== 'image/jpeg' ) {
        $logo_data = false;
        $logo_size = false;
    }

    $document_labels = array(
        'carta_identita'     => 'Carta d’identità',
        'permesso_soggiorno' => 'Permesso di soggiorno',
        'passaporto'         => 'Passaporto',
    );
    $status_labels = sport_theme_iscrizioni_status_labels();
    $children_chunks = array_chunk( array_values( $children ), 3 );
    $children_chunks = $children_chunks ?: array( array() );
    $page_count = count( $children_chunks );
    $objects = array(
        '<< /Type /Catalog /Pages 2 0 R >>',
        null,
        '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
        '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>',
    );
    if ( $logo_data && $logo_size ) {
        $objects[] = "<< /Type /XObject /Subtype /Image /Width {$logo_size[0]} /Height {$logo_size[1]} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length " . strlen( $logo_data ) . " >>\nstream\n{$logo_data}\nendstream";
    }
    $page_ids = array();

    $text = static function ( $value, $x, $y, $size = 10, $font = 'F1' ) {
        return sprintf( "BT /%s %F Tf 1 0 0 1 %F %F Tm (%s) Tj ET\n", $font, $size, $x, $y, sport_theme_pdf_escape_text( $value ) );
    };
    $line = static function ( $x1, $y1, $x2, $y2 ) {
        return sprintf( "%F %F m %F %F l S\n", $x1, $y1, $x2, $y2 );
    };
    $rect = static function ( $x, $y, $w, $h, $fill = false ) {
        return sprintf( "%F %F %F %F re %s\n", $x, $y, $w, $h, $fill ? 'f' : 'S' );
    };
    $wrapped_text = static function ( $value, $x, $y, $max_chars, $size = 9, $font = 'F1', $leading = 12 ) use ( $text ) {
        $out = '';
        $lines = explode( "\n", wordwrap( trim( (string) $value ), $max_chars, "\n", true ) );
        foreach ( $lines as $index => $line_value ) {
            $out .= $text( $line_value, $x, $y - ( $index * $leading ), $size, $font );
        }
        return $out;
    };

    foreach ( $children_chunks as $page_index => $page_children ) {
        $stream = "0 0 0 rg 0 0 0 RG 1 w\n";
        if ( $logo_data && $logo_size ) {
            $stream .= "q 82 0 0 115 42 684 cm /ImLogo Do Q\n";
        }
        $company_x = ( $logo_data && $logo_size ) ? 142 : 42;
        $stream .= $text( $company_profile['name'] ?? 'AC Taverne', $company_x, 790, 13, 'F2' );
        $stream .= $text( $company_profile['society_number'] ?? '', $company_x, 774, 11 );
        $company_y = 748;
        foreach ( (array) ( $company_profile['address_lines'] ?? array() ) as $company_line ) {
            $stream .= $text( $company_line, $company_x, $company_y, 10 );
            $company_y -= 14;
        }
        $company_y -= 10;
        $stream .= $text( $company_profile['phone'] ?? '', $company_x, $company_y, 10 );
        $company_y -= 14;
        $stream .= $text( $company_profile['email'] ?? '', $company_x, $company_y, 10 );
        $company_y -= 14;
        $stream .= $text( $company_profile['website'] ?? '', $company_x, $company_y, 10 );
        $company_y -= 14;
        $stream .= $text( $company_profile['vat'] ?? '', $company_x, $company_y, 10, 'F2' );

        $stream .= $text( 'CONFERMA', 390, 790, 26, 'F2' );
        $stream .= $text( 'ISCRIZIONE', 390, 764, 26, 'F2' );
        $stream .= $text( 'Pratica: #' . (int) $registration->id, 390, 728, 9, 'F2' );
        $stream .= $text( 'Data conferma: ' . mysql2date( 'd.m.Y', current_time( 'mysql' ) ), 390, 714, 9 );
        $stream .= $text( 'Stagione: ' . $season, 390, 700, 9 );
        $stream .= $line( 42, 642, 553, 642 );

        $stream .= $text( 'RIEPILOGO PRATICA', 42, 612, 10, 'F2' );
        $stream .= $text( 'Codice pratica', 42, 592, 8, 'F2' );
        $stream .= $wrapped_text( $registration->uuid, 42, 580, 48, 8 );
        $stream .= $text( 'Tipo iscrizione', 300, 592, 8, 'F2' );
        $stream .= $text( $type_label, 300, 580, 9 );
        $stream .= $text( 'Stato', 390, 592, 8, 'F2' );
        $stream .= $text( sport_theme_iscrizione_label_value( $registration->stato, $status_labels ), 390, 580, 9 );
        $stream .= $text( 'Importo previsto', 485, 592, 8, 'F2' );
        $stream .= $text( 'CHF ' . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ), 485, 580, 9, 'F2' );

        $stream .= $text( 'RESPONSABILE LEGALE', 42, 542, 10, 'F2' );
        $stream .= $text( $responsabile ?: '-', 42, 522, 12, 'F2' );
        $stream .= $text( 'Email: ' . ( $registration->responsabile_email ?: '-' ), 42, 506, 9 );
        $stream .= $text( 'Telefono: ' . ( $registration->responsabile_telefono ?: '-' ), 300, 506, 9 );
        $stream .= $text( 'Responsabilità genitoriale: ' . sport_theme_iscrizione_label_value(
            $registration->responsabilita_genitoriale,
            array(
                'padre'         => 'Padre',
                'madre'         => 'Madre',
                'tutore_legale' => 'Tutore legale',
            )
        ), 42, 491, 9 );

        $stream .= $line( 42, 466, 553, 466 );
        $stream .= $text( 'ISCRITTI', 42, 444, 10, 'F2' );

        $box_y = 416;
        foreach ( $page_children as $child ) {
            $child_name = trim( (string) $child->nome . ' ' . (string) $child->cognome );
            $birth_date = $child->data_nascita ? mysql2date( 'd.m.Y', $child->data_nascita ) : '-';
            $address = trim( (string) $child->indirizzo . ' ' . (string) $child->cap_citta );

            $stream .= $text( $child_name ?: 'Iscritto', 42, $box_y, 11, 'F2' );
            $stream .= $text( 'Nascita: ' . $birth_date, 42, $box_y - 18, 8.5 );
            $stream .= $text( 'Nazionalità: ' . ( $child->nazionalita ?: '-' ), 172, $box_y - 18, 8.5 );
            $stream .= $text( 'Categoria: ' . ( $child->categoria ? sport_theme_iscrizione_label_value( $child->categoria, sport_theme_iscrizioni_category_options() ) : 'Da assegnare' ), 315, $box_y - 18, 8.5 );
            $stream .= $wrapped_text( 'Indirizzo: ' . ( $address ?: '-' ), 42, $box_y - 34, 82, 8.5 );
            $stream .= $text( 'Documento: ' . sport_theme_iscrizione_label_value( $child->tipo_documento, $document_labels ), 42, $box_y - 58, 8.5 );
            $stream .= $text( 'Cellulare: ' . ( $child->cellulare ?: '-' ), 300, $box_y - 58, 8.5 );
            $stream .= $line( 42, $box_y - 76, 553, $box_y - 76 );
            $box_y -= 98;
        }

        if ( $page_index === $page_count - 1 ) {
            $stream .= $text( 'PAGAMENTO', 42, 142, 10, 'F2' );
            $stream .= $text( sport_theme_iscrizione_payment_description(), 42, 122, 11, 'F2' );
            $stream .= $text( 'Importo previsto: CHF ' . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ), 42, 106, 9 );
            $discount_y = 92;
            foreach ( sport_theme_iscrizione_discount_lines( $registration ) as $discount_line ) {
                $stream .= $text( $discount_line, 42, $discount_y, 9 );
                $discount_y -= 12;
            }
            $stream .= $wrapped_text( 'La presente conferma attesta la ricezione della richiesta d’iscrizione. La pratica sarà verificata dalla segreteria AC Taverne.', 42, 68, 96, 8.5 );
        }

        $stream .= $text( 'Pagina ' . ( $page_index + 1 ) . ' di ' . $page_count, 500, 34, 8 );

        $content_id = count( $objects ) + 1;
        $page_id = $content_id + 1;
        $xobjects = ( $logo_data && $logo_size ) ? ' /XObject << /ImLogo 5 0 R >>' : '';
        $objects[] = "<< /Length " . strlen( $stream ) . " >>\nstream\n{$stream}\nendstream";
        $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R /F2 4 0 R >>{$xobjects} >> /Contents {$content_id} 0 R >>";
        $page_ids[] = $page_id;
    }

    $kids       = implode( ' ', array_map( static function ( $id ) { return $id . ' 0 R'; }, $page_ids ) );
    $objects[1] = "<< /Type /Pages /Kids [{$kids}] /Count {$page_count} >>";

    $pdf     = "%PDF-1.4\n";
    $offsets = array( 0 );
    foreach ( $objects as $index => $object ) {
        $offsets[] = strlen( $pdf );
        $object_id = $index + 1;
        $pdf      .= "{$object_id} 0 obj\n{$object}\nendobj\n";
    }

    $xref = strlen( $pdf );
    $pdf .= "xref\n0 " . ( count( $objects ) + 1 ) . "\n0000000000 65535 f \n";
    for ( $i = 1; $i <= count( $objects ); $i++ ) {
        $pdf .= sprintf( "%010d 00000 n \n", $offsets[ $i ] );
    }
    $pdf .= "trailer\n<< /Size " . ( count( $objects ) + 1 ) . " /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

    if ( file_put_contents( $path, $pdf ) === false ) {
        return new WP_Error( 'pdf_write', 'Impossibile scrivere il PDF.' );
    }

    return $path;
}

function sport_theme_iscrizione_label_value( $value, $labels ) {
    return $labels[ $value ] ?? str_replace( '_', ' ', (string) $value );
}

function sport_theme_log_iscrizione_event( $iscrizione_id, $azione, $messaggio, $created_by = null ) {
    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();

    return $wpdb->insert(
        $tables['logs'],
        array(
            'iscrizione_id' => (int) $iscrizione_id,
            'azione'        => sanitize_key( $azione ),
            'messaggio'     => sanitize_textarea_field( $messaggio ),
            'created_by'    => $created_by === null ? get_current_user_id() : (int) $created_by,
            'created_at'    => current_time( 'mysql' ),
        ),
        array( '%d', '%s', '%s', '%d', '%s' )
    );
}

function sport_theme_send_iscrizione_received_email( $iscrizione_id, $notify_internal = true ) {
    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $registration = $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$tables['registrations']} WHERE id = %d", $iscrizione_id )
    );

    if ( ! $registration ) {
        return false;
    }

    $children = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM {$tables['children']} WHERE iscrizione_id = %d ORDER BY child_index ASC", $iscrizione_id )
    );

    $settings             = sport_theme_iscrizioni_email_settings();
    $internal_recipients  = sport_theme_parse_email_list( $settings['new_registration_recipients'] ?? '' );
    $confirmation_pdf     = sport_theme_create_iscrizione_confirmation_pdf( $registration, $children );
    $attachments          = is_wp_error( $confirmation_pdf ) ? array() : array( $confirmation_pdf );
    $parent_headers       = array( 'Content-Type: text/plain; charset=UTF-8' );
    $internal_headers     = array( 'Content-Type: text/plain; charset=UTF-8' );
    $responsabile         = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $child_names          = array();

    foreach ( $children as $child ) {
        $child_names[] = trim( (string) $child->nome . ' ' . (string) $child->cognome );
    }

    $type_label = $registration->tipo_iscrizione === 'scuola_calcio' ? 'Scuola Calcio' : 'Allievi';
    $payment_label = sport_theme_iscrizione_label_value(
        $registration->metodo_pagamento,
        array(
            'stripe'  => 'Pagamento online con carta',
            'fattura' => 'Fattura QR',
        )
    );
    $subject = 'AC Taverne - Conferma ricezione iscrizione #' . (int) $registration->id;
    $season = $registration->stagione_sportiva ?: sport_theme_current_sport_season();
    $amount_label = 'CHF ' . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" );
    $discount_lines = sport_theme_iscrizione_discount_lines( $registration );
    $greeting_name = $responsabile ?: 'famiglia';
    $message = "Gentile {$greeting_name},\n\n";
    $message .= "abbiamo ricevuto correttamente la vostra richiesta d’iscrizione.\n";
    $message .= "La segreteria verificherà i dati inseriti e i documenti caricati; vi contatteremo via email solo se saranno necessarie informazioni mancanti o correzioni.\n\n";
    $message .= "RIEPILOGO PRATICA\n";
    $message .= "Pratica: #{$registration->id}\n";
    $message .= "Codice pratica: {$registration->uuid}\n";
    $message .= "Stagione sportiva: {$season}\n";
    $message .= "Tipo iscrizione: {$type_label}\n";
    $message .= "Iscritto/i: " . ( implode( ', ', array_filter( $child_names ) ) ?: '-' ) . "\n";
    $message .= "Importo previsto: {$amount_label}\n";
    $message .= "Metodo pagamento scelto: " . ( $payment_label ?: 'Da definire' ) . "\n";
    foreach ( $discount_lines as $discount_line ) {
        $message .= $discount_line . "\n";
    }
    $message .= "\n";
    $message .= "In allegato trovate il riepilogo della richiesta d’iscrizione in formato PDF.\n\n";
    $message .= "Per domande o correzioni potete rispondere a questa email indicando il codice pratica.\n\n";
    $message .= "AC Taverne";

    $sent_parent = true;
    if ( is_email( $registration->responsabile_email ) ) {
        $sent_parent = wp_mail( $registration->responsabile_email, $subject, $message, $parent_headers, $attachments );
    }

    if ( $notify_internal && $internal_recipients ) {
        $internal_subject = 'AC Taverne - Nuova iscrizione ricevuta';
        $internal_message = "È stata inviata una nuova iscrizione dal sito.\n\n";
        $internal_message .= "Pratica: #{$registration->id}\n";
        $internal_message .= "Codice pratica: {$registration->uuid}\n";
        $internal_message .= "Responsabile: {$responsabile}\n";
        $internal_message .= "Email: {$registration->responsabile_email}\n";
        $internal_message .= "Telefono: {$registration->responsabile_telefono}\n";
        $internal_message .= "Bambino/i: " . implode( ', ', array_filter( $child_names ) ) . "\n";
        $internal_message .= "Metodo pagamento scelto: " . ( $registration->metodo_pagamento ?: 'Da definire' ) . "\n";
        $internal_message .= "Importo previsto: CHF " . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) . "\n";
        foreach ( sport_theme_iscrizione_discount_lines( $registration ) as $discount_line ) {
            $internal_message .= $discount_line . "\n";
        }
        $internal_message .= "\n";
        $internal_message .= "La conferma d’iscrizione PDF è allegata alla presente email.";

        wp_mail( $internal_recipients, $internal_subject, $internal_message, $internal_headers, $attachments );
    }

    return $sent_parent;
}

function sport_theme_send_iscrizione_status_email( $iscrizione_id, $new_status ) {
    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $registration = $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$tables['registrations']} WHERE id = %d", $iscrizione_id )
    );

    if ( ! $registration || ! is_email( $registration->responsabile_email ) ) {
        return false;
    }

    $labels       = sport_theme_iscrizioni_status_labels();
    $status_label = sport_theme_iscrizione_label_value( $new_status, $labels );
    $responsabile = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $subject      = $new_status === 'confermata' ? 'AC Taverne - Iscrizione confermata' : 'AC Taverne - Aggiornamento iscrizione';
    $message = "Gentile {$registration->responsabile_nome} {$registration->responsabile_cognome},\n\n";

    if ( $new_status === 'confermata' ) {
        $message = "Gentile {$responsabile},\n\n";
        $message .= "Complimenti, la tua/e iscrizione/i è/sono stata/e confermata/e.\n";
        $message .= "Ti preghiamo di passare al prossimo step per effettuare il pagamento della tassa sociale.\n\n";
        $message .= "Codice pratica: {$registration->uuid}\n";
        $message .= "Importo: CHF " . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) . "\n";
        foreach ( sport_theme_iscrizione_discount_lines( $registration ) as $discount_line ) {
            $message .= $discount_line . "\n";
        }
        $message .= "Metodo pagamento selezionato: " . ( $registration->metodo_pagamento === 'stripe' ? 'Carta' : 'Fattura' ) . "\n\n";
        $message .= "AC Taverne";

        return wp_mail( $registration->responsabile_email, $subject, $message, array( 'Content-Type: text/plain; charset=UTF-8' ) );
    }

    $message .= "la vostra iscrizione è stata aggiornata.\n";
    $message .= "Codice pratica: {$registration->uuid}\n";
    $message .= "Nuovo stato: {$status_label}\n\n";

    if ( $new_status === 'documenti_mancanti' ) {
        $message .= "Alcuni documenti risultano mancanti o da verificare. La segreteria vi contatterà con le indicazioni necessarie.\n\n";
    } elseif ( $new_status === 'approvata' ) {
        $message .= "La pratica è stata approvata dalla segreteria.\n\n";
    } elseif ( $new_status === 'confermata' ) {
        $message .= "L'iscrizione è confermata.\n\n";
    } elseif ( $new_status === 'in_verifica' ) {
        $message .= "La pratica è ora in fase di verifica.\n\n";
    }

    $message .= "AC Taverne";

    return wp_mail( $registration->responsabile_email, $subject, $message, array( 'Content-Type: text/plain; charset=UTF-8' ) );
}

function sport_theme_handle_update_iscrizione_status() {
    sport_theme_iscrizioni_require_segreteria_access();
    check_admin_referer( 'act_update_iscrizione_status' );

    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $id     = isset( $_POST['iscrizione_id'] ) ? absint( $_POST['iscrizione_id'] ) : 0;
    $stato  = sport_theme_sanitize_iscrizione_key( $_POST['stato'] ?? '', sport_theme_iscrizioni_allowed_statuses() );

    if ( ! $id || ! $stato ) {
        wp_safe_redirect( wp_get_referer() ?: home_url( '/area-segreteria/' ) );
        exit;
    }

    $previous_status = $wpdb->get_var(
        $wpdb->prepare( "SELECT stato FROM {$tables['registrations']} WHERE id = %d", $id )
    );

    $wpdb->update(
        $tables['registrations'],
        array(
            'stato'      => $stato,
            'updated_at' => current_time( 'mysql' ),
        ),
        array( 'id' => $id ),
        array( '%s', '%s' ),
        array( '%d' )
    );

    if ( $previous_status && $previous_status !== $stato ) {
        sport_theme_send_iscrizione_status_email( $id, $stato );
    }

    $wpdb->insert(
        $tables['logs'],
        array(
            'iscrizione_id' => $id,
            'azione'        => 'stato_modificato',
            'messaggio'     => 'Stato aggiornato a: ' . $stato,
            'created_by'    => get_current_user_id(),
            'created_at'    => current_time( 'mysql' ),
        ),
        array( '%d', '%s', '%s', '%d', '%s' )
    );

    wp_safe_redirect( wp_get_referer() ?: home_url( '/area-segreteria/' ) );
    exit;
}
add_action( 'admin_post_act_update_iscrizione_status', 'sport_theme_handle_update_iscrizione_status' );

function sport_theme_handle_quick_iscrizione_action() {
    sport_theme_iscrizioni_require_segreteria_access();
    check_admin_referer( 'act_quick_iscrizione_action' );

    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $id     = isset( $_POST['iscrizione_id'] ) ? absint( $_POST['iscrizione_id'] ) : 0;
    $quick  = isset( $_POST['quick_action'] ) ? sanitize_key( wp_unslash( $_POST['quick_action'] ) ) : '';

    if ( ! $id || ! $quick ) {
        wp_safe_redirect( wp_get_referer() ?: home_url( '/area-segreteria/' ) );
        exit;
    }

    $registration = $wpdb->get_row(
        $wpdb->prepare( "SELECT stato, stato_pagamento, tipo_iscrizione, riduzione_fratelli, sconto_meta_stagione FROM {$tables['registrations']} WHERE id = %d", $id )
    );

    if ( ! $registration ) {
        wp_safe_redirect( wp_get_referer() ?: home_url( '/area-segreteria/' ) );
        exit;
    }

    $updates = array( 'updated_at' => current_time( 'mysql' ) );
    $formats = array( '%s' );
    $message = '';

    if ( $quick === 'in_verifica' ) {
        $updates['stato'] = 'in_verifica';
        $formats[] = '%s';
        $message = 'Azione rapida: pratica messa in verifica.';
    } elseif ( $quick === 'documenti_mancanti' ) {
        $updates['stato'] = 'documenti_mancanti';
        $formats[] = '%s';
        $message = 'Azione rapida: documenti mancanti.';
    } elseif ( $quick === 'confermata' ) {
        $updates['stato'] = 'confermata';
        $formats[] = '%s';
        $message = 'Azione rapida: iscrizione confermata.';
    } elseif ( $quick === 'pagato' ) {
        $updates['stato_pagamento'] = 'pagato';
        $formats[] = '%s';
        $message = 'Azione rapida: pagamento segnato come ricevuto.';
    } elseif ( $quick === 'meta_stagione_50' ) {
        if ( ! empty( $registration->sconto_meta_stagione ) ) {
            wp_safe_redirect( wp_get_referer() ?: home_url( '/area-segreteria/' ) );
            exit;
        }

        $children = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM {$tables['children']} WHERE iscrizione_id = %d ORDER BY child_index ASC", $id )
        );
        $discounted_total = 0.00;

        foreach ( $children as $child ) {
            $current_quota = sport_theme_get_iscrizione_child_amount( $child, $registration->tipo_iscrizione );
            $discounted_quota = round( $current_quota * 0.5, 2 );
            $discounted_total += $discounted_quota;

            $wpdb->update(
                $tables['children'],
                array(
                    'quota_chf'   => $discounted_quota,
                    'updated_at'  => current_time( 'mysql' ),
                ),
                array( 'id' => (int) $child->id ),
                array( '%f', '%s' ),
                array( '%d' )
            );
        }

        if ( ! empty( $registration->riduzione_fratelli ) && $registration->tipo_iscrizione === 'allievi' ) {
            $discounted_total -= 50.00;
        }

        $updates['importo_totale_chf'] = max( 0, $discounted_total );
        $updates['sconto_meta_stagione'] = 1;
        $formats[] = '%f';
        $formats[] = '%d';
        $message = 'Azione rapida: applicato sconto metà stagione del 50%.';
    } elseif ( $quick === 'rimuovi_meta_stagione_50' ) {
        $children = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM {$tables['children']} WHERE iscrizione_id = %d ORDER BY child_index ASC", $id )
        );
        $restored_total = 0.00;

        foreach ( $children as $child ) {
            $standard_quota = sport_theme_calculate_iscrizione_child_amount( $registration->tipo_iscrizione, (int) $child->child_index );
            $restored_total += $standard_quota;

            $wpdb->update(
                $tables['children'],
                array(
                    'quota_chf'  => $standard_quota,
                    'updated_at' => current_time( 'mysql' ),
                ),
                array( 'id' => (int) $child->id ),
                array( '%f', '%s' ),
                array( '%d' )
            );
        }

        if ( ! empty( $registration->riduzione_fratelli ) && $registration->tipo_iscrizione === 'allievi' ) {
            $restored_total -= 50.00;
        }

        $updates['importo_totale_chf'] = max( 0, $restored_total );
        $updates['sconto_meta_stagione'] = 0;
        $formats[] = '%f';
        $formats[] = '%d';
        $message = 'Azione rapida: rimosso sconto metà stagione e ripristinate le quote standard.';
    }

    if ( count( $updates ) <= 1 ) {
        wp_safe_redirect( wp_get_referer() ?: home_url( '/area-segreteria/' ) );
        exit;
    }

    $wpdb->update( $tables['registrations'], $updates, array( 'id' => $id ), $formats, array( '%d' ) );

    if ( isset( $updates['stato'] ) && $registration->stato !== $updates['stato'] ) {
        sport_theme_send_iscrizione_status_email( $id, $updates['stato'] );
    }

    $wpdb->insert(
        $tables['logs'],
        array(
            'iscrizione_id' => $id,
            'azione'        => 'azione_rapida',
            'messaggio'     => $message,
            'created_by'    => get_current_user_id(),
            'created_at'    => current_time( 'mysql' ),
        ),
        array( '%d', '%s', '%s', '%d', '%s' )
    );

    wp_safe_redirect( wp_get_referer() ?: home_url( '/area-segreteria/' ) );
    exit;
}
add_action( 'admin_post_act_quick_iscrizione_action', 'sport_theme_handle_quick_iscrizione_action' );

function sport_theme_handle_resend_iscrizione_confirmation() {
    sport_theme_iscrizioni_require_segreteria_access();
    check_admin_referer( 'act_resend_iscrizione_confirmation' );

    $id = isset( $_POST['iscrizione_id'] ) ? absint( $_POST['iscrizione_id'] ) : 0;
    $redirect = $id ? add_query_arg( 'edit_iscrizione', $id, home_url( '/area-segreteria/' ) ) : home_url( '/area-segreteria/' );
    $sent = $id ? sport_theme_send_iscrizione_received_email( $id, false ) : false;

    if ( $sent ) {
        sport_theme_log_iscrizione_event( $id, 'conferma_reinviata', 'Conferma iscrizione reinviata manualmente.' );
    }

    wp_safe_redirect( add_query_arg( 'confirmation_sent', $sent ? '1' : '0', $redirect ) . '#segreteria-edit' );
    exit;
}
add_action( 'admin_post_act_resend_iscrizione_confirmation', 'sport_theme_handle_resend_iscrizione_confirmation' );

function sport_theme_handle_send_payment_reminder() {
    sport_theme_iscrizioni_require_segreteria_access();
    check_admin_referer( 'act_send_payment_reminder' );

    $id = isset( $_POST['iscrizione_id'] ) ? absint( $_POST['iscrizione_id'] ) : 0;
    $redirect = $id ? add_query_arg( 'edit_iscrizione', $id, home_url( '/area-segreteria/' ) ) : home_url( '/area-segreteria/' );
    $invoice_data = $id ? sport_theme_load_iscrizione_for_invoice( $id ) : null;

    if ( ! $invoice_data ) {
        wp_safe_redirect( add_query_arg( 'payment_reminder_sent', '0', $redirect ) . '#segreteria-edit' );
        exit;
    }

    list( $registration, $children ) = $invoice_data;
    if ( ! is_email( $registration->responsabile_email ) ) {
        wp_safe_redirect( add_query_arg( 'payment_reminder_sent', '0', $redirect ) . '#segreteria-edit' );
        exit;
    }

    $responsabile = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $child_names = array();
    foreach ( $children as $child ) {
        $child_names[] = trim( (string) $child->nome . ' ' . (string) $child->cognome );
    }

    $payment_link = '';
    if ( $registration->metodo_pagamento === 'stripe' ) {
        $payment_link = $registration->stripe_payment_url ?: '';
    } elseif ( $registration->metodo_pagamento === 'fattura' ) {
        $payment_link = sport_theme_iscrizione_invoice_url( $registration, true );
    }

    if ( ! $payment_link ) {
        wp_safe_redirect( add_query_arg( 'payment_reminder_sent', '0', $redirect ) . '#segreteria-edit' );
        exit;
    }

    $message = "Gentile {$responsabile},\n\n";
    $message .= "vi ricordiamo che il pagamento della tassa sociale risulta ancora aperto.\n\n";
    $message .= "Pratica: #{$registration->id}\n";
    $message .= "Bambino/i: " . implode( ', ', array_filter( $child_names ) ) . "\n";
    $message .= "Importo: CHF " . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) . "\n";
    $message .= "Link pagamento/fattura: {$payment_link}\n\n";
    $message .= "AC Taverne";

    $sent = wp_mail(
        $registration->responsabile_email,
        'AC Taverne - Promemoria pagamento iscrizione',
        $message,
        array( 'Content-Type: text/plain; charset=UTF-8' )
    );

    if ( $sent ) {
        sport_theme_log_iscrizione_event( $id, 'promemoria_pagamento', 'Promemoria pagamento inviato a ' . $registration->responsabile_email );
    }

    wp_safe_redirect( add_query_arg( 'payment_reminder_sent', $sent ? '1' : '0', $redirect ) . '#segreteria-edit' );
    exit;
}
add_action( 'admin_post_act_send_payment_reminder', 'sport_theme_handle_send_payment_reminder' );

function sport_theme_iscrizione_billing_settings() {
    $settings = array(
        'creditor_name' => 'AC Taverne',
        'street'        => 'Via Traversée',
        'house_number'  => '2',
        'postal_code'   => '6807',
        'city'          => 'Taverne',
        'country'       => 'CH',
        'iban'          => defined( 'ACT_TAVERNE_QR_IBAN' ) ? ACT_TAVERNE_QR_IBAN : 'CH95 0076 4160 1705 6200 2',
    );

    return apply_filters( 'sport_theme_iscrizione_billing_settings', $settings );
}

function sport_theme_invoice_company_profile() {
    return apply_filters(
        'sport_theme_invoice_company_profile',
        array(
            'logo_path'      => get_template_directory() . '/assets/images/invoice/ac-taverne-word-logo.jpg',
            'logo_url'       => get_template_directory_uri() . '/assets/images/invoice/ac-taverne-word-logo.jpg',
            'name'           => 'AC Taverne',
            'society_number' => 'Nr. Società 4091',
            'address_lines'  => array(
                'Via Traversée 2',
                '6807 Taverne',
                'Casella Postale 703',
            ),
            'phone'          => 'Tel: +4191 945 22 95',
            'email'          => 'info@actaverne.com',
            'website'        => 'www.actaverne.com',
            'vat'            => 'P. Iva: CHE-169.678.709',
        )
    );
}

function sport_theme_normalize_iban( $iban ) {
    return strtoupper( preg_replace( '/[^A-Z0-9]/i', '', (string) $iban ) );
}

function sport_theme_is_valid_iban( $iban ) {
    $iban = sport_theme_normalize_iban( $iban );

    if ( ! preg_match( '/^[A-Z]{2}[0-9]{2}[A-Z0-9]+$/', $iban ) ) {
        return false;
    }

    if ( in_array( substr( $iban, 0, 2 ), array( 'CH', 'LI' ), true ) && strlen( $iban ) !== 21 ) {
        return false;
    }

    $rearranged = substr( $iban, 4 ) . substr( $iban, 0, 4 );
    $numeric = '';

    for ( $i = 0, $len = strlen( $rearranged ); $i < $len; $i++ ) {
        $char = $rearranged[ $i ];
        $numeric .= ctype_alpha( $char ) ? (string) ( ord( $char ) - 55 ) : $char;
    }

    $remainder = 0;
    for ( $i = 0, $len = strlen( $numeric ); $i < $len; $i++ ) {
        $remainder = ( $remainder * 10 + (int) $numeric[ $i ] ) % 97;
    }

    return $remainder === 1;
}

function sport_theme_is_qr_iban( $iban ) {
    $iban = sport_theme_normalize_iban( $iban );
    $qr_iid = (int) substr( $iban, 4, 5 );

    return $qr_iid >= 30000 && $qr_iid <= 31999;
}

function sport_theme_split_street_number( $address ) {
    $address = trim( preg_replace( '/\s+/', ' ', (string) $address ) );

    if ( preg_match( '/^(.+?)\s+([0-9]+[A-Za-z]?)$/', $address, $matches ) ) {
        return array( trim( $matches[1] ), trim( $matches[2] ) );
    }

    return array( $address, null );
}

function sport_theme_split_postal_city( $value ) {
    $value = trim( preg_replace( '/\s+/', ' ', (string) $value ) );

    if ( preg_match( '/^([0-9]{4})\s+(.+)$/', $value, $matches ) ) {
        return array( $matches[1], trim( $matches[2] ) );
    }

    return array( '', '' );
}

function sport_theme_iscrizione_invoice_reference_seed( $registration ) {
    $season = preg_replace( '/\D+/', '', (string) ( $registration->stagione_sportiva ?? '' ) );
    return 'ACT' . ( $season ?: gmdate( 'Y' ) ) . (int) $registration->id;
}

function sport_theme_iscrizione_invoice_token( $registration ) {
    $payload = implode(
        '|',
        array(
            (int) $registration->id,
            (string) $registration->uuid,
            (string) $registration->created_at,
        )
    );

    return hash_hmac( 'sha256', $payload, wp_salt( 'auth' ) );
}

function sport_theme_iscrizione_invoice_url( $registration, $public = false ) {
    $args = array(
        'action'        => 'act_iscrizione_invoice',
        'iscrizione_id' => (int) $registration->id,
    );

    if ( $public ) {
        $args['invoice_token'] = sport_theme_iscrizione_invoice_token( $registration );

        return add_query_arg( $args, admin_url( 'admin-post.php' ) );
    }

    return wp_nonce_url(
        add_query_arg( $args, admin_url( 'admin-post.php' ) ),
        'act_iscrizione_invoice_' . (int) $registration->id
    );
}

function sport_theme_load_iscrizione_for_invoice( $id ) {
    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $registration = $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$tables['registrations']} WHERE id = %d", $id )
    );

    if ( ! $registration ) {
        return null;
    }

    $children = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM {$tables['children']} WHERE iscrizione_id = %d ORDER BY child_index ASC", $id )
    );

    return array( $registration, $children );
}

function sport_theme_create_iscrizione_qr_bill( $registration, $children ) {
    if ( ! class_exists( '\Sprain\SwissQrBill\QrBill' ) ) {
        throw new Exception( 'Libreria QR-fattura non disponibile.' );
    }

    $settings = sport_theme_iscrizione_billing_settings();
    $iban     = sport_theme_normalize_iban( $settings['iban'] ?? '' );

    if ( ! sport_theme_is_valid_iban( $iban ) ) {
        throw new Exception( 'IBAN non valido o incompleto. Un IBAN svizzero deve avere 21 caratteri, per esempio CH00 0000 0000 0000 0000 0.' );
    }

    $qr_bill = \Sprain\SwissQrBill\QrBill::create();
    $qr_bill->setCreditor(
        \Sprain\SwissQrBill\DataGroup\Element\StructuredAddress::createWithStreet(
            $settings['creditor_name'],
            $settings['street'],
            $settings['house_number'],
            $settings['postal_code'],
            $settings['city'],
            $settings['country']
        )
    );
    $qr_bill->setCreditorInformation(
        \Sprain\SwissQrBill\DataGroup\Element\CreditorInformation::create( $iban )
    );

    $debtor_name = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $first_child = $children[0] ?? null;
    if ( $debtor_name && $first_child ) {
        list( $street, $house_number ) = sport_theme_split_street_number( $first_child->indirizzo ?? '' );
        list( $postal_code, $city ) = sport_theme_split_postal_city( $first_child->cap_citta ?? '' );

        if ( $postal_code && $city ) {
            $qr_bill->setUltimateDebtor(
                \Sprain\SwissQrBill\DataGroup\Element\StructuredAddress::createWithStreet(
                    $debtor_name,
                    $street,
                    $house_number,
                    $postal_code,
                    $city,
                    'CH'
                )
            );
        }
    }

    $amount = max( 0, (float) $registration->importo_totale_chf );
    $qr_bill->setPaymentAmountInformation(
        \Sprain\SwissQrBill\DataGroup\Element\PaymentAmountInformation::create( 'CHF', $amount )
    );

    if ( sport_theme_is_qr_iban( $iban ) ) {
        $reference = \Sprain\SwissQrBill\Reference\QrPaymentReferenceGenerator::generate( null, (string) (int) $registration->id );
        $reference_type = \Sprain\SwissQrBill\DataGroup\Element\PaymentReference::TYPE_QR;
    } else {
        $reference = \Sprain\SwissQrBill\Reference\RfCreditorReferenceGenerator::generate( sport_theme_iscrizione_invoice_reference_seed( $registration ) );
        $reference_type = \Sprain\SwissQrBill\DataGroup\Element\PaymentReference::TYPE_SCOR;
    }

    $qr_bill->setPaymentReference(
        \Sprain\SwissQrBill\DataGroup\Element\PaymentReference::create( $reference_type, $reference )
    );

    $child_names = array();
    foreach ( $children as $child ) {
        $child_names[] = trim( (string) $child->nome . ' ' . (string) $child->cognome );
    }
    $discount_lines = sport_theme_iscrizione_discount_lines( $registration );
    $invoice_number = date_i18n( 'Y', current_time( 'timestamp' ) ) . '-' . str_pad( (string) (int) $registration->id, 4, '0', STR_PAD_LEFT );
    $base_amount = sport_theme_calculate_iscrizione_amount( $registration->tipo_iscrizione, count( $children ), false );
    $discount_amount = max( 0, $base_amount - (float) $registration->importo_totale_chf );

    $type_label = $registration->tipo_iscrizione === 'scuola_calcio' ? 'Scuola Calcio' : 'Allievi';
    $message = trim( sprintf(
        'AC Taverne %s %s - %s',
        $type_label,
        $registration->stagione_sportiva,
        implode( ', ', array_filter( $child_names ) )
    ) );

    $qr_bill->setAdditionalInformation(
        \Sprain\SwissQrBill\DataGroup\Element\AdditionalInformation::create( mb_substr( $message, 0, 140 ) )
    );

    return array( $qr_bill, $reference, $reference_type, $message, $child_names );
}

function sport_theme_handle_iscrizione_invoice() {
    $id = isset( $_GET['iscrizione_id'] ) ? absint( $_GET['iscrizione_id'] ) : 0;
    if ( ! $id ) {
        wp_die( 'Link fattura non valido.', 403 );
    }

    $invoice_data = sport_theme_load_iscrizione_for_invoice( $id );

    if ( ! $invoice_data ) {
        wp_die( 'Iscrizione non trovata.', 404 );
    }

    list( $registration, $children ) = $invoice_data;

    $valid_public_token = isset( $_GET['invoice_token'] ) && hash_equals(
        sport_theme_iscrizione_invoice_token( $registration ),
        sanitize_text_field( wp_unslash( $_GET['invoice_token'] ) )
    );
    $valid_secretariat_nonce = is_user_logged_in()
        && sport_theme_can_access_segreteria()
        && isset( $_GET['_wpnonce'] )
        && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'act_iscrizione_invoice_' . $id );

    if ( ! $valid_public_token && ! $valid_secretariat_nonce ) {
        wp_die( 'Link fattura non valido.', 403 );
    }

    try {
        list( $qr_bill, $reference, $reference_type, $message ) = sport_theme_create_iscrizione_qr_bill( $registration, $children );
        $display_options = new \Sprain\SwissQrBill\PaymentPart\Output\DisplayOptions();
        $display_options
            ->setPrintable( false )
            ->setDisplayTextDownArrows( false )
            ->setDisplayScissors( false )
            ->setPositionScissorsAtBottom( false );

        $payment_part = ( new \Sprain\SwissQrBill\PaymentPart\Output\HtmlOutput\HtmlOutput( $qr_bill, 'it' ) )
            ->setDisplayOptions( $display_options )
            ->getPaymentPart();
    } catch ( Exception $e ) {
        wp_die(
            '<h1>Fattura QR non generata</h1><p>' . esc_html( $e->getMessage() ) . '</p><p>IBAN inserito: ' . esc_html( sport_theme_iscrizione_billing_settings()['iban'] ?? '' ) . '</p>',
            'Fattura QR',
            array( 'response' => 400 )
        );
    }

    $responsabile = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $type_label = $registration->tipo_iscrizione === 'scuola_calcio' ? 'Scuola Calcio' : 'Allievi';
    $billing_settings = sport_theme_iscrizione_billing_settings();
    $company_profile = sport_theme_invoice_company_profile();
    $invoice_logo_url = $company_profile['logo_url'] ?? '';
    $child_names = array();
    foreach ( $children as $child ) {
        $child_names[] = trim( (string) $child->nome . ' ' . (string) $child->cognome );
    }
    $discount_lines = sport_theme_iscrizione_discount_lines( $registration );
    $invoice_number = date_i18n( 'Y', current_time( 'timestamp' ) ) . '-' . str_pad( (string) (int) $registration->id, 4, '0', STR_PAD_LEFT );
    $base_amount = sport_theme_calculate_iscrizione_amount( $registration->tipo_iscrizione, count( $children ), false );
    $discount_amount = max( 0, $base_amount - (float) $registration->importo_totale_chf );

    nocache_headers();
    header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
    ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html( 'Fattura iscrizione #' . $registration->id . ' - AC Taverne' ); ?></title>
    <style>
        @page { size: A4; margin: 0; }
        body { margin: 0; background: #f3f4f6; color: #111; font-family: Arial, Helvetica, sans-serif; }
        .invoice-wrap { width: min(100%, 210mm); margin: 0 auto; padding: 22px 14px 42px; box-sizing: border-box; }
        .invoice-sheet { width: 210mm; min-height: 297mm; max-width: 100%; margin: 0 auto; background: #fff; box-shadow: 0 16px 40px rgba(0,0,0,.12); box-sizing: border-box; display: flex; flex-direction: column; }
        .invoice-header { display: flex; justify-content: space-between; gap: 34px; padding: 34px 38px 24px; border-bottom: 1px solid #d7d7d7; }
        .invoice-header h1 { margin: 0 0 12px; font-size: 32px; line-height: 1; text-transform: uppercase; letter-spacing: 0; }
        .invoice-brand { display: flex; align-items: flex-start; gap: 16px; }
        .invoice-logo { display: block; width: 92px; height: auto; flex: 0 0 auto; }
        .invoice-company p, .invoice-meta p { margin: 0; font-size: 14px; line-height: 1.45; }
        .invoice-company strong { font-size: 16px; }
        .invoice-company a { color: #0645ad; text-decoration: underline; }
        .invoice-company .invoice-vat { font-weight: 700; }
        .invoice-meta { text-align: right; padding-top: 2px; }
        .invoice-body { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; padding: 28px 38px 34px; }
        .invoice-box h2 { margin: 0 0 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 0; }
        .invoice-box p { margin: 0; font-size: 15px; line-height: 1.55; }
        .invoice-box strong { display: block; margin-bottom: 6px; font-size: 16px; }
        .invoice-muted { color: #555; }
        .invoice-child-line { display: block; color: #111; }
        .invoice-discount { display: block; margin-top: 6px; font-weight: 700; color: #111; }
        .invoice-total { grid-column: 1 / -1; display: flex; justify-content: space-between; align-items: center; padding-top: 22px; border-top: 1px solid #ddd; }
        .invoice-total span { font-size: 14px; text-transform: uppercase; font-weight: 700; }
        .invoice-total strong { font-size: 30px; }
        .invoice-summary { grid-column: 1 / -1; margin-top: -12px; padding: 0 0 4px; }
        .invoice-summary h2 { margin: 0 0 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 0; }
        .invoice-summary-row { display: flex; justify-content: space-between; gap: 24px; max-width: 100%; margin: 4px 0; font-size: 14px; line-height: 1.35; }
        .invoice-summary-row strong { font-size: 14px; }
        .invoice-actions { display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 16px; }
        .invoice-actions button { border: 0; background: #e30613; color: #fff; padding: 12px 18px; font-weight: 800; text-transform: uppercase; cursor: pointer; }
        .invoice-qr { overflow-x: auto; margin-top: auto; padding: 0 0 20px; }
        .invoice-qr #qr-bill { margin: 0 auto; }
        @media print {
            body { background: #fff; }
            .invoice-wrap { width: 210mm; padding: 0; }
            .invoice-sheet { box-shadow: none; }
            .invoice-header { display: flex; }
            .invoice-meta { text-align: right; margin-top: 0; padding-top: 2px; }
            .invoice-body { display: grid; grid-template-columns: 1fr 1fr; }
            .invoice-actions { display: none; }
        }
        @media screen and (max-width: 760px) {
            .invoice-header, .invoice-body { display: block; padding-left: 22px; padding-right: 22px; }
            .invoice-meta { text-align: left; margin-top: 22px; }
            .invoice-box { margin-bottom: 22px; }
        }
    </style>
</head>
<body>
    <div class="invoice-wrap">
        <div class="invoice-actions">
            <button type="button" onclick="window.print()">Stampa / Salva PDF</button>
        </div>
        <article class="invoice-sheet">
            <header class="invoice-header">
                <div class="invoice-brand">
                    <?php if ( $invoice_logo_url ) : ?>
                        <img class="invoice-logo" src="<?php echo esc_url( $invoice_logo_url ); ?>" alt="AC Taverne">
                    <?php endif; ?>
                    <div class="invoice-company">
                        <p>
                            <strong><?php echo esc_html( $company_profile['name'] ?? 'AC Taverne' ); ?></strong><br>
                            <?php echo esc_html( $company_profile['society_number'] ?? '' ); ?><br><br>
                            <?php foreach ( (array) ( $company_profile['address_lines'] ?? array() ) as $company_line ) : ?>
                                <?php echo esc_html( $company_line ); ?><br>
                            <?php endforeach; ?>
                            <br>
                            <?php echo esc_html( $company_profile['phone'] ?? '' ); ?><br>
                            <?php if ( ! empty( $company_profile['email'] ) ) : ?>
                                <a href="mailto:<?php echo esc_attr( $company_profile['email'] ); ?>"><?php echo esc_html( $company_profile['email'] ); ?></a><br>
                            <?php endif; ?>
                            <?php echo esc_html( $company_profile['website'] ?? '' ); ?><br>
                            <span class="invoice-vat"><?php echo esc_html( $company_profile['vat'] ?? '' ); ?></span>
                        </p>
                    </div>
                </div>
                <div class="invoice-meta">
                    <h1>Fattura</h1>
                    <p><strong>Fattura n.: <?php echo esc_html( $invoice_number ); ?></strong></p>
                    <p>Pratica: #<?php echo esc_html( $registration->id ); ?></p>
                    <p>Data fattura: <?php echo esc_html( mysql2date( 'd.m.Y', current_time( 'mysql' ) ) ); ?></p>
                    <p>Stagione: <?php echo esc_html( $registration->stagione_sportiva ); ?></p>
                    <p>Riferimento: <?php echo esc_html( $reference ); ?></p>
                </div>
            </header>
            <section class="invoice-body">
                <div class="invoice-box">
                    <h2>Destinatario fattura</h2>
                    <p>
                        <strong><?php echo esc_html( $responsabile ?: '-' ); ?></strong>
                        <?php echo esc_html( $registration->responsabile_email ?: '' ); ?>
                    </p>
                </div>
                <div class="invoice-box">
                    <h2>Dettaglio iscrizione</h2>
                    <p>
                        <strong><?php echo esc_html( sport_theme_iscrizione_payment_description() ); ?></strong>
                        Categoria: <?php echo esc_html( $type_label ); ?> · Stagione <?php echo esc_html( $registration->stagione_sportiva ); ?><br>
                        <?php if ( array_filter( $child_names ) ) : ?>
                            <?php foreach ( array_values( array_filter( $child_names ) ) as $child_index => $child_name ) : ?>
                                <span class="invoice-child-line">Iscritto <?php echo esc_html( $child_index + 1 ); ?>: <?php echo esc_html( $child_name ); ?></span>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <span class="invoice-child-line">Iscritto 1: -</span>
                        <?php endif; ?>
                        <?php foreach ( $discount_lines as $discount_line ) : ?>
                            <span class="invoice-discount"><?php echo esc_html( $discount_line ); ?></span>
                        <?php endforeach; ?>
                    </p>
                </div>
                <div class="invoice-total">
                    <span>Totale fattura da pagare</span>
                    <strong>CHF <?php echo esc_html( number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) ); ?></strong>
                </div>
                <div class="invoice-summary">
                    <h2>Riepilogo importo</h2>
                    <div class="invoice-summary-row">
                        <span>Tassa sociale iscrizione</span>
                        <span>CHF <?php echo esc_html( number_format( $base_amount, 2, '.', "'" ) ); ?></span>
                    </div>
                    <?php if ( $discount_amount > 0 ) : ?>
                        <div class="invoice-summary-row">
                            <span>Riduzioni applicate</span>
                            <span>- CHF <?php echo esc_html( number_format( $discount_amount, 2, '.', "'" ) ); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="invoice-summary-row">
                        <strong>Totale fattura</strong>
                        <strong>CHF <?php echo esc_html( number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) ); ?></strong>
                    </div>
                </div>
            </section>
            <section class="invoice-qr">
                <?php echo $payment_part; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </section>
        </article>
    </div>
</body>
</html>
    <?php
    exit;
}
add_action( 'admin_post_act_iscrizione_invoice', 'sport_theme_handle_iscrizione_invoice' );
add_action( 'admin_post_nopriv_act_iscrizione_invoice', 'sport_theme_handle_iscrizione_invoice' );

function sport_theme_create_iscrizione_invoice_pdf( $registration, $children, $invoice_url = '' ) {
    try {
        list( $qr_bill, $reference, , $message, $child_names ) = sport_theme_create_iscrizione_qr_bill( $registration, $children );
    } catch ( Exception $e ) {
        return new WP_Error( 'invoice_qr_failed', $e->getMessage() );
    }

    $upload_dir = wp_upload_dir();
    if ( ! empty( $upload_dir['error'] ) ) {
        return new WP_Error( 'invoice_upload_dir', $upload_dir['error'] );
    }

    $dir = trailingslashit( $upload_dir['basedir'] ) . 'act-iscrizioni-pdf';
    if ( ! wp_mkdir_p( $dir ) ) {
        return new WP_Error( 'invoice_pdf_dir', 'Impossibile creare la cartella PDF fatture.' );
    }

    $pdf_path = trailingslashit( $dir ) . sanitize_file_name( 'fattura-iscrizione-' . (int) $registration->id . '.pdf' );
    $qr_png   = trailingslashit( $dir ) . sanitize_file_name( 'fattura-iscrizione-' . (int) $registration->id . '-qr.png' );
    $qr_jpg   = trailingslashit( $dir ) . sanitize_file_name( 'fattura-iscrizione-' . (int) $registration->id . '-qr.jpg' );

    try {
        $qr_bill->getQrCode( 'png' )->writeFile( $qr_png );
    } catch ( Exception $e ) {
        return new WP_Error( 'invoice_qr_image_failed', $e->getMessage() );
    }

    $image_path = '';
    if ( function_exists( 'imagecreatefrompng' ) && function_exists( 'imagejpeg' ) ) {
        $png_image = @imagecreatefrompng( $qr_png );
        if ( $png_image ) {
            $width  = imagesx( $png_image );
            $height = imagesy( $png_image );
            $canvas = imagecreatetruecolor( $width, $height );
            $white  = imagecolorallocate( $canvas, 255, 255, 255 );
            imagefilledrectangle( $canvas, 0, 0, $width, $height, $white );
            imagecopy( $canvas, $png_image, 0, 0, 0, 0, $width, $height );
            imagejpeg( $canvas, $qr_jpg, 92 );
            imagedestroy( $png_image );
            imagedestroy( $canvas );
            $image_path = file_exists( $qr_jpg ) ? $qr_jpg : '';
        }
    }

    $image_data = $image_path ? file_get_contents( $image_path ) : false;
    $image_size = $image_path ? getimagesize( $image_path ) : false;
    if ( ! $image_data || ! $image_size ) {
        return new WP_Error( 'invoice_qr_embed_failed', 'Impossibile preparare il QR per il PDF.' );
    }

    $company_profile = sport_theme_invoice_company_profile();
    $logo_path       = $company_profile['logo_path'] ?? '';
    $logo_data       = ( $logo_path && file_exists( $logo_path ) ) ? file_get_contents( $logo_path ) : false;
    $logo_size       = ( $logo_path && file_exists( $logo_path ) ) ? getimagesize( $logo_path ) : false;
    if ( $logo_size && ( $logo_size['mime'] ?? '' ) !== 'image/jpeg' ) {
        $logo_data = false;
        $logo_size = false;
    }

    $billing_settings = sport_theme_iscrizione_billing_settings();
    $responsabile     = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $type_label       = $registration->tipo_iscrizione === 'scuola_calcio' ? 'Scuola Calcio' : 'Allievi';
    $total_label      = 'CHF ' . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" );
    $discount_lines   = sport_theme_iscrizione_discount_lines( $registration );
    $invoice_number   = date_i18n( 'Y', current_time( 'timestamp' ) ) . '-' . str_pad( (string) (int) $registration->id, 4, '0', STR_PAD_LEFT );
    $base_amount      = sport_theme_calculate_iscrizione_amount( $registration->tipo_iscrizione, count( $children ), false );
    $discount_amount  = max( 0, $base_amount - (float) $registration->importo_totale_chf );
    $reference_label  = trim( chunk_split( (string) $reference, 4, ' ' ) );
    $first_child      = $children[0] ?? null;
    $debtor_lines     = array_filter(
        array(
            $responsabile,
            $first_child->indirizzo ?? '',
            $first_child->cap_citta ?? '',
        )
    );

    $text = static function ( $value, $x, $y, $size = 10, $font = 'F1' ) {
        return sprintf( "BT /%s %F Tf 1 0 0 1 %F %F Tm (%s) Tj ET\n", $font, $size, $x, $y, sport_theme_pdf_escape_text( $value ) );
    };
    $line = static function ( $x1, $y1, $x2, $y2 ) {
        return sprintf( "%F %F m %F %F l S\n", $x1, $y1, $x2, $y2 );
    };
    $wrapped_text = static function ( $value, $x, $y, $max_chars, $size = 9, $font = 'F1', $leading = 12 ) use ( $text ) {
        $out   = '';
        $lines = explode( "\n", wordwrap( trim( (string) $value ), $max_chars, "\n", true ) );
        foreach ( $lines as $index => $line_value ) {
            $out .= $text( $line_value, $x, $y - ( $index * $leading ), $size, $font );
        }
        return $out;
    };

    $stream = "0 0 0 rg 0 0 0 RG 1 w\n";
    if ( $logo_data && $logo_size ) {
        $stream .= "q 82 0 0 115 42 684 cm /ImLogo Do Q\n";
    }
    $company_x = ( $logo_data && $logo_size ) ? 142 : 42;
    $stream .= $text( $company_profile['name'] ?? 'AC Taverne', $company_x, 790, 13, 'F2' );
    $stream .= $text( $company_profile['society_number'] ?? '', $company_x, 774, 11 );
    $company_y = 748;
    foreach ( (array) ( $company_profile['address_lines'] ?? array() ) as $company_line ) {
        $stream .= $text( $company_line, $company_x, $company_y, 10 );
        $company_y -= 14;
    }
    $company_y -= 10;
    $stream .= $text( $company_profile['phone'] ?? '', $company_x, $company_y, 10 );
    $company_y -= 14;
    $stream .= $text( $company_profile['email'] ?? '', $company_x, $company_y, 10 );
    $company_y -= 14;
    $stream .= $text( $company_profile['website'] ?? '', $company_x, $company_y, 10 );
    $company_y -= 14;
    $stream .= $text( $company_profile['vat'] ?? '', $company_x, $company_y, 10, 'F2' );

    $stream .= $text( 'FATTURA', 430, 790, 26, 'F2' );
    $stream .= $text( 'Fattura n.: ' . $invoice_number, 430, 758, 10, 'F2' );
    $stream .= $text( 'Pratica: #' . (int) $registration->id, 430, 745, 9 );
    $stream .= $text( 'Data fattura: ' . mysql2date( 'd.m.Y', current_time( 'mysql' ) ), 430, 732, 9 );
    $stream .= $text( 'Stagione: ' . ( $registration->stagione_sportiva ?: sport_theme_current_sport_season() ), 430, 719, 9 );
    $stream .= $text( 'Riferimento: ' . $reference, 430, 706, 9 );
    $stream .= $line( 42, 642, 553, 642 );

    $stream .= $text( 'DESTINATARIO FATTURA', 42, 612, 10, 'F2' );
    $stream .= $text( $responsabile ?: '-', 42, 592, 12, 'F2' );
    $stream .= $text( $registration->responsabile_email ?: '-', 42, 574, 10 );

    $stream .= $text( 'DETTAGLIO ISCRIZIONE', 300, 612, 10, 'F2' );
    $stream .= $text( sport_theme_iscrizione_payment_description(), 300, 592, 12, 'F2' );
    $stream .= $text( 'Categoria: ' . $type_label . ' · Stagione ' . ( $registration->stagione_sportiva ?: sport_theme_current_sport_season() ), 300, 574, 10 );
    $child_y = 556;
    if ( $child_names ) {
        foreach ( array_values( array_filter( $child_names ) ) as $child_index => $child_name ) {
            $stream .= $wrapped_text( 'Iscritto ' . ( $child_index + 1 ) . ': ' . $child_name, 300, $child_y, 42, 10 );
            $child_y -= 14;
        }
    } else {
        $stream .= $text( 'Iscritto 1: -', 300, $child_y, 10 );
        $child_y -= 14;
    }
    $discount_y = $child_y - 10;
    foreach ( $discount_lines as $discount_line ) {
        $stream .= $text( $discount_line, 300, $discount_y, 9, 'F2' );
        $discount_y -= 12;
    }

    $stream .= $line( 42, 500, 553, 500 );
    $stream .= $text( 'TOTALE FATTURA DA PAGARE', 42, 468, 13, 'F2' );
    $stream .= $text( $total_label, 428, 466, 22, 'F2' );
    $stream .= $text( 'RIEPILOGO IMPORTO', 42, 430, 10, 'F2' );
    $stream .= $text( 'Tassa sociale iscrizione', 42, 414, 9 );
    $stream .= $text( 'CHF ' . number_format( $base_amount, 2, '.', "'" ), 428, 414, 9 );
    $summary_y = 400;
    if ( $discount_amount > 0 ) {
        $stream .= $text( 'Riduzioni applicate', 42, $summary_y, 9 );
        $stream .= $text( '- CHF ' . number_format( $discount_amount, 2, '.', "'" ), 428, $summary_y, 9 );
        $summary_y -= 14;
    }
    $stream .= $text( 'Totale fattura', 42, $summary_y, 9, 'F2' );
    $stream .= $text( $total_label, 428, $summary_y, 9, 'F2' );

    $stream .= $line( 42, 286, 553, 286 );
    $stream .= $line( 200, 286, 200, 40 );
    $stream .= $text( 'Ricevuta', 42, 260, 12, 'F2' );
    $stream .= $text( 'Conto / Pagabile a', 42, 238, 7.5, 'F2' );
    $stream .= $text( $billing_settings['iban'] ?? '', 42, 226, 7.5 );
    $stream .= $text( $billing_settings['creditor_name'] ?? 'AC Taverne', 42, 214, 7.5 );
    $stream .= $text( trim( ( $billing_settings['street'] ?? '' ) . ' ' . ( $billing_settings['house_number'] ?? '' ) ), 42, 202, 7.5 );
    $stream .= $text( trim( ( $billing_settings['postal_code'] ?? '' ) . ' ' . ( $billing_settings['city'] ?? '' ) ), 42, 190, 7.5 );
    $stream .= $text( 'Riferimento', 42, 170, 7.5, 'F2' );
    $stream .= $wrapped_text( $reference_label, 42, 158, 24, 7.5, 'F1', 9 );
    $stream .= $text( 'Pagabile da', 42, 134, 7.5, 'F2' );
    $receipt_debtor_y = 122;
    foreach ( $debtor_lines as $debtor_line ) {
        $stream .= $wrapped_text( $debtor_line, 42, $receipt_debtor_y, 24, 7.5, 'F1', 9 );
        $receipt_debtor_y -= 9;
    }
    $stream .= $text( 'Valuta', 42, 68, 7.5, 'F2' );
    $stream .= $text( 'Importo', 92, 68, 7.5, 'F2' );
    $stream .= $text( 'CHF', 42, 54, 9 );
    $stream .= $text( number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ), 92, 54, 9 );
    $stream .= $text( 'Punto di accettazione', 104, 88, 7.5 );

    $stream .= $text( 'Sezione pagamento', 218, 260, 14, 'F2' );
    $stream .= "q 130 0 0 130 218 92 cm /Im1 Do Q\n";
    $stream .= $text( 'Valuta', 218, 72, 8, 'F2' );
    $stream .= $text( 'Importo', 268, 72, 8, 'F2' );
    $stream .= $text( 'CHF', 218, 58, 10 );
    $stream .= $text( number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ), 268, 58, 10 );
    $stream .= $text( 'Conto / Pagabile a', 365, 238, 8, 'F2' );
    $stream .= $text( $billing_settings['iban'] ?? '', 365, 224, 9 );
    $stream .= $text( $billing_settings['creditor_name'] ?? 'AC Taverne', 365, 210, 9 );
    $stream .= $text( trim( ( $billing_settings['street'] ?? '' ) . ' ' . ( $billing_settings['house_number'] ?? '' ) ), 365, 196, 9 );
    $stream .= $text( trim( ( $billing_settings['postal_code'] ?? '' ) . ' ' . ( $billing_settings['city'] ?? '' ) ), 365, 182, 9 );
    $stream .= $text( 'Riferimento', 365, 160, 8, 'F2' );
    $stream .= $wrapped_text( $reference_label, 365, 146, 28, 9 );
    $stream .= $text( 'Informazioni supplementari', 365, 118, 8, 'F2' );
    $stream .= $wrapped_text( $message, 365, 104, 30, 8, 'F1', 10 );
    $stream .= $text( 'Pagabile da', 365, 66, 8, 'F2' );
    $payment_debtor_y = 54;
    foreach ( $debtor_lines as $debtor_line ) {
        $stream .= $wrapped_text( $debtor_line, 365, $payment_debtor_y, 30, 8, 'F1', 9 );
        $payment_debtor_y -= 9;
    }

    $image_object = "<< /Type /XObject /Subtype /Image /Width {$image_size[0]} /Height {$image_size[1]} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length " . strlen( $image_data ) . " >>\nstream\n{$image_data}\nendstream";
    $logo_object  = ( $logo_data && $logo_size ) ? "<< /Type /XObject /Subtype /Image /Width {$logo_size[0]} /Height {$logo_size[1]} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length " . strlen( $logo_data ) . " >>\nstream\n{$logo_data}\nendstream" : '';

    $page_object_id    = ( $logo_object ) ? 8 : 7;
    $content_object_id = ( $logo_object ) ? 7 : 6;
    $xobjects          = '/Im1 5 0 R';
    if ( $logo_object ) {
        $xobjects .= ' /ImLogo 6 0 R';
    }

    $objects = array(
        '<< /Type /Catalog /Pages 2 0 R >>',
        '<< /Type /Pages /Kids [' . $page_object_id . ' 0 R] /Count 1 >>',
        '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
        '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>',
        $image_object,
    );
    if ( $logo_object ) {
        $objects[] = $logo_object;
    }
    $objects[] = "<< /Length " . strlen( $stream ) . " >>\nstream\n{$stream}\nendstream";
    $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> /XObject << ' . $xobjects . ' >> >> /Contents ' . $content_object_id . ' 0 R >>';

    $pdf     = "%PDF-1.4\n";
    $offsets = array( 0 );
    foreach ( $objects as $index => $object ) {
        $offsets[] = strlen( $pdf );
        $object_id = $index + 1;
        $pdf      .= "{$object_id} 0 obj\n{$object}\nendobj\n";
    }

    $xref = strlen( $pdf );
    $pdf .= "xref\n0 " . ( count( $objects ) + 1 ) . "\n0000000000 65535 f \n";
    for ( $i = 1; $i <= count( $objects ); $i++ ) {
        $pdf .= sprintf( "%010d 00000 n \n", $offsets[ $i ] );
    }
    $pdf .= "trailer\n<< /Size " . ( count( $objects ) + 1 ) . " /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

    if ( file_put_contents( $pdf_path, $pdf ) === false ) {
        return new WP_Error( 'invoice_pdf_write_failed', 'Impossibile scrivere il PDF fattura.' );
    }

    return $pdf_path;
}

function sport_theme_handle_send_iscrizione_invoice() {
    sport_theme_iscrizioni_require_segreteria_access();
    check_admin_referer( 'act_send_iscrizione_invoice' );

    $id = isset( $_POST['iscrizione_id'] ) ? absint( $_POST['iscrizione_id'] ) : 0;
    $redirect = $id ? add_query_arg( 'edit_iscrizione', $id, home_url( '/area-segreteria/' ) ) : home_url( '/area-segreteria/' );
    $invoice_data = $id ? sport_theme_load_iscrizione_for_invoice( $id ) : null;

    if ( ! $invoice_data ) {
        wp_safe_redirect( add_query_arg( 'invoice_sent', '0', $redirect ) );
        exit;
    }

    list( $registration, $children ) = $invoice_data;

    if ( ! is_email( $registration->responsabile_email ) ) {
        wp_safe_redirect( add_query_arg( 'invoice_sent', '0', $redirect ) );
        exit;
    }

    try {
        list( , $reference, , $message, $child_names ) = sport_theme_create_iscrizione_qr_bill( $registration, $children );
    } catch ( Exception $e ) {
        wp_safe_redirect( add_query_arg( 'invoice_sent', '0', $redirect ) );
        exit;
    }

    $invoice_url = sport_theme_iscrizione_invoice_url( $registration, true );
    $invoice_pdf = sport_theme_create_iscrizione_invoice_pdf( $registration, $children, $invoice_url );
    $invoice_attachments = is_wp_error( $invoice_pdf ) ? array() : array( $invoice_pdf );
    $responsabile = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $subject = 'AC Taverne - Fattura iscrizione';
    $email_message = "Gentile {$responsabile},\n\n";
    $email_message .= "Grazie mille, la pratica d'iscrizione è stata verificata.\n";
    if ( $invoice_attachments ) {
        $email_message .= "In allegato trovate la fattura con cedola di versamento QR in formato PDF.\n";
        $email_message .= "Se l'allegato non fosse visibile, potete aprire la fattura anche da questo link:\n";
        $email_message .= $invoice_url . "\n\n";
    } else {
        $email_message .= "Potete aprire la fattura con cedola di versamento QR dal seguente link:\n";
        $email_message .= $invoice_url . "\n\n";
    }
    $email_message .= "Dettagli:\n";
    $email_message .= "Pratica: #{$registration->id}\n";
    $email_message .= "Stagione: {$registration->stagione_sportiva}\n";
    $email_message .= "Descrizione: " . sport_theme_iscrizione_payment_description() . "\n";
    $email_message .= "Iscrizione: {$message}\n";
    $email_message .= "Bambino/i: " . implode( ', ', array_filter( $child_names ) ) . "\n";
    $email_message .= "Importo: CHF " . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) . "\n";
    foreach ( sport_theme_iscrizione_discount_lines( $registration ) as $discount_line ) {
        $email_message .= $discount_line . "\n";
    }
    $email_message .= "Riferimento: {$reference}\n\n";
    $email_message .= "AC Taverne";

    $sent = wp_mail(
        $registration->responsabile_email,
        $subject,
        $email_message,
        array( 'Content-Type: text/plain; charset=UTF-8' ),
        $invoice_attachments
    );

    global $wpdb;
    $tables = sport_theme_iscrizioni_table_names();

    if ( $sent ) {
        $wpdb->update(
            $tables['registrations'],
            array(
                'metodo_pagamento' => 'fattura',
                'stato_pagamento'  => $registration->stato_pagamento === 'pagato' ? 'pagato' : 'in_attesa',
                'updated_at'       => current_time( 'mysql' ),
            ),
            array( 'id' => $id ),
            array( '%s', '%s', '%s' ),
            array( '%d' )
        );

        $wpdb->insert(
            $tables['logs'],
            array(
                'iscrizione_id' => $id,
                'azione'        => 'fattura_inviata',
                'messaggio'     => 'Fattura QR inviata a ' . $registration->responsabile_email,
                'created_by'    => get_current_user_id(),
                'created_at'    => current_time( 'mysql' ),
            ),
            array( '%d', '%s', '%s', '%d', '%s' )
        );

        $settings           = sport_theme_iscrizioni_email_settings();
        $invoice_recipients = sport_theme_parse_email_list( $settings['invoice_notice_recipients'] ?? '' );

        if ( $invoice_recipients ) {
            $internal_message = "La pratica #{$registration->id} è stata impostata con pagamento tramite fattura/cedola.\n\n";
            $internal_message .= "Responsabile: {$responsabile}\n";
            $internal_message .= "Email genitore: {$registration->responsabile_email}\n";
            $internal_message .= "Bambino/i: " . implode( ', ', array_filter( $child_names ) ) . "\n";
            $internal_message .= "Importo: CHF " . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) . "\n";
            foreach ( sport_theme_iscrizione_discount_lines( $registration ) as $discount_line ) {
                $internal_message .= $discount_line . "\n";
            }
            $internal_message .= "Descrizione fattura: " . sport_theme_iscrizione_payment_description() . "\n";
            $internal_message .= "Link fattura: {$invoice_url}";

            wp_mail(
                $invoice_recipients,
                'AC Taverne - Fattura iscrizione da gestire',
                $internal_message,
                array( 'Content-Type: text/plain; charset=UTF-8' ),
                $invoice_attachments
            );
        }
    }

    wp_safe_redirect( add_query_arg( 'invoice_sent', $sent ? '1' : '0', $redirect ) );
    exit;
}
add_action( 'admin_post_act_send_iscrizione_invoice', 'sport_theme_handle_send_iscrizione_invoice' );

function sport_theme_stripe_settings() {
    $settings = array(
        'publishable_key' => defined( 'ACT_TAVERNE_STRIPE_PUBLISHABLE_KEY' ) ? ACT_TAVERNE_STRIPE_PUBLISHABLE_KEY : '',
        'secret_key'      => defined( 'ACT_TAVERNE_STRIPE_SECRET_KEY' ) ? ACT_TAVERNE_STRIPE_SECRET_KEY : '',
        'webhook_secret'  => defined( 'ACT_TAVERNE_STRIPE_WEBHOOK_SECRET' ) ? ACT_TAVERNE_STRIPE_WEBHOOK_SECRET : '',
    );

    return apply_filters( 'sport_theme_stripe_settings', $settings );
}

function sport_theme_stripe_secret_key() {
    $settings = sport_theme_stripe_settings();
    return trim( (string) ( $settings['secret_key'] ?? '' ) );
}

function sport_theme_stripe_is_configured() {
    return class_exists( '\Stripe\StripeClient' ) && sport_theme_stripe_secret_key() !== '';
}

function sport_theme_stripe_child_names( $children ) {
    $names = array();
    foreach ( $children as $child ) {
        $names[] = trim( (string) $child->nome . ' ' . (string) $child->cognome );
    }

    return array_filter( $names );
}

function sport_theme_stripe_payment_return_url( $registration, $status ) {
    return add_query_arg(
        array(
            'action'        => 'act_stripe_payment_return',
            'iscrizione_id' => (int) $registration->id,
            'status'        => $status,
            'payment_token' => sport_theme_iscrizione_invoice_token( $registration ),
        ),
        admin_url( 'admin-post.php' )
    );
}

function sport_theme_create_stripe_checkout_session( $registration, $children ) {
    if ( ! sport_theme_stripe_is_configured() ) {
        throw new Exception( 'Stripe non configurato.' );
    }

    $amount = (float) $registration->importo_totale_chf;
    if ( $amount <= 0 ) {
        throw new Exception( 'Importo iscrizione non valido.' );
    }

    $stripe = new \Stripe\StripeClient( sport_theme_stripe_secret_key() );
    $child_names = sport_theme_stripe_child_names( $children );
    $type_label = $registration->tipo_iscrizione === 'scuola_calcio' ? 'Scuola Calcio' : 'Allievi';
    $description = trim( sprintf(
        '%s - %s %s - %s',
        sport_theme_iscrizione_payment_description(),
        $type_label,
        $registration->stagione_sportiva,
        implode( ', ', $child_names )
    ) );

    return $stripe->checkout->sessions->create(
        array(
            'mode'                 => 'payment',
            'customer_creation'    => 'always',
            'customer_email'       => $registration->responsabile_email ?: null,
            'client_reference_id'  => (string) $registration->id,
            'success_url'          => sport_theme_stripe_payment_return_url( $registration, 'success' ) . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => sport_theme_stripe_payment_return_url( $registration, 'cancel' ),
            'invoice_creation'     => array(
                'enabled'      => true,
                'invoice_data' => array(
                    'description' => sport_theme_iscrizione_payment_description(),
                    'footer'      => 'AC Taverne - ' . sport_theme_iscrizione_payment_description(),
                    'metadata'    => array(
                        'iscrizione_id' => (string) $registration->id,
                        'uuid'          => (string) $registration->uuid,
                    ),
                ),
            ),
            'metadata'             => array(
                'iscrizione_id' => (string) $registration->id,
                'uuid'          => (string) $registration->uuid,
            ),
            'payment_intent_data'  => array(
                'metadata' => array(
                    'iscrizione_id' => (string) $registration->id,
                    'uuid'          => (string) $registration->uuid,
                ),
            ),
            'line_items'           => array(
                array(
                    'quantity'   => 1,
                    'price_data' => array(
                        'currency'     => 'chf',
                        'unit_amount'  => (int) round( $amount * 100 ),
                        'product_data' => array(
                            'name'        => sport_theme_iscrizione_payment_description(),
                            'description' => mb_substr( $description, 0, 255 ),
                        ),
                    ),
                ),
            ),
        )
    );
}

function sport_theme_handle_send_stripe_payment() {
    sport_theme_iscrizioni_require_segreteria_access();
    check_admin_referer( 'act_send_stripe_payment' );

    $id = isset( $_POST['iscrizione_id'] ) ? absint( $_POST['iscrizione_id'] ) : 0;
    $redirect = $id ? add_query_arg( 'edit_iscrizione', $id, home_url( '/area-segreteria/' ) ) : home_url( '/area-segreteria/' );
    $invoice_data = $id ? sport_theme_load_iscrizione_for_invoice( $id ) : null;

    if ( ! $invoice_data ) {
        wp_safe_redirect( add_query_arg( 'stripe_sent', '0', $redirect ) );
        exit;
    }

    list( $registration, $children ) = $invoice_data;
    if ( ! is_email( $registration->responsabile_email ) ) {
        wp_safe_redirect( add_query_arg( 'stripe_sent', '0', $redirect ) );
        exit;
    }

    try {
        $session = sport_theme_create_stripe_checkout_session( $registration, $children );
    } catch ( Exception $e ) {
        wp_safe_redirect( add_query_arg( 'stripe_sent', '0', $redirect ) );
        exit;
    }

    $payment_url = (string) $session->url;
    $child_names = sport_theme_stripe_child_names( $children );
    $responsabile = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $subject = 'AC Taverne - Pagamento iscrizione';
    $email_message = "Gentile {$responsabile},\n\n";
    $email_message .= "Complimenti, la tua/e iscrizione/i è/sono stata/e confermata/e.\n";
    $email_message .= "Ti preghiamo di passare al prossimo step per effettuare il pagamento della tassa sociale con carta.\n\n";
    $email_message .= "Potete effettuare il pagamento online dal seguente link:\n";
    $email_message .= $payment_url . "\n\n";
    $email_message .= "Dettagli:\n";
    $email_message .= "Pratica: #{$registration->id}\n";
    $email_message .= "Stagione: {$registration->stagione_sportiva}\n";
    $email_message .= "Descrizione: " . sport_theme_iscrizione_payment_description() . "\n";
    $email_message .= "Bambino/i: " . implode( ', ', $child_names ) . "\n";
    $email_message .= "Importo: CHF " . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) . "\n";
    foreach ( sport_theme_iscrizione_discount_lines( $registration ) as $discount_line ) {
        $email_message .= $discount_line . "\n";
    }
    $email_message .= "\n";
    $email_message .= "AC Taverne";

    $sent = wp_mail(
        $registration->responsabile_email,
        $subject,
        $email_message,
        array( 'Content-Type: text/plain; charset=UTF-8' )
    );

    global $wpdb;
    $tables = sport_theme_iscrizioni_table_names();

    if ( $sent ) {
        $wpdb->update(
            $tables['registrations'],
            array(
                'metodo_pagamento'           => 'stripe',
                'stato_pagamento'            => $registration->stato_pagamento === 'pagato' ? 'pagato' : 'in_attesa',
                'stripe_checkout_session_id' => (string) $session->id,
                'stripe_payment_url'         => esc_url_raw( $payment_url ),
                'stripe_payment_sent_at'     => current_time( 'mysql' ),
                'updated_at'                 => current_time( 'mysql' ),
            ),
            array( 'id' => $id ),
            array( '%s', '%s', '%s', '%s', '%s', '%s' ),
            array( '%d' )
        );

        $wpdb->insert(
            $tables['logs'],
            array(
                'iscrizione_id' => $id,
                'azione'        => 'stripe_inviato',
                'messaggio'     => 'Link pagamento Stripe inviato a ' . $registration->responsabile_email,
                'created_by'    => get_current_user_id(),
                'created_at'    => current_time( 'mysql' ),
            ),
            array( '%d', '%s', '%s', '%d', '%s' )
        );
    }

    wp_safe_redirect( add_query_arg( 'stripe_sent', $sent ? '1' : '0', $redirect ) );
    exit;
}
add_action( 'admin_post_act_send_stripe_payment', 'sport_theme_handle_send_stripe_payment' );

function sport_theme_stripe_value( $object, $key ) {
    if ( is_array( $object ) ) {
        return $object[ $key ] ?? null;
    }

    if ( is_object( $object ) ) {
        return $object->{$key} ?? null;
    }

    return null;
}

function sport_theme_stripe_metadata_array( $metadata ) {
    if ( is_array( $metadata ) ) {
        return $metadata;
    }

    if ( is_object( $metadata ) && method_exists( $metadata, 'toArray' ) ) {
        return $metadata->toArray();
    }

    if ( is_object( $metadata ) ) {
        return get_object_vars( $metadata );
    }

    return array();
}

function sport_theme_get_stripe_invoice_details_from_session( $session ) {
    $details = array(
        'customer_id'  => '',
        'invoice_id'   => '',
        'invoice_url'  => '',
        'invoice_pdf'  => '',
    );

    $session_id = (string) sport_theme_stripe_value( $session, 'id' );
    $customer   = sport_theme_stripe_value( $session, 'customer' );
    $invoice    = sport_theme_stripe_value( $session, 'invoice' );

    if ( is_string( $customer ) ) {
        $details['customer_id'] = $customer;
    } elseif ( $customer ) {
        $details['customer_id'] = (string) sport_theme_stripe_value( $customer, 'id' );
    }

    if ( is_string( $invoice ) ) {
        $details['invoice_id'] = $invoice;
    } elseif ( $invoice ) {
        $details['invoice_id']  = (string) sport_theme_stripe_value( $invoice, 'id' );
        $details['invoice_url'] = (string) sport_theme_stripe_value( $invoice, 'hosted_invoice_url' );
        $details['invoice_pdf'] = (string) sport_theme_stripe_value( $invoice, 'invoice_pdf' );
    }

    if ( ( ! $details['invoice_url'] || ! $details['invoice_pdf'] ) && $session_id && sport_theme_stripe_is_configured() ) {
        try {
            $stripe          = new \Stripe\StripeClient( sport_theme_stripe_secret_key() );
            $expanded_session = $stripe->checkout->sessions->retrieve( $session_id, array( 'expand' => array( 'invoice' ) ) );
            $expanded_invoice = sport_theme_stripe_value( $expanded_session, 'invoice' );
            $expanded_customer = sport_theme_stripe_value( $expanded_session, 'customer' );

            if ( is_string( $expanded_customer ) ) {
                $details['customer_id'] = $expanded_customer;
            } elseif ( $expanded_customer ) {
                $details['customer_id'] = (string) sport_theme_stripe_value( $expanded_customer, 'id' );
            }

            if ( is_string( $expanded_invoice ) ) {
                $details['invoice_id'] = $expanded_invoice;
            } elseif ( $expanded_invoice ) {
                $details['invoice_id']  = (string) sport_theme_stripe_value( $expanded_invoice, 'id' );
                $details['invoice_url'] = (string) sport_theme_stripe_value( $expanded_invoice, 'hosted_invoice_url' );
                $details['invoice_pdf'] = (string) sport_theme_stripe_value( $expanded_invoice, 'invoice_pdf' );
            }
        } catch ( Exception $e ) {
            // Il pagamento resta valido anche se Stripe non restituisce subito la fattura.
        }
    }

    return $details;
}

function sport_theme_send_stripe_paid_notifications( $registration_id, $session_id = '' ) {
    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $registration = $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$tables['registrations']} WHERE id = %d", $registration_id )
    );

    if ( ! $registration ) {
        return false;
    }

    $children = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM {$tables['children']} WHERE iscrizione_id = %d ORDER BY child_index ASC", $registration_id )
    );
    $child_names = array();
    foreach ( $children as $child ) {
        $child_names[] = trim( (string) $child->nome . ' ' . (string) $child->cognome );
    }

    $responsabile = trim( (string) $registration->responsabile_nome . ' ' . (string) $registration->responsabile_cognome );
    $headers      = array( 'Content-Type: text/plain; charset=UTF-8' );

    if ( is_email( $registration->responsabile_email ) ) {
        $parent_message = "Gentile {$responsabile},\n\n";
        $parent_message .= "Il tuo pagamento è andato a buon fine, riceverai la fattura come giustificativo nei prossimi giorni.\n\n";
        $parent_message .= "Dettagli:\n";
        $parent_message .= "Pratica: #{$registration->id}\n";
        $parent_message .= "Descrizione: " . sport_theme_iscrizione_payment_description() . "\n";
        $parent_message .= "Importo: CHF " . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) . "\n\n";
        foreach ( sport_theme_iscrizione_discount_lines( $registration ) as $discount_line ) {
            $parent_message .= $discount_line . "\n";
        }
        $parent_message .= "\n";
        if ( ! empty( $registration->stripe_invoice_pdf ) ) {
            $parent_message .= "Fattura Stripe PDF: {$registration->stripe_invoice_pdf}\n\n";
        } elseif ( ! empty( $registration->stripe_invoice_url ) ) {
            $parent_message .= "Fattura Stripe: {$registration->stripe_invoice_url}\n\n";
        }
        $parent_message .= "AC Taverne";

        wp_mail( $registration->responsabile_email, 'AC Taverne - Pagamento ricevuto', $parent_message, $headers );
    }

    $settings           = sport_theme_iscrizioni_email_settings();
    $payment_recipients = sport_theme_parse_email_list( $settings['payment_card_recipients'] ?? '' );

    if ( $payment_recipients ) {
        $internal_message = "Pagamento con carta confermato da Stripe.\n\n";
        $internal_message .= "Pratica: #{$registration->id}\n";
        $internal_message .= "Responsabile: {$responsabile}\n";
        $internal_message .= "Email genitore: {$registration->responsabile_email}\n";
        $internal_message .= "Bambino/i: " . implode( ', ', array_filter( $child_names ) ) . "\n";
        $internal_message .= "Importo: CHF " . number_format( (float) $registration->importo_totale_chf, 2, '.', "'" ) . "\n";
        foreach ( sport_theme_iscrizione_discount_lines( $registration ) as $discount_line ) {
            $internal_message .= $discount_line . "\n";
        }
        $internal_message .= "Descrizione: " . sport_theme_iscrizione_payment_description() . "\n";
        if ( ! empty( $registration->stripe_invoice_id ) ) {
            $internal_message .= "Fattura Stripe: {$registration->stripe_invoice_id}\n";
        }
        if ( ! empty( $registration->stripe_invoice_pdf ) ) {
            $internal_message .= "PDF fattura Stripe: {$registration->stripe_invoice_pdf}\n";
        } elseif ( ! empty( $registration->stripe_invoice_url ) ) {
            $internal_message .= "Link fattura Stripe: {$registration->stripe_invoice_url}\n";
        }
        $internal_message .= "Sessione Stripe: {$session_id}";

        wp_mail( $payment_recipients, 'AC Taverne - Pagamento carta ricevuto', $internal_message, $headers );
    }

    return true;
}

function sport_theme_mark_stripe_session_paid( $session ) {
    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $session_id = (string) sport_theme_stripe_value( $session, 'id' );
    $payment_intent = sport_theme_stripe_value( $session, 'payment_intent' );
    $payment_intent = is_string( $payment_intent ) ? $payment_intent : (string) sport_theme_stripe_value( $payment_intent, 'id' );
    $metadata = sport_theme_stripe_metadata_array( sport_theme_stripe_value( $session, 'metadata' ) );
    $registration_id = isset( $metadata['iscrizione_id'] ) ? absint( $metadata['iscrizione_id'] ) : 0;

    if ( ! $registration_id && $session_id ) {
        $registration_id = (int) $wpdb->get_var(
            $wpdb->prepare( "SELECT id FROM {$tables['registrations']} WHERE stripe_checkout_session_id = %s", $session_id )
        );
    }

    if ( ! $registration_id ) {
        return false;
    }

    $previous_payment_status = $wpdb->get_var(
        $wpdb->prepare( "SELECT stato_pagamento FROM {$tables['registrations']} WHERE id = %d", $registration_id )
    );
    $stripe_invoice_details = sport_theme_get_stripe_invoice_details_from_session( $session );

    $wpdb->update(
        $tables['registrations'],
        array(
            'metodo_pagamento'           => 'stripe',
            'stato_pagamento'            => 'pagato',
            'stripe_customer_id'          => $stripe_invoice_details['customer_id'],
            'stripe_checkout_session_id' => $session_id,
            'stripe_payment_intent_id'   => $payment_intent,
            'stripe_invoice_id'           => $stripe_invoice_details['invoice_id'],
            'stripe_invoice_url'          => esc_url_raw( $stripe_invoice_details['invoice_url'] ),
            'stripe_invoice_pdf'          => esc_url_raw( $stripe_invoice_details['invoice_pdf'] ),
            'stripe_paid_at'             => current_time( 'mysql' ),
            'updated_at'                 => current_time( 'mysql' ),
        ),
        array( 'id' => $registration_id ),
        array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
        array( '%d' )
    );

    $wpdb->insert(
        $tables['logs'],
        array(
            'iscrizione_id' => $registration_id,
            'azione'        => 'stripe_pagato',
            'messaggio'     => 'Pagamento Stripe confermato. Sessione: ' . $session_id . ( $stripe_invoice_details['invoice_id'] ? '. Fattura Stripe: ' . $stripe_invoice_details['invoice_id'] : '' ),
            'created_by'    => 0,
            'created_at'    => current_time( 'mysql' ),
        ),
        array( '%d', '%s', '%s', '%d', '%s' )
    );

    if ( $previous_payment_status !== 'pagato' ) {
        sport_theme_send_stripe_paid_notifications( $registration_id, $session_id );
    }

    return true;
}

function sport_theme_handle_stripe_payment_return() {
    $id = isset( $_GET['iscrizione_id'] ) ? absint( $_GET['iscrizione_id'] ) : 0;
    $status = isset( $_GET['status'] ) ? sanitize_key( wp_unslash( $_GET['status'] ) ) : '';
    $invoice_data = $id ? sport_theme_load_iscrizione_for_invoice( $id ) : null;

    if ( ! $invoice_data ) {
        wp_die( 'Pratica non trovata.', 404 );
    }

    list( $registration ) = $invoice_data;
    $token = isset( $_GET['payment_token'] ) ? sanitize_text_field( wp_unslash( $_GET['payment_token'] ) ) : '';
    if ( ! hash_equals( sport_theme_iscrizione_invoice_token( $registration ), $token ) ) {
        wp_die( 'Link pagamento non valido.', 403 );
    }

    if ( $status === 'success' && isset( $_GET['session_id'] ) && sport_theme_stripe_is_configured() ) {
        try {
            $stripe = new \Stripe\StripeClient( sport_theme_stripe_secret_key() );
            $session = $stripe->checkout->sessions->retrieve( sanitize_text_field( wp_unslash( $_GET['session_id'] ) ) );
            if ( $session && $session->payment_status === 'paid' ) {
                sport_theme_mark_stripe_session_paid( $session );
            }
        } catch ( Exception $e ) {
            // Il webhook resta la fonte principale; questa verifica è solo un fallback.
        }
    }

    nocache_headers();
    header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
    ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html( $status === 'success' ? 'Pagamento ricevuto' : 'Pagamento annullato' ); ?></title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #050505; color: #fff; font-family: Arial, Helvetica, sans-serif; }
        main { width: min(100% - 32px, 680px); border: 2px solid #e30613; padding: 34px; background: #0b0b0b; }
        h1 { margin: 0 0 14px; color: #e30613; font-size: 34px; line-height: 1; text-transform: uppercase; }
        p { margin: 0; font-size: 18px; line-height: 1.55; }
    </style>
</head>
<body>
    <main>
        <?php if ( $status === 'success' ) : ?>
            <h1>Pagamento ricevuto</h1>
            <p>Grazie. Il pagamento è stato registrato o verrà confermato automaticamente appena Stripe invierà la conferma.</p>
        <?php else : ?>
            <h1>Pagamento annullato</h1>
            <p>Il pagamento non è stato completato. Potete riaprire il link ricevuto via email per riprovare.</p>
        <?php endif; ?>
    </main>
</body>
</html>
    <?php
    exit;
}
add_action( 'admin_post_act_stripe_payment_return', 'sport_theme_handle_stripe_payment_return' );
add_action( 'admin_post_nopriv_act_stripe_payment_return', 'sport_theme_handle_stripe_payment_return' );

function sport_theme_register_stripe_webhook_route() {
    register_rest_route(
        'act/v1',
        '/stripe-webhook',
        array(
            'methods'             => 'POST',
            'callback'            => 'sport_theme_handle_stripe_webhook',
            'permission_callback' => '__return_true',
        )
    );
}
add_action( 'rest_api_init', 'sport_theme_register_stripe_webhook_route' );

function sport_theme_handle_stripe_webhook( WP_REST_Request $request ) {
    $settings = sport_theme_stripe_settings();
    $webhook_secret = trim( (string) ( $settings['webhook_secret'] ?? '' ) );

    if ( ! $webhook_secret || ! class_exists( '\Stripe\Webhook' ) ) {
        return new WP_REST_Response( array( 'message' => 'Webhook Stripe non configurato.' ), 400 );
    }

    $payload = $request->get_body();
    $signature = $request->get_header( 'stripe-signature' );

    try {
        $event = \Stripe\Webhook::constructEvent( $payload, $signature, $webhook_secret );
    } catch ( Exception $e ) {
        return new WP_REST_Response( array( 'message' => 'Firma webhook non valida.' ), 400 );
    }

    if ( $event->type === 'checkout.session.completed' ) {
        sport_theme_mark_stripe_session_paid( $event->data->object );
    }

    return new WP_REST_Response( array( 'received' => true ), 200 );
}

function sport_theme_handle_delete_iscrizione() {
    sport_theme_iscrizioni_require_segreteria_access();
    check_admin_referer( 'act_delete_iscrizione' );

    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $id     = isset( $_POST['iscrizione_id'] ) ? absint( $_POST['iscrizione_id'] ) : 0;

    if ( ! $id ) {
        wp_safe_redirect( wp_get_referer() ?: home_url( '/area-segreteria/' ) );
        exit;
    }

    $documents = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM {$tables['documents']} WHERE iscrizione_id = %d", $id )
    );

    foreach ( $documents as $document ) {
        if ( $document->storage === 'media' && $document->attachment_id ) {
            wp_delete_attachment( (int) $document->attachment_id, true );
            continue;
        }

        if ( $document->private_path ) {
            $path = realpath( $document->private_path );
            $uploads = wp_upload_dir();
            $private_root = realpath( trailingslashit( $uploads['basedir'] ) . 'ac-taverne-private' );
            if ( $path && $private_root && strpos( $path, $private_root ) === 0 && file_exists( $path ) ) {
                wp_delete_file( $path );
            }
        }
    }

    $wpdb->delete( $tables['documents'], array( 'iscrizione_id' => $id ), array( '%d' ) );
    $wpdb->delete( $tables['children'], array( 'iscrizione_id' => $id ), array( '%d' ) );
    $wpdb->delete( $tables['logs'], array( 'iscrizione_id' => $id ), array( '%d' ) );
    $wpdb->delete( $tables['registrations'], array( 'id' => $id ), array( '%d' ) );

    wp_safe_redirect( wp_get_referer() ?: home_url( '/area-segreteria/' ) );
    exit;
}
add_action( 'admin_post_act_delete_iscrizione', 'sport_theme_handle_delete_iscrizione' );

function sport_theme_delete_private_iscrizione_file( $path ) {
    if ( ! $path ) {
        return;
    }

    $real_path = realpath( $path );
    $uploads = wp_upload_dir();
    $private_root = realpath( trailingslashit( $uploads['basedir'] ) . 'ac-taverne-private' );

    if ( $real_path && $private_root && strpos( $real_path, $private_root ) === 0 && file_exists( $real_path ) ) {
        wp_delete_file( $real_path );
    }
}

function sport_theme_replace_iscrizione_document_from_upload( $document_id, $field_name, $registration ) {
    global $wpdb;

    $file = sport_theme_get_uploaded_file( $field_name );
    if ( ! $file ) {
        return null;
    }

    $tables = sport_theme_iscrizioni_table_names();
    $document = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$tables['documents']} WHERE id = %d AND iscrizione_id = %d",
            $document_id,
            (int) $registration->id
        )
    );

    if ( ! $document ) {
        return new WP_Error( 'document_not_found', 'Documento non trovato.' );
    }

    if ( $document->storage === 'media' ) {
        $new_file = sport_theme_store_media_iscrizione_file( $field_name );
        if ( is_wp_error( $new_file ) ) {
            return $new_file;
        }

        if ( $document->attachment_id ) {
            wp_delete_attachment( (int) $document->attachment_id, true );
        }

        $wpdb->update(
            $tables['documents'],
            array(
                'attachment_id' => (int) $new_file['attachment_id'],
                'private_path'  => null,
                'private_url'   => esc_url_raw( $new_file['attachment_url'] ?? '' ),
                'original_name' => $new_file['original_name'] ?? '',
                'mime_type'     => $new_file['mime_type'] ?? '',
                'file_size'     => (int) ( $new_file['file_size'] ?? 0 ),
                'created_at'    => current_time( 'mysql' ),
            ),
            array( 'id' => (int) $document->id ),
            array( '%d', '%s', '%s', '%s', '%s', '%d', '%s' ),
            array( '%d' )
        );

        return true;
    }

    $prefix = trim(
        'bambino-' . ( $document->child_index ? (int) $document->child_index : 'pratica' ) . '-' . sanitize_key( $document->ruolo_file ),
        '-'
    );
    $new_file = sport_theme_store_private_iscrizione_file( $field_name, $registration->uuid, $prefix );
    if ( is_wp_error( $new_file ) ) {
        return $new_file;
    }

    sport_theme_delete_private_iscrizione_file( $document->private_path );

    $wpdb->update(
        $tables['documents'],
        array(
            'attachment_id' => null,
            'private_path'  => $new_file['path'] ?? null,
            'private_url'   => null,
            'original_name' => $new_file['original_name'] ?? '',
            'mime_type'     => $new_file['mime_type'] ?? '',
            'file_size'     => (int) ( $new_file['file_size'] ?? 0 ),
            'created_at'    => current_time( 'mysql' ),
        ),
        array( 'id' => (int) $document->id ),
        array( '%d', '%s', '%s', '%s', '%s', '%d', '%s' ),
        array( '%d' )
    );

    return true;
}

function sport_theme_handle_update_iscrizione_detail() {
    sport_theme_iscrizioni_require_segreteria_access();
    check_admin_referer( 'act_update_iscrizione_detail' );

    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $id     = isset( $_POST['iscrizione_id'] ) ? absint( $_POST['iscrizione_id'] ) : 0;

    if ( ! $id ) {
        wp_safe_redirect( home_url( '/area-segreteria/' ) );
        exit;
    }

    $registration = $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$tables['registrations']} WHERE id = %d", $id )
    );

    if ( ! $registration ) {
        wp_safe_redirect( home_url( '/area-segreteria/' ) );
        exit;
    }

    $change_messages = array();
    $status_labels = sport_theme_iscrizioni_status_labels();
    $category_labels = sport_theme_iscrizioni_category_options();
    $tipo_iscrizione = sport_theme_sanitize_iscrizione_key( $_POST['tipo_iscrizione'] ?? $registration->tipo_iscrizione, array( 'allievi', 'scuola_calcio' ), 'allievi' );
    $stato = sport_theme_sanitize_iscrizione_key( $_POST['stato'] ?? $registration->stato, sport_theme_iscrizioni_allowed_statuses(), 'nuova' );
    $metodo_pagamento = sport_theme_sanitize_iscrizione_key( $_POST['metodo_pagamento'] ?? $registration->metodo_pagamento, array( 'stripe', 'fattura' ), 'fattura' );
    $stato_pagamento = sport_theme_sanitize_iscrizione_key( $_POST['stato_pagamento'] ?? $registration->stato_pagamento, array( 'non_pagato', 'in_attesa', 'pagato', 'annullato' ), 'non_pagato' );
    $responsabilita = sport_theme_sanitize_iscrizione_key( $_POST['responsabilita_genitoriale'] ?? $registration->responsabilita_genitoriale, array( 'padre', 'madre', 'tutore_legale' ), 'padre' );
    $riduzione_fratelli = ! empty( $_POST['riduzione_fratelli'] ) && $tipo_iscrizione === 'allievi' ? 1 : 0;
    $stagione_sportiva = sanitize_text_field( wp_unslash( $_POST['stagione_sportiva'] ?? $registration->stagione_sportiva ) );
    if ( ! preg_match( '/^\d{4}\/\d{4}$/', $stagione_sportiva ) ) {
        $stagione_sportiva = sport_theme_current_sport_season();
    }

    if ( $registration->stato !== $stato ) {
        $change_messages[] = 'Stato: ' . sport_theme_iscrizione_label_value( $registration->stato, $status_labels ) . ' -> ' . sport_theme_iscrizione_label_value( $stato, $status_labels );
    }
    if ( $registration->metodo_pagamento !== $metodo_pagamento ) {
        $change_messages[] = 'Metodo pagamento: ' . ( $registration->metodo_pagamento ?: 'Da definire' ) . ' -> ' . $metodo_pagamento;
    }
    if ( $registration->stato_pagamento !== $stato_pagamento ) {
        $change_messages[] = 'Stato pagamento: ' . ( $registration->stato_pagamento ?: 'non_pagato' ) . ' -> ' . $stato_pagamento;
    }
    if ( (string) $registration->responsabile_email !== sport_theme_clean_email_field( 'responsabile_email' ) ) {
        $change_messages[] = 'Email responsabile aggiornata.';
    }
    if ( trim( (string) $registration->note_interne ) !== trim( sport_theme_clean_textarea_field( 'note_interne' ) ) ) {
        $change_messages[] = 'Note interne aggiornate.';
    }

    $wpdb->update(
        $tables['registrations'],
        array(
            'tipo_iscrizione'            => $tipo_iscrizione,
            'stagione_sportiva'          => $stagione_sportiva,
            'stato'                      => $stato,
            'metodo_pagamento'           => $metodo_pagamento,
            'stato_pagamento'            => $stato_pagamento,
            'riduzione_fratelli'         => $riduzione_fratelli,
            'responsabilita_genitoriale' => $responsabilita,
            'responsabile_nome'          => sport_theme_clean_text_field( 'responsabile_nome' ),
            'responsabile_cognome'       => sport_theme_clean_text_field( 'responsabile_cognome' ),
            'responsabile_telefono'      => sport_theme_clean_text_field( 'responsabile_telefono' ),
            'responsabile_email'         => sport_theme_clean_email_field( 'responsabile_email' ),
            'note_interne'               => sport_theme_clean_textarea_field( 'note_interne' ),
            'updated_at'                 => current_time( 'mysql' ),
        ),
        array( 'id' => $id ),
        array( '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
        array( '%d' )
    );

    $posted_children = isset( $_POST['children'] ) && is_array( $_POST['children'] ) ? wp_unslash( $_POST['children'] ) : array();
    foreach ( $posted_children as $child_id => $child_data ) {
        $child_id = absint( $child_id );
        if ( ! $child_id || ! is_array( $child_data ) ) {
            continue;
        }

        $existing_child = $wpdb->get_row(
            $wpdb->prepare( "SELECT id, child_index, nome, cognome, categoria FROM {$tables['children']} WHERE id = %d AND iscrizione_id = %d", $child_id, $id )
        );

        if ( ! $existing_child ) {
            continue;
        }

        $date = isset( $child_data['data_nascita'] ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $child_data['data_nascita'] ) ? $child_data['data_nascita'] : null;
        $categoria = sport_theme_sanitize_iscrizione_key(
            $child_data['categoria'] ?? '',
            array_keys( sport_theme_iscrizioni_category_options() ),
            ''
        );
        if ( (string) $existing_child->categoria !== $categoria ) {
            $child_name = trim( (string) $existing_child->nome . ' ' . (string) $existing_child->cognome );
            $change_messages[] = ( $child_name ?: 'Bambino ' . (int) $existing_child->child_index ) . ': categoria ' . sport_theme_iscrizione_label_value( $existing_child->categoria, $category_labels ) . ' -> ' . sport_theme_iscrizione_label_value( $categoria, $category_labels );
        }
        $quota_chf = isset( $child_data['quota_chf'] ) ? (float) str_replace( ',', '.', sanitize_text_field( $child_data['quota_chf'] ) ) : null;
        if ( $quota_chf === null || $quota_chf < 0 ) {
            $quota_chf = sport_theme_calculate_iscrizione_child_amount( $tipo_iscrizione, (int) $existing_child->child_index );
        }

        $wpdb->update(
            $tables['children'],
            array(
                'nome'                       => sanitize_text_field( $child_data['nome'] ?? '' ),
                'cognome'                    => sanitize_text_field( $child_data['cognome'] ?? '' ),
                'data_nascita'               => $date,
                'nazionalita'                => sanitize_text_field( $child_data['nazionalita'] ?? '' ),
                'avs'                        => sanitize_text_field( $child_data['avs'] ?? '' ),
                'indirizzo'                  => sanitize_text_field( $child_data['indirizzo'] ?? '' ),
                'cap_citta'                  => sanitize_text_field( $child_data['cap_citta'] ?? '' ),
                'email'                      => sanitize_email( $child_data['email'] ?? '' ),
                'cellulare'                  => sanitize_text_field( $child_data['cellulare'] ?? '' ),
                'categoria'                  => $categoria,
                'quota_chf'                  => $quota_chf,
                'salute_allergie_medicinali' => sport_theme_sanitize_iscrizione_key( $child_data['salute_allergie_medicinali'] ?? '', array( 'si', 'no' ), 'no' ),
                'salute_dettagli'            => sanitize_textarea_field( $child_data['salute_dettagli'] ?? '' ),
                'altro_sport'                => sport_theme_sanitize_iscrizione_key( $child_data['altro_sport'] ?? '', array( 'si', 'no' ), 'no' ),
                'sport_societa'              => sanitize_text_field( $child_data['sport_societa'] ?? '' ),
                'sport_giorni'               => sanitize_text_field( $child_data['sport_giorni'] ?? '' ),
                'tragitto_autonomo'          => sport_theme_sanitize_iscrizione_key( $child_data['tragitto_autonomo'] ?? '', array( 'si', 'no' ), 'no' ),
                'abile_sport'                => sport_theme_sanitize_iscrizione_key( $child_data['abile_sport'] ?? '', array( 'si', 'no' ), 'si' ),
                'tipo_documento'             => sport_theme_sanitize_iscrizione_key( $child_data['tipo_documento'] ?? '', array( 'carta_identita', 'permesso_soggiorno', 'passaporto' ), 'carta_identita' ),
                'updated_at'                 => current_time( 'mysql' ),
            ),
            array( 'id' => $child_id ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
            array( '%d' )
        );
    }

    $documents_replaced = 0;
    if ( ! empty( $_FILES ) ) {
        foreach ( array_keys( $_FILES ) as $file_field ) {
            if ( strpos( $file_field, 'replace_document_' ) !== 0 ) {
                continue;
            }

            $document_id = absint( substr( $file_field, strlen( 'replace_document_' ) ) );
            if ( ! $document_id ) {
                continue;
            }

            $replace_result = sport_theme_replace_iscrizione_document_from_upload( $document_id, $file_field, $registration );
            if ( is_wp_error( $replace_result ) ) {
                wp_die( esc_html( $replace_result->get_error_message() ), 500 );
            }

            if ( $replace_result ) {
                $documents_replaced++;
            }
        }
    }

    $updated_children = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM {$tables['children']} WHERE iscrizione_id = %d ORDER BY child_index ASC", $id ),
        ARRAY_A
    );

    $wpdb->update(
        $tables['registrations'],
        array(
            'numero_bambini' => count( $updated_children ),
            'importo_totale_chf' => sport_theme_sum_iscrizione_children_amounts( $updated_children, $tipo_iscrizione, (bool) $riduzione_fratelli ),
            'dati_json'      => wp_json_encode( array( 'children' => $updated_children ), JSON_UNESCAPED_UNICODE ),
            'updated_at'     => current_time( 'mysql' ),
        ),
        array( 'id' => $id ),
        array( '%d', '%f', '%s', '%s' ),
        array( '%d' )
    );

    sport_theme_log_iscrizione_event(
        $id,
        'dati_modificati',
        $change_messages ? implode( '; ', array_unique( $change_messages ) ) : 'Dati iscrizione aggiornati dalla segreteria.'
    );

    if ( $documents_replaced > 0 ) {
        $wpdb->insert(
            $tables['logs'],
            array(
                'iscrizione_id' => $id,
                'azione'        => 'documenti_sostituiti',
                'messaggio'     => $documents_replaced . ' documento/i sostituito/i dalla segreteria.',
                'created_by'    => get_current_user_id(),
                'created_at'    => current_time( 'mysql' ),
            ),
            array( '%d', '%s', '%s', '%d', '%s' )
        );
    }

    if ( $registration->stato !== $stato ) {
        sport_theme_send_iscrizione_status_email( $id, $stato );
    }

    wp_safe_redirect( add_query_arg( array( 'updated' => '1' ), home_url( '/area-segreteria/' ) ) . '#segreteria-dashboard' );
    exit;
}
add_action( 'admin_post_act_update_iscrizione_detail', 'sport_theme_handle_update_iscrizione_detail' );

function sport_theme_handle_download_iscrizione_document() {
    sport_theme_iscrizioni_require_segreteria_access();

    $document_id = isset( $_GET['document_id'] ) ? absint( $_GET['document_id'] ) : 0;
    if ( ! $document_id || ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'act_download_document_' . $document_id ) ) {
        wp_die( 'Link documento non valido.', 403 );
    }

    global $wpdb;
    $tables = sport_theme_iscrizioni_table_names();
    $document = $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$tables['documents']} WHERE id = %d", $document_id )
    );

    if ( ! $document ) {
        wp_die( 'Documento non trovato.', 404 );
    }

    if ( $document->storage === 'media' && $document->attachment_id ) {
        $media_path = get_attached_file( (int) $document->attachment_id );
        $path = $media_path ? realpath( $media_path ) : '';
    } else {
        $path = $document->private_path ? realpath( $document->private_path ) : '';
    }

    if ( ! $path || ! file_exists( $path ) || ! is_readable( $path ) ) {
        wp_die( 'File non disponibile.', 404 );
    }

    $uploads = wp_upload_dir();
    if ( $document->storage === 'media' ) {
        $uploads_root = realpath( $uploads['basedir'] );
        if ( ! $uploads_root || strpos( $path, $uploads_root ) !== 0 ) {
            wp_die( 'Percorso file non consentito.', 403 );
        }
    } else {
        $private_root = realpath( trailingslashit( $uploads['basedir'] ) . 'ac-taverne-private' );
        if ( ! $private_root || strpos( $path, $private_root ) !== 0 ) {
            wp_die( 'Percorso file non consentito.', 403 );
        }
    }

    $filename = $document->original_name ? $document->original_name : basename( $path );
    $mime = $document->mime_type ? $document->mime_type : 'application/octet-stream';

    nocache_headers();
    header( 'Content-Type: ' . $mime );
    header( 'Content-Length: ' . filesize( $path ) );
    header( 'Content-Disposition: attachment; filename="' . sanitize_file_name( $filename ) . '"' );
    readfile( $path );
    exit;
}
add_action( 'admin_post_act_download_iscrizione_document', 'sport_theme_handle_download_iscrizione_document' );

function sport_theme_get_iscrizioni_dashboard_filters() {
    $filter_tipo      = isset( $_GET['tipo'] ) ? sanitize_key( wp_unslash( $_GET['tipo'] ) ) : '';
    $filter_stato     = isset( $_GET['stato'] ) ? sanitize_key( wp_unslash( $_GET['stato'] ) ) : '';
    $filter_pagamento = isset( $_GET['pagamento'] ) ? sanitize_key( wp_unslash( $_GET['pagamento'] ) ) : '';
    $filter_categoria = isset( $_GET['categoria'] ) ? sanitize_key( wp_unslash( $_GET['categoria'] ) ) : '';
    $filter_pratiche  = isset( $_GET['pratiche'] ) ? sanitize_key( wp_unslash( $_GET['pratiche'] ) ) : '';
    $filter_stagione  = isset( $_GET['stagione'] ) ? sanitize_text_field( wp_unslash( $_GET['stagione'] ) ) : '';
    $search_query     = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';

    if ( ! in_array( $filter_tipo, array( 'allievi', 'scuola_calcio' ), true ) ) {
        $filter_tipo = '';
    }
    if ( ! in_array( $filter_stato, sport_theme_iscrizioni_allowed_statuses(), true ) ) {
        $filter_stato = '';
    }
    if ( ! in_array( $filter_pagamento, array( 'stripe', 'fattura' ), true ) ) {
        $filter_pagamento = '';
    }
    if ( $filter_categoria !== '__unassigned' && ! array_key_exists( $filter_categoria, sport_theme_iscrizioni_category_options() ) ) {
        $filter_categoria = '';
    }
    if ( ! in_array( $filter_pratiche, array( 'incomplete', 'duplicate' ), true ) ) {
        $filter_pratiche = '';
    }
    if ( $filter_stagione !== '' && ! preg_match( '/^\d{4}\/\d{4}$/', $filter_stagione ) ) {
        $filter_stagione = '';
    }

    return compact( 'filter_tipo', 'filter_stato', 'filter_pagamento', 'filter_categoria', 'filter_pratiche', 'filter_stagione', 'search_query' );
}

function sport_theme_build_iscrizioni_where_sql( $filters, $wpdb ) {
    $where = array( '1=1' );

    if ( ! empty( $filters['filter_tipo'] ) ) {
        $where[] = $wpdb->prepare( 'i.tipo_iscrizione = %s', $filters['filter_tipo'] );
    }
    if ( ! empty( $filters['filter_stato'] ) ) {
        $where[] = $wpdb->prepare( 'i.stato = %s', $filters['filter_stato'] );
    }
    if ( ! empty( $filters['filter_pagamento'] ) ) {
        $where[] = $wpdb->prepare( 'i.metodo_pagamento = %s', $filters['filter_pagamento'] );
    }
    if ( ! empty( $filters['filter_categoria'] ) && $filters['filter_categoria'] === '__unassigned' ) {
        $where[] = "(b.categoria = '' OR b.categoria IS NULL)";
    } elseif ( ! empty( $filters['filter_categoria'] ) ) {
        $where[] = $wpdb->prepare( 'b.categoria = %s', $filters['filter_categoria'] );
    }
    if ( ! empty( $filters['filter_stagione'] ) ) {
        $where[] = $wpdb->prepare( 'i.stagione_sportiva = %s', $filters['filter_stagione'] );
    }
    if ( ! empty( $filters['filter_pratiche'] ) && $filters['filter_pratiche'] === 'incomplete' ) {
        $tables = sport_theme_iscrizioni_table_names();
        $where[] = "(i.stato NOT IN ('approvata', 'confermata') OR i.metodo_pagamento = '' OR i.metodo_pagamento IS NULL OR i.stato_pagamento <> 'pagato' OR EXISTS (SELECT 1 FROM {$tables['children']} bi WHERE bi.iscrizione_id = i.id AND (bi.categoria = '' OR bi.categoria IS NULL)))";
    } elseif ( ! empty( $filters['filter_pratiche'] ) && $filters['filter_pratiche'] === 'duplicate' ) {
        $tables = sport_theme_iscrizioni_table_names();
        $where[] = "LOWER(i.responsabile_email) IN (SELECT email_key FROM (SELECT LOWER(responsabile_email) AS email_key FROM {$tables['registrations']} WHERE responsabile_email <> '' GROUP BY LOWER(responsabile_email) HAVING COUNT(*) > 1) duplicate_filter)";
    }
    if ( ! empty( $filters['search_query'] ) ) {
        $like = '%' . $wpdb->esc_like( $filters['search_query'] ) . '%';
        $where[] = $wpdb->prepare(
            '(i.uuid LIKE %s OR i.responsabile_nome LIKE %s OR i.responsabile_cognome LIKE %s OR i.responsabile_email LIKE %s OR i.responsabile_telefono LIKE %s OR b.nome LIKE %s OR b.cognome LIKE %s OR b.email LIKE %s OR b.cellulare LIKE %s OR b.avs LIKE %s OR b.data_nascita LIKE %s OR b.categoria LIKE %s)',
            $like,
            $like,
            $like,
            $like,
            $like,
            $like,
            $like,
            $like,
            $like,
            $like,
            $like,
            $like
        );
    }

    return 'WHERE ' . implode( ' AND ', $where );
}

function sport_theme_xlsx_clean_text( $value ) {
    $value = (string) $value;
    return preg_replace( '/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $value );
}

function sport_theme_xlsx_escape( $value ) {
    return htmlspecialchars( sport_theme_xlsx_clean_text( $value ), ENT_XML1 | ENT_COMPAT, 'UTF-8' );
}

function sport_theme_xlsx_column_name( $index ) {
    $index = (int) $index;
    $name  = '';

    while ( $index > 0 ) {
        $index--;
        $name  = chr( 65 + ( $index % 26 ) ) . $name;
        $index = (int) floor( $index / 26 );
    }

    return $name;
}

function sport_theme_xlsx_unique_sheet_name( $name, &$used_names ) {
    $name = trim( preg_replace( '/[\[\]\:\*\?\/\\\\]/', ' ', (string) $name ) );
    $name = preg_replace( '/\s+/', ' ', $name );
    $name = $name !== '' ? $name : 'Foglio';
    $base = mb_substr( $name, 0, 31 );
    $name = $base;
    $i    = 2;

    while ( isset( $used_names[ mb_strtolower( $name ) ] ) ) {
        $suffix = ' ' . $i;
        $name   = mb_substr( $base, 0, 31 - mb_strlen( $suffix ) ) . $suffix;
        $i++;
    }

    $used_names[ mb_strtolower( $name ) ] = true;
    return $name;
}

function sport_theme_xlsx_worksheet_xml( $rows ) {
    $row_count = count( $rows );
    $col_count = 0;

    foreach ( $rows as $row ) {
        $col_count = max( $col_count, count( $row ) );
    }

    $last_cell = sport_theme_xlsx_column_name( max( 1, $col_count ) ) . max( 1, $row_count );
    $xml       = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $xml      .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
    $xml      .= '<dimension ref="A1:' . $last_cell . '"/>';
    $xml      .= '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>';
    $xml      .= '<sheetFormatPr defaultRowHeight="18"/>';
    if ( $col_count > 0 ) {
        $xml .= '<cols>';
        for ( $col_index = 1; $col_index <= $col_count; $col_index++ ) {
            $width = in_array( $col_index, array( 1, 8, 9, 10, 11, 17, 18 ), true ) ? 14 : 22;
            if ( in_array( $col_index, array( 2, 13, 14, 15, 16, 19, 20, 23, 24, 25, 37, 38, 39, 40 ), true ) ) {
                $width = 30;
            }
            $xml .= '<col min="' . $col_index . '" max="' . $col_index . '" width="' . $width . '" customWidth="1"/>';
        }
        $xml .= '</cols>';
    }
    $xml      .= '<sheetData>';

    foreach ( $rows as $row_index => $row ) {
        $excel_row = $row_index + 1;
        $xml      .= '<row r="' . $excel_row . '">';

        for ( $col_index = 0; $col_index < $col_count; $col_index++ ) {
            $cell_ref = sport_theme_xlsx_column_name( $col_index + 1 ) . $excel_row;
            $value    = $row[ $col_index ] ?? '';
            $style    = $row_index === 0 ? ' s="1"' : '';
            $xml     .= '<c r="' . $cell_ref . '"' . $style . ' t="inlineStr"><is><t>' . sport_theme_xlsx_escape( $value ) . '</t></is></c>';
        }

        $xml .= '</row>';
    }

    $xml .= '</sheetData>';

    if ( $row_count > 1 && $col_count > 0 ) {
        $xml .= '<autoFilter ref="A1:' . sport_theme_xlsx_column_name( $col_count ) . $row_count . '"/>';
    }

    $xml .= '</worksheet>';
    return $xml;
}

function sport_theme_output_iscrizioni_xlsx( $sheets, $filename ) {
    if ( ! class_exists( 'ZipArchive' ) ) {
        wp_die( 'Export Excel non disponibile: estensione ZIP mancante.', 500 );
    }

    $tmp = tempnam( get_temp_dir(), 'act-export-' );
    if ( ! $tmp ) {
        wp_die( 'Impossibile creare il file Excel temporaneo.', 500 );
    }

    $zip = new ZipArchive();
    if ( true !== $zip->open( $tmp, ZipArchive::OVERWRITE ) ) {
        wp_die( 'Impossibile preparare il file Excel.', 500 );
    }

    $used_names = array();
    $sheet_defs = array();
    $sheet_id   = 1;

    foreach ( $sheets as $sheet_name => $rows ) {
        $sheet_defs[] = array(
            'id'   => $sheet_id,
            'name' => sport_theme_xlsx_unique_sheet_name( $sheet_name, $used_names ),
            'rows' => $rows,
        );
        $sheet_id++;
    }

    $content_types = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $content_types .= '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">';
    $content_types .= '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>';
    $content_types .= '<Default Extension="xml" ContentType="application/xml"/>';
    $content_types .= '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>';

    foreach ( $sheet_defs as $sheet ) {
        $content_types .= '<Override PartName="/xl/worksheets/sheet' . $sheet['id'] . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
    }

    $content_types .= '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>';
    $content_types .= '</Types>';
    $zip->addFromString( '[Content_Types].xml', $content_types );
    $zip->addFromString( '_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>' );

    $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets>';
    $rels     = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';

    foreach ( $sheet_defs as $sheet ) {
        $workbook .= '<sheet name="' . sport_theme_xlsx_escape( $sheet['name'] ) . '" sheetId="' . $sheet['id'] . '" r:id="rId' . $sheet['id'] . '"/>';
        $rels     .= '<Relationship Id="rId' . $sheet['id'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $sheet['id'] . '.xml"/>';
        $zip->addFromString( 'xl/worksheets/sheet' . $sheet['id'] . '.xml', sport_theme_xlsx_worksheet_xml( $sheet['rows'] ) );
    }

    $workbook .= '</sheets></workbook>';
    $rels     .= '<Relationship Id="rIdStyles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';
    $rels     .= '</Relationships>';

    $zip->addFromString( 'xl/workbook.xml', $workbook );
    $zip->addFromString( 'xl/_rels/workbook.xml.rels', $rels );
    $zip->addFromString(
        'xl/styles.xml',
        '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
        . '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><color rgb="FF000000"/><name val="Calibri"/></font></fonts>'
        . '<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FFFFE600"/><bgColor indexed="64"/></patternFill></fill></fills>'
        . '<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border><border><left style="thin"><color rgb="FFCCCCCC"/></left><right style="thin"><color rgb="FFCCCCCC"/></right><top style="thin"><color rgb="FFCCCCCC"/></top><bottom style="thin"><color rgb="FFCCCCCC"/></bottom><diagonal/></border></borders>'
        . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
        . '<cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"/></cellXfs>'
        . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
        . '</styleSheet>'
    );
    $zip->close();

    nocache_headers();
    header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Content-Length: ' . filesize( $tmp ) );
    readfile( $tmp );
    unlink( $tmp );
    exit;
}

function sport_theme_handle_export_iscrizioni_csv() {
    sport_theme_iscrizioni_require_segreteria_access();

    if ( ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'act_export_iscrizioni_csv' ) ) {
        wp_die( 'Export non autorizzato.', 403 );
    }

    global $wpdb;

    $tables = sport_theme_iscrizioni_table_names();
    $filters = sport_theme_get_iscrizioni_dashboard_filters();
    $where_sql = sport_theme_build_iscrizioni_where_sql( $filters, $wpdb );

    $rows = $wpdb->get_results(
        "SELECT i.id, i.uuid, i.tipo_iscrizione, i.stagione_sportiva, i.stato, i.metodo_pagamento, i.stato_pagamento, i.importo_totale_chf, i.riduzione_fratelli, i.sconto_meta_stagione, i.responsabilita_genitoriale,
                i.responsabile_nome, i.responsabile_cognome, i.responsabile_telefono, i.responsabile_email, i.numero_bambini, i.note_interne, i.created_at,
                b.child_index, b.nome, b.cognome, b.data_nascita, b.nazionalita, b.avs, b.indirizzo, b.cap_citta, b.email, b.cellulare, b.categoria, b.quota_chf,
                b.salute_allergie_medicinali, b.salute_dettagli, b.altro_sport, b.sport_societa, b.sport_giorni, b.tragitto_autonomo, b.abile_sport, b.tipo_documento
         FROM {$tables['registrations']} i
         LEFT JOIN {$tables['children']} b ON b.iscrizione_id = i.id
         {$where_sql}
         ORDER BY i.created_at DESC, b.child_index ASC"
    );

    $documents_by_child = array();
    $documents_by_registration = array();

    if ( ! empty( $rows ) ) {
        $registration_ids = array_values( array_unique( array_map( 'absint', wp_list_pluck( $rows, 'id' ) ) ) );
        $placeholders = implode( ',', array_fill( 0, count( $registration_ids ), '%d' ) );

        $documents = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT id, iscrizione_id, child_index, ruolo_file, original_name
                 FROM {$tables['documents']}
                 WHERE iscrizione_id IN ({$placeholders})
                 ORDER BY child_index ASC, id ASC",
                $registration_ids
            )
        );

        foreach ( $documents as $document ) {
            $download_url = add_query_arg(
                array(
                    'action'      => 'act_download_iscrizione_document',
                    'document_id' => (int) $document->id,
                    '_wpnonce'    => wp_create_nonce( 'act_download_document_' . (int) $document->id ),
                ),
                admin_url( 'admin-post.php' )
            );

            $label = $document->original_name ? $document->original_name : str_replace( '_', ' ', $document->ruolo_file );
            $entry = $label . ': ' . esc_url_raw( $download_url );

            if ( $document->child_index ) {
                $documents_by_child[ (int) $document->iscrizione_id ][ (int) $document->child_index ][ $document->ruolo_file ][] = $entry;
            } else {
                $documents_by_registration[ (int) $document->iscrizione_id ][ $document->ruolo_file ][] = $entry;
            }
        }
    }

    $headers = array(
        'ID iscrizione',
        'Codice',
        'Tipo iscrizione',
        'Stagione sportiva',
        'Stato iscrizione',
        'Metodo pagamento',
        'Stato pagamento',
        'Quota allievo CHF',
        'Riduzione fratelli',
        'Sconto metà stagione',
        'Data invio',
        'Responsabilita genitoriale',
        'Nome responsabile',
        'Cognome responsabile',
        'Telefono responsabile',
        'Email responsabile',
        'Numero bambini',
        'Indice bambino',
        'Nome bambino',
        'Cognome bambino',
        'Data nascita',
        'Nazionalita',
        'AVS',
        'Indirizzo',
        'CAP e citta',
        'Email bambino',
        'Cellulare bambino',
        'Categoria assegnata',
        'Allergie o medicinali',
        'Dettagli salute',
        'Altro sport',
        'Societa altro sport',
        'Giorni altro sport',
        'Tragitto autonomo',
        'Abile sport',
        'Tipo documento',
        'Link foto giocatore',
        'Link documenti identita',
        'Link certificato tutela',
        'Note interne',
    );

    $category_options = sport_theme_iscrizioni_category_options();
    $all_export_rows  = array();
    $category_rows    = array();
    $summary_statuses = array();
    $summary_payments = array();
    $summary_categories = array();

    foreach ( $rows as $row ) {
        $child_documents = $documents_by_child[ (int) $row->id ][ (int) $row->child_index ] ?? array();
        $registration_documents = $documents_by_registration[ (int) $row->id ] ?? array();
        $photo_links = $child_documents['foto_giocatore'] ?? array();
        $identity_links = array();

        foreach ( $child_documents as $role => $links ) {
            if ( $role === 'foto_giocatore' ) {
                continue;
            }

            $identity_links = array_merge( $identity_links, $links );
        }

        $guardian_links = $registration_documents['certificato_tutela'] ?? array();
        $child_amount = sport_theme_get_iscrizione_child_amount( $row, $row->tipo_iscrizione );
        $sibling_reduction_label = 'No';
        if ( ! empty( $row->riduzione_fratelli ) ) {
            $sibling_reduction_label = 'Sì, CHF 50';
        } elseif (
            $row->tipo_iscrizione === 'allievi'
            && (int) $row->numero_bambini > 1
            && (int) $row->child_index > 1
            && (
                ( empty( $row->sconto_meta_stagione ) && $child_amount < 300 )
                || ( ! empty( $row->sconto_meta_stagione ) && $child_amount < 150 )
            )
        ) {
            $sibling_reduction_label = 'Sì';
        }

        $category_label = sport_theme_iscrizione_label_value( $row->categoria, $category_options );
        if ( ! $category_label ) {
            $category_label = 'Da assegnare';
        }
        $summary_statuses[ $row->stato ] = ( $summary_statuses[ $row->stato ] ?? 0 ) + 1;
        $summary_payments[ $row->stato_pagamento ] = ( $summary_payments[ $row->stato_pagamento ] ?? 0 ) + 1;
        $summary_categories[ $category_label ] = ( $summary_categories[ $category_label ] ?? 0 ) + 1;

        $export_row = array(
            $row->id,
            $row->uuid,
            $row->tipo_iscrizione,
            $row->stagione_sportiva ?: sport_theme_current_sport_season(),
            $row->stato,
            $row->metodo_pagamento,
            $row->stato_pagamento,
            number_format( $child_amount, 2, '.', '' ),
            $sibling_reduction_label,
            ! empty( $row->sconto_meta_stagione ) ? 'Sì' : 'No',
            $row->created_at,
            $row->responsabilita_genitoriale,
            $row->responsabile_nome,
            $row->responsabile_cognome,
            $row->responsabile_telefono,
            $row->responsabile_email,
            $row->numero_bambini,
            $row->child_index,
            $row->nome,
            $row->cognome,
            $row->data_nascita,
            $row->nazionalita,
            $row->avs,
            $row->indirizzo,
            $row->cap_citta,
            $row->email,
            $row->cellulare,
            sport_theme_iscrizione_label_value( $row->categoria, $category_options ),
            $row->salute_allergie_medicinali,
            $row->salute_dettagli,
            $row->altro_sport,
            $row->sport_societa,
            $row->sport_giorni,
            $row->tragitto_autonomo,
            $row->abile_sport,
            $row->tipo_documento,
            implode( ' | ', $photo_links ),
            implode( ' | ', $identity_links ),
            implode( ' | ', $guardian_links ),
            $row->note_interne,
        );

        $all_export_rows[] = $export_row;

        if ( ! isset( $category_rows[ $category_label ] ) ) {
            $category_rows[ $category_label ] = array();
        }
        $category_rows[ $category_label ][] = $export_row;
    }

    $summary_rows = array(
        array( 'Indicatore', 'Valore' ),
        array( 'Righe esportate', count( $all_export_rows ) ),
        array( 'Pratiche esportate', count( array_unique( array_map( static function ( $row ) { return $row[0]; }, $all_export_rows ) ) ) ),
        array( '', '' ),
        array( 'Stato iscrizione', 'Righe' ),
    );
    foreach ( $summary_statuses as $status => $count ) {
        $summary_rows[] = array( sport_theme_iscrizione_label_value( $status, sport_theme_iscrizioni_status_labels() ), $count );
    }
    $summary_rows[] = array( '', '' );
    $summary_rows[] = array( 'Stato pagamento', 'Righe' );
    foreach ( $summary_payments as $payment_status => $count ) {
        $summary_rows[] = array( $payment_status ?: 'non_pagato', $count );
    }
    $summary_rows[] = array( '', '' );
    $summary_rows[] = array( 'Categoria', 'Righe' );
    foreach ( $summary_categories as $category_label => $count ) {
        $summary_rows[] = array( $category_label, $count );
    }

    $sheets = array(
        'Tutte' => array_merge( array( $headers ), $all_export_rows ),
        'Riepilogo' => $summary_rows,
    );

    foreach ( $category_options as $category_key => $category_label ) {
        if ( $category_key === '' ) {
            continue;
        }

        if ( ! empty( $category_rows[ $category_label ] ) ) {
            $sheets[ $category_label ] = array_merge( array( $headers ), $category_rows[ $category_label ] );
            unset( $category_rows[ $category_label ] );
        }
    }

    foreach ( $category_rows as $category_label => $category_export_rows ) {
        $sheets[ $category_label ] = array_merge( array( $headers ), $category_export_rows );
    }

    sport_theme_output_iscrizioni_xlsx(
        $sheets,
        'iscrizioni-ac-taverne-' . date( 'Y-m-d' ) . '.xlsx'
    );
}
add_action( 'admin_post_act_export_iscrizioni_csv', 'sport_theme_handle_export_iscrizioni_csv' );
