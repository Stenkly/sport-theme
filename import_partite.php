<?php
require_once('/Users/stanoje/Local Sites/ac-taverne/app/public/wp-load.php');

$partite = [
    [
        'title' => 'SC YF Juventus vs AC Taverne',
        'data' => '23.05.2026',
        'ora' => '16:00',
        'avversario' => 'SC YF Juventus',
        'in_casa' => '0', // 0 = away, 1 = home
        'stadio' => 'Trasferta',
        'risultato' => ''
    ],
    [
        'title' => 'FC Mendrisio vs AC Taverne',
        'data' => '30.05.2026',
        'ora' => '16:00',
        'avversario' => 'FC Mendrisio',
        'in_casa' => '0',
        'stadio' => 'Trasferta',
        'risultato' => ''
    ]
];

foreach ($partite as $p) {
    // Controlliamo se esiste già
    $existing = get_page_by_title($p['title'], OBJECT, 'partita');
    
    if (!$existing) {
        $post_id = wp_insert_post([
            'post_title' => $p['title'],
            'post_status' => 'publish',
            'post_type' => 'partita'
        ]);
        
        update_post_meta($post_id, '_data_partita', $p['data']);
        update_post_meta($post_id, '_ora_partita', $p['ora']);
        update_post_meta($post_id, '_avversario', $p['avversario']);
        update_post_meta($post_id, '_in_casa', $p['in_casa']);
        update_post_meta($post_id, '_stadio', $p['stadio']);
        update_post_meta($post_id, '_risultato', $p['risultato']);
        
        echo "Partita inserita: " . $p['title'] . "\n";
    } else {
        echo "Partita già presente: " . $p['title'] . "\n";
    }
}
echo "Finito!\n";
