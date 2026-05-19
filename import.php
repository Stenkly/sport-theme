<?php
require_once('/Users/stanoje/Local Sites/ac-taverne/app/public/wp-load.php');
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
$files = scandir($image_dir);

echo "Starting import...\n";

foreach ($players_data as $p) {
    // Check if post already exists by title
    $title = $p['nome'] . ' ' . ucfirst(strtolower($p['cognome']));
    $existing = get_page_by_title($title, OBJECT, 'giocatore');
    
    if ($existing) {
        $post_id = $existing->ID;
        echo "Found existing player: {$title} (ID: {$post_id})\n";
    } else {
        $post_data = array(
            'post_title'    => $title,
            'post_status'   => 'publish',
            'post_type'     => 'giocatore'
        );
        $post_id = wp_insert_post($post_data);
        echo "Created player: {$title} (ID: {$post_id})\n";
    }

    if (is_wp_error($post_id)) {
        echo "Error creating {$title}\n";
        continue;
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
    
    // Attempt to attach image
    if (!has_post_thumbnail($post_id)) {
        $found_img = false;
        $search_name = strtolower($p['nome'] . ' ' . $p['cognome']);
        $search_name_rev = strtolower($p['cognome'] . ' ' . $p['nome']);
        
        // Custom overrides for specific file names from the directory listing
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
            echo " -> Uploaded and attached image: {$found_img}\n";
        } else {
            echo " -> No matching image found for {$title}\n";
        }
    } else {
        echo " -> Already has thumbnail\n";
    }
}
echo "Done!\n";
?>
