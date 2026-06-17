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
        return false;
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
    return true;
}

while ( have_posts() ) : the_post();
    $hero_image_url = sport_theme_get_societa_home_hero_url();
    $hero_sottotitolo = get_post_meta( get_the_ID(), '_sponsor_hero_sottotitolo', true ) ?: "INSIEME ALLE AZIENDE CHE SOSTENGONO L'AC TAVERNE.";
?>

<main id="primary" class="site-main page-sponsor-societa">
    <section class="news-hero">
        <div class="news-hero-wrapper">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" alt="<?php echo esc_attr( get_the_title() ); ?>">
            <div class="club-hero-fade"></div>
            <div class="news-hero-content container">
                <h1 class="text-white">Sponsor</h1>
                <hr>
                <p class="text-white hero-subtitle"><?php echo esc_html( $hero_sottotitolo ); ?></p>
            </div>
        </div>
    </section>

    <?php
    ob_start();
    $has_main_sponsors = sport_theme_render_societa_sponsor_grid( 'main' );
    $main_sponsor_html = ob_get_clean();
    if ( $has_main_sponsors ) :
    ?>
        <section class="container societa-sponsor-section">
            <?php echo $main_sponsor_html; ?>
        </section>
    <?php endif; ?>

    <?php
    ob_start();
    $has_partner_sponsors = sport_theme_render_societa_sponsor_grid( 'partner' );
    $partner_sponsor_html = ob_get_clean();
    if ( $has_partner_sponsors ) :
    ?>
        <section class="container societa-sponsor-section">
            <?php echo $partner_sponsor_html; ?>
        </section>
    <?php endif; ?>
</main>

<?php
endwhile;
get_footer('societa');
?>
