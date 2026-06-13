<?php
/**
 * Template Name: Sponsor AC Taverne
 *
 * @package Sport_Theme
 */

get_header('societa');

function sport_theme_render_societa_sponsor_grid( $level ) {
    $query = new WP_Query([
        'post_type'      => 'sponsor',
        'posts_per_page' => -1,
        'meta_query'     => [
            'relation' => 'AND',
            [
                'key'     => '_livello_sponsor',
                'value'   => $level,
                'compare' => '=',
            ],
            [
                'relation' => 'OR',
                [
                    'key'     => '_destinazione_sponsor',
                    'value'   => 'societa',
                    'compare' => '=',
                ],
                [
                    'key'     => '_destinazione_sponsor',
                    'value'   => 'entrambi',
                    'compare' => '=',
                ],
            ],
        ],
    ]);

    if ( ! $query->have_posts() ) {
        echo '<p class="societa-sponsor-empty">Nessuno sponsor inserito in questa sezione.</p>';
        return;
    }

    echo '<div class="societa-sponsor-grid">';
    while ( $query->have_posts() ) {
        $query->the_post();
        $site = get_post_meta( get_the_ID(), '_sito_url', true );
        $logo = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
        $tag_open = $site ? '<a href="' . esc_url( $site ) . '" target="_blank" rel="noopener" class="societa-sponsor-card">' : '<div class="societa-sponsor-card">';
        $tag_close = $site ? '</a>' : '</div>';

        echo $tag_open;
        if ( $logo ) {
            echo '<img src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_the_title() ) . '">';
        } else {
            echo '<span>' . esc_html( get_the_title() ) . '</span>';
        }
        echo $tag_close;
    }
    echo '</div>';
    wp_reset_postdata();
}

while ( have_posts() ) : the_post();
    $hero_image_url = has_post_thumbnail()
        ? get_the_post_thumbnail_url( get_the_ID(), 'full' )
        : get_template_directory_uri() . '/assets/images/campo-taverne.jpg';
?>

<main id="primary" class="site-main page-sponsor-societa">
    <section class="news-hero">
        <div class="news-hero-wrapper">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" alt="<?php echo esc_attr( get_the_title() ); ?>">
            <div class="club-hero-fade"></div>
            <div class="news-hero-content container">
                <h1 class="text-white">Sponsor</h1>
                <hr>
            </div>
        </div>
    </section>

    <section class="container societa-sponsor-intro">
        <p>Le aziende partner sostengono la crescita dell'AC Taverne e contribuiscono allo sviluppo sportivo, sociale e formativo del club.</p>
    </section>

    <section class="container societa-sponsor-section">
        <h2>Main Sponsor</h2>
        <?php sport_theme_render_societa_sponsor_grid( 'main' ); ?>
    </section>

    <section class="container societa-sponsor-section">
        <h2>Partner</h2>
        <?php sport_theme_render_societa_sponsor_grid( 'partner' ); ?>
    </section>
</main>

<?php
endwhile;
get_footer('societa');
?>
