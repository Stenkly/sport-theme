<?php
/**
 * Template Name: Pagina Iscriviti (Società)
 *
 * @package Sport_Theme
 */

get_header('societa');

while ( have_posts() ) : the_post();
?>

<section class="news-hero">
    <?php
    $hero_image_url = has_post_thumbnail()
        ? get_the_post_thumbnail_url( get_the_ID(), 'full' )
        : 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=2000&auto=format&fit=crop';
    ?>
    <div class="club-hero-wrapper">
        <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="width:100%; height:100%; object-fit:cover; object-position:center top; display:block;" alt="<?php echo esc_attr(get_the_title()); ?>">
        <div style="position:absolute; bottom:0; left:0; width:100%; height:70%; background:linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events:none;"></div>
        <div class="news-hero-content container" style="position:absolute; bottom:40px; left:0; right:0; text-align:left;">
            <h1 class="club-hero-title"><?php the_title(); ?></h1>
        </div>
    </div>
</section>

<main id="primary" class="site-main container" style="padding-top: 50px; padding-bottom: 80px;">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry-content" style="color:#fff; font-size:16px; line-height:1.7;">
            <?php the_content(); ?>
        </div>
    </article>
</main>

<?php
endwhile;
get_footer('societa');
?>
