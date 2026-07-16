<?php
/**
 * Template Name: Area Segreteria
 *
 * @package Sport_Theme
 */

get_header('societa');

$hero_image_url = sport_theme_get_societa_home_hero_url();
$can_access = is_user_logged_in() && sport_theme_can_access_segreteria();

$totale_iscrizioni = 0;
$iscrizioni_da_verificare = 0;
$iscrizioni_confermate = 0;
$iscrizioni_allievi = 0;
$iscrizioni_scuola_calcio = 0;
$iscrizioni_fattura = 0;
$iscrizioni_stripe = 0;
$totale_allievi = 0;
$iscrizioni_da_assegnare = 0;
$iscrizioni_pagamento_aperto = 0;
$iscrizioni_pagamento_pagato = 0;
$iscrizioni_duplicate = 0;
$filtered_count = 0;
$recent_iscrizioni = array();
$documents_by_iscrizione = array();
$edit_iscrizione_id = isset($_GET['edit_iscrizione']) ? absint($_GET['edit_iscrizione']) : 0;
$edit_iscrizione = null;
$edit_children = array();
$edit_documents = array();
$edit_logs = array();
$duplicate_email_counts = array();
$duplicate_email_has_discount = array();
$edit_duplicate_iscrizioni = array();
$table_exists = false;
$allievi_birthdate_cutoff = function_exists('sport_theme_get_allievi_birthdate_cutoff') ? sport_theme_get_allievi_birthdate_cutoff() : '2017-12-31';
$new_registrations_discount_50_active = function_exists('sport_theme_new_registrations_discount_50_is_active')
    ? sport_theme_new_registrations_discount_50_is_active()
    : false;

$filter_tipo = isset($_GET['tipo']) ? sanitize_key(wp_unslash($_GET['tipo'])) : '';
$filter_stato = isset($_GET['stato']) ? sanitize_key(wp_unslash($_GET['stato'])) : '';
$filter_pagamento = isset($_GET['pagamento']) ? sanitize_key(wp_unslash($_GET['pagamento'])) : '';
$filter_categoria = isset($_GET['categoria']) ? sanitize_key(wp_unslash($_GET['categoria'])) : '';
$filter_pratiche = isset($_GET['pratiche']) ? sanitize_key(wp_unslash($_GET['pratiche'])) : '';
$filter_stagione = isset($_GET['stagione']) ? sanitize_text_field(wp_unslash($_GET['stagione'])) : '';
$search_query = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
$current_page = isset($_GET['pagina']) ? max(1, absint($_GET['pagina'])) : 1;
$per_page = 30;
$total_pages = 1;

$allowed_tipi = array('allievi', 'scuola_calcio');
$category_options = function_exists('sport_theme_iscrizioni_category_options') ? sport_theme_iscrizioni_category_options() : array('' => 'Da assegnare');
$edit_category_options = array_diff_key($category_options, array('allievi_f' => true, 'allievi_g' => true));
$status_labels = function_exists('sport_theme_iscrizioni_status_labels') ? sport_theme_iscrizioni_status_labels() : array();
$allowed_stati = function_exists('sport_theme_iscrizioni_allowed_statuses')
    ? sport_theme_iscrizioni_allowed_statuses()
    : array('nuova', 'in_verifica', 'documenti_mancanti', 'approvata', 'confermata', 'archiviata');
$allowed_pagamenti = array('stripe', 'fattura');

if (!in_array($filter_tipo, $allowed_tipi, true)) {
    $filter_tipo = '';
}
if (!in_array($filter_stato, $allowed_stati, true)) {
    $filter_stato = '';
}
if (!in_array($filter_pagamento, $allowed_pagamenti, true)) {
    $filter_pagamento = '';
}
if ($filter_categoria !== '__unassigned' && !array_key_exists($filter_categoria, $category_options)) {
    $filter_categoria = '';
}
if (!in_array($filter_pratiche, array('incomplete', 'duplicate'), true)) {
    $filter_pratiche = '';
}
if ($filter_stagione !== '' && !preg_match('/^\d{4}\/\d{4}$/', $filter_stagione)) {
    $filter_stagione = '';
}

