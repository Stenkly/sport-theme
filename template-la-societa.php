<?php
/**
 * Template Name: Pagina La Società
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-la-societa">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        if ( has_post_thumbnail() ) {
            $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } else {
            $hero_image_url = 'https://images.unsplash.com/photo-1518622358385-8ea7d0794bf6?q=80&w=2000&auto=format&fit=crop';
        }
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh; min-height: 400px;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="news-hero-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,1) 0%, transparent 100%); pointer-events: none;"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 0; left: 0; right: 0; text-align: left; padding-bottom: 30px;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">AC TAVERNE</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid rgba(255,255,255,1); margin: 20px 0;">
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="container" style="padding-top: 50px; padding-bottom: 60px;">
                <?php
        // Recupero campi custom se esistono, altrimenti placeholder
        $titolo_1 = get_post_meta(get_the_ID(), '_soc_titolo_1', true) ?: 'LA SOCIETÀ';
        $sottotitolo_1 = get_post_meta(get_the_ID(), '_soc_sottotitolo_1', true) ?: '';
        $testo_1 = get_post_meta(get_the_ID(), '_soc_testo_1', true) ?: "Da oltre 70 anni, l’AC Taverne rappresenta molto più di una squadra di calcio: è una realtà sportiva radicata nel territorio, un punto di riferimento per centinaia di giovani e famiglie del Luganese. Fondata nel 1950, la nostra società è cresciuta mantenendo vivi i valori che da sempre ci contraddistinguono: passione, appartenenza, rispetto e spirito di comunità.\n\nOggi l’AC Taverne è una società moderna e dinamica, impegnata quotidianamente nella crescita sportiva e personale dei propri tesserati. Dalla scuola calcio alla Prima Squadra, ogni atleta viene accompagnato in un percorso fondato sulla formazione tecnica, sull’educazione sportiva e sul fair-play. Il nostro Centro Sportivo è un luogo di incontro aperto alla comunità, dove volontari, famiglie, sponsor e tifosi condividono la stessa passione per i colori gialloneri. \n\nCon oltre 300 giovani coinvolti ogni stagione, un settore femminile in continua crescita e una Prima Squadra competitiva nel panorama calcistico svizzero, l’AC Taverne guarda al futuro con ambizione, mantenendo solide le proprie radici e la propria identità.";
        
        $titolo_2 = get_post_meta(get_the_ID(), '_soc_titolo_2', true) ?: 'IL PROGETTO';
        $sottotitolo_2 = get_post_meta(get_the_ID(), '_soc_sottotitolo_2', true) ?: '';
        $testo_2 = get_post_meta(get_the_ID(), '_soc_testo_2', true) ?: "Il progetto AC Taverne nasce dalla volontà di costruire un ambiente sportivo sano, professionale e inclusivo, capace di valorizzare ogni ragazzo e ragazza attraverso il calcio. Investiamo costantemente nella qualità della formazione, nelle infrastrutture e nello sviluppo di uno staff tecnico qualificato, con l’obiettivo di offrire ai nostri giovani un percorso completo, dentro e fuori dal campo. \n\nLa crescita del settore giovanile rappresenta il cuore del nostro lavoro quotidiano. Crediamo in un calcio che sappia educare ai valori del sacrificio, della disciplina, del rispetto e del lavoro di squadra. Per questo promuoviamo una metodologia condivisa, che accompagna i giocatori in ogni fase del loro percorso sportivo, creando continuità tra scuola calcio, settore allievi e Prima Squadra. \n\nParallelamente, l’AC Taverne continua a rafforzare il proprio legame con il territorio, coinvolgendo aziende, famiglie e sostenitori in una visione comune di crescita sostenibile. Ogni iniziativa della società nasce con l’obiettivo di creare appartenenza, emozione e nuove opportunità per le future generazioni di sportivi.";

        $titolo_3 = get_post_meta(get_the_ID(), '_soc_titolo_3', true) ?: 'LA VISIONE';
        $sottotitolo_3 = get_post_meta(get_the_ID(), '_soc_sottotitolo_3', true) ?: '';
        $testo_3 = get_post_meta(get_the_ID(), '_soc_testo_3', true) ?: "La nostra visione è diventare un punto di riferimento nel calcio regionale svizzero, distinguendosi per qualità formativa, organizzazione e identità. Vogliamo essere una società capace di coniugare ambizione sportiva e responsabilità educativa, creando un ambiente in cui ogni atleta possa esprimere il proprio potenziale.\n\nGuardiamo al futuro con l’obiettivo di consolidare la crescita della Prima Squadra e del settore giovanile, investendo nel talento locale e nella valorizzazione dei giovani. Crediamo in un modello di calcio moderno, inclusivo e sostenibile, capace di trasmettere emozioni dentro il campo e valori autentici nella vita quotidiana. \n\nL’AC Taverne vuole continuare a essere una famiglia sportiva aperta alla comunità, dove passione, lavoro e appartenenza si trasformano ogni giorno in energia positiva per il territorio e per le nuove generazioni.";

        $titolo_4 = get_post_meta(get_the_ID(), '_soc_titolo_4', true) ?: 'LO STATUTO';
        $sottotitolo_4 = get_post_meta(get_the_ID(), '_soc_sottotitolo_4', true) ?: 'Scarica lo statuto ufficiale dell’AC Taverne in formato PDF.';
        $file_statuto = get_post_meta(get_the_ID(), '_soc_file_statuto', true) ?: '#';
        ?>

        <!-- Sezione 1 -->
        <div class="soc-section" style="margin-bottom: 60px;">
            <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($titolo_1); ?></h2>
            <?php if ($sottotitolo_1): ?>
                <h3 class="text-white" style="font-size: 18px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; line-height: 1.4;"><?php echo esc_html($sottotitolo_1); ?></h3>
            <?php endif; ?>
            <p class="text-white" style="font-size: 14px; line-height: 1.6;"><?php echo nl2br(esc_html($testo_1)); ?></p>
        </div>

        <!-- Sezione 2 -->
        <div class="soc-section" style="margin-bottom: 60px;">
            <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($titolo_2); ?></h2>
            <?php if ($sottotitolo_2): ?>
                <h3 class="text-white" style="font-size: 18px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; line-height: 1.4;"><?php echo esc_html($sottotitolo_2); ?></h3>
            <?php endif; ?>
            <p class="text-white" style="font-size: 14px; line-height: 1.6;"><?php echo nl2br(esc_html($testo_2)); ?></p>
        </div>

        <!-- Sezione 3 -->
        <div class="soc-section" style="margin-bottom: 60px;">
            <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($titolo_3); ?></h2>
            <?php if ($sottotitolo_3): ?>
                <h3 class="text-white" style="font-size: 18px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; line-height: 1.4;"><?php echo esc_html($sottotitolo_3); ?></h3>
            <?php endif; ?>
            <p class="text-white" style="font-size: 14px; line-height: 1.6;"><?php echo nl2br(esc_html($testo_3)); ?></p>
        </div>

        <!-- Sezione 4 -->
        <div class="soc-section" style="margin-bottom: 60px;">
            <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($titolo_4); ?></h2>
            <?php if ($sottotitolo_4): ?>
                <h3 class="text-white" style="font-size: 18px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; line-height: 1.4;"><?php echo esc_html($sottotitolo_4); ?></h3>
            <?php endif; ?>
            <a href="<?php echo esc_url($file_statuto); ?>" target="_blank" class="btn-statuto" style="display: inline-block; background-color: var(--c-primary); color: var(--c-black); font-weight: 700; padding: 10px 40px; text-transform: uppercase; text-decoration: none; font-size: 14px; letter-spacing: 1px; margin-top: 10px; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">SCARICA</a>
        </div>

        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.4); margin-bottom: 40px;">

        <!-- SPONSOR -->
        <h3 class="text-white" style="font-size: 26px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px;">SPONSOR</h3>
        <?php sport_theme_render_global_sponsors(); ?>

    </div>
</main>

<?php get_footer('societa'); ?>
