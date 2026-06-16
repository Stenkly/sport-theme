<?php
/**
 * Template Name: Pagina Club dei 100
 *
 * @package Sport_Theme
 */

get_header('societa');
?>

<main id="primary" class="site-main page-club100">

    <!-- HERO IMMAGINE -->
    <section class="news-hero">
        <?php
        $hero_image_url = sport_theme_get_societa_home_hero_url();
        ?>
        <div class="news-hero-wrapper" style="position: relative; width: 100%; height: 50vh;">
            <img src="<?php echo esc_url( $hero_image_url ); ?>" class="hero-image" style="height: 100%; width: 100%; object-fit: cover; object-position: center;" alt="<?php echo esc_attr(get_the_title()); ?>">
            <div class="club-hero-fade"></div>
            
            <div class="news-hero-content container" style="position: absolute; bottom: 40px; left: 0; right: 0; text-align: left;">
                <h1 class="text-white" style="font-size: 55px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 2px;">CLUB DEI 100</h1>
                <hr class="sc-divider" style="border: 0; border-top: 2px solid white; margin: 20px 0;">
                <?php sport_theme_render_societa_submenu(); ?>
                <p class="text-white" style="font-size: 24px; font-weight: 700; text-transform: uppercase; margin: 20px 0 0 0; line-height: 1.3;">
                    IL CLUB SOSTENITORE DELL’AC TAVERNE.<br>SOSTIENI LA NOSTRA SOCIETÀ PER I NOSTRI ALLIEVI.
                </p>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <div class="container" style="padding-top: 10px; padding-bottom: 60px;">
        
        <div class="text-white club100-content" style="font-size: 16px; line-height: 1.8; margin-bottom: 50px;">
            <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; color: var(--c-primary); border-bottom: 2px solid white; padding-bottom: 10px;">Cos’è il Club dei 100?</h3>
            <p style="font-size: 18px; font-weight: 600; margin-bottom: 30px;">E’ semplicemente un Club sostenitore dell’AC Taverne.</p>

            <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; color: var(--c-primary); border-bottom: 2px solid white; padding-bottom: 10px;">Come funziona?</h3>
            <p>Chi aderisce all’iniziativa, sarà invitato a una splendida e ottima cena dove sarete nostri graditi ospiti. Durante la cena verranno estratti dei numeri che vanno da 1 a 100, a questo numero sarà abbinato un socio (i numeri verranno assegnati in base all’ordine d’iscrizione) ed un valore compreso da 2.-CHF a 200.- CHF, questo valore sarà il costo da pagare quale tassa d’appartenenza al club.</p>
            <p>Se siete fortunati pagherete CHF 2.-, se lo siete un po’ meno pagherete al massimo CHF 200.-.</p>
            <p>Se qualcuno non potrà partecipare alla cena, verrà estratto il suo nome da qualcuno dei presenti e di conseguenza dovrà pagare il rispettivo in CHF.</p>
            <p style="margin-bottom: 30px;">Durante la cena verranno estratti anche 3 biglietti come lotteria AC Taverne con i seguenti premi:</p>

            <ul style="list-style: none; padding-left: 0; margin-bottom: 30px; font-size: 18px; font-weight: 700;">
                <li style="margin-bottom: 8px;">1° Buono AC Taverne del valore di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CHF 200.-</li>
                <li style="margin-bottom: 8px;">2° Buono AC Taverne del valore di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CHF 100.-</li>
                <li style="margin-bottom: 15px;">3° Buono AC Taverne del valore di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CHF 50.-</li>
            </ul>

            <p>I buoni avranno validità di 1 anno e potrete usarli solo presso la società AC Taverne e per qualsiasi cosa che riguarda l'acquisto in società, buvette, acquisto materiale per i ragazzi. (escluso tassa sociale)</p>
            <p>Verranno offerti inoltre 2 aperitivi in stagione, durante una partita della prima squadra.</p>
            <p style="margin-bottom: 30px;">Tutto questo lo si fa al sostegno della nostra società AC Taverne per i nostri allievi.</p>

            <p style="margin-bottom: 30px;">Il vostro sostegno è la nostra miglior soddisfazione e al momento della vostra iscrizione ve lo dimostreremo inviandovi la tessera socio club dei 100 che vi permetterà di partecipare ai diversi eventi.</p>

            <p>In caso foste propensi ad aderire all’iniziativa, vi chiediamo gentilmente di compilare al più presto possibile il contratto che trovate in buvette e ritornarlo per e-mail o via posta all’indirizzo indicato nel contratto. e-mail: <a href="mailto:marketing@actaverne.com" style="color: var(--c-primary); text-decoration: none; font-weight: 700;">marketing@actaverne.com</a></p>
            <p style="margin-bottom: 40px;">Potete anche consegnarlo in buvette.</p>

            <div style="text-align: right; margin-top: 40px; line-height: 1.4; font-size: 16px;">
                Sportivi Saluti<br>
                <strong>Antonio Londino</strong><br>
                <span style="color: var(--c-primary); font-weight: 700;">Responsabile Club dei 100</span>
            </div>
        </div>

    </div>

    <!-- FORM SEZIONE (Larghezza allineata al testo del container superiore) -->
    <div class="container" style="padding-bottom: 60px;">
        <div class="club100-form-band" style="border-top: 2px solid #555; border-bottom: 2px solid #555; background-color: #000; display: flex; justify-content: center;">
            <!-- Form -->
            <div class="club100-form-left" style="width: 100%; max-width: 900px; padding: 60px 0; display: flex; flex-direction: column; justify-content: center;">
                <h2 class="text-primary" style="font-size: 34px; font-weight: 700; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px; text-align: left;">ISCRIVITI</h2>
                
                <?php if ( isset($_GET['iscritto']) && $_GET['iscritto'] == '1' ) : ?>
                    <div style="background-color: var(--c-primary); color: #000; padding: 15px; margin-bottom: 20px; font-weight: bold;">
                        Grazie! La tua richiesta è stata inviata con successo.
                    </div>
                <?php endif; ?>
 
                <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="hs-contact-form">
                    <input type="hidden" name="action" value="club100_subscribe">
                    <?php wp_nonce_field('club100_form_nonce', 'club100_nonce'); ?>
                    
                    <div style="display: flex; gap: 30px; margin-bottom: 25px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Nome*</label>
                            <input type="text" name="c100_nome" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="Nome">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Numero di telefono*</label>
                            <input type="text" name="c100_telefono" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="Numero di telefono">
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 30px; margin-bottom: 25px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">La tua e-mail*</label>
                            <input type="email" name="c100_email" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="E-mail">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Oggetto*</label>
                            <input type="text" name="c100_oggetto" required style="width: 100%; background: transparent; border: 2px solid white; color: white; padding: 12px; font-size: 14px;" placeholder="Oggetto">
                        </div>
                    </div>
 
                    <div style="margin-bottom: 40px;">
                        <label class="text-white" style="display: block; margin-bottom: 8px; font-size: 13px;">Testo*</label>
                        <input type="text" name="c100_testo" required style="width: 100%; background: transparent; border: none; border-bottom: 2px solid white; color: white; padding: 12px 0; font-size: 14px;" placeholder="Testo">
                    </div>
 
                    <div style="text-align: right;">
                        <button type="submit" style="background-color: var(--c-primary); color: #000; border: none; padding: 14px 40px; font-weight: bold; text-transform: uppercase; cursor: pointer; letter-spacing: 1px; font-size: 14px; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">INVIA RICHIESTA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</main>

<style>
.club100-content h3 {
    color: var(--c-primary) !important;
    font-size: 18px !important;
    margin-bottom: 20px !important;
}
.club100-content p {
    margin-bottom: 20px;
}
</style>

<?php get_footer('societa'); ?>