if ( function_exists( 'sport_theme_create_iscrizioni_tables' ) && function_exists( 'sport_theme_iscrizioni_table_names' ) ) {
    sport_theme_create_iscrizioni_tables();

    global $wpdb;
    $tables = sport_theme_iscrizioni_table_names();
    $registrations_table = $tables['registrations'];
    $children_table = $tables['children'];

    $table_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $registrations_table ) ) );

    if ( $table_exists === $registrations_table ) {
        $table_exists = true;

        $stats = $wpdb->get_row(
            "SELECT
                COUNT(i.id) AS totale,
                SUM(CASE WHEN i.stato = 'nuova' THEN 1 ELSE 0 END) AS da_verificare,
                SUM(CASE WHEN i.stato IN ('approvata', 'confermata') THEN 1 ELSE 0 END) AS confermate,
                SUM(CASE WHEN i.tipo_iscrizione = 'allievi' THEN COALESCE(NULLIF(child_counts.children_count, 0), NULLIF(i.numero_bambini, 0), 1) ELSE 0 END) AS allievi,
                SUM(CASE WHEN i.tipo_iscrizione = 'scuola_calcio' THEN COALESCE(NULLIF(child_counts.children_count, 0), NULLIF(i.numero_bambini, 0), 1) ELSE 0 END) AS scuola_calcio,
                SUM(CASE WHEN i.metodo_pagamento = 'fattura' THEN 1 ELSE 0 END) AS fattura,
                SUM(CASE WHEN i.metodo_pagamento = 'stripe' THEN 1 ELSE 0 END) AS stripe,
                SUM(CASE WHEN child_counts.unassigned_count > 0 THEN 1 ELSE 0 END) AS da_assegnare,
                SUM(CASE WHEN i.stato_pagamento = 'pagato' THEN 1 ELSE 0 END) AS pagamento_pagato,
                SUM(CASE WHEN i.stato_pagamento <> 'pagato' THEN 1 ELSE 0 END) AS pagamento_aperto,
                SUM(CASE WHEN duplicate_counts.email_total > 1 THEN 1 ELSE 0 END) AS duplicate_email
             FROM {$registrations_table} i
             LEFT JOIN (
                SELECT iscrizione_id,
                    COUNT(*) AS children_count,
                    SUM(CASE WHEN categoria = '' OR categoria IS NULL THEN 1 ELSE 0 END) AS unassigned_count
                FROM {$children_table}
                GROUP BY iscrizione_id
             ) child_counts ON child_counts.iscrizione_id = i.id"
             . " LEFT JOIN (
                SELECT LOWER(responsabile_email) AS email_key, COUNT(*) AS email_total
                FROM {$registrations_table}
                WHERE responsabile_email <> ''
                GROUP BY LOWER(responsabile_email)
             ) duplicate_counts ON duplicate_counts.email_key = LOWER(i.responsabile_email)"
        );

        $totale_iscrizioni = (int) ($stats->totale ?? 0);
        $iscrizioni_da_verificare = (int) ($stats->da_verificare ?? 0);
        $iscrizioni_confermate = (int) ($stats->confermate ?? 0);
        $iscrizioni_allievi = (int) ($stats->allievi ?? 0);
        $iscrizioni_scuola_calcio = (int) ($stats->scuola_calcio ?? 0);
        $iscrizioni_fattura = (int) ($stats->fattura ?? 0);
        $iscrizioni_stripe = (int) ($stats->stripe ?? 0);
        $totale_allievi = $iscrizioni_allievi + $iscrizioni_scuola_calcio;
        $iscrizioni_da_assegnare = (int) ($stats->da_assegnare ?? 0);
        $iscrizioni_pagamento_aperto = (int) ($stats->pagamento_aperto ?? 0);
        $iscrizioni_pagamento_pagato = (int) ($stats->pagamento_pagato ?? 0);
        $iscrizioni_duplicate = (int) ($stats->duplicate_email ?? 0);

        $where = array('1=1');
        if ($filter_tipo) {
            $where[] = $wpdb->prepare('i.tipo_iscrizione = %s', $filter_tipo);
        }
        if ($filter_stato) {
            $where[] = $wpdb->prepare('i.stato = %s', $filter_stato);
        }
        if ($filter_pagamento) {
            $where[] = $wpdb->prepare('i.metodo_pagamento = %s', $filter_pagamento);
        }
        if ($filter_categoria === '__unassigned') {
            $where[] = "(b.categoria = '' OR b.categoria IS NULL)";
        } elseif ($filter_categoria) {
            $where[] = $wpdb->prepare('b.categoria = %s', $filter_categoria);
        }
        if ($filter_stagione) {
            $where[] = $wpdb->prepare('i.stagione_sportiva = %s', $filter_stagione);
        }
        if ($filter_pratiche === 'incomplete') {
            $where[] = "(i.stato NOT IN ('approvata', 'confermata') OR i.metodo_pagamento = '' OR i.metodo_pagamento IS NULL OR i.stato_pagamento <> 'pagato' OR EXISTS (SELECT 1 FROM {$children_table} bi WHERE bi.iscrizione_id = i.id AND (bi.categoria = '' OR bi.categoria IS NULL)))";
        } elseif ($filter_pratiche === 'duplicate') {
            $where[] = "LOWER(i.responsabile_email) IN (SELECT email_key FROM (SELECT LOWER(responsabile_email) AS email_key FROM {$registrations_table} WHERE responsabile_email <> '' GROUP BY LOWER(responsabile_email) HAVING COUNT(*) > 1) duplicate_filter)";
        }
        if ($search_query !== '') {
            $like = '%' . $wpdb->esc_like($search_query) . '%';
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
        $where_sql = 'WHERE ' . implode(' AND ', $where);

        $filtered_count = (int) $wpdb->get_var(
            "SELECT COUNT(DISTINCT i.id)
             FROM {$registrations_table} i
             LEFT JOIN {$children_table} b ON b.iscrizione_id = i.id
             {$where_sql}"
        );
        $total_pages = max(1, (int) ceil($filtered_count / $per_page));
        $current_page = min($current_page, $total_pages);
        $offset = ($current_page - 1) * $per_page;

        $recent_iscrizioni = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT i.id, i.uuid, i.tipo_iscrizione, i.stagione_sportiva, i.stato, i.metodo_pagamento, i.stato_pagamento, i.importo_totale_chf, i.riduzione_fratelli, i.sconto_meta_stagione, i.responsabile_nome, i.responsabile_cognome, i.responsabile_email, i.numero_bambini, i.stripe_invoice_url, i.stripe_invoice_pdf, i.stripe_payment_url, i.created_at,
                    GROUP_CONCAT(CONCAT(b.nome, ' ', b.cognome) ORDER BY b.child_index SEPARATOR ', ') AS bambini,
                    GROUP_CONCAT(DISTINCT NULLIF(b.categoria, '') ORDER BY b.categoria SEPARATOR ', ') AS categorie,
                    SUM(CASE WHEN b.categoria = '' OR b.categoria IS NULL THEN 1 ELSE 0 END) AS categorie_da_assegnare
                 FROM {$registrations_table} i
                 LEFT JOIN {$children_table} b ON b.iscrizione_id = i.id
                 {$where_sql}
                 GROUP BY i.id, i.uuid, i.tipo_iscrizione, i.stagione_sportiva, i.stato, i.metodo_pagamento, i.stato_pagamento, i.importo_totale_chf, i.riduzione_fratelli, i.sconto_meta_stagione, i.responsabile_nome, i.responsabile_cognome, i.responsabile_email, i.numero_bambini, i.stripe_invoice_url, i.stripe_invoice_pdf, i.stripe_payment_url, i.created_at
                 ORDER BY i.created_at DESC
                 LIMIT %d OFFSET %d",
                $per_page,
                $offset
            )
        );

        if (!empty($recent_iscrizioni)) {
            $ids = array_map('absint', wp_list_pluck($recent_iscrizioni, 'id'));
            $id_placeholders = implode(',', array_fill(0, count($ids), '%d'));
            $recent_emails = array();
            foreach ($recent_iscrizioni as $recent_iscrizione) {
                $recent_email = strtolower(trim((string) $recent_iscrizione->responsabile_email));
                if ($recent_email !== '') {
                    $recent_emails[] = $recent_email;
                }
            }
            $recent_emails = array_values(array_unique($recent_emails));

            if (!empty($recent_emails)) {
                $email_placeholders = implode(',', array_fill(0, count($recent_emails), '%s'));
                $duplicate_rows = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT LOWER(responsabile_email) AS email_key,
                                COUNT(*) AS total,
                                MAX(CASE WHEN riduzione_fratelli = 1 THEN 1 ELSE 0 END) AS has_discount
                         FROM {$registrations_table}
                         WHERE LOWER(responsabile_email) IN ({$email_placeholders})
                         GROUP BY LOWER(responsabile_email)
                         HAVING COUNT(*) > 1",
                        $recent_emails
                    )
                );

                foreach ($duplicate_rows as $duplicate_row) {
                    $duplicate_email_counts[$duplicate_row->email_key] = (int) $duplicate_row->total;
                    $duplicate_email_has_discount[$duplicate_row->email_key] = !empty($duplicate_row->has_discount);
                }
            }

            $documents_table = $tables['documents'];
            $document_rows = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT id, iscrizione_id, child_index, ruolo_file, storage, original_name, mime_type
                     FROM {$documents_table}
                     WHERE iscrizione_id IN ({$id_placeholders})
                     ORDER BY child_index ASC, id ASC",
                    $ids
                )
            );

            foreach ($document_rows as $document_row) {
                $documents_by_iscrizione[(int) $document_row->iscrizione_id][] = $document_row;
            }
        }

        if ($edit_iscrizione_id) {
            $edit_iscrizione = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM {$registrations_table} WHERE id = %d", $edit_iscrizione_id)
            );

            if ($edit_iscrizione) {
                $edit_children = $wpdb->get_results(
                    $wpdb->prepare("SELECT * FROM {$children_table} WHERE iscrizione_id = %d ORDER BY child_index ASC", $edit_iscrizione_id)
                );

                $documents_table = $tables['documents'];
                $edit_document_rows = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT id, iscrizione_id, child_index, ruolo_file, storage, original_name, mime_type
                         FROM {$documents_table}
                         WHERE iscrizione_id = %d
                         ORDER BY child_index ASC, id ASC",
                        $edit_iscrizione_id
                    )
                );

                foreach ($edit_document_rows as $document_row) {
                    $edit_documents[(int) ($document_row->child_index ?: 0)][] = $document_row;
                }

                $logs_table = $tables['logs'];
                $edit_logs = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT l.*, u.display_name
                         FROM {$logs_table} l
                         LEFT JOIN {$wpdb->users} u ON u.ID = l.created_by
                         WHERE l.iscrizione_id = %d
                         ORDER BY l.created_at DESC
                         LIMIT 20",
                        $edit_iscrizione_id
                    )
                );

                if (!empty($edit_iscrizione->responsabile_email)) {
                    $edit_duplicate_iscrizioni = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT id, tipo_iscrizione, stato, stato_pagamento, importo_totale_chf, riduzione_fratelli, numero_bambini, created_at
                             FROM {$registrations_table}
                             WHERE LOWER(responsabile_email) = LOWER(%s)
                               AND id <> %d
                             ORDER BY created_at DESC",
                            $edit_iscrizione->responsabile_email,
                            $edit_iscrizione_id
                        )
                    );
                }
            }
        }
    }
}

$active_filters = array_filter(array($filter_tipo, $filter_stato, $filter_pagamento, $filter_categoria, $filter_pratiche, $filter_stagione, $search_query));
$export_args = array(
    'action'   => 'act_export_iscrizioni_csv',
    '_wpnonce' => wp_create_nonce('act_export_iscrizioni_csv'),
);
if ($filter_tipo) {
    $export_args['tipo'] = $filter_tipo;
}
if ($filter_stato) {
    $export_args['stato'] = $filter_stato;
}
if ($filter_pagamento) {
    $export_args['pagamento'] = $filter_pagamento;
}
if ($filter_categoria) {
    $export_args['categoria'] = $filter_categoria;
}
if ($filter_pratiche) {
    $export_args['pratiche'] = $filter_pratiche;
}
if ($filter_stagione) {
    $export_args['stagione'] = $filter_stagione;
}
if ($search_query !== '') {
    $export_args['q'] = $search_query;
}
$export_url = add_query_arg($export_args, admin_url('admin-post.php', is_ssl() ? 'https' : 'http'));
?>

