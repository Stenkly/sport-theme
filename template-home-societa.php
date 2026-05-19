<?php
/**
 * Template Name: Home Società
 *
 * La seconda "anima" del sito: sezione Club / Società con menu autonomo.
 * Adesso utilizza esattamente le stesse proporzioni (grid, container, sezioni) della Prima Squadra.
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-home-societa">

    <!-- HERO IMMAGINE (Stesse proporzioni di Prima Squadra: 50vh, font giganti) -->
    <section class="news-hero">
        <div class="news-hero-wrapper" style="position: relative; width: 90%; margin: 0 auto; height: 95vh; background-color: var(--c-black); overflow: hidden;">
            <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1920&auto=format&fit=crop" class="hero-image" style="width: 100%; height: 100%; object-fit: contain; object-position: center bottom; display: block; animation: heroFadeIn 1.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;" alt="AC Taverne squadra" loading="eager">
            
            <!-- Sfumatura nera in basso per fondersi con il resto della pagina -->
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 10%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <style>
            @keyframes heroFadeIn {
                from { opacity: 0; transform: scale(1.02); }
                to { opacity: 1; transform: scale(1); }
            }
            </style>
            <div class="news-hero-content container" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin-bottom: 25px; letter-spacing: 2px;">
                    AC TAVERNE:<br>PASSIONE, VALORI E<br>FUTURO DAL 1950
                </h1>
                <a href="<?php echo esc_url( site_url('/iscritti') ); ?>" class="btn-sm btn-primary" style="display:inline-block; border: 2px solid var(--c-primary); background: transparent; color: var(--c-primary); font-size: 15px; padding: 12px 35px;" onmouseover="this.style.background='var(--c-primary)'; this.style.color='var(--c-black)';" onmouseout="this.style.background='transparent'; this.style.color='var(--c-primary)';">ISCRIVITI ORA</a>
            </div>
        </div>
    </section>

    <!-- CATEGORIE (Stessa larghezza container 1400px, 3 colonne) -->
    <section class="ps-section container" style="padding-top: 50px;">
        <div class="ps-grid grid-3">
            <a href="<?php echo esc_url( site_url('/scuola-calcio') ); ?>" class="hs-cat-item" style="text-decoration: none; text-align: center; display: block; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="hs-cat-img" style="width: 100%; aspect-ratio: 16/10; overflow: hidden; margin-bottom: 15px;">
                    <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?q=80&w=600&auto=format&fit=crop" alt="Scuola Calcio" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <span class="hs-cat-label" style="color: var(--c-white); font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; padding-bottom: 8px; border-bottom: 3px solid var(--c-primary); display: inline-block;">SCUOLA CALCIO</span>
            </a>
            <a href="<?php echo esc_url( site_url('/allievi') ); ?>" class="hs-cat-item" style="text-decoration: none; text-align: center; display: block; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="hs-cat-img" style="width: 100%; aspect-ratio: 16/10; overflow: hidden; margin-bottom: 15px;">
                    <img src="https://images.unsplash.com/photo-1508344928928-7137b29de218?q=80&w=600&auto=format&fit=crop" alt="Allievi" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <span class="hs-cat-label" style="color: var(--c-white); font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; padding-bottom: 8px; border-bottom: 3px solid var(--c-primary); display: inline-block;">ALLIEVI</span>
            </a>
            <a href="<?php echo esc_url( site_url('/femminile') ); ?>" class="hs-cat-item" style="text-decoration: none; text-align: center; display: block; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="hs-cat-img" style="width: 100%; aspect-ratio: 16/10; overflow: hidden; margin-bottom: 15px;">
                    <img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?q=80&w=600&auto=format&fit=crop" alt="Femminile" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <span class="hs-cat-label" style="color: var(--c-white); font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; padding-bottom: 8px; border-bottom: 3px solid var(--c-primary); display: inline-block;">FEMMINILE</span>
            </a>
        </div>
        <div style="width: 100%; height: 1px; background-color: rgba(255,255,255,0.1); margin-top: 45px;"></div>
    </section>

    <!-- NEWS (Design stile Prima Squadra ma con colori mockup) -->
    <section class="ps-section container">
        <div class="section-header" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; margin-bottom: 30px;">
            <h2 class="section-title text-white">NEWS</h2>
            <a href="<?php echo esc_url( site_url('/news') ); ?>" style="color: var(--c-primary); font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; text-decoration: none;">SCOPRI</a>
        </div>

        <div class="ps-grid grid-3">
            <?php
            $news_q = new WP_Query(array(
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ));

            if ( $news_q->have_posts() ) :
                while ( $news_q->have_posts() ) : $news_q->the_post();
                    $thumb = has_post_thumbnail()
                        ? get_the_post_thumbnail_url(get_the_ID(), 'medium_large')
                        : 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=600';
            ?>
                <div class="news-card-hs" style="background-color: var(--c-gray); overflow: hidden; position: relative;">
                    <div style="position: relative; width: 100%; aspect-ratio: 16/10; overflow: hidden;">
                        <span style="position: absolute; top: 15px; left: 15px; background-color: rgba(0,0,0,0.7); color: var(--c-white); font-size: 18px; font-weight: 700; padding: 6px 14px; z-index: 2;"><?php echo get_the_date('d.m'); ?></span>
                        <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="padding: 22px 20px 25px;">
                        <h3 class="text-white" style="font-size: 17px; font-weight: 700; text-transform: uppercase; line-height: 1.4; margin-bottom: 18px;"><?php echo wp_trim_words(get_the_title(), 7, '...'); ?></h3>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn-sm btn-primary" style="display:inline-block; font-size: 12px; padding: 10px 20px;">LEGGI ARTICOLO</a>
                    </div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                for ($i = 0; $i < 3; $i++) :
            ?>
                <div class="news-card-hs" style="background-color: var(--c-gray); overflow: hidden; position: relative;">
                    <div style="position: relative; width: 100%; aspect-ratio: 16/10; overflow: hidden;">
                        <span style="position: absolute; top: 15px; left: 15px; background-color: rgba(0,0,0,0.7); color: var(--c-white); font-size: 18px; font-weight: 700; padding: 6px 14px; z-index: 2;">28.02</span>
                        <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=600&auto=format&fit=crop" alt="News" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="padding: 22px 20px 25px;">
                        <h3 class="text-white" style="font-size: 17px; font-weight: 700; text-transform: uppercase; line-height: 1.4; margin-bottom: 18px;">SI È CONCLUSO OPENAIR.<br>SIAMO LIETI DI...</h3>
                        <a href="#" class="btn-sm btn-primary" style="display:inline-block; font-size: 12px; padding: 10px 20px;">LEGGI ARTICOLO</a>
                    </div>
                </div>
            <?php
                endfor;
            endif;
            ?>
        </div>
        
        <div class="carousel-nav" style="justify-content: center; gap: 15px; padding-top: 35px;">
            <span class="nav-arrow text-primary"><i class="fa-solid fa-chevron-left"></i></span>
            <span class="nav-dots"><i class="fa-solid fa-circle active"></i><i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i></span>
            <span class="nav-arrow text-primary"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
    </section>

    <!-- EVENTI -->
    <section class="ps-section container">
        <div class="section-header" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; margin-bottom: 30px;">
            <h2 class="section-title text-white">EVENTI</h2>
            <a href="<?php echo esc_url( site_url('/eventi') ); ?>" style="color: var(--c-primary); font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; text-decoration: none;">SCOPRI</a>
        </div>

        <div class="ps-grid grid-3">
            <?php
            $eventi_q = new WP_Query(array(
                'post_type'      => 'evento',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ));

            if ( $eventi_q->have_posts() ) :
                while ( $eventi_q->have_posts() ) : $eventi_q->the_post();
                    $thumb = has_post_thumbnail()
                        ? get_the_post_thumbnail_url(get_the_ID(), 'medium_large')
                        : 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=600';
                    $data_ev = get_post_meta(get_the_ID(), '_data_evento', true);
                    $date_display = $data_ev ? date('d.m', strtotime($data_ev)) : get_the_date('d.m');
            ?>
                <div class="news-card-hs" style="background-color: var(--c-gray); overflow: hidden; position: relative;">
                    <div style="position: relative; width: 100%; aspect-ratio: 4/3; overflow: hidden;">
                        <span style="position: absolute; top: 15px; left: 15px; background-color: rgba(0,0,0,0.8); border-left: 4px solid var(--c-primary); color: var(--c-white); font-size: 18px; font-weight: 700; padding: 6px 14px; z-index: 2;"><?php echo $date_display; ?></span>
                        <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; inset: 0; background: rgba(242, 227, 2, 0.25); pointer-events: none;"></div>
                    </div>
                    <div style="padding: 22px 20px 25px;">
                        <h3 class="text-white" style="font-size: 17px; font-weight: 700; text-transform: uppercase; line-height: 1.4; margin-bottom: 20px;"><?php echo esc_html(get_the_title()); ?></h3>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" style="color: var(--c-white); font-size: 14px; font-weight: 700; text-transform: uppercase; text-decoration: none; letter-spacing: 1px;">SCOPRI &nbsp;<span style="opacity:0.5;">|</span></a>
                    </div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                $evento_imgs = array(
                    'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1508344928928-7137b29de218?q=80&w=600&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1553147573-0ff7d2b45053?q=80&w=600&auto=format&fit=crop',
                );
                for ($i = 0; $i < 3; $i++) :
            ?>
                <div class="news-card-hs" style="background-color: var(--c-gray); overflow: hidden; position: relative;">
                    <div style="position: relative; width: 100%; aspect-ratio: 4/3; overflow: hidden;">
                        <span style="position: absolute; top: 15px; left: 15px; background-color: rgba(0,0,0,0.8); border-left: 4px solid var(--c-primary); color: var(--c-white); font-size: 18px; font-weight: 700; padding: 6px 14px; z-index: 2;">28.02</span>
                        <img src="<?php echo $evento_imgs[$i]; ?>" alt="Evento" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; inset: 0; background: rgba(242, 227, 2, 0.25); pointer-events: none;"></div>
                    </div>
                    <div style="padding: 22px 20px 25px;">
                        <h3 class="text-white" style="font-size: 17px; font-weight: 700; text-transform: uppercase; line-height: 1.4; margin-bottom: 20px;">DESCRIZIONE EVENTO<br>(TORNEO, CENA)</h3>
                        <a href="#" style="color: var(--c-white); font-size: 14px; font-weight: 700; text-transform: uppercase; text-decoration: none; letter-spacing: 1px;">SCOPRI &nbsp;<span style="opacity:0.5;">|</span></a>
                    </div>
                </div>
            <?php
                endfor;
            endif;
            ?>
        </div>
        
        <div class="carousel-nav" style="justify-content: center; gap: 15px; padding-top: 35px;">
            <span class="nav-arrow text-primary"><i class="fa-solid fa-chevron-left"></i></span>
            <span class="nav-dots"><i class="fa-solid fa-circle active"></i><i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i></span>
            <span class="nav-arrow text-primary"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
    </section>

    <!-- SPONSOR -->
    <section class="ps-section container">
        <h2 class="section-title text-white" style="margin-bottom: 8px;">SPONSOR</h2>
        <div style="width: 100%; height: 1px; background-color: rgba(255,255,255,0.1); margin-bottom: 35px;"></div>
        
        <div style="display: flex; align-items: center; justify-content: flex-start; gap: 50px; flex-wrap: wrap;">
            <?php
            $sponsor_q = new WP_Query(array('post_type' => 'sponsor', 'posts_per_page' => -1));
            if ($sponsor_q->have_posts()) :
                while ($sponsor_q->have_posts()) : $sponsor_q->the_post();
                    $sito = get_post_meta(get_the_ID(), '_sito_url', true);
                    $logo = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
                    if ($logo) :
            ?>
                        <a href="<?php echo $sito ? esc_url($sito) : '#'; ?>" target="_blank" style="display: flex; align-items: center; opacity: 0.85; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.85'">
                            <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" style="max-height: 45px; width: auto; filter: brightness(0) invert(1);">
                        </a>
            <?php
                    endif;
                endwhile;
                wp_reset_postdata();
            else :
                $placeholders = array('BancaStato', 'BRIC&Ograve;', 'RAIFFEISEN', 'AIL');
                foreach ($placeholders as $sp) :
            ?>
                <div style="color: var(--c-white); font-size: 22px; font-weight: 700; opacity: 0.7;">
                    <span><?php echo $sp; ?></span>
                </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </section>

    <!-- INSTAGRAM -->
    <section style="background-color: var(--c-black); padding-bottom: 50px; margin-top: 40px;">
        <!-- Barra Profilo -->
        <div style="background-color: #1a1a1a; padding: 20px 0;">
            <div class="container" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo.png"
                         alt="AC Taverne" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 2px solid var(--c-primary);"
                         onerror="this.src='https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=60';" loading="lazy">
                    <div>
                        <p style="color: var(--c-white); font-size: 16px; font-weight: 700; margin: 0;">AC Taverne</p>
                        <p style="color: #999; font-size: 13px; margin: 0; margin-top: 2px;">@ac_taverne</p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 30px; text-align: center;">
                    <div><strong style="color: var(--c-white); font-size: 16px; display: block;">688</strong><span style="color: #999; font-size: 11px;">post</span></div>
                    <div><strong style="color: var(--c-white); font-size: 16px; display: block;">4.2K</strong><span style="color: #999; font-size: 11px;">follower</span></div>
                    <div><strong style="color: var(--c-white); font-size: 16px; display: block;">95</strong><span style="color: #999; font-size: 11px;">profili seguiti</span></div>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <a href="#" style="width: 35px; height: 35px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; color: var(--c-white); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--c-primary)'; this.style.color='var(--c-primary)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.3)'; this.style.color='var(--c-white)';"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" style="width: 35px; height: 35px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; color: var(--c-white); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--c-primary)'; this.style.color='var(--c-primary)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.3)'; this.style.color='var(--c-white)';"><i class="fa-brands fa-facebook-f"></i></a>
                </div>
            </div>
        </div>

        <!-- Griglia Foto (Stessa laghezza del container) -->
        <div class="container" style="padding: 0;">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 3px;">
                <?php
                $insta_imgs = array(
                    'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=400&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1508344928928-7137b29de218?q=80&w=400&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?q=80&w=400&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1553147573-0ff7d2b45053?q=80&w=400&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=400&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1522778526582-12002162a043?q=80&w=400&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1551280857-2b9eb02029c3?q=80&w=400&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?q=80&w=400&auto=format&fit=crop',
                );
                foreach ($insta_imgs as $idx => $src) :
                ?>
                    <div style="aspect-ratio: 1/1; overflow: hidden; position: relative;">
                        <img src="<?php echo esc_url($src); ?>" alt="Instagram <?php echo $idx + 1; ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php get_footer('societa'); ?>
