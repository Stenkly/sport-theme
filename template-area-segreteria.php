<?php
/**
 * Template Name: Area Segreteria
 *
 * @package Sport_Theme
 */

get_header('societa');

$hero_image_url = sport_theme_get_societa_home_hero_url();
$can_access = is_user_logged_in() && sport_theme_can_access_segreteria();

$iscrizioni_count = post_type_exists('iscrizione')
    ? wp_count_posts('iscrizione')
    : null;

$totale_iscrizioni = 0;
if ($iscrizioni_count) {
    foreach ((array) $iscrizioni_count as $status_count) {
        $totale_iscrizioni += (int) $status_count;
    }
}

$recent_iscrizioni = post_type_exists('iscrizione')
    ? new WP_Query([
        'post_type'      => 'iscrizione',
        'posts_per_page' => 8,
        'post_status'    => ['publish', 'pending', 'draft'],
        'orderby'        => 'date',
        'order'          => 'DESC',
    ])
    : null;
?>

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
            <section class="segreteria-dashboard">
                <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;">Dashboard Iscrizioni</h2>

                <div class="segreteria-stats" style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; margin-bottom: 34px;">
                    <div style="border: 2px solid #555; padding: 24px; background: #080808;">
                        <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: #aaa; margin-bottom: 8px;">Totale iscrizioni</p>
                        <strong style="display: block; color: var(--c-primary); font-size: 42px; line-height: 1;"><?php echo esc_html($totale_iscrizioni); ?></strong>
                    </div>
                    <div style="border: 2px solid #555; padding: 24px; background: #080808;">
                        <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: #aaa; margin-bottom: 8px;">Da verificare</p>
                        <strong style="display: block; color: var(--c-primary); font-size: 42px; line-height: 1;"><?php echo $iscrizioni_count ? esc_html((int) ($iscrizioni_count->pending ?? 0)) : '0'; ?></strong>
                    </div>
                    <div style="border: 2px solid #555; padding: 24px; background: #080808;">
                        <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: #aaa; margin-bottom: 8px;">Confermate</p>
                        <strong style="display: block; color: var(--c-primary); font-size: 42px; line-height: 1;"><?php echo $iscrizioni_count ? esc_html((int) ($iscrizioni_count->publish ?? 0)) : '0'; ?></strong>
                    </div>
                </div>

                <div style="border: 2px solid #555; background: #080808; overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; color: #fff; font-size: 15px;">
                        <thead>
                            <tr style="border-bottom: 2px solid #555; color: var(--c-primary); text-transform: uppercase;">
                                <th style="text-align: left; padding: 16px;">Iscrizione</th>
                                <th style="text-align: left; padding: 16px;">Data</th>
                                <th style="text-align: left; padding: 16px;">Stato</th>
                                <th style="text-align: left; padding: 16px;">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_iscrizioni && $recent_iscrizioni->have_posts()) : ?>
                                <?php while ($recent_iscrizioni->have_posts()) : $recent_iscrizioni->the_post(); ?>
                                    <tr style="border-bottom: 1px solid #333;">
                                        <td style="padding: 16px;"><?php the_title(); ?></td>
                                        <td style="padding: 16px;"><?php echo esc_html(get_the_date('d.m.Y')); ?></td>
                                        <td style="padding: 16px;"><?php echo esc_html(get_post_status()); ?></td>
                                        <td style="padding: 16px;"><a class="text-primary" href="<?php echo esc_url(get_edit_post_link(get_the_ID())); ?>">Apri</a></td>
                                    </tr>
                                <?php endwhile; wp_reset_postdata(); ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" style="padding: 22px; color: #aaa;">Nessuna iscrizione registrata. La dashboard e pronta per essere collegata ai moduli.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php get_footer('societa'); ?>