<style>
@media (max-width: 600px) {
    .page-area-segreteria #segreteria-edit,
    .page-area-segreteria #segreteria-edit .segreteria-dashboard-head,
    .page-area-segreteria #segreteria-edit .segreteria-head-actions,
    .page-area-segreteria #segreteria-edit .segreteria-detail-actions,
    .page-area-segreteria #segreteria-edit .segreteria-operational-summary,
    .page-area-segreteria #segreteria-edit .segreteria-summary-item,
    .page-area-segreteria #segreteria-edit .segreteria-edit-form,
    .page-area-segreteria #segreteria-edit .segreteria-edit-grid,
    .page-area-segreteria #segreteria-edit .segreteria-edit-card,
    .page-area-segreteria #segreteria-edit .segreteria-edit-fields,
    .page-area-segreteria #segreteria-edit .segreteria-edit-documents,
    .page-area-segreteria #segreteria-edit .segreteria-edit-actions,
    .page-area-segreteria #segreteria-edit .segreteria-fee-breakdown {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box !important;
    }

    .page-area-segreteria #segreteria-edit,
    .page-area-segreteria #segreteria-edit .segreteria-dashboard-head,
    .page-area-segreteria #segreteria-edit .segreteria-head-actions,
    .page-area-segreteria #segreteria-edit .segreteria-detail-actions,
    .page-area-segreteria #segreteria-edit .segreteria-operational-summary,
    .page-area-segreteria #segreteria-edit .segreteria-edit-form,
    .page-area-segreteria #segreteria-edit .segreteria-edit-grid,
    .page-area-segreteria #segreteria-edit .segreteria-edit-fields,
    .page-area-segreteria #segreteria-edit .segreteria-edit-actions,
    .page-area-segreteria #segreteria-edit .segreteria-quick-actions-detail {
        display: grid !important;
        grid-template-columns: 1fr !important;
    }

    .page-area-segreteria #segreteria-edit .segreteria-dashboard-head,
    .page-area-segreteria #segreteria-edit .segreteria-head-actions,
    .page-area-segreteria #segreteria-edit .segreteria-detail-actions,
    .page-area-segreteria #segreteria-edit .segreteria-edit-card {
        padding: 16px !important;
    }

    .page-area-segreteria #segreteria-edit .segreteria-detail-actions > *,
    .page-area-segreteria #segreteria-edit .segreteria-edit-fields > *,
    .page-area-segreteria #segreteria-edit .segreteria-edit-fields label,
    .page-area-segreteria #segreteria-edit .segreteria-edit-fields label.wide,
    .page-area-segreteria #segreteria-edit .segreteria-document-row,
    .page-area-segreteria #segreteria-edit .segreteria-file-replace,
    .page-area-segreteria #segreteria-edit .segreteria-documents-list,
    .page-area-segreteria #segreteria-edit .segreteria-log-list {
        grid-column: auto !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box !important;
    }

    .page-area-segreteria #segreteria-edit .segreteria-head-actions a,
    .page-area-segreteria #segreteria-edit .segreteria-head-actions button,
    .page-area-segreteria #segreteria-edit .segreteria-head-actions form,
    .page-area-segreteria #segreteria-edit .segreteria-quick-actions-detail button,
    .page-area-segreteria #segreteria-edit .segreteria-quick-actions-detail form,
    .page-area-segreteria #segreteria-edit .segreteria-edit-actions a,
    .page-area-segreteria #segreteria-edit .segreteria-edit-actions button,
    .page-area-segreteria #segreteria-edit input,
    .page-area-segreteria #segreteria-edit select,
    .page-area-segreteria #segreteria-edit textarea {
        width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box !important;
    }

    .page-area-segreteria #segreteria-edit .segreteria-head-actions,
    .page-area-segreteria #segreteria-edit .segreteria-quick-actions-detail,
    .page-area-segreteria #segreteria-edit .segreteria-edit-actions {
        align-items: stretch !important;
        justify-content: stretch !important;
        white-space: normal !important;
    }
}
</style>

