<?php
/**
 * Template Name: Area Riservata
 *
 * @package Sport_Theme
 */

// Se l'utente non è loggato, reindirizza al login o mostra un form custom.
if ( ! is_user_logged_in() ) {
    get_header();
    ?>
    <main id="primary" class="site-main container">
        <section class="restricted-access text-center">
            <h1>Accesso Riservato</h1>
            <p>Questa sezione è riservata esclusivamente agli allenatori.</p>
            <p>Effettua il login per accedere.</p>
            <div class="login-form-container">
                <?php
                wp_login_form( array(
                    'redirect'       => get_permalink(),
                    'form_id'        => 'loginform-reserved',
                    'label_username' => __( 'Nome utente o Email' ),
                    'label_password' => __( 'Password' ),
                    'label_remember' => __( 'Ricordami' ),
                    'label_log_in'   => __( 'Accedi' ),
                ) );
                ?>
            </div>
        </section>
    </main>
    <?php
    get_footer();
    exit;
}

// Controllo ruoli (opzionale): Se vogliamo che SOLO l'allenatore e l'admin accedano.
$current_user = wp_get_current_user();
$allowed_roles = array( 'administrator', 'allenatore' );
$user_has_access = array_intersect( $allowed_roles, $current_user->roles );

if ( empty( $user_has_access ) ) {
    get_header();
    ?>
    <main id="primary" class="site-main container">
        <section class="restricted-access text-center">
            <h1>Accesso Negato</h1>
            <p>Siamo spiacenti, il tuo account non ha i permessi necessari per visualizzare quest'area.</p>
            <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Esci e prova con un altro account</a>
        </section>
    </main>
    <?php
    get_footer();
    exit;
}

get_header();
?>

	<main id="primary" class="site-main container">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    <p class="reserved-badge">Area Allenatori</p>
                </header><!-- .entry-header -->

                <div class="entry-content">
                    <?php
                    // Qui verranno caricati i contenuti protetti e la documentazione tecnica inseriti dall'editor WP.
                    the_content();
                    ?>
                </div><!-- .entry-content -->
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

<?php
get_footer();