<main id="primary" class="site-main page-area-segreteria">
    <section class="news-hero">
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url($hero_image_url); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="club-hero-fade"></div>

            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">AREA SEGRETERIA</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid white; margin: 20px 0;">
                <?php sport_theme_render_societa_submenu(); ?>
            </div>
        </div>
    </section>

    <div class="container area-segreteria-content" style="padding-top: 10px; padding-bottom: 60px;">
        <?php if (!is_user_logged_in()) : ?>
            <div class="login-wrapper" style="background-color: #111; border: 1px solid #333; border-top: 3px solid var(--c-primary); padding: 40px; max-width: 500px; margin: 0 auto 60px auto;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <i class="fas fa-lock" style="font-size: 40px; color: var(--c-primary); margin-bottom: 15px;"></i>
                    <h3 style="color: white; font-size: 24px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">ACCESSO SEGRETERIA</h3>
                </div>

                <?php
                if ( isset($_GET['login']) && $_GET['login'] == 'failed' ) {
                    echo '<p style="color: #ff4444; font-size: 14px; text-align: center; margin-bottom: 20px;">Credenziali non valide. Riprova.</p>';
                }
                ?>

                <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                    <div style="margin-bottom: 20px;">
                        <label for="user_login" style="display: block; color: white; margin-bottom: 8px; font-size: 13px;">Nome utente</label>
                        <input type="text" name="log" id="user_login" style="width: 100%; background: transparent; border: 1px solid #555; color: white; padding: 12px; font-size: 14px;" value="" size="20" required>
                    </div>
                    <div style="margin-bottom: 30px;">
                        <label for="user_pass" style="display: block; color: white; margin-bottom: 8px; font-size: 13px;">Password</label>
                        <input type="password" name="pwd" id="user_pass" style="width: 100%; background: transparent; border: 1px solid #555; color: white; padding: 12px; font-size: 14px;" value="" size="20" required>
                    </div>
                    <p class="forgetmenot" style="margin-bottom: 20px;">
                        <label for="rememberme" style="color: #aaa; font-size: 13px;"><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Ricordami</label>
                    </p>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" style="width: 100%; background-color: var(--c-primary); color: #000; border: none; padding: 14px; font-weight: 700; text-transform: uppercase; cursor: pointer; letter-spacing: 1px; transition: opacity 0.3s;" value="ACCEDI" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        <input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>">
                        <input type="hidden" name="testcookie" value="1">
                    </p>
                </form>
            </div>
        <?php elseif (!$can_access) : ?>
            <section style="max-width: 760px; margin: 0 auto; border: 2px solid #555; padding: 40px; background: #080808;">
                <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 18px; text-transform: uppercase;">Accesso non autorizzato</h2>
                <p class="text-white" style="font-size: 19px; line-height: 1.7;">Il tuo account non ha i permessi per accedere all'Area Segreteria.</p>
            </section>
        <?php else : ?>
            <?php if ($edit_iscrizione) : ?>
                <section id="segreteria-edit" class="segreteria-edit">
                    <?php $edit_has_payment_due = (float) ($edit_iscrizione->importo_totale_chf ?? 0) > 0; ?>
                    <div class="segreteria-dashboard-head">
                        <div>
                            <span class="segreteria-kicker">Modifica iscrizione</span>
                            <h2><?php echo esc_html('Iscrizione #' . $edit_iscrizione->id); ?></h2>
                        </div>
                        <div class="segreteria-head-actions">
                            <?php if ($edit_has_payment_due) : ?>
                                <a class="segreteria-export-link" data-payment-action="fattura" <?php echo $edit_iscrizione->metodo_pagamento !== 'fattura' ? 'style="display:none;"' : ''; ?> target="_blank" rel="noopener" href="<?php echo esc_url(function_exists('sport_theme_iscrizione_invoice_url') ? sport_theme_iscrizione_invoice_url($edit_iscrizione, false) : wp_nonce_url(add_query_arg(array('action' => 'act_iscrizione_invoice', 'iscrizione_id' => (int) $edit_iscrizione->id), admin_url('admin-post.php')), 'act_iscrizione_invoice_' . (int) $edit_iscrizione->id)); ?>">Fattura QR</a>
                                <form class="segreteria-invoice-form segreteria-invoice-form-head" data-payment-action="fattura" <?php echo $edit_iscrizione->metodo_pagamento !== 'fattura' ? 'style="display:none;"' : ''; ?> method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Inviare la fattura QR al genitore?');">
                                    <?php wp_nonce_field('act_send_iscrizione_invoice'); ?>
                                    <input type="hidden" name="action" value="act_send_iscrizione_invoice">
                                    <input type="hidden" name="iscrizione_id" value="<?php echo esc_attr((int) $edit_iscrizione->id); ?>">
                                    <button type="submit">Invia fattura</button>
                                </form>
                                <form class="segreteria-stripe-form segreteria-stripe-form-head" data-payment-action="stripe" <?php echo $edit_iscrizione->metodo_pagamento !== 'stripe' ? 'style="display:none;"' : ''; ?> method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Inviare il link di pagamento Stripe al genitore?');">
                                    <?php wp_nonce_field('act_send_stripe_payment'); ?>
                                    <input type="hidden" name="action" value="act_send_stripe_payment">
                                    <input type="hidden" name="iscrizione_id" value="<?php echo esc_attr((int) $edit_iscrizione->id); ?>">
                                    <button type="submit">Invia Stripe</button>
                                </form>
                                <?php
                                $stripe_invoice_link = !empty($edit_iscrizione->stripe_invoice_pdf) ? $edit_iscrizione->stripe_invoice_pdf : ($edit_iscrizione->stripe_invoice_url ?? '');
                                ?>
                                <?php if ($stripe_invoice_link) : ?>
                                    <a class="segreteria-export-link" data-payment-action="stripe" <?php echo $edit_iscrizione->metodo_pagamento !== 'stripe' ? 'style="display:none;"' : ''; ?> target="_blank" rel="noopener" href="<?php echo esc_url($stripe_invoice_link); ?>">Fattura Stripe</a>
                                <?php endif; ?>
                            <?php else : ?>
                                <span class="segreteria-discount-active-label">Nessun pagamento dovuto</span>
                            <?php endif; ?>
                            <form class="segreteria-inline-action-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Reinviare la conferma iscrizione al responsabile?');">
                                <?php wp_nonce_field('act_resend_iscrizione_confirmation'); ?>
                                <input type="hidden" name="action" value="act_resend_iscrizione_confirmation">
                                <input type="hidden" name="iscrizione_id" value="<?php echo esc_attr((int) $edit_iscrizione->id); ?>">
                                <button type="submit">Reinvia conferma</button>
                            </form>
                            <?php if ($edit_has_payment_due) : ?>
                                <form class="segreteria-inline-action-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Inviare un promemoria pagamento al responsabile?');">
                                    <?php wp_nonce_field('act_send_payment_reminder'); ?>
                                    <input type="hidden" name="action" value="act_send_payment_reminder">
                                    <input type="hidden" name="iscrizione_id" value="<?php echo esc_attr((int) $edit_iscrizione->id); ?>">
                                    <button type="submit">Promemoria pagamento</button>
                                </form>
                            <?php endif; ?>
                            <a class="segreteria-export-link" href="<?php echo esc_url(get_permalink() . '#segreteria-dashboard'); ?>">Torna alla dashboard</a>
                        </div>
                    </div>

                    <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
                        <div class="segreteria-edit-notice">Modifiche salvate correttamente.</div>
                    <?php endif; ?>
                    <?php if (isset($_GET['invoice_sent']) && $_GET['invoice_sent'] === '1') : ?>
                        <div class="segreteria-edit-notice">Fattura inviata correttamente al genitore.</div>
                    <?php elseif (isset($_GET['invoice_sent']) && $_GET['invoice_sent'] === '0') : ?>
                        <div class="segreteria-edit-notice segreteria-edit-notice-error">Fattura non inviata. Controlla email del responsabile e dati fattura.</div>
                    <?php endif; ?>
                    <?php if (isset($_GET['stripe_sent']) && $_GET['stripe_sent'] === '1') : ?>
                        <div class="segreteria-edit-notice">Link pagamento Stripe inviato correttamente al genitore.</div>
                    <?php elseif (isset($_GET['stripe_sent']) && $_GET['stripe_sent'] === '0') : ?>
                        <div class="segreteria-edit-notice segreteria-edit-notice-error">Link Stripe non inviato. Controlla configurazione Stripe, email e importo.</div>
                    <?php endif; ?>
                    <?php if (isset($_GET['confirmation_sent']) && $_GET['confirmation_sent'] === '1') : ?>
                        <div class="segreteria-edit-notice">Conferma iscrizione reinviata correttamente.</div>
                    <?php elseif (isset($_GET['confirmation_sent']) && $_GET['confirmation_sent'] === '0') : ?>
                        <div class="segreteria-edit-notice segreteria-edit-notice-error">Conferma non reinviata. Controlla email responsabile.</div>
                    <?php endif; ?>
                    <?php if (isset($_GET['payment_reminder_sent']) && $_GET['payment_reminder_sent'] === '1') : ?>
                        <div class="segreteria-edit-notice">Promemoria pagamento inviato correttamente.</div>
                    <?php elseif (isset($_GET['payment_reminder_sent']) && $_GET['payment_reminder_sent'] === '0') : ?>
                        <div class="segreteria-edit-notice segreteria-edit-notice-error">Promemoria non inviato. Controlla email o link pagamento disponibile.</div>
                    <?php endif; ?>
                    <?php if (!empty($edit_duplicate_iscrizioni)) : ?>
                        <div class="segreteria-edit-notice segreteria-edit-notice-warning">
                            Possibile secondo figlio: questa email è già presente in <?php echo esc_html(count($edit_duplicate_iscrizioni)); ?> altra/e pratica/e.
                            <?php foreach ($edit_duplicate_iscrizioni as $duplicate_iscrizione) : ?>
                                <a href="<?php echo esc_url(add_query_arg('edit_iscrizione', (int) $duplicate_iscrizione->id, get_permalink()) . '#segreteria-edit'); ?>">
                                    #<?php echo esc_html((int) $duplicate_iscrizione->id); ?> · <?php echo esc_html(mysql2date('d.m.Y', $duplicate_iscrizione->created_at)); ?> · CHF <?php echo esc_html(number_format((float) $duplicate_iscrizione->importo_totale_chf, 0, '.', "'")); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <section class="segreteria-detail-actions">
                        <div>
                            <span class="segreteria-kicker">Azioni rapide</span>
                            <h3>Gestione pratica</h3>
                            <p>Cambia rapidamente lo stato operativo dell'iscrizione. Le azioni che modificano lo stato inviano una email al responsabile.</p>
                        </div>
                        <div class="segreteria-quick-actions segreteria-quick-actions-detail">
                            <?php
                            $quick_actions = array(
                                'in_verifica' => 'In verifica',
                                'documenti_mancanti' => 'Documenti mancanti',
                                'confermata' => 'Conferma',
                                'pagato' => 'Pagamento ricevuto',
                            );
                            $active_discount = function_exists('sport_theme_iscrizione_discount_percentage')
                                ? sport_theme_iscrizione_discount_percentage($edit_iscrizione)
                                : (!empty($edit_iscrizione->sconto_meta_stagione) ? 50 : 0);
                            if ($active_discount !== 50) {
                                $quick_actions['sconto_50'] = 'Sconto 50%';
                            }
                            if ($active_discount !== 100) {
                                $quick_actions['sconto_100'] = 'Sconto 100%';
                            }
                            if ($active_discount) {
                                $quick_actions['rimuovi_sconto'] = 'Togli sconto';
                            }
                            ?>
                            <?php foreach ($quick_actions as $quick_key => $quick_label) : ?>
                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                    <?php wp_nonce_field('act_quick_iscrizione_action'); ?>
                                    <input type="hidden" name="action" value="act_quick_iscrizione_action">
                                    <input type="hidden" name="iscrizione_id" value="<?php echo esc_attr((int) $edit_iscrizione->id); ?>">
                                    <input type="hidden" name="quick_action" value="<?php echo esc_attr($quick_key); ?>">
                                    <button type="submit"><?php echo esc_html($quick_label); ?></button>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <?php
                    $edit_unassigned_children = 0;
                    foreach ($edit_children as $edit_child_check) {
                        if (empty($edit_child_check->categoria)) {
                            $edit_unassigned_children++;
                        }
                    }
                    $edit_payment_open = ($edit_iscrizione->stato_pagamento ?? '') !== 'pagato';
                    ?>
                    <section class="segreteria-operational-summary">
                        <div class="segreteria-summary-item <?php echo $edit_unassigned_children ? 'is-warning' : 'is-ok'; ?>">
                            <span>Categoria</span>
                            <strong><?php echo $edit_unassigned_children ? esc_html($edit_unassigned_children . ' da assegnare') : 'OK'; ?></strong>
                        </div>
                        <div class="segreteria-summary-item <?php echo $edit_payment_open ? 'is-warning' : 'is-ok'; ?>">
                            <span>Pagamento</span>
                            <strong><?php echo esc_html($edit_iscrizione->stato_pagamento ?: 'non_pagato'); ?></strong>
                        </div>
                        <div class="segreteria-summary-item <?php echo !empty($edit_duplicate_iscrizioni) ? 'is-warning' : 'is-ok'; ?>">
                            <span>Duplicati</span>
                            <strong><?php echo !empty($edit_duplicate_iscrizioni) ? esc_html(count($edit_duplicate_iscrizioni) . ' trovati') : 'Nessuno'; ?></strong>
                        </div>
                        <div class="segreteria-summary-item">
                            <span>Documenti</span>
                            <strong><?php echo esc_html(count($edit_document_rows ?? array())); ?></strong>
                        </div>
                    </section>

                    <form class="segreteria-edit-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                        <?php wp_nonce_field('act_update_iscrizione_detail'); ?>
                        <input type="hidden" name="action" value="act_update_iscrizione_detail">
                        <input type="hidden" name="iscrizione_id" value="<?php echo esc_attr((int) $edit_iscrizione->id); ?>">

                        <div class="segreteria-edit-grid">
                            <section class="segreteria-edit-card">
                                <h3>Pratica</h3>
                                <div class="segreteria-edit-fields">
                                    <label>Tipo iscrizione
                                        <select name="tipo_iscrizione">
                                            <option value="allievi" <?php selected($edit_iscrizione->tipo_iscrizione, 'allievi'); ?>>Allievi</option>
                                            <option value="scuola_calcio" <?php selected($edit_iscrizione->tipo_iscrizione, 'scuola_calcio'); ?>>Scuola Calcio</option>
                                        </select>
                                    </label>
                                    <label>Stagione sportiva
                                        <input type="text" name="stagione_sportiva" value="<?php echo esc_attr($edit_iscrizione->stagione_sportiva ?: (function_exists('sport_theme_current_sport_season') ? sport_theme_current_sport_season() : '')); ?>" placeholder="2026/2027">
                                    </label>
                                    <label>Stato
                                        <select name="stato">
                                            <?php foreach ($allowed_stati as $status_option) : ?>
                                                <option value="<?php echo esc_attr($status_option); ?>" <?php selected($edit_iscrizione->stato, $status_option); ?>><?php echo esc_html($status_labels[$status_option] ?? str_replace('_', ' ', $status_option)); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    <label>Metodo pagamento
                                        <select name="metodo_pagamento" data-payment-method-select>
                                            <option value="fattura" <?php selected($edit_iscrizione->metodo_pagamento, 'fattura'); ?>>Fattura</option>
                                            <option value="stripe" <?php selected($edit_iscrizione->metodo_pagamento, 'stripe'); ?>>Stripe</option>
                                        </select>
                                    </label>
                                    <label>Stato pagamento
                                        <select name="stato_pagamento">
                                            <option value="non_pagato" <?php selected($edit_iscrizione->stato_pagamento, 'non_pagato'); ?>>Non pagato</option>
                                            <option value="in_attesa" <?php selected($edit_iscrizione->stato_pagamento, 'in_attesa'); ?>>In attesa</option>
                                            <option value="pagato" <?php selected($edit_iscrizione->stato_pagamento, 'pagato'); ?>>Pagato</option>
                                            <option value="annullato" <?php selected($edit_iscrizione->stato_pagamento, 'annullato'); ?>>Annullato</option>
                                        </select>
                                    </label>
                                    <?php if ($active_discount) : ?>
                                        <label>Riduzione fratello/sorella
                                            <input type="text" value="Non cumulabile con lo sconto <?php echo esc_attr($active_discount); ?>%" readonly>
                                        </label>
                                    <?php else : ?>
                                        <label>Riduzione fratello/sorella
                                            <select name="riduzione_fratelli">
                                                <option value="0" <?php selected((int) ($edit_iscrizione->riduzione_fratelli ?? 0), 0); ?>>No</option>
                                                <option value="1" <?php selected((int) ($edit_iscrizione->riduzione_fratelli ?? 0), 1); ?>>Sì, applica - CHF 50</option>
                                            </select>
                                        </label>
                                    <?php endif; ?>
                                    <label>Totale quota
                                        <input type="text" value="CHF <?php echo esc_attr(number_format((float) ($edit_iscrizione->importo_totale_chf ?? 0), 0, '.', "'")); ?>" readonly>
                                    </label>
                                    <?php if (!empty($edit_children)) : ?>
                                        <div class="segreteria-fee-breakdown wide">
                                            <strong>Ripartizione quote</strong>
                                            <?php foreach (array_values($edit_children) as $fee_index => $fee_child) : ?>
                                                <?php
                                                $fee_amount = function_exists('sport_theme_get_iscrizione_child_amount')
                                                    ? sport_theme_get_iscrizione_child_amount($fee_child, $edit_iscrizione->tipo_iscrizione)
                                                    : ($edit_iscrizione->tipo_iscrizione === 'scuola_calcio' ? 150 : ($fee_index === 0 ? 300 : 250));
                                                $fee_name = trim($fee_child->nome . ' ' . $fee_child->cognome);
                                                ?>
                                                <span><?php echo esc_html(($fee_name ?: 'Bambino ' . ($fee_index + 1)) . ': CHF ' . number_format($fee_amount, 0, '.', "'")); ?></span>
                                            <?php endforeach; ?>
                                            <?php if (!empty($edit_iscrizione->riduzione_fratelli) && !$active_discount) : ?>
                                                <span>Riduzione fratello/sorella: - CHF 50</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </section>

                            <section class="segreteria-edit-card">
                                <h3>Genitore / Tutore</h3>
                                <div class="segreteria-edit-fields">
                                    <label>Responsabilità
                                        <select name="responsabilita_genitoriale">
                                            <option value="padre" <?php selected($edit_iscrizione->responsabilita_genitoriale, 'padre'); ?>>Padre</option>
                                            <option value="madre" <?php selected($edit_iscrizione->responsabilita_genitoriale, 'madre'); ?>>Madre</option>
                                            <option value="tutore_legale" <?php selected($edit_iscrizione->responsabilita_genitoriale, 'tutore_legale'); ?>>Tutore legale</option>
                                        </select>
                                    </label>
                                    <label>Nome
                                        <input type="text" name="responsabile_nome" value="<?php echo esc_attr($edit_iscrizione->responsabile_nome); ?>">
                                    </label>
                                    <label>Cognome
                                        <input type="text" name="responsabile_cognome" value="<?php echo esc_attr($edit_iscrizione->responsabile_cognome); ?>">
                                    </label>
                                    <label>Telefono
                                        <input type="text" name="responsabile_telefono" value="<?php echo esc_attr($edit_iscrizione->responsabile_telefono); ?>">
                                    </label>
                                    <label>Email
                                        <input type="email" name="responsabile_email" value="<?php echo esc_attr($edit_iscrizione->responsabile_email); ?>">
                                    </label>
                                </div>
                            </section>
                        </div>

                        <?php foreach (array_values($edit_children) as $child_position => $child) : ?>
                            <?php
                            $child_fee_amount = function_exists('sport_theme_get_iscrizione_child_amount')
                                ? sport_theme_get_iscrizione_child_amount($child, $edit_iscrizione->tipo_iscrizione)
                                : ($edit_iscrizione->tipo_iscrizione === 'scuola_calcio' ? 150 : ($child_position === 0 ? 300 : 250));
                            ?>
                            <section class="segreteria-edit-card segreteria-edit-child">
                                <h3><?php echo esc_html('Bambino ' . (int) $child->child_index . ' - ' . trim($child->nome . ' ' . $child->cognome)); ?></h3>
                                <div class="segreteria-edit-fields">
                                    <label>Quota allievo CHF
                                        <input type="number" name="children[<?php echo esc_attr((int) $child->id); ?>][quota_chf]" value="<?php echo esc_attr(number_format($child_fee_amount, 2, '.', '')); ?>" min="0" step="0.01">
                                    </label>
                                    <label>Nome
                                        <input type="text" name="children[<?php echo esc_attr((int) $child->id); ?>][nome]" value="<?php echo esc_attr($child->nome); ?>">
                                    </label>
                                    <label>Cognome
                                        <input type="text" name="children[<?php echo esc_attr((int) $child->id); ?>][cognome]" value="<?php echo esc_attr($child->cognome); ?>">
                                    </label>
                                    <label>Data nascita
                                        <input type="date" name="children[<?php echo esc_attr((int) $child->id); ?>][data_nascita]" value="<?php echo esc_attr($child->data_nascita); ?>">
                                    </label>
                                    <label>Nazionalità
                                        <input type="text" name="children[<?php echo esc_attr((int) $child->id); ?>][nazionalita]" value="<?php echo esc_attr($child->nazionalita); ?>">
                                    </label>
                                    <label>AVS
                                        <input type="text" name="children[<?php echo esc_attr((int) $child->id); ?>][avs]" value="<?php echo esc_attr($child->avs); ?>">
                                    </label>
                                    <label>Indirizzo
                                        <input type="text" name="children[<?php echo esc_attr((int) $child->id); ?>][indirizzo]" value="<?php echo esc_attr($child->indirizzo); ?>">
                                    </label>
                                    <label>CAP e città
                                        <input type="text" name="children[<?php echo esc_attr((int) $child->id); ?>][cap_citta]" value="<?php echo esc_attr($child->cap_citta); ?>">
                                    </label>
                                    <label>Email bambino
                                        <input type="email" name="children[<?php echo esc_attr((int) $child->id); ?>][email]" value="<?php echo esc_attr($child->email); ?>">
                                    </label>
                                    <label>Cellulare bambino
                                        <input type="text" name="children[<?php echo esc_attr((int) $child->id); ?>][cellulare]" value="<?php echo esc_attr($child->cellulare); ?>">
                                    </label>
                                    <label>Categoria assegnata
                                        <select name="children[<?php echo esc_attr((int) $child->id); ?>][categoria]">
                                            <?php foreach ($edit_category_options as $category_key => $category_label) : ?>
                                                <option value="<?php echo esc_attr($category_key); ?>" <?php selected($child->categoria ?? '', $category_key); ?>><?php echo esc_html($category_label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    <label>Allergie o medicinali
                                        <select name="children[<?php echo esc_attr((int) $child->id); ?>][salute_allergie_medicinali]">
                                            <option value="no" <?php selected($child->salute_allergie_medicinali, 'no'); ?>>No</option>
                                            <option value="si" <?php selected($child->salute_allergie_medicinali, 'si'); ?>>Sì</option>
                                        </select>
                                    </label>
                                    <label class="wide">Dettagli salute
                                        <textarea name="children[<?php echo esc_attr((int) $child->id); ?>][salute_dettagli]" rows="4"><?php echo esc_textarea($child->salute_dettagli); ?></textarea>
                                    </label>
                                    <label>Altro sport
                                        <select name="children[<?php echo esc_attr((int) $child->id); ?>][altro_sport]">
                                            <option value="no" <?php selected($child->altro_sport, 'no'); ?>>No</option>
                                            <option value="si" <?php selected($child->altro_sport, 'si'); ?>>Sì</option>
                                        </select>
                                    </label>
                                    <label>Società altro sport
                                        <input type="text" name="children[<?php echo esc_attr((int) $child->id); ?>][sport_societa]" value="<?php echo esc_attr($child->sport_societa); ?>">
                                    </label>
                                    <label>Giorni altro sport
                                        <input type="text" name="children[<?php echo esc_attr((int) $child->id); ?>][sport_giorni]" value="<?php echo esc_attr($child->sport_giorni); ?>">
                                    </label>
                                    <label>Tragitto autonomo
                                        <select name="children[<?php echo esc_attr((int) $child->id); ?>][tragitto_autonomo]">
                                            <option value="no" <?php selected($child->tragitto_autonomo, 'no'); ?>>No</option>
                                            <option value="si" <?php selected($child->tragitto_autonomo, 'si'); ?>>Sì</option>
                                        </select>
                                    </label>
                                    <label>Abile sport
                                        <select name="children[<?php echo esc_attr((int) $child->id); ?>][abile_sport]">
                                            <option value="si" <?php selected($child->abile_sport, 'si'); ?>>Sì</option>
                                            <option value="no" <?php selected($child->abile_sport, 'no'); ?>>No</option>
                                        </select>
                                    </label>
                                    <label>Tipo documento
                                        <select name="children[<?php echo esc_attr((int) $child->id); ?>][tipo_documento]">
                                            <option value="carta_identita" <?php selected($child->tipo_documento, 'carta_identita'); ?>>Carta identità</option>
                                            <option value="permesso_soggiorno" <?php selected($child->tipo_documento, 'permesso_soggiorno'); ?>>Permesso soggiorno</option>
                                            <option value="passaporto" <?php selected($child->tipo_documento, 'passaporto'); ?>>Passaporto</option>
                                        </select>
                                    </label>
                                </div>

                                <?php $child_documents = $edit_documents[(int) $child->child_index] ?? array(); ?>
                                <?php if (!empty($child_documents)) : ?>
                                    <div class="segreteria-edit-documents">
                                        <strong>Documenti bambino</strong>
                                        <div class="segreteria-documents-list">
                                            <?php foreach ($child_documents as $document) : ?>
                                                <?php
                                                $download_url = wp_nonce_url(
                                                    add_query_arg(array('action' => 'act_download_iscrizione_document', 'document_id' => (int) $document->id), admin_url('admin-post.php')),
                                                    'act_download_document_' . (int) $document->id
                                                );
                                                $doc_label = str_replace('_', ' ', $document->ruolo_file);
                                                $file_accept = $document->ruolo_file === 'foto_giocatore' ? 'image/*' : 'image/*,.pdf';
                                                ?>
                                                <div class="segreteria-document-row">
                                                    <a href="<?php echo esc_url($download_url); ?>"><?php echo esc_html($doc_label); ?></a>
                                                    <label class="segreteria-file-replace">
                                                        <span>Sostituisci</span>
                                                        <input type="file" name="replace_document_<?php echo esc_attr((int) $document->id); ?>" accept="<?php echo esc_attr($file_accept); ?>">
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </section>
                        <?php endforeach; ?>

                        <?php if (!empty($edit_documents[0])) : ?>
                            <section class="segreteria-edit-card">
                                <h3>Documenti pratica</h3>
                                <div class="segreteria-documents-list">
                                    <?php foreach ($edit_documents[0] as $document) : ?>
                                        <?php
                                        $download_url = wp_nonce_url(
                                            add_query_arg(array('action' => 'act_download_iscrizione_document', 'document_id' => (int) $document->id), admin_url('admin-post.php')),
                                            'act_download_document_' . (int) $document->id
                                        );
                                        $doc_label = str_replace('_', ' ', $document->ruolo_file);
                                        ?>
                                        <div class="segreteria-document-row">
                                            <a href="<?php echo esc_url($download_url); ?>"><?php echo esc_html($doc_label); ?></a>
                                            <label class="segreteria-file-replace">
                                                <span>Sostituisci</span>
                                                <input type="file" name="replace_document_<?php echo esc_attr((int) $document->id); ?>" accept="image/*,.pdf">
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <section class="segreteria-edit-card">
                            <h3>Note interne</h3>
                            <div class="segreteria-edit-fields">
                                <label class="wide">Annotazioni segreteria
                                    <textarea name="note_interne" rows="6" placeholder="Note private visibili solo alla segreteria"><?php echo esc_textarea($edit_iscrizione->note_interne ?? ''); ?></textarea>
                                </label>
                            </div>
                        </section>

                        <section class="segreteria-edit-card">
                            <h3>Storico modifiche</h3>
                            <?php if (!empty($edit_logs)) : ?>
                                <div class="segreteria-log-list">
                                    <?php foreach ($edit_logs as $log_entry) : ?>
                                        <article class="segreteria-log-entry">
                                            <strong><?php echo esc_html(str_replace('_', ' ', $log_entry->azione)); ?></strong>
                                            <span><?php echo esc_html(mysql2date('d.m.Y H:i', $log_entry->created_at)); ?> · <?php echo esc_html($log_entry->display_name ?: 'Sistema'); ?></span>
                                            <p><?php echo esc_html($log_entry->messaggio ?: '-'); ?></p>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <p class="segreteria-record-meta">Nessuna modifica registrata.</p>
                            <?php endif; ?>
                        </section>

                        <div class="segreteria-edit-actions">
                            <button type="submit">Salva modifiche</button>
                            <a href="<?php echo esc_url(get_permalink() . '#segreteria-dashboard'); ?>">Annulla</a>
                        </div>
                    </form>
                    <script>
                    (function(){
                        var paymentSelect = document.querySelector('[data-payment-method-select]');
                        if (!paymentSelect) return;

                        function syncPaymentActions() {
                            var selected = paymentSelect.value;
                            document.querySelectorAll('[data-payment-action]').forEach(function(action) {
                                action.style.display = action.getAttribute('data-payment-action') === selected ? '' : 'none';
                            });
                        }

                        paymentSelect.addEventListener('change', syncPaymentActions);
                        syncPaymentActions();
                    })();
                    </script>
                </section>
            <?php else : ?>
            <section id="segreteria-dashboard" class="segreteria-dashboard">
                <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
                    <div class="segreteria-edit-notice">Modifiche salvate correttamente.</div>
                <?php endif; ?>
                <?php if (isset($_GET['sconto_nuove_iscrizioni']) && $_GET['sconto_nuove_iscrizioni'] === 'salvato') : ?>
                    <div class="segreteria-edit-notice">Regola sconto nuove iscrizioni aggiornata.</div>
                <?php endif; ?>

                <div class="segreteria-dashboard-head">
                    <div>
                        <span class="segreteria-kicker">Gestione iscrizioni</span>
                        <h2>Dashboard Segreteria</h2>
                        <p>Controllo operativo delle iscrizioni Allievi e Scuola Calcio.</p>
                    </div>
                    <div class="segreteria-head-actions">
                        <span class="segreteria-sync-dot" aria-hidden="true"></span>
                        <span>Aggiornato ora</span>
                        <a class="segreteria-export-link" href="<?php echo esc_url($export_url); ?>">Scarica Excel</a>
                    </div>
                </div>

                <div class="segreteria-stats">
                    <article class="segreteria-stat-card stat-primary">
                        <span>Totale iscrizioni</span>
                        <strong><?php echo esc_html($totale_iscrizioni); ?></strong>
                        <small><?php echo esc_html($filtered_count); ?> nel filtro attuale</small>
                    </article>
                    <article class="segreteria-stat-card">
                        <span>Allievi totali</span>
                        <strong><?php echo esc_html($totale_allievi); ?></strong>
                        <small><?php echo esc_html($iscrizioni_allievi); ?> allievi · <?php echo esc_html($iscrizioni_scuola_calcio); ?> scuola calcio</small>
                    </article>
                    <article class="segreteria-stat-card">
                        <span>Da assegnare</span>
                        <strong><?php echo esc_html($iscrizioni_da_assegnare); ?></strong>
                        <small>Pratiche con almeno una categoria mancante</small>
                    </article>
                    <article class="segreteria-stat-card">
                        <span>Pagamenti aperti</span>
                        <strong><?php echo esc_html($iscrizioni_pagamento_aperto); ?></strong>
                        <small><?php echo esc_html($iscrizioni_pagamento_pagato); ?> pagati · <?php echo esc_html($iscrizioni_fattura); ?> fattura · <?php echo esc_html($iscrizioni_stripe); ?> Stripe</small>
                    </article>
                </div>

                <div class="segreteria-insights">
                    <div class="segreteria-insight">
                        <span>Da verificare</span>
                        <strong><?php echo esc_html($iscrizioni_da_verificare); ?></strong>
                    </div>
                    <div class="segreteria-insight">
                        <span>Confermate</span>
                        <strong><?php echo esc_html($iscrizioni_confermate); ?></strong>
                    </div>
                    <div class="segreteria-insight">
                        <span>Email duplicate</span>
                        <strong><?php echo esc_html($iscrizioni_duplicate); ?></strong>
                    </div>
                    <div class="segreteria-insight">
                        <span>Risultati lista</span>
                        <strong><?php echo esc_html($filtered_count); ?></strong>
                    </div>
                </div>

                <form class="segreteria-filters" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-top:20px;">
                    <input type="hidden" name="action" value="act_update_allievi_birthdate_cutoff">
                    <?php wp_nonce_field('act_update_allievi_birthdate_cutoff'); ?>
                    <div class="segreteria-filter-field">
                        <label for="allievi-birthdate-cutoff">Data di confine per le iscrizioni di Scuola Calcio</label>
                        <input id="allievi-birthdate-cutoff" type="date" name="allievi_birthdate_cutoff" value="<?php echo esc_attr($allievi_birthdate_cutoff); ?>">
                    </div>
                    <div class="segreteria-filter-actions">
                        <button type="submit">Salva regola</button>
                    </div>
                </form>

                <form class="segreteria-filters segreteria-discount-rule" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-top:14px;">
                    <input type="hidden" name="action" value="act_update_new_registrations_discount">
                    <?php wp_nonce_field('act_update_new_registrations_discount'); ?>
                    <div class="segreteria-filter-field">
                        <span class="segreteria-filter-label">Sconto 50% sulle nuove iscrizioni</span>
                        <label class="segreteria-toggle" for="new-registrations-discount-50">
                            <input id="new-registrations-discount-50" type="checkbox" name="discount_50_active" value="1" <?php checked($new_registrations_discount_50_active); ?>>
                            <span class="segreteria-toggle-track" aria-hidden="true"><span></span></span>
                            <strong><?php echo $new_registrations_discount_50_active ? 'Attivo' : 'Non attivo'; ?></strong>
                        </label>
                        <small>Si applica solo alle nuove pratiche e sostituisce la riduzione fratelli.</small>
                    </div>
                    <div class="segreteria-filter-actions">
                        <button type="submit">Salva sconto</button>
                    </div>
                </form>

                <form class="segreteria-filters" method="get" action="<?php echo esc_url(get_permalink()); ?>">
                    <div class="segreteria-filter-field segreteria-filter-search">
                        <label for="segreteria-q">Cerca</label>
                        <input id="segreteria-q" type="search" name="q" value="<?php echo esc_attr($search_query); ?>" placeholder="Nome, email, telefono, AVS o codice">
                    </div>
                    <div class="segreteria-filter-field">
                        <label for="segreteria-tipo">Tipo</label>
                        <select id="segreteria-tipo" name="tipo">
                            <option value="">Tutte</option>
                            <option value="allievi" <?php selected($filter_tipo, 'allievi'); ?>>Allievi</option>
                            <option value="scuola_calcio" <?php selected($filter_tipo, 'scuola_calcio'); ?>>Scuola Calcio</option>
                        </select>
                    </div>
                    <div class="segreteria-filter-field">
                        <label for="segreteria-stagione">Stagione</label>
                        <input id="segreteria-stagione" type="text" name="stagione" value="<?php echo esc_attr($filter_stagione); ?>" placeholder="<?php echo esc_attr(function_exists('sport_theme_current_sport_season') ? sport_theme_current_sport_season() : '2026/2027'); ?>">
                    </div>
                    <div class="segreteria-filter-field">
                        <label for="segreteria-categoria">Categoria</label>
                        <select id="segreteria-categoria" name="categoria">
                            <option value="">Tutte</option>
                            <option value="__unassigned" <?php selected($filter_categoria, '__unassigned'); ?>>Da assegnare</option>
                            <?php foreach ($category_options as $category_key => $category_label) : ?>
                                <?php if ($category_key === '') { continue; } ?>
                                <option value="<?php echo esc_attr($category_key); ?>" <?php selected($filter_categoria, $category_key); ?>><?php echo esc_html($category_label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="segreteria-filter-field">
                        <label for="segreteria-stato">Stato</label>
                        <select id="segreteria-stato" name="stato">
                            <option value="">Tutti</option>
                            <?php foreach ($allowed_stati as $status_option) : ?>
                                <option value="<?php echo esc_attr($status_option); ?>" <?php selected($filter_stato, $status_option); ?>><?php echo esc_html($status_labels[$status_option] ?? str_replace('_', ' ', $status_option)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="segreteria-filter-field">
                        <label for="segreteria-pagamento">Pagamento</label>
                        <select id="segreteria-pagamento" name="pagamento">
                            <option value="">Tutti</option>
                            <option value="fattura" <?php selected($filter_pagamento, 'fattura'); ?>>Fattura</option>
                            <option value="stripe" <?php selected($filter_pagamento, 'stripe'); ?>>Stripe</option>
                        </select>
                    </div>
                    <div class="segreteria-filter-field">
                        <label for="segreteria-pratiche">Pratiche</label>
                        <select id="segreteria-pratiche" name="pratiche">
                            <option value="">Tutte</option>
                            <option value="incomplete" <?php selected($filter_pratiche, 'incomplete'); ?>>Incomplete</option>
                            <option value="duplicate" <?php selected($filter_pratiche, 'duplicate'); ?>>Email duplicate</option>
                        </select>
                    </div>
                    <div class="segreteria-filter-actions">
                        <button type="submit">Filtra</button>
                        <?php if (!empty($active_filters)) : ?>
                            <a href="<?php echo esc_url(get_permalink()); ?>">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>

                <?php if (!$table_exists) : ?>
                    <div class="segreteria-empty-state">
                        <h3>Database iscrizioni non ancora inizializzato</h3>
                        <p>Le tabelle verranno create automaticamente al primo caricamento completo di WordPress.</p>
                    </div>
                <?php endif; ?>

                <div class="segreteria-table-card">
                    <div class="segreteria-table-head">
                        <div>
                            <h3>Iscrizioni ricevute</h3>
                            <p>Apri una pratica per modificare dati, documenti, note e azioni operative.</p>
                        </div>
                        <span><?php echo esc_html($filtered_count); ?> risultati</span>
                    </div>

                    <div class="segreteria-table-wrap">
                    <table class="segreteria-table">
                        <thead>
                            <tr>
                                <th>Iscrizione</th>
                                <th>Categoria</th>
                                <th>Stato</th>
                                <th>Pagamento</th>
                                <th>Data</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_iscrizioni)) : ?>
                                <?php foreach ($recent_iscrizioni as $iscrizione) : ?>
                                    <?php
                                    $tipo_label = $iscrizione->tipo_iscrizione === 'scuola_calcio' ? 'Scuola Calcio' : 'Allievi';
                                    $responsabile = trim($iscrizione->responsabile_nome . ' ' . $iscrizione->responsabile_cognome);
                                    $status_class = 'status-' . sanitize_html_class($iscrizione->stato);
                                    $payment_class = 'payment-' . sanitize_html_class($iscrizione->metodo_pagamento ?: 'none');
                                    $email_key = strtolower((string) $iscrizione->responsabile_email);
                                    $same_email_count = $email_key && isset($duplicate_email_counts[$email_key]) ? (int) $duplicate_email_counts[$email_key] : 0;
                                    $same_email_discount_applied = $email_key && !empty($duplicate_email_has_discount[$email_key]);
                                    $invoice_url = function_exists('sport_theme_iscrizione_invoice_url')
                                        ? sport_theme_iscrizione_invoice_url($iscrizione, false)
                                        : wp_nonce_url(
                                            add_query_arg(
                                                array(
                                                    'action' => 'act_iscrizione_invoice',
                                                    'iscrizione_id' => (int) $iscrizione->id,
                                                ),
                                                admin_url('admin-post.php')
                                            ),
                                            'act_iscrizione_invoice_' . (int) $iscrizione->id
                                        );
                                    $category_labels = array();
                                    foreach (array_filter(explode(',', (string) ($iscrizione->categorie ?? ''))) as $category_key) {
                                        $category_key = trim($category_key);
                                        $category_labels[] = $category_options[$category_key] ?? str_replace('_', ' ', $category_key);
                                    }
                                    ?>
                                    <tr>
                                        <td data-label="Iscrizione">
                                            <div class="segreteria-record-title"><?php echo esc_html($iscrizione->bambini ?: 'Iscrizione #' . $iscrizione->id); ?></div>
                                            <div class="segreteria-record-meta">#<?php echo esc_html($iscrizione->id); ?> · <?php echo esc_html((int) $iscrizione->numero_bambini); ?> bambino/i</div>
                                            <div class="segreteria-record-meta"><?php echo esc_html($responsabile ?: '-'); ?> · <?php echo esc_html($iscrizione->responsabile_email ?: '-'); ?></div>
                                            <?php if ($same_email_count > 1 && !$same_email_discount_applied) : ?>
                                                <div class="segreteria-family-alert">Possibile secondo figlio · verifica riduzione fratelli</div>
                                            <?php elseif ($same_email_count > 1) : ?>
                                                <div class="segreteria-family-alert segreteria-family-alert-muted">Email condivisa con altra pratica</div>
                                            <?php endif; ?>
                                            <div class="segreteria-record-meta"><?php echo esc_html($tipo_label); ?> · <?php echo esc_html($iscrizione->stagione_sportiva ?: (function_exists('sport_theme_current_sport_season') ? sport_theme_current_sport_season() : '')); ?></div>
                                        </td>
                                        <td data-label="Categoria">
                                            <span class="segreteria-record-title"><?php echo esc_html($category_labels ? implode(', ', $category_labels) : 'Da assegnare'); ?></span>
                                        </td>
                                        <td data-label="Stato">
                                            <span class="segreteria-status <?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_labels[$iscrizione->stato] ?? $iscrizione->stato); ?></span>
                                        </td>
                                        <td data-label="Pagamento">
                                            <span class="segreteria-payment <?php echo esc_attr($payment_class); ?>"><?php echo esc_html($iscrizione->metodo_pagamento ?: 'Da definire'); ?></span>
                                            <span class="segreteria-record-meta"><?php echo esc_html($iscrizione->stato_pagamento ?: 'non_pagato'); ?></span>
                                            <?php $discount_percentage = function_exists('sport_theme_iscrizione_discount_percentage') ? sport_theme_iscrizione_discount_percentage($iscrizione) : (!empty($iscrizione->sconto_meta_stagione) ? 50 : 0); ?>
                                            <?php if (!empty($iscrizione->riduzione_fratelli) && $discount_percentage === 0) : ?>
                                                <span class="segreteria-record-meta">Riduzione fratelli - CHF 50</span>
                                            <?php endif; ?>
                                            <?php if ($discount_percentage) : ?>
                                                <span class="segreteria-record-meta segreteria-discount-active-label">Sconto -<?php echo esc_html($discount_percentage); ?>%</span>
                                            <?php endif; ?>
                                            <span class="segreteria-record-meta">CHF <?php echo esc_html(number_format((float) $iscrizione->importo_totale_chf, 0, '.', "'")); ?></span>
                                        </td>
                                        <td data-label="Data">
                                            <?php echo esc_html(mysql2date('d.m.Y', $iscrizione->created_at)); ?><br>
                                            <span class="segreteria-record-meta"><?php echo esc_html(mysql2date('H:i', $iscrizione->created_at)); ?></span>
                                        </td>
                                        <td data-label="Azioni">
                                            <div class="segreteria-row-actions">
                                                <a class="segreteria-edit-link" href="<?php echo esc_url(add_query_arg('edit_iscrizione', (int) $iscrizione->id, get_permalink()) . '#segreteria-edit'); ?>">Apri</a>
                                                <?php if ((float) $iscrizione->importo_totale_chf <= 0) : ?>
                                                    <span class="segreteria-record-meta segreteria-discount-active-label">Nessun pagamento dovuto</span>
                                                <?php elseif ($iscrizione->metodo_pagamento === 'fattura') : ?>
                                                    <a class="segreteria-edit-link" target="_blank" rel="noopener" href="<?php echo esc_url($invoice_url); ?>">Fattura QR</a>
                                                    <form class="segreteria-invoice-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Inviare la fattura QR al genitore?');">
                                                        <?php wp_nonce_field('act_send_iscrizione_invoice'); ?>
                                                        <input type="hidden" name="action" value="act_send_iscrizione_invoice">
                                                        <input type="hidden" name="iscrizione_id" value="<?php echo esc_attr((int) $iscrizione->id); ?>">
                                                        <button type="submit">Invia fattura</button>
                                                    </form>
                                                <?php elseif ($iscrizione->metodo_pagamento === 'stripe') : ?>
                                                    <?php $stripe_invoice_link = !empty($iscrizione->stripe_invoice_pdf) ? $iscrizione->stripe_invoice_pdf : ($iscrizione->stripe_invoice_url ?? ''); ?>
                                                    <?php if ($stripe_invoice_link) : ?>
                                                        <a class="segreteria-edit-link" target="_blank" rel="noopener" href="<?php echo esc_url($stripe_invoice_link); ?>">Fattura Stripe</a>
                                                    <?php endif; ?>
                                                    <form class="segreteria-stripe-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Inviare il link di pagamento Stripe al genitore?');">
                                                        <?php wp_nonce_field('act_send_stripe_payment'); ?>
                                                        <input type="hidden" name="action" value="act_send_stripe_payment">
                                                        <input type="hidden" name="iscrizione_id" value="<?php echo esc_attr((int) $iscrizione->id); ?>">
                                                        <button type="submit">Invia Stripe</button>
                                                    </form>
                                                <?php endif; ?>
                                                <form class="segreteria-delete-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Eliminare definitivamente questa iscrizione e i documenti collegati?');">
                                                    <?php wp_nonce_field('act_delete_iscrizione'); ?>
                                                    <input type="hidden" name="action" value="act_delete_iscrizione">
                                                    <input type="hidden" name="iscrizione_id" value="<?php echo esc_attr((int) $iscrizione->id); ?>">
                                                    <button type="submit">Elimina</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="segreteria-empty-row">
                                            <h3>Nessuna iscrizione trovata</h3>
                                            <p><?php echo !empty($active_filters) ? 'Prova a modificare i filtri di ricerca.' : 'La dashboard è pronta per ricevere i moduli.'; ?></p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php if ($total_pages > 1) : ?>
                        <?php
                        $pagination_args = array();
                        if ($filter_tipo) {
                            $pagination_args['tipo'] = $filter_tipo;
                        }
                        if ($filter_stato) {
                            $pagination_args['stato'] = $filter_stato;
                        }
                        if ($filter_pagamento) {
                            $pagination_args['pagamento'] = $filter_pagamento;
                        }
                        if ($filter_categoria) {
                            $pagination_args['categoria'] = $filter_categoria;
                        }
                        if ($filter_pratiche) {
                            $pagination_args['pratiche'] = $filter_pratiche;
                        }
                        if ($filter_stagione) {
                            $pagination_args['stagione'] = $filter_stagione;
                        }
                        if ($search_query !== '') {
                            $pagination_args['q'] = $search_query;
                        }
                        $previous_url = add_query_arg(array_merge($pagination_args, array('pagina' => max(1, $current_page - 1))), get_permalink()) . '#segreteria-dashboard';
                        $next_url = add_query_arg(array_merge($pagination_args, array('pagina' => min($total_pages, $current_page + 1))), get_permalink()) . '#segreteria-dashboard';
                        ?>
                        <nav class="segreteria-pagination" aria-label="Paginazione iscrizioni">
                            <span>Pagina <?php echo esc_html($current_page); ?> di <?php echo esc_html($total_pages); ?></span>
                            <div>
                                <?php if ($current_page > 1) : ?>
                                    <a href="<?php echo esc_url($previous_url); ?>">Indietro</a>
                                <?php else : ?>
                                    <span class="disabled">Indietro</span>
                                <?php endif; ?>
                                <?php if ($current_page < $total_pages) : ?>
                                    <a href="<?php echo esc_url($next_url); ?>">Avanti</a>
                                <?php else : ?>
                                    <span class="disabled">Avanti</span>
                                <?php endif; ?>
                            </div>
                        </nav>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<?php get_footer('societa'); ?>
