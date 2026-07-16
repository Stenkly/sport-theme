<?php
/**
 * Template Name: Pagina Iscriviti (Società)
 *
 * @package Sport_Theme
 */

get_header('societa');

while ( have_posts() ) : the_post();
$classificazione_url = function_exists( 'sport_theme_get_iscrizioni_classificazione_url' )
    ? sport_theme_get_iscrizioni_classificazione_url( get_the_ID() )
    : '';
$classificazione_stagione = get_post_meta( get_the_ID(), '_iscrizioni_classificazione_stagione', true );
$classificazione_is_image = $classificazione_url && preg_match( '/\.(jpe?g|png|webp|gif)(\?.*)?$/i', $classificazione_url );
$allievi_birthdate_cutoff = function_exists( 'sport_theme_get_allievi_birthdate_cutoff' ) ? sport_theme_get_allievi_birthdate_cutoff() : '2017-12-31';
$scuola_calcio_birthdate_min = function_exists( 'sport_theme_get_scuola_calcio_birthdate_min' ) ? sport_theme_get_scuola_calcio_birthdate_min() : '2018-01-01';
$new_registrations_discount_50_active = function_exists( 'sport_theme_new_registrations_discount_50_is_active' )
    ? sport_theme_new_registrations_discount_50_is_active()
    : false;
$today_date = date( 'Y-m-d' );
?>

<main id="primary" class="site-main page-iscritti">
    <section class="iscrizione-intro container" data-iscrizione-intro>
        <div class="iscrizione-panel">
            <div class="iscrizione-eyebrow">AC Taverne</div>
            <h1>Iscrizione AC Taverne</h1>

            <div class="iscrizione-grid">
                <div class="iscrizione-copy">
                    <h2>Benvenuto!</h2>
                    <p>Prima di iniziare, assicurati di avere a portata di mano, nella galleria del telefono o sul PC, questi file.</p>

                    <div class="iscrizione-checklist">
                        <div class="iscrizione-item">
                            <span class="iscrizione-number">01</span>
                            <div>
                                <h3>Foto del giocatore</h3>
                                <p>Non serve una foto professionale. Va benissimo un selfie o una foto fatta in casa, purché sia <strong>in primo piano dalle spalle in su</strong>, con il viso ben visibile e uno sfondo neutro.</p>
                            </div>
                        </div>

                        <div class="iscrizione-item">
                            <span class="iscrizione-number">02</span>
                            <div>
                                <h3>Documento d'identità</h3>
                                <p>Se usi il <strong>passaporto</strong>, basta la foto della pagina con i dati. Se usi <strong>carta d'identità o permesso di soggiorno</strong>, servono le foto di fronte e retro.</p>
                            </div>
                        </div>

                        <div class="iscrizione-item">
                            <span class="iscrizione-number">03</span>
                            <div>
                                <h3>Certificato di tutela</h3>
                                <p>Serve solo se chi iscrive il minore è un <strong>tutore legale</strong> e non un genitore. In quel caso tieni pronto il documento di nomina.</p>
                            </div>
                        </div>

                        <div class="iscrizione-item">
                            <span class="iscrizione-number">04</span>
                            <div>
                                <h3>Regolamento</h3>
                                <p>Prima di proseguire è necessario leggere il regolamento dell'AC Taverne.</p>
                                <button type="button" class="iscrizione-rule-link" data-regolamento-open>Leggi il regolamento</button>
                            </div>
                        </div>

                        <div class="iscrizione-item iscrizione-classificazione-item">
                            <span class="iscrizione-number">05</span>
                            <div>
                                <h3>Classificazione per anno di nascita</h3>
                                <p>Consulta la classificazione <?php echo $classificazione_stagione ? esc_html( $classificazione_stagione ) : 'della stagione'; ?> prima di scegliere il modulo. <strong>Scuola Calcio: Allievi F e G.</strong> <strong>Allievi: E, D, C, B e A.</strong></p>
                                <?php if ( $classificazione_url ) : ?>
                                    <button type="button" class="iscrizione-rule-link iscrizione-classificazione-link" data-classificazione-open>Apri classificazione</button>
                                    <?php if ( $classificazione_is_image ) : ?>
                                        <button type="button" class="iscrizione-classificazione-preview" data-classificazione-open aria-label="Apri classificazione per anno di nascita">
                                            <img src="<?php echo esc_url( $classificazione_url ); ?>" alt="Classificazione per anno di nascita">
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="iscrizione-item">
                            <span class="iscrizione-number">06</span>
                            <div>
                                <?php if ( $new_registrations_discount_50_active ) : ?>
                                    <h3>Sconto nuove iscrizioni</h3>
                                    <p>È attivo uno sconto del <strong>50%</strong> sulla quota. Lo sconto non è cumulabile con la riduzione fratelli.</p>
                                <?php else : ?>
                                    <h3>Riduzione fratelli</h3>
                                    <p>È prevista una riduzione di <strong>CHF 50</strong> per allievi con fratello o sorella regolarmente iscritto alla società. La riduzione non si applica agli <strong>Allievi F e G</strong>.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <label class="iscrizione-acceptance">
                        <input type="checkbox" data-regolamento-check>
                        <span>Ho letto e accetto il regolamento.</span>
                    </label>

                    <div class="iscrizione-cta-group">
                        <a href="#modulo-iscrizione" class="iscrizione-cta is-disabled" aria-disabled="true" data-iscrizione-cta data-registration-type="allievi">
                            Iscrizione Allievi <span aria-hidden="true">→</span>
                        </a>
                        <a href="#modulo-iscrizione" class="iscrizione-cta iscrizione-cta-secondary is-disabled" aria-disabled="true" data-iscrizione-cta data-registration-type="scuola_calcio">
                            Iscrizione Scuola Calcio <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="modulo-iscrizione" class="iscrizione-form-section container" data-iscrizione-form hidden>
        <div class="iscrizione-form-panel">
            <button type="button" class="iscrizione-back-btn" data-iscrizione-back>
                <span aria-hidden="true">←</span> Torna alle informazioni
            </button>
            <div class="iscrizione-form-kicker">Step 01</div>
            <h2 data-player-title>Dati del Giocatore</h2>

            <form class="iscrizione-player-form" action="#" method="post" data-player-form>
                <input type="hidden" name="tipo_iscrizione" value="allievi" data-registration-type-field>

                <div class="iscrizione-errors" data-player-errors hidden>
                    <h3>Controlla questi campi</h3>
                    <ul></ul>
                </div>

                <div class="iscrizione-field">
                    <label for="giocatore-cognome">Cognome <span>*</span></label>
                    <div class="iscrizione-input-wrap">
                        <input id="giocatore-cognome" type="text" name="giocatore_cognome" autocomplete="family-name" pattern="[A-Za-zÀ-ÖØ-öø-ÿ' -]+" data-only-text required>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="giocatore-nome">Nome <span>*</span></label>
                    <div class="iscrizione-input-wrap">
                        <input id="giocatore-nome" type="text" name="giocatore_nome" autocomplete="given-name" pattern="[A-Za-zÀ-ÖØ-öø-ÿ' -]+" data-only-text required>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="giocatore-data-nascita">Data di nascita <span>*</span></label>
                    <div class="iscrizione-input-wrap has-icon">
                        <input id="giocatore-data-nascita" type="date" name="giocatore_data_nascita" min="1990-01-01" max="<?php echo esc_attr( $today_date ); ?>" required>
                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="giocatore-nazionalita">Nazionalità <span>*</span></label>
                    <div class="iscrizione-input-wrap iscrizione-nationality-wrap">
                        <input id="giocatore-nazionalita" type="text" name="giocatore_nazionalita" autocomplete="country-name" data-nationality-input required>
                        <datalist id="lista-nazionalita" data-nationality-list>
                            <option value="Svizzera"></option>
                        </datalist>
                        <div class="iscrizione-nationality-menu" data-nationality-menu hidden></div>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="giocatore-avs">Numero AVS (13 cifre) <span>*</span></label>
                    <div class="iscrizione-input-wrap">
                        <input id="giocatore-avs" type="text" name="giocatore_avs" inputmode="numeric" maxlength="16" pattern="756\.[0-9]{4}\.[0-9]{4}\.[0-9]{2}" placeholder="Formato: 756.xxxx.xxxx.xx" data-avs required>
                    </div>
                    <p class="iscrizione-help">Lo trovi sulla tessera della cassa malati</p>
                </div>

                <div class="iscrizione-field">
                    <label for="giocatore-indirizzo">Indirizzo completo (Via e numero civico) <span>*</span></label>
                    <div class="iscrizione-input-wrap">
                        <input id="giocatore-indirizzo" type="text" name="giocatore_indirizzo" autocomplete="street-address" required>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="giocatore-cap-citta">CAP e Città <span>*</span></label>
                    <div class="iscrizione-input-wrap">
                        <input id="giocatore-cap-citta" type="text" name="giocatore_cap_citta" autocomplete="postal-code" required>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="giocatore-email">Email del Giocatore (se ne possiede una)</label>
                    <div class="iscrizione-input-wrap has-icon">
                        <input id="giocatore-email" type="email" name="giocatore_email" autocomplete="email">
                        <i class="fa-regular fa-at" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="giocatore-cellulare">Cellulare del Giocatore (se ne possiede uno)</label>
                    <div class="iscrizione-input-wrap has-icon">
                        <input id="giocatore-cellulare" type="tel" name="giocatore_cellulare" autocomplete="tel">
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="iscrizione-extra-children" data-extra-children></div>

                <div class="iscrizione-add-children" data-add-children-block>
                    <p>Vuoi iscrivere un secondo figlio?</p>
                    <button type="button" class="iscrizione-add-child-btn" data-add-child>
                        Aggiungi figlio <span aria-hidden="true">+</span>
                    </button>
                </div>

                <div class="iscrizione-form-actions">
                    <button type="button" class="iscrizione-next-btn" data-player-next>Continua <span aria-hidden="true">→</span></button>
                </div>
            </form>
        </div>
    </section>

    <section class="iscrizione-form-section container" data-iscrizione-step-two hidden>
        <div class="iscrizione-form-panel">
            <button type="button" class="iscrizione-back-btn" data-step-two-back>
                <span aria-hidden="true">←</span> Torna ai dati del giocatore
            </button>
            <div class="iscrizione-form-kicker">Step 02</div>
            <h2>Salute e Autorizzazioni</h2>

            <form class="iscrizione-health-form" action="#" method="post" data-health-form>
                <div class="iscrizione-errors" data-health-errors hidden>
                    <h3>Controlla questi campi</h3>
                    <ul></ul>
                </div>

                <div class="iscrizione-step-children" data-health-children></div>

                <div class="iscrizione-form-actions">
                    <button type="button" class="iscrizione-next-btn" data-health-next>Continua <span aria-hidden="true">→</span></button>
                </div>
            </form>
        </div>
    </section>

    <section class="iscrizione-form-section container" data-iscrizione-step-three hidden>
        <div class="iscrizione-form-panel">
            <button type="button" class="iscrizione-back-btn" data-step-three-back>
                <span aria-hidden="true">←</span> Torna a salute e autorizzazioni
            </button>
            <div class="iscrizione-form-kicker">Step 03</div>
            <h2>Foto e Documenti</h2>

            <form class="iscrizione-documents-form" action="#" method="post" enctype="multipart/form-data" data-documents-form>
                <div class="iscrizione-errors" data-documents-errors hidden>
                    <h3>Controlla questi campi</h3>
                    <ul></ul>
                </div>

                <div class="iscrizione-step-children" data-document-children></div>

                <div class="iscrizione-form-actions">
                    <button type="button" class="iscrizione-next-btn" data-documents-next>Continua <span aria-hidden="true">→</span></button>
                </div>
            </form>
        </div>
    </section>

    <section class="iscrizione-form-section container" data-iscrizione-step-four hidden>
        <div class="iscrizione-form-panel">
            <button type="button" class="iscrizione-back-btn" data-step-four-back>
                <span aria-hidden="true">←</span> Torna a foto e documenti
            </button>
            <div class="iscrizione-form-kicker">Step 04</div>
            <h2>Responsabilità genitoriale</h2>

            <form class="iscrizione-guardian-form" action="#" method="post" enctype="multipart/form-data" data-guardian-form>
                <div class="iscrizione-errors" data-guardian-errors hidden>
                    <h3>Controlla questi campi</h3>
                    <ul></ul>
                </div>

                <fieldset class="iscrizione-choice-field">
                    <legend>Chi esercita la responsabilità genitoriale sul giocatore?</legend>
                    <div class="iscrizione-choice-group iscrizione-choice-group-wide">
                        <label><input type="radio" name="responsabilita_genitoriale" value="padre" required data-guardian-choice> Padre</label>
                        <label><input type="radio" name="responsabilita_genitoriale" value="madre" required data-guardian-choice> Madre</label>
                        <label><input type="radio" name="responsabilita_genitoriale" value="tutore_legale" required data-guardian-choice> Tutore legale</label>
                    </div>
                </fieldset>

                <div class="iscrizione-field">
                    <label for="responsabile-nome">Nome</label>
                    <div class="iscrizione-input-wrap">
                        <input id="responsabile-nome" type="text" name="responsabile_nome" autocomplete="given-name" pattern="[A-Za-zÀ-ÖØ-öø-ÿ' -]+" data-only-text required>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="responsabile-cognome">Cognome</label>
                    <div class="iscrizione-input-wrap">
                        <input id="responsabile-cognome" type="text" name="responsabile_cognome" autocomplete="family-name" pattern="[A-Za-zÀ-ÖØ-öø-ÿ' -]+" data-only-text required>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="responsabile-telefono">Numero di telefono</label>
                    <div class="iscrizione-input-wrap has-icon">
                        <input id="responsabile-telefono" type="tel" name="responsabile_telefono" autocomplete="tel" required>
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="iscrizione-field">
                    <label for="responsabile-email">Email</label>
                    <div class="iscrizione-input-wrap has-icon">
                        <input id="responsabile-email" type="email" name="responsabile_email" autocomplete="email" required>
                        <i class="fa-solid fa-at" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="iscrizione-document-upload-group iscrizione-guardian-upload" data-guardian-certificate hidden>
                    <div class="iscrizione-field iscrizione-file-field">
                        <label for="certificato-tutela">Certificato di tutela</label>
                        <p class="iscrizione-help">Carica il documento che conferma la tutela legale.</p>
                        <label class="iscrizione-file-box" for="certificato-tutela">
                            <input id="certificato-tutela" type="file" name="certificato_tutela" accept="image/*,.pdf">
                            <span class="file-title">Carica file</span>
                            <span class="file-action">Fai clic per scegliere un file o trascinalo qui</span>
                            <span class="file-instruction">Limite dimensioni: 10 MB</span>
                            <span class="file-name" data-file-name>Nessun file selezionato</span>
                        </label>
                    </div>
                </div>

                <div class="iscrizione-form-actions">
                    <button type="button" class="iscrizione-next-btn" data-guardian-next>Continua <span aria-hidden="true">→</span></button>
                </div>
            </form>
        </div>
    </section>

    <section class="iscrizione-form-section container" data-iscrizione-step-five hidden>
        <div class="iscrizione-form-panel">
            <button type="button" class="iscrizione-back-btn" data-step-five-back>
                <span aria-hidden="true">←</span> Torna a responsabilità genitoriale
            </button>
            <div class="iscrizione-form-kicker">Step 05</div>
            <h2>Pagamento</h2>

            <form class="iscrizione-payment-form" action="#" method="post" data-payment-form>
                <div class="iscrizione-errors" data-payment-errors hidden>
                    <h3>Controlla questi campi</h3>
                    <ul></ul>
                </div>

                <div class="iscrizione-payment-summary" data-payment-summary>
                    <h3>Riepilogo quota</h3>
                    <div class="iscrizione-payment-summary-lines" data-payment-summary-lines></div>
                    <div class="iscrizione-payment-summary-total">
                        <span>Totale</span>
                        <strong data-payment-total>CHF 0</strong>
                    </div>
                </div>

                <fieldset class="iscrizione-choice-field">
                    <legend>Come vuoi pagare l'iscrizione?</legend>
                    <div class="iscrizione-choice-group iscrizione-choice-group-wide">
                        <label><input type="radio" name="metodo_pagamento" value="stripe" required data-payment-choice> Carta / Stripe</label>
                        <label><input type="radio" name="metodo_pagamento" value="fattura" required data-payment-choice> Fattura</label>
                    </div>
                </fieldset>

                <div class="iscrizione-payment-box" data-payment-info="stripe" hidden>
                    <h3>Pagamento online</h3>
                    <p>Con un solo iscritto si aprirà subito il pagamento sicuro con carta tramite Stripe. Dopo il pagamento riceverai un’unica email con conferma dell’iscrizione, conferma del pagamento e ricevuta. Con due o più iscritti, la segreteria verificherà prima la pratica.</p>
                </div>

                <div class="iscrizione-payment-box" data-payment-info="fattura" hidden>
                    <h3>Pagamento tramite fattura</h3>
                    <p>Con un solo iscritto la fattura QR verrà generata e inviata automaticamente via email. Con due o più iscritti, la segreteria verificherà prima la pratica.</p>
                </div>

                <div class="iscrizione-submit-status" data-submit-status hidden></div>

                <div class="iscrizione-form-actions">
                    <button type="button" class="iscrizione-next-btn" data-payment-next>Invia iscrizione <span aria-hidden="true">→</span></button>
                </div>
            </form>
        </div>
    </section>

    <div class="iscrizione-modal" data-regolamento-modal aria-hidden="true">
        <div class="iscrizione-modal-backdrop" data-regolamento-close></div>
        <div class="iscrizione-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="regolamento-title">
            <button type="button" class="iscrizione-modal-close" data-regolamento-close aria-label="Chiudi regolamento">×</button>
            <div class="iscrizione-modal-header">
                <span>AC Taverne</span>
                <h2 id="regolamento-title">Regolamento iscrizione</h2>
            </div>
            <div class="iscrizione-modal-body">
                <p class="regolamento-lead">I tesserati giocano per l'AC Taverne. La società è responsabile di inserire bambini e ragazzi nella categoria o squadra adatta alle qualità individuali.</p>

                <h3>Iscrizione</h3>
                <p>Per iscriversi ad una delle squadre della società è necessario prendere contatto con il responsabile della sezione allievi Franco Ruberto oppure con il Vice Responsabile Allievi Daniele Meneghelli scrivendo a <strong>info@actaverne.com</strong> oppure registrandosi sul sito <strong>www.actaverne.com</strong>, sezione allievi.</p>
                <p>Una volta ricevuta l'iscrizione verranno inviati e richiesti i seguenti documenti: autocertificazione sullo stato di salute dell'allievo, modulo ordinazione materiale dagli allievi E, autorizzazione campo/casa per minorenni e tagliando genitori.</p>
                <p>In caso di nuovo tesseramento sono richiesti una foto formato tessera, anche da telefono in formato jpeg, e copia di un documento d'identità. Per carta d'identità servono fronte e retro. Fino all'arrivo della tessera da Berna il nuovo giocatore potrà partecipare agli allenamenti, ma non alle partite ufficiali o ai tornei.</p>

                <h3>Materiale</h3>
                <p>Ogni allievo ha diritto al materiale previsto per la propria categoria. Gli allievi sono tenuti a rispettare il materiale ricevuto. Il training in dotazione deve essere utilizzato unicamente per partite di campionato e tornei, non per gli allenamenti.</p>
                <p>Al momento del tesseramento, o del rifacimento totale del materiale dopo almeno 3 anni, il contributo a carico degli allievi comporta un versamento di CHF 100.00. Per evitare scambi o appropriazioni involontarie, il materiale deve essere personalizzato con il nome del bambino.</p>

                <h3>Comunicazioni e dati personali</h3>
                <p>Ogni allievo riceve la lista della squadra con giocatori, allenatori, giorni e orari di allenamento. Il canale di comunicazione viene stabilito dall'allenatore, normalmente WhatsApp.</p>
                <p>In caso di cambiamento dei dati personali, i genitori sono pregati di inviare un'e-mail a <strong>info@actaverne.com</strong> e di segnalare eventuali inesattezze presenti nella lista squadra.</p>

                <h3>Spogliatoi</h3>
                <p>Secondo le direttive di tutela verso i minori, nessun adulto può accedere agli spogliatoi quando sono occupati dai ragazzi. Gli allenatori sono gli unici autorizzati ad accedervi esclusivamente per la pausa di gioco e per la teoria prepartita. I genitori non accedono al settore spogliatoi e possono seguire allenamenti e partite dalla tribuna.</p>

                <h3>Tassa iscrizione annua</h3>
                <ul>
                    <li>Allievi F + G: CHF 150 annui + contributo materiale CHF 50.</li>
                    <li>Girone ritorno: metà quota CHF 75 + contributo materiale CHF 50.</li>
                    <li>Allievi E-D-C-B-A: CHF 300 annui.</li>
                </ul>
                <p>Il mancato pagamento della tassa sociale entro i termini indicati comporta la sospensione dell'allievo da allenamenti, partite e tornei. È prevista una riduzione di CHF 50 per allievi con fratello o sorella regolarmente iscritto alla società, esclusi Allievi F e G.</p>

                <h3>Durata corsi e periodo di prova</h3>
                <p>Le date precise dell'inizio degli allenamenti vengono comunicate via WhatsApp o per iscritto. Gli allievi F + G hanno un allenamento settimanale e partecipano a diversi tornei. Gli allievi E-D-C-B-A hanno da due a tre allenamenti settimanali, una partita di campionato il sabato e alcuni tornei durante la stagione.</p>
                <p>Ogni ragazzo ha diritto ad una settimana di prova. Per gli allievi di scuola calcio, Allievi F e G, il periodo di prova è di uno o due allenamenti.</p>

                <h3>Impianti, salute e sponsorizzazioni</h3>
                <p>Gli allievi hanno a disposizione l'impianto sportivo comunale di Taverne. I genitori di tutti gli allievi AC Taverne devono compilare e sottoscrivere online l'autocertificazione sullo stato di salute del figlio o della figlia. Senza questa sottoscrizione l'allievo non potrà partecipare alle attività della squadra.</p>
                <p>Qualsiasi sponsorizzazione riguardante la sezione allievi deve essere sottoposta e autorizzata dalla società.</p>

                <h3>Partecipazione attività sociali</h3>
                <p>Fare parte di una società sportiva significa viverla a 360 gradi. Vi sono attività organizzate a favore dei ragazzi dove può essere richiesta la partecipazione degli stessi o dei genitori, tra cui Torneo Primo Maggio, allenamenti Scuola Calcio, arbitraggio e attività ballboy.</p>

                <h3>Fair play e sicurezza</h3>
                <p>I genitori e gli allievi sono invitati a prendere nota delle norme indicate, sottoscrivendo per accettazione il presente regolamento. Per la loro incolumità personale gli allievi non devono portare anelli, orologi o orecchini durante allenamenti, partite amichevoli e ufficiali.</p>
                <p><strong>Attenzione:</strong> la società non accetta comportamenti che non rientrano nel codice fair play. In caso di negligenza dell'allievo o dei genitori, l'AC Taverne si riserva la possibilità di prendere provvedimenti che possono arrivare alla sospensione o diffida degli interessati.</p>

                <h3>Autorizzazione uso immagini e video</h3>
                <p>Con l'accettazione del regolamento si autorizza l'AC Taverne a fotografare, filmare, intervistare, registrare e pubblicare immagini, video e materiali multimediali che ritraggono l'iscritto o socio su strumenti interni, social media ufficiali e materiale promozionale.</p>
                <ul>
                    <li>L'autorizzazione può essere revocata in ogni momento, senza pregiudicare le pubblicazioni già effettuate.</li>
                    <li>Le riprese rispetteranno il diritto alla privacy del minore e, ove necessario, il suo giudizio.</li>
                    <li>Il club tratterà questi dati in modo sicuro e trasparente, conforme alla Legge federale sulla protezione dei dati.</li>
                    <li>È possibile esercitare i diritti di accesso, rettifica, cancellazione, limitazione e revoca.</li>
                </ul>
            </div>
            <div class="iscrizione-modal-actions">
                <button type="button" class="iscrizione-modal-confirm" data-regolamento-accept>Ho letto il regolamento</button>
            </div>
        </div>
    </div>

    <div class="iscrizione-modal iscrizione-child-notice-modal" data-add-child-notice aria-hidden="true">
        <div class="iscrizione-modal-backdrop" data-add-child-notice-close></div>
        <div class="iscrizione-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="add-child-notice-title">
            <button type="button" class="iscrizione-modal-close" data-add-child-notice-close aria-label="Chiudi avviso">×</button>
            <div class="iscrizione-modal-header">
                <span>AC Taverne</span>
                <h2 id="add-child-notice-title">Prima di continuare</h2>
            </div>
            <div class="iscrizione-modal-body">
                <p>Qui puoi aggiungere solo un altro figlio Allievi. Per la Scuola Calcio devi compilare un’iscrizione separata.</p>
            </div>
            <div class="iscrizione-modal-actions">
                <button type="button" class="iscrizione-modal-confirm" data-add-child-notice-confirm>Aggiungi figlio Allievi</button>
            </div>
        </div>
    </div>

    <?php if ( $classificazione_url ) : ?>
        <div class="iscrizione-modal iscrizione-classificazione-modal" data-classificazione-modal aria-hidden="true">
            <div class="iscrizione-modal-backdrop" data-classificazione-close></div>
            <div class="iscrizione-modal-dialog iscrizione-classificazione-dialog" role="dialog" aria-modal="true" aria-labelledby="classificazione-title">
                <button type="button" class="iscrizione-modal-close" data-classificazione-close aria-label="Chiudi classificazione">×</button>
                <div class="iscrizione-modal-header">
                    <span>AC Taverne</span>
                    <h2 id="classificazione-title">Classificazione <?php echo $classificazione_stagione ? esc_html( $classificazione_stagione ) : ''; ?></h2>
                </div>
                <div class="iscrizione-classificazione-modal-body">
                    <?php if ( $classificazione_is_image ) : ?>
                        <img src="<?php echo esc_url( $classificazione_url ); ?>" alt="Classificazione per anno di nascita">
                    <?php else : ?>
                        <iframe src="<?php echo esc_url( $classificazione_url ); ?>" title="Classificazione per anno di nascita"></iframe>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<script>
window.acTaverneIscrizioni = {
    ajaxUrl: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
    nonce: '<?php echo esc_js( wp_create_nonce( 'act_iscrizione_submit' ) ); ?>',
    newRegistrationDiscount50: <?php echo $new_registrations_discount_50_active ? 'true' : 'false'; ?>
};
</script>

<script>
(function(){
    var modal = document.querySelector('[data-regolamento-modal]');
    var openBtn = document.querySelector('[data-regolamento-open]');
    var closeEls = document.querySelectorAll('[data-regolamento-close]');
    var acceptBtn = document.querySelector('[data-regolamento-accept]');
    var classificazioneModal = document.querySelector('[data-classificazione-modal]');
    var classificazioneOpenEls = document.querySelectorAll('[data-classificazione-open]');
    var classificazioneCloseEls = document.querySelectorAll('[data-classificazione-close]');
    var addChildNoticeModal = document.querySelector('[data-add-child-notice]');
    var addChildNoticeCloseEls = document.querySelectorAll('[data-add-child-notice-close]');
    var addChildNoticeConfirm = document.querySelector('[data-add-child-notice-confirm]');
    var checkbox = document.querySelector('[data-regolamento-check]');
    var ctaButtons = document.querySelectorAll('[data-iscrizione-cta]');
    var intro = document.querySelector('[data-iscrizione-intro]');
    var formSection = document.querySelector('[data-iscrizione-form]');
    var backBtn = document.querySelector('[data-iscrizione-back]');
    var playerForm = document.querySelector('[data-player-form]');
    var playerTitle = document.querySelector('[data-player-title]');
    var registrationTypeField = document.querySelector('[data-registration-type-field]');
    var playerNext = document.querySelector('[data-player-next]');
    var stepTwo = document.querySelector('[data-iscrizione-step-two]');
    var stepTwoBack = document.querySelector('[data-step-two-back]');
    var playerErrors = document.querySelector('[data-player-errors]');
    var healthForm = document.querySelector('[data-health-form]');
    var healthNext = document.querySelector('[data-health-next]');
    var healthErrors = document.querySelector('[data-health-errors]');
    var healthChildren = document.querySelector('[data-health-children]');
    var stepThree = document.querySelector('[data-iscrizione-step-three]');
    var stepThreeBack = document.querySelector('[data-step-three-back]');
    var documentsForm = document.querySelector('[data-documents-form]');
    var documentsNext = document.querySelector('[data-documents-next]');
    var documentsErrors = document.querySelector('[data-documents-errors]');
    var documentChildren = document.querySelector('[data-document-children]');
    var stepFour = document.querySelector('[data-iscrizione-step-four]');
    var stepFourBack = document.querySelector('[data-step-four-back]');
    var guardianForm = document.querySelector('[data-guardian-form]');
    var guardianNext = document.querySelector('[data-guardian-next]');
    var guardianErrors = document.querySelector('[data-guardian-errors]');
    var stepFive = document.querySelector('[data-iscrizione-step-five]');
    var stepFiveBack = document.querySelector('[data-step-five-back]');
    var paymentForm = document.querySelector('[data-payment-form]');
    var paymentNext = document.querySelector('[data-payment-next]');
    var paymentErrors = document.querySelector('[data-payment-errors]');
    var paymentSummaryLines = document.querySelector('[data-payment-summary-lines]');
    var paymentTotal = document.querySelector('[data-payment-total]');
    var submitStatus = document.querySelector('[data-submit-status]');
    var ajaxConfig = window.acTaverneIscrizioni || {};
    var extraChildren = document.querySelector('[data-extra-children]');
    var addChildBtn = document.querySelector('[data-add-child]');
    var addChildrenBlock = document.querySelector('[data-add-children-block]');
    var nextChildIndex = 1;
    var registrationType = 'allievi';
    var stepStorageKey = 'ac_taverne_iscrizione_step';
    var typeStorageKey = 'ac_taverne_iscrizione_type';
    var allieviBirthdateCutoff = '<?php echo esc_js( $allievi_birthdate_cutoff ); ?>';
    var scuolaCalcioBirthdateMin = '<?php echo esc_js( $scuola_calcio_birthdate_min ); ?>';
    var todayDate = '<?php echo esc_js( $today_date ); ?>';

    if (!modal || !openBtn || !checkbox || !ctaButtons.length || !intro || !formSection) return;

    function rememberStep(step) {
        try {
            window.sessionStorage.setItem(stepStorageKey, step);
            window.sessionStorage.setItem(typeStorageKey, registrationType);
        } catch (error) {}
    }

    function isScuolaCalcioRegistration() {
        return registrationType === 'scuola_calcio';
    }

    function validateBirthdateField(input) {
        var scuolaCalcio = isScuolaCalcioRegistration();

        if (!input.value) {
            input.setCustomValidity('');
            return;
        }

        if (!scuolaCalcio && input.value > allieviBirthdateCutoff) {
            input.setCustomValidity('Questa data di nascita rientra nella Scuola Calcio, non negli Allievi.');
            return;
        }

        if (scuolaCalcio && input.value < scuolaCalcioBirthdateMin) {
            input.setCustomValidity('Questa data di nascita rientra negli Allievi, non nella Scuola Calcio.');
            return;
        }

        input.setCustomValidity('');
    }

    function updateBirthdateLimits() {
        document.querySelectorAll('input[type="date"][name$="_data_nascita"], input[type="date"][name="giocatore_data_nascita"]').forEach(function(input){
            validateBirthdateField(input);
        });
    }

    function clearExtraChildren() {
        if (extraChildren) {
            extraChildren.innerHTML = '';
        }
        nextChildIndex = 1;
    }

    function applyRegistrationType() {
        var scuolaCalcio = isScuolaCalcioRegistration();

        if (registrationTypeField) {
            registrationTypeField.value = registrationType;
        }

        if (playerTitle) {
            playerTitle.textContent = scuolaCalcio ? 'Dati del Bambino' : 'Dati del Giocatore';
        }

        if (scuolaCalcio) {
            clearExtraChildren();
        }

        if (addChildrenBlock) {
            addChildrenBlock.hidden = scuolaCalcio;
        }

        updateBirthdateLimits();
    }

    function showStep(step, shouldScroll) {
        var currentStep = step || 'intro';

        applyRegistrationType();

        if (currentStep === 'health') {
            renderHealthChildren();
        }

        if (currentStep === 'documents') {
            renderDocumentChildren();
        }

        if (currentStep === 'payment') {
            updatePaymentSummary();
        }

        intro.hidden = currentStep !== 'intro';
        formSection.hidden = currentStep !== 'player';
        if (stepTwo) stepTwo.hidden = currentStep !== 'health';
        if (stepThree) stepThree.hidden = currentStep !== 'documents';
        if (stepFour) stepFour.hidden = currentStep !== 'guardian';
        if (stepFive) stepFive.hidden = currentStep !== 'payment';

        if (currentStep !== 'intro') {
            checkbox.checked = true;
            updateCta();
        }

        rememberStep(currentStep);

        if (shouldScroll) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function updateCta() {
        var accepted = checkbox.checked;
        ctaButtons.forEach(function(button){
            button.classList.toggle('is-disabled', !accepted);
            button.setAttribute('aria-disabled', accepted ? 'false' : 'true');
        });
    }

    function openModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function openClassificazioneModal() {
        if (!classificazioneModal) return;
        classificazioneModal.classList.add('is-open');
        classificazioneModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeClassificazioneModal() {
        if (!classificazioneModal) return;
        classificazioneModal.classList.remove('is-open');
        classificazioneModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function openAddChildNotice() {
        if (!addChildNoticeModal) {
            addChildSection();
            return;
        }

        addChildNoticeModal.classList.add('is-open');
        addChildNoticeModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeAddChildNotice() {
        if (!addChildNoticeModal) return;
        addChildNoticeModal.classList.remove('is-open');
        addChildNoticeModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', openModal);
    closeEls.forEach(function(el){ el.addEventListener('click', closeModal); });
    classificazioneOpenEls.forEach(function(el){ el.addEventListener('click', openClassificazioneModal); });
    classificazioneCloseEls.forEach(function(el){ el.addEventListener('click', closeClassificazioneModal); });
    addChildNoticeCloseEls.forEach(function(el){ el.addEventListener('click', closeAddChildNotice); });
    if (addChildNoticeConfirm) {
        addChildNoticeConfirm.addEventListener('click', function(){
            closeAddChildNotice();
            addChildSection();
        });
    }
    checkbox.addEventListener('change', updateCta);

    if (acceptBtn) {
        acceptBtn.addEventListener('click', function(){
            checkbox.checked = true;
            updateCta();
            closeModal();
        });
    }

    ctaButtons.forEach(function(button){
        button.addEventListener('click', function(event){
            if (!checkbox.checked) {
                event.preventDefault();
                openModal();
                return;
            }

            event.preventDefault();
            registrationType = button.dataset.registrationType || 'allievi';
            applyRegistrationType();
            showStep('player', true);
        });
    });

    if (backBtn) {
        backBtn.addEventListener('click', function(){
            showStep('intro', true);
        });
    }

    if (playerNext && playerForm && stepTwo) {
        playerNext.addEventListener('click', function(){
            updateBirthdateLimits();
            var invalidFields = Array.from(playerForm.querySelectorAll('input')).filter(function(input){
                return !input.checkValidity();
            });

            playerForm.querySelectorAll('.is-invalid').forEach(function(field){
                field.classList.remove('is-invalid');
            });

            if (playerErrors) {
                playerErrors.hidden = true;
                playerErrors.querySelector('ul').innerHTML = '';
            }

            if (invalidFields.length) {
                if (playerErrors) {
                    var list = playerErrors.querySelector('ul');
                    invalidFields.forEach(function(input){
                        var field = input.closest('.iscrizione-field');
                        var label = field ? field.querySelector('label') : null;
                        var childSection = input.closest('.iscrizione-child-section');
                        var childTitle = childSection ? childSection.querySelector('.iscrizione-child-header h3') : null;
                        var item = document.createElement('li');
                        var fieldName = label ? label.textContent.replace('*', '').trim() : input.name;
                        item.textContent = childTitle ? childTitle.textContent.trim() + ' - ' + fieldName : fieldName;
                        list.appendChild(item);
                        if (field) field.classList.add('is-invalid');
                    });
                    playerErrors.hidden = false;
                    playerErrors.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                invalidFields[0].reportValidity();
                return;
            }

            showStep('health', true);
        });
    }

    if (stepTwoBack && stepTwo) {
        stepTwoBack.addEventListener('click', function(){
            showStep('player', true);
        });
    }

    function updateConditionalFields(scope) {
        scope.querySelectorAll('[data-toggle-target]').forEach(function(input){
            var target = scope.querySelector('[data-conditional="' + input.dataset.toggleTarget + '"]');
            if (!target) return;

            var checked = scope.querySelector('input[name="' + input.name + '"]:checked');
            var show = checked && checked.value === 'si';
            target.hidden = !show;
            target.querySelectorAll('input, textarea').forEach(function(field){
                field.required = show;
                if (!show) field.value = '';
            });
        });
    }

    function escapeHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function(char){
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function getInputValue(selector) {
        var input = document.querySelector(selector);
        return input ? input.value.trim() : '';
    }

    function getExtraChildSections() {
        return extraChildren ? Array.from(extraChildren.querySelectorAll(':scope > .iscrizione-child-section')) : [];
    }

    function childOrdinal(number) {
        return ['primo', 'secondo', 'terzo', 'quarto'][number - 1] || number + '°';
    }

    function getRegisteredChildren() {
        var children = [{
            index: 1,
            fallback: 'Primo figlio',
            nome: getInputValue('[name="giocatore_nome"]'),
            cognome: getInputValue('[name="giocatore_cognome"]')
        }];

        if (isScuolaCalcioRegistration()) {
            return children.map(function(child){
                var fullName = [child.nome, child.cognome].filter(Boolean).join(' ');
                child.title = fullName ? 'Bambino - ' + fullName : 'Bambino';
                return child;
            });
        }

        getExtraChildSections().forEach(function(section, position){
            var childIndex = section.dataset.childIndex;
            children.push({
                index: childIndex,
                fallback: childOrdinal(position + 2).charAt(0).toUpperCase() + childOrdinal(position + 2).slice(1) + ' figlio',
                nome: getInputValue('[name="figlio_' + childIndex + '_nome"]'),
                cognome: getInputValue('[name="figlio_' + childIndex + '_cognome"]')
            });
        });

        return children.map(function(child, position){
            var fullName = [child.nome, child.cognome].filter(Boolean).join(' ');
            child.title = fullName ? 'Figlio ' + (position + 1) + ' - ' + fullName : child.fallback;
            return child;
        });
    }

    function getChildrenSignature(children) {
        return children.map(function(child){
            return child.index + ':' + child.title;
        }).join('|');
    }

    function bindHealthControls(scope) {
        if (!scope) return;
        scope.querySelectorAll('[data-toggle-target]').forEach(function(input){
            input.addEventListener('change', function(){
                updateConditionalFields(scope);
            });
        });
    }

    function renderHealthChildren() {
        if (!healthChildren) return;

        var children = getRegisteredChildren();
        var signature = getChildrenSignature(children);
        if (healthChildren.dataset.signature === signature && healthChildren.children.length) {
            updateConditionalFields(healthChildren);
            return;
        }

        healthChildren.dataset.signature = signature;
        healthChildren.innerHTML = '';

        children.forEach(function(child){
            var childIndex = child.index;
            var healthTarget = 'figlio_' + childIndex + '_health_details';
            var sportTarget = 'figlio_' + childIndex + '_sport_details';
            var section = document.createElement('section');
            section.className = 'iscrizione-child-section iscrizione-step-child-card';
            section.dataset.childIndex = String(childIndex);
            section.innerHTML =
                '<div class="iscrizione-child-header">' +
                    '<h3>' + escapeHtml(child.title) + '</h3>' +
                '</div>' +
                '<div class="iscrizione-child-fields">' +
                    '<fieldset class="iscrizione-choice-field">' +
                        '<legend>Il ragazzo è allergico o prende medicinali?</legend>' +
                        '<div class="iscrizione-choice-group">' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_salute_allergie_medicinali" value="no" data-toggle-target="' + healthTarget + '" required> No</label>' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_salute_allergie_medicinali" value="si" data-toggle-target="' + healthTarget + '" required> Sì</label>' +
                        '</div>' +
                        '<div class="iscrizione-conditional" data-conditional="' + healthTarget + '" hidden>' +
                            '<label for="figlio-' + childIndex + '-salute-dettagli">Indica allergie, medicinali o informazioni importanti</label>' +
                            '<textarea id="figlio-' + childIndex + '-salute-dettagli" name="figlio_' + childIndex + '_salute_dettagli" rows="4"></textarea>' +
                        '</div>' +
                    '</fieldset>' +
                    '<fieldset class="iscrizione-choice-field">' +
                        '<legend>Il ragazzo pratica un altro sport?</legend>' +
                        '<div class="iscrizione-choice-group">' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_altro_sport" value="no" data-toggle-target="' + sportTarget + '" required> No</label>' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_altro_sport" value="si" data-toggle-target="' + sportTarget + '" required> Sì</label>' +
                        '</div>' +
                        '<div class="iscrizione-conditional" data-conditional="' + sportTarget + '" hidden>' +
                            '<div class="iscrizione-field">' +
                                '<label for="figlio-' + childIndex + '-sport-societa">Per quale società?</label>' +
                                '<div class="iscrizione-input-wrap">' +
                                    '<input id="figlio-' + childIndex + '-sport-societa" type="text" name="figlio_' + childIndex + '_sport_societa">' +
                                '</div>' +
                            '</div>' +
                            '<div class="iscrizione-field">' +
                                '<label for="figlio-' + childIndex + '-sport-giorni">Giorni di allenamento</label>' +
                                '<div class="iscrizione-input-wrap">' +
                                    '<input id="figlio-' + childIndex + '-sport-giorni" type="text" name="figlio_' + childIndex + '_sport_giorni" placeholder="Esempio: lunedì e mercoledì">' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</fieldset>' +
                    '<fieldset class="iscrizione-choice-field">' +
                        '<legend>I genitori autorizzano il ragazzo a fare il tragitto casa-campo e campo-casa da solo?</legend>' +
                        '<div class="iscrizione-choice-group">' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_tragitto_autonomo" value="no" required> No</label>' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_tragitto_autonomo" value="si" required> Sì</label>' +
                        '</div>' +
                    '</fieldset>' +
                    '<fieldset class="iscrizione-choice-field">' +
                        '<legend>I genitori dichiarano che il figlio è abile a fare sport?</legend>' +
                        '<div class="iscrizione-choice-group">' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_abile_sport" value="no" required> No</label>' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_abile_sport" value="si" required> Sì</label>' +
                        '</div>' +
                    '</fieldset>' +
                '</div>';

            healthChildren.appendChild(section);
        });

        bindHealthControls(healthChildren);
        updateConditionalFields(healthChildren);
    }

    if (healthNext && healthForm) {
        healthNext.addEventListener('click', function(){
            var invalidFields = Array.from(healthForm.querySelectorAll('input, textarea')).filter(function(field){
                return !field.checkValidity();
            });

            healthForm.querySelectorAll('.is-invalid').forEach(function(field){
                field.classList.remove('is-invalid');
            });

            if (healthErrors) {
                healthErrors.hidden = true;
                healthErrors.querySelector('ul').innerHTML = '';
            }

            if (invalidFields.length) {
                if (healthErrors) {
                    var list = healthErrors.querySelector('ul');
                    var seenHealthErrors = new Set();
                    invalidFields.forEach(function(field){
                        var group = field.closest('.iscrizione-choice-field') || field.closest('.iscrizione-field');
                        var legend = group ? group.querySelector('legend') : null;
                        var label = group ? group.querySelector('label[for="' + field.id + '"]') : null;
                        var childSection = field.closest('.iscrizione-child-section');
                        var childTitle = childSection ? childSection.querySelector('.iscrizione-child-header h3') : null;
                        var errorKey = field.type === 'radio' ? field.name : field.name + ':' + field.id;
                        if (seenHealthErrors.has(errorKey)) return;
                        seenHealthErrors.add(errorKey);
                        var item = document.createElement('li');
                        var fieldName = legend ? legend.textContent.trim() : (label ? label.textContent.trim() : field.name);
                        item.textContent = childTitle ? childTitle.textContent.trim() + ' - ' + fieldName : fieldName;
                        list.appendChild(item);
                        if (group) group.classList.add('is-invalid');
                    });
                    healthErrors.hidden = false;
                    healthErrors.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                invalidFields[0].reportValidity();
                return;
            }

            if (stepThree) {
                showStep('documents', true);
            }
        });
    }

    if (stepThreeBack && stepThree && stepTwo) {
        stepThreeBack.addEventListener('click', function(){
            showStep('health', true);
        });
    }

    function fileUploadHtml(id, name, label, help, accept, required) {
        return '' +
            '<div class="iscrizione-field iscrizione-file-field">' +
                '<label for="' + id + '">' + label + '</label>' +
                (help ? '<p class="iscrizione-help">' + help + '</p>' : '') +
                '<label class="iscrizione-file-box" for="' + id + '">' +
                    '<input id="' + id + '" type="file" name="' + name + '" accept="' + accept + '"' + (required ? ' required' : '') + '>' +
                    '<span class="file-title">Carica file</span>' +
                    '<span class="file-action">Fai clic per scegliere un file o trascinalo qui</span>' +
                    '<span class="file-instruction">Limite dimensioni: 10 MB</span>' +
                    '<span class="file-name" data-file-name>Nessun file selezionato</span>' +
                '</label>' +
            '</div>';
    }

    function renderDocumentChildren() {
        if (!documentChildren) return;

        var children = getRegisteredChildren();
        var signature = getChildrenSignature(children);
        if (documentChildren.dataset.signature === signature && documentChildren.children.length) {
            updateDocumentUploads();
            return;
        }

        documentChildren.dataset.signature = signature;
        documentChildren.innerHTML = '';

        children.forEach(function(child){
            var childIndex = child.index;
            var section = document.createElement('section');
            section.className = 'iscrizione-child-section iscrizione-step-child-card iscrizione-document-child-card';
            section.dataset.childIndex = String(childIndex);
            section.innerHTML =
                '<div class="iscrizione-child-header">' +
                    '<h3>' + escapeHtml(child.title) + '</h3>' +
                '</div>' +
                '<div class="iscrizione-child-fields">' +
                    fileUploadHtml(
                        'figlio-' + childIndex + '-foto-giocatore',
                        'figlio_' + childIndex + '_foto_giocatore',
                        'Foto del giocatore',
                        'Carica una foto in primo piano dalle spalle in su, con viso ben visibile e sfondo neutro.',
                        'image/*',
                        true
                    ) +
                    '<fieldset class="iscrizione-choice-field">' +
                        '<legend>Quale documento vuoi caricare?</legend>' +
                        '<div class="iscrizione-choice-group iscrizione-choice-group-wide">' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_tipo_documento" value="carta_identita" required data-document-choice> Carta d’identità</label>' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_tipo_documento" value="permesso_soggiorno" required data-document-choice> Permesso di soggiorno</label>' +
                            '<label><input type="radio" name="figlio_' + childIndex + '_tipo_documento" value="passaporto" required data-document-choice> Passaporto</label>' +
                        '</div>' +
                    '</fieldset>' +
                    '<div class="iscrizione-document-upload-group" data-document-upload="carta_identita" hidden>' +
                        fileUploadHtml(
                            'figlio-' + childIndex + '-carta-identita-fronte',
                            'figlio_' + childIndex + '_carta_identita_fronte',
                            'Carta d’identità - fronte',
                            'Foto o PDF del lato frontale, leggibile e senza riflessi.',
                            'image/*,.pdf',
                            false
                        ) +
                        fileUploadHtml(
                            'figlio-' + childIndex + '-carta-identita-retro',
                            'figlio_' + childIndex + '_carta_identita_retro',
                            'Carta d’identità - retro',
                            'Foto o PDF del lato posteriore, completo e ben leggibile.',
                            'image/*,.pdf',
                            false
                        ) +
                    '</div>' +
                    '<div class="iscrizione-document-upload-group" data-document-upload="permesso_soggiorno" hidden>' +
                        fileUploadHtml(
                            'figlio-' + childIndex + '-permesso-fronte',
                            'figlio_' + childIndex + '_permesso_soggiorno_fronte',
                            'Permesso di soggiorno - fronte',
                            'Foto o PDF del lato frontale del permesso.',
                            'image/*,.pdf',
                            false
                        ) +
                        fileUploadHtml(
                            'figlio-' + childIndex + '-permesso-retro',
                            'figlio_' + childIndex + '_permesso_soggiorno_retro',
                            'Permesso di soggiorno - retro',
                            'Foto o PDF del lato posteriore del permesso.',
                            'image/*,.pdf',
                            false
                        ) +
                    '</div>' +
                    '<div class="iscrizione-document-upload-group" data-document-upload="passaporto" hidden>' +
                        fileUploadHtml(
                            'figlio-' + childIndex + '-passaporto-fronte',
                            'figlio_' + childIndex + '_passaporto_fronte',
                            'Passaporto - pagina con i dati',
                            'Carica la pagina con foto e dati personali, ben leggibile.',
                            'image/*,.pdf',
                            false
                        ) +
                    '</div>' +
                '</div>';

            documentChildren.appendChild(section);
        });

        bindDocumentControls(documentChildren);
        bindFileUploadBoxes(documentChildren);
        updateDocumentUploads();
    }

    function updateDocumentUploads() {
        if (!documentsForm) return;
        documentsForm.querySelectorAll('.iscrizione-document-child-card').forEach(function(card){
            var selected = card.querySelector('[data-document-choice]:checked');
            card.querySelectorAll('[data-document-upload]').forEach(function(group){
                var isActive = selected && group.dataset.documentUpload === selected.value;
                group.hidden = !isActive;
                group.querySelectorAll('input[type="file"]').forEach(function(input){
                    input.required = !!isActive;
                    if (!isActive) {
                        input.value = '';
                        var fileName = input.closest('.iscrizione-file-box')?.querySelector('[data-file-name]');
                        if (fileName) fileName.textContent = 'Nessun file selezionato';
                    }
                });
            });
        });
    }

    function updateFileName(input) {
        var fileName = input.closest('.iscrizione-file-box')?.querySelector('[data-file-name]');
        if (fileName) {
            fileName.textContent = input.files && input.files.length ? input.files[0].name : 'Nessun file selezionato';
        }
    }

    function bindFileUploadBoxes(scope) {
        scope.querySelectorAll('input[type="file"]').forEach(function(input){
            input.addEventListener('change', function(){
                updateFileName(input);
            });
        });
        scope.querySelectorAll('.iscrizione-file-box').forEach(function(box){
            var input = box.querySelector('input[type="file"]');
            if (!input) return;
            ['dragenter', 'dragover'].forEach(function(eventName){
                box.addEventListener(eventName, function(event){
                    event.preventDefault();
                    box.classList.add('is-dragging');
                });
            });
            ['dragleave', 'drop'].forEach(function(eventName){
                box.addEventListener(eventName, function(event){
                    event.preventDefault();
                    box.classList.remove('is-dragging');
                });
            });
            box.addEventListener('drop', function(event){
                if (!event.dataTransfer || !event.dataTransfer.files.length) return;
                input.files = event.dataTransfer.files;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    }

    function bindDocumentControls(scope) {
        if (!scope) return;
        scope.querySelectorAll('[data-document-choice]').forEach(function(input){
            input.addEventListener('change', updateDocumentUploads);
        });
    }

    if (documentsNext && documentsForm) {
        documentsNext.addEventListener('click', function(){
            updateDocumentUploads();
            var invalidFields = Array.from(documentsForm.querySelectorAll('input')).filter(function(field){
                return !field.checkValidity();
            });

            documentsForm.querySelectorAll('.is-invalid').forEach(function(field){
                field.classList.remove('is-invalid');
            });

            if (documentsErrors) {
                documentsErrors.hidden = true;
                documentsErrors.querySelector('ul').innerHTML = '';
            }

            if (invalidFields.length) {
                if (documentsErrors) {
                    var list = documentsErrors.querySelector('ul');
                    var seenDocumentErrors = new Set();
                    invalidFields.forEach(function(field){
                        var group = field.closest('.iscrizione-choice-field') || field.closest('.iscrizione-field');
                        var legend = group ? group.querySelector('legend') : null;
                        var label = group ? group.querySelector('label[for="' + field.id + '"]') : null;
                        var childSection = field.closest('.iscrizione-child-section');
                        var childTitle = childSection ? childSection.querySelector('.iscrizione-child-header h3') : null;
                        var errorKey = field.type === 'radio' ? field.name : field.name + ':' + field.id;
                        if (seenDocumentErrors.has(errorKey)) return;
                        seenDocumentErrors.add(errorKey);
                        var item = document.createElement('li');
                        var fieldName = legend ? legend.textContent.trim() : (label ? label.textContent.trim() : field.name);
                        item.textContent = childTitle ? childTitle.textContent.trim() + ' - ' + fieldName : fieldName;
                        list.appendChild(item);
                        if (group) group.classList.add('is-invalid');
                    });
                    documentsErrors.hidden = false;
                    documentsErrors.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                invalidFields[0].reportValidity();
                return;
            }

            showStep('guardian', true);
        });
    }

    if (stepFourBack && stepFour) {
        stepFourBack.addEventListener('click', function(){
            showStep('documents', true);
        });
    }

    function updateGuardianCertificate() {
        if (!guardianForm) return;
        var selected = guardianForm.querySelector('input[name="responsabilita_genitoriale"]:checked');
        var certificate = guardianForm.querySelector('[data-guardian-certificate]');
        if (!certificate) return;

        var show = selected && selected.value === 'tutore_legale';
        certificate.hidden = !show;
        certificate.querySelectorAll('input[type="file"]').forEach(function(input){
            input.required = !!show;
            if (!show) {
                input.value = '';
                updateFileName(input);
            }
        });
    }

    if (guardianForm) {
        guardianForm.querySelectorAll('[data-guardian-choice]').forEach(function(input){
            input.addEventListener('change', updateGuardianCertificate);
        });
        bindFileUploadBoxes(guardianForm);
        updateGuardianCertificate();
    }

    if (guardianNext && guardianForm) {
        guardianNext.addEventListener('click', function(){
            updateGuardianCertificate();
            var invalidFields = Array.from(guardianForm.querySelectorAll('input')).filter(function(field){
                return !field.checkValidity();
            });

            guardianForm.querySelectorAll('.is-invalid').forEach(function(field){
                field.classList.remove('is-invalid');
            });

            if (guardianErrors) {
                guardianErrors.hidden = true;
                guardianErrors.querySelector('ul').innerHTML = '';
            }

            if (invalidFields.length) {
                if (guardianErrors) {
                    var list = guardianErrors.querySelector('ul');
                    var seenGuardianErrors = new Set();
                    invalidFields.forEach(function(field){
                        var group = field.closest('.iscrizione-choice-field') || field.closest('.iscrizione-field');
                        var legend = group ? group.querySelector('legend') : null;
                        var label = group ? group.querySelector('label[for="' + field.id + '"]') : null;
                        var errorKey = field.type === 'radio' ? field.name : field.name + ':' + field.id;
                        if (seenGuardianErrors.has(errorKey)) return;
                        seenGuardianErrors.add(errorKey);
                        var item = document.createElement('li');
                        item.textContent = legend ? legend.textContent.trim() : (label ? label.textContent.trim() : field.name);
                        list.appendChild(item);
                        if (group) group.classList.add('is-invalid');
                    });
                    guardianErrors.hidden = false;
                    guardianErrors.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                invalidFields[0].reportValidity();
                return;
            }

            showStep('payment', true);
        });
    }

    if (stepFiveBack && stepFive) {
        stepFiveBack.addEventListener('click', function(){
            showStep('guardian', true);
        });
    }

    function getRegisteredChildrenCount() {
        if (isScuolaCalcioRegistration()) {
            return 1;
        }

        return 1 + (extraChildren ? extraChildren.querySelectorAll('.iscrizione-child-section').length : 0);
    }

    function formatChf(amount) {
        return 'CHF ' + String(amount).replace(/\B(?=(\d{3})+(?!\d))/g, "'");
    }

    function calculateRegistrationAmount() {
        var childrenCount = getRegisteredChildrenCount();
        var summary;

        if (isScuolaCalcioRegistration()) {
            summary = {
                total: 150,
                lines: ['Scuola Calcio: CHF 150']
            };
        } else {
            var lines = ['1° figlio Allievi: CHF 300'];
            for (var i = 2; i <= childrenCount; i++) {
                lines.push(i + '° figlio Allievi: CHF 250');
            }

            summary = {
                total: 300 + Math.max(0, childrenCount - 1) * 250,
                lines: lines
            };
        }

        if (ajaxConfig.newRegistrationDiscount50) {
            var discountAmount = summary.total * 0.5;
            summary.lines.push('Sconto nuove iscrizioni 50%: - ' + formatChf(discountAmount));
            summary.total -= discountAmount;
        }

        return summary;
    }

    function updatePaymentSummary() {
        var summary = calculateRegistrationAmount();

        if (paymentSummaryLines) {
            paymentSummaryLines.innerHTML = '';
            summary.lines.forEach(function(line){
                var item = document.createElement('span');
                item.textContent = line;
                paymentSummaryLines.appendChild(item);
            });
        }

        if (paymentTotal) {
            paymentTotal.textContent = formatChf(summary.total);
        }
    }

    function updatePaymentInfo() {
        if (!paymentForm) return;
        var selected = paymentForm.querySelector('input[name="metodo_pagamento"]:checked');
        paymentForm.querySelectorAll('[data-payment-info]').forEach(function(box){
            box.hidden = !(selected && box.dataset.paymentInfo === selected.value);
        });
        updatePaymentSummary();
    }

    if (paymentForm) {
        paymentForm.querySelectorAll('[data-payment-choice]').forEach(function(input){
            input.addEventListener('change', updatePaymentInfo);
        });
        updatePaymentInfo();
    }

    function setSubmitStatus(message, type) {
        if (!submitStatus) return;
        submitStatus.hidden = false;
        submitStatus.classList.remove('is-success', 'is-error', 'is-loading');
        submitStatus.classList.add(type ? 'is-' + type : 'is-loading');
        submitStatus.textContent = message;
    }

    function appendFormData(target, form) {
        if (!form) return;
        var data = new FormData(form);
        data.forEach(function(value, key){
            if (value instanceof File && !value.name) {
                return;
            }
            target.append(key, value);
        });
    }

    function resetSubmissionState() {
        try {
            window.sessionStorage.removeItem(stepStorageKey);
            window.sessionStorage.removeItem(typeStorageKey);
        } catch (error) {}
    }

    function submitRegistration() {
        if (!ajaxConfig.ajaxUrl || !ajaxConfig.nonce) {
            setSubmitStatus('Configurazione invio mancante. Ricarica la pagina e riprova.', 'error');
            return;
        }

        var payload = new FormData();
        payload.append('action', 'act_submit_iscrizione');
        payload.append('nonce', ajaxConfig.nonce);

        appendFormData(payload, playerForm);
        appendFormData(payload, healthForm);
        appendFormData(payload, documentsForm);
        appendFormData(payload, guardianForm);
        appendFormData(payload, paymentForm);

        if (paymentNext) {
            paymentNext.disabled = true;
            paymentNext.classList.add('is-loading');
        }

        setSubmitStatus('Invio iscrizione in corso...', 'loading');

        fetch(ajaxConfig.ajaxUrl, {
            method: 'POST',
            body: payload,
            credentials: 'same-origin'
        })
        .then(function(response){
            return response.json().then(function(body){
                return { ok: response.ok, body: body };
            });
        })
        .then(function(result){
            if (!result.ok || !result.body || !result.body.success) {
                var message = result.body && result.body.data && result.body.data.message
                    ? result.body.data.message
                    : 'Non è stato possibile inviare l’iscrizione.';
                throw new Error(message);
            }

            resetSubmissionState();
            var responseData = result.body.data || {};
            setSubmitStatus(responseData.message || 'Iscrizione inviata correttamente.', 'success');
            if (paymentNext) {
                paymentNext.hidden = true;
            }
            if (responseData.redirect_url) {
                window.location.assign(responseData.redirect_url);
            }
        })
        .catch(function(error){
            setSubmitStatus(error.message || 'Errore durante l’invio. Riprova tra poco.', 'error');
            if (paymentNext) {
                paymentNext.disabled = false;
                paymentNext.classList.remove('is-loading');
            }
        });
    }

    if (paymentNext && paymentForm) {
        paymentNext.addEventListener('click', function(){
            updatePaymentInfo();
            var invalidFields = Array.from(paymentForm.querySelectorAll('input')).filter(function(field){
                return !field.checkValidity();
            });

            paymentForm.querySelectorAll('.is-invalid').forEach(function(field){
                field.classList.remove('is-invalid');
            });

            if (paymentErrors) {
                paymentErrors.hidden = true;
                paymentErrors.querySelector('ul').innerHTML = '';
            }

            if (invalidFields.length) {
                if (paymentErrors) {
                    var list = paymentErrors.querySelector('ul');
                    var seenPaymentErrors = new Set();
                    invalidFields.forEach(function(field){
                        var group = field.closest('.iscrizione-choice-field') || field.closest('.iscrizione-field');
                        var legend = group ? group.querySelector('legend') : null;
                        var errorKey = field.type === 'radio' ? field.name : field.name + ':' + field.id;
                        if (seenPaymentErrors.has(errorKey)) return;
                        seenPaymentErrors.add(errorKey);
                        var item = document.createElement('li');
                        item.textContent = legend ? legend.textContent.trim() : field.name;
                        list.appendChild(item);
                        if (group) group.classList.add('is-invalid');
                    });
                    paymentErrors.hidden = false;
                    paymentErrors.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                invalidFields[0].reportValidity();
                return;
            }

            submitRegistration();
        });
    }

    function bindTextRules(scope) {
        scope.querySelectorAll('[data-only-text]').forEach(function(input){
        input.addEventListener('input', function(){
            input.value = input.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ' -]/g, '');
        });

        input.addEventListener('invalid', function(){
            input.setCustomValidity('Inserisci solo lettere, spazi, apostrofi o trattini.');
        });

        input.addEventListener('blur', function(){
            input.setCustomValidity('');
        });
        });
    }

    function bindAvsRules(scope) {
        scope.querySelectorAll('[data-avs]').forEach(function(input){
        function formatAvs(value) {
            var digits = value.replace(/\D/g, '').slice(0, 13);
            if (digits.length > 0 && !digits.startsWith('756')) {
                digits = '756' + digits.replace(/^756/, '').slice(0, 10);
            }

            var parts = [];
            if (digits.length > 0) parts.push(digits.slice(0, 3));
            if (digits.length > 3) parts.push(digits.slice(3, 7));
            if (digits.length > 7) parts.push(digits.slice(7, 11));
            if (digits.length > 11) parts.push(digits.slice(11, 13));
            return parts.join('.');
        }

        input.addEventListener('focus', function(){
            if (!input.value) input.value = '756.';
        });

        input.addEventListener('input', function(){
            input.value = formatAvs(input.value);
            input.setCustomValidity('');
        });

        input.addEventListener('invalid', function(){
            input.setCustomValidity('Inserisci un numero AVS valido nel formato 756.xxxx.xxxx.xx');
        });

        input.addEventListener('blur', function(){
            input.setCustomValidity('');
        });
        });
    }

    bindTextRules(document);
    bindAvsRules(document);

    var nationalityList = document.querySelector('[data-nationality-list]');
    if (nationalityList) {
        var fallbackCountries = [
            'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua e Barbuda', 'Arabia Saudita', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaigian',
            'Bahamas', 'Bahrein', 'Bangladesh', 'Barbados', 'Belgio', 'Belize', 'Benin', 'Bhutan', 'Bielorussia', 'Bolivia', 'Bosnia ed Erzegovina', 'Botswana', 'Brasile', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi',
            'Cambogia', 'Camerun', 'Canada', 'Capo Verde', 'Ciad', 'Cile', 'Cina', 'Cipro', 'Colombia', 'Comore', 'Congo', 'Corea del Nord', 'Corea del Sud', 'Costa Rica', 'Costa d’Avorio', 'Croazia', 'Cuba',
            'Danimarca', 'Dominica', 'Ecuador', 'Egitto', 'El Salvador', 'Emirati Arabi Uniti', 'Eritrea', 'Estonia', 'Etiopia',
            'Figi', 'Filippine', 'Finlandia', 'Francia', 'Gabon', 'Gambia', 'Georgia', 'Germania', 'Ghana', 'Giamaica', 'Giappone', 'Gibuti', 'Giordania', 'Grecia', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guinea Equatoriale', 'Guyana',
            'Haiti', 'Honduras', 'India', 'Indonesia', 'Iran', 'Iraq', 'Irlanda', 'Islanda', 'Israele', 'Italia',
            'Kazakistan', 'Kenya', 'Kirghizistan', 'Kiribati', 'Kosovo', 'Kuwait',
            'Laos', 'Lesotho', 'Lettonia', 'Libano', 'Liberia', 'Libia', 'Liechtenstein', 'Lituania', 'Lussemburgo',
            'Macedonia del Nord', 'Madagascar', 'Malawi', 'Malaysia', 'Maldive', 'Mali', 'Malta', 'Marocco', 'Mauritania', 'Mauritius', 'Messico', 'Micronesia', 'Moldavia', 'Monaco', 'Mongolia', 'Montenegro', 'Mozambico', 'Myanmar',
            'Namibia', 'Nauru', 'Nepal', 'Nicaragua', 'Niger', 'Nigeria', 'Norvegia', 'Nuova Zelanda',
            'Oman', 'Paesi Bassi', 'Pakistan', 'Palau', 'Palestina', 'Panama', 'Papua Nuova Guinea', 'Paraguay', 'Perù', 'Polonia', 'Portogallo',
            'Qatar', 'Regno Unito', 'Repubblica Ceca', 'Repubblica Centrafricana', 'Repubblica Democratica del Congo', 'Repubblica Dominicana', 'Romania', 'Ruanda', 'Russia',
            'Saint Kitts e Nevis', 'Saint Lucia', 'Saint Vincent e Grenadine', 'Samoa', 'San Marino', 'São Tomé e Príncipe', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Siria', 'Slovacchia', 'Slovenia', 'Somalia', 'Spagna', 'Sri Lanka', 'Stati Uniti', 'Sudafrica', 'Sudan', 'Sudan del Sud', 'Suriname', 'Svezia',
            'Tagikistan', 'Tanzania', 'Thailandia', 'Timor Est', 'Togo', 'Tonga', 'Trinidad e Tobago', 'Tunisia', 'Turchia', 'Turkmenistan', 'Tuvalu',
            'Ucraina', 'Uganda', 'Ungheria', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vaticano', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe', 'Altro'
        ];

        var countries = fallbackCountries;
        if (window.Intl && Intl.DisplayNames && Intl.supportedValuesOf) {
            try {
                var regionNames = new Intl.DisplayNames(['it'], { type: 'region' });
                var regionCodes = Intl.supportedValuesOf('region');
                if (regionCodes && regionCodes.length) {
                    countries = regionCodes
                        .map(function(code){ return regionNames.of(code); })
                        .filter(Boolean);
                }
            } catch (error) {
                countries = fallbackCountries;
            }
        }

        countries = Array.from(new Set(countries.filter(function(country){
            return country && country.toLowerCase() !== 'svizzera';
        }))).sort(function(a, b){
            return a.localeCompare(b, 'it');
        });

        nationalityList.innerHTML = '';
        ['Svizzera'].concat(countries).forEach(function(country){
            var option = document.createElement('option');
            option.value = country;
            nationalityList.appendChild(option);
        });

        var nationalityInput = document.querySelector('[data-nationality-input]');
        var nationalityMenu = document.querySelector('[data-nationality-menu]');
        var allNationalities = ['Svizzera'].concat(countries);

        function closeNationalityMenu() {
            if (nationalityMenu) nationalityMenu.hidden = true;
        }

        function renderNationalityMenu() {
            if (!nationalityInput || !nationalityMenu) return;

            var search = nationalityInput.value.trim().toLowerCase();
            var matches = allNationalities.filter(function(country){
                return !search || country.toLowerCase().indexOf(search) !== -1;
            });

            nationalityMenu.innerHTML = '';
            matches.forEach(function(country){
                var button = document.createElement('button');
                button.type = 'button';
                button.textContent = country;
                button.addEventListener('mousedown', function(event){
                    event.preventDefault();
                    nationalityInput.value = country;
                    closeNationalityMenu();
                });
                nationalityMenu.appendChild(button);
            });

            nationalityMenu.hidden = matches.length === 0;
        }

        if (nationalityInput && nationalityMenu) {
            nationalityInput.addEventListener('focus', renderNationalityMenu);
            nationalityInput.addEventListener('input', renderNationalityMenu);
            nationalityInput.addEventListener('blur', function(){
                window.setTimeout(closeNationalityMenu, 120);
            });
        }

        function createNationalityMenu(input, menu) {
            function closeMenu() {
                menu.hidden = true;
            }

            function renderMenu() {
                var search = input.value.trim().toLowerCase();
                var matches = allNationalities.filter(function(country){
                    return !search || country.toLowerCase().indexOf(search) !== -1;
                });

                menu.innerHTML = '';
                matches.forEach(function(country){
                    var button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = country;
                    button.addEventListener('mousedown', function(event){
                        event.preventDefault();
                        input.value = country;
                        closeMenu();
                    });
                    menu.appendChild(button);
                });

                menu.hidden = matches.length === 0;
            }

            input.addEventListener('focus', renderMenu);
            input.addEventListener('input', renderMenu);
            input.addEventListener('blur', function(){
                window.setTimeout(closeMenu, 120);
            });
        }

        function createChildField(childIndex, key, label, type, options) {
            var opts = options || {};
            var field = document.createElement('div');
            field.className = 'iscrizione-field';
            var id = 'figlio-' + childIndex + '-' + key;
            var required = opts.required !== false;
            var icon = opts.icon || '';
            var wrapClass = 'iscrizione-input-wrap' + (icon || opts.nationality ? ' has-icon' : '') + (opts.nationality ? ' iscrizione-nationality-wrap' : '');
            var attrs = [
                'id="' + id + '"',
                'name="figlio_' + childIndex + '_' + key + '"',
                'type="' + type + '"'
            ];

            if (required) attrs.push('required');
            if (opts.autocomplete) attrs.push('autocomplete="' + opts.autocomplete + '"');
            if (opts.pattern) attrs.push('pattern="' + opts.pattern + '"');
            if (opts.onlyText) attrs.push('data-only-text');
            if (opts.avs) attrs.push('data-avs inputmode="numeric" maxlength="16" placeholder="Formato: 756.xxxx.xxxx.xx"');
            if (opts.min) attrs.push('min="' + opts.min + '"');
            if (opts.max) attrs.push('max="' + opts.max + '"');

            field.innerHTML =
                '<label for="' + id + '">' + label + (required ? ' <span>*</span>' : '') + '</label>' +
                '<div class="' + wrapClass + '">' +
                    '<input ' + attrs.join(' ') + '>' +
                    (icon ? '<i class="' + icon + '" aria-hidden="true"></i>' : '') +
                    (opts.nationality ? '<div class="iscrizione-nationality-menu" data-nationality-menu hidden></div>' : '') +
                '</div>' +
                (opts.help ? '<p class="iscrizione-help">' + opts.help + '</p>' : '');

            return field;
        }

        function updateAddChildButton() {
            if (!addChildBtn || !addChildrenBlock) return;
            if (isScuolaCalcioRegistration()) {
                addChildrenBlock.hidden = true;
                return;
            }

            var visibleChildren = 1 + getExtraChildSections().length;
            if (visibleChildren >= 4) {
                addChildrenBlock.hidden = true;
                return;
            }

            addChildrenBlock.hidden = false;
            var next = visibleChildren + 1;
            addChildrenBlock.querySelector('p').textContent = 'Vuoi iscrivere un ' + childOrdinal(next) + ' figlio?';
            addChildBtn.innerHTML = 'Aggiungi figlio <span aria-hidden="true">+</span>';
        }

        function addChildSection() {
            if (isScuolaCalcioRegistration()) return;

            var visibleChildren = 1 + getExtraChildSections().length;
            if (!extraChildren || visibleChildren >= 4) return;
            nextChildIndex += 1;
            var childIndex = nextChildIndex;
            var displayIndex = visibleChildren + 1;

            var section = document.createElement('section');
            section.className = 'iscrizione-child-section';
            section.dataset.childIndex = String(childIndex);
            section.innerHTML =
                '<div class="iscrizione-child-header">' +
                    '<h3>Dati del ' + childOrdinal(displayIndex) + ' figlio</h3>' +
                    '<button type="button" class="iscrizione-remove-child" data-remove-child>Rimuovi</button>' +
                '</div>';

            var fields = document.createElement('div');
            fields.className = 'iscrizione-child-fields';
            fields.appendChild(createChildField(childIndex, 'cognome', 'Cognome', 'text', { autocomplete: 'family-name', pattern: "[A-Za-zÀ-ÖØ-öø-ÿ' -]+", onlyText: true }));
            fields.appendChild(createChildField(childIndex, 'nome', 'Nome', 'text', { autocomplete: 'given-name', pattern: "[A-Za-zÀ-ÖØ-öø-ÿ' -]+", onlyText: true }));
            fields.appendChild(createChildField(childIndex, 'data_nascita', 'Data di nascita', 'date', { min: '1990-01-01', max: todayDate, icon: 'fa-regular fa-calendar' }));
            fields.appendChild(createChildField(childIndex, 'nazionalita', 'Nazionalità', 'text', { autocomplete: 'country-name', nationality: true }));
            fields.appendChild(createChildField(childIndex, 'avs', 'Numero AVS (13 cifre)', 'text', { avs: true, help: 'Lo trovi sulla tessera della cassa malati' }));
            fields.appendChild(createChildField(childIndex, 'indirizzo', 'Indirizzo completo (Via e numero civico)', 'text', { autocomplete: 'street-address' }));
            fields.appendChild(createChildField(childIndex, 'cap_citta', 'CAP e Città', 'text', { autocomplete: 'postal-code' }));
            fields.appendChild(createChildField(childIndex, 'email', 'Email del Giocatore (se ne possiede una)', 'email', { required: false, autocomplete: 'email', icon: 'fa-regular fa-at' }));
            fields.appendChild(createChildField(childIndex, 'cellulare', 'Cellulare del Giocatore (se ne possiede uno)', 'tel', { required: false, autocomplete: 'tel', icon: 'fa-solid fa-phone' }));
            section.appendChild(fields);
            extraChildren.appendChild(section);

            bindTextRules(section);
            bindAvsRules(section);
            section.querySelectorAll('[data-nationality-menu]').forEach(function(menu){
                var input = menu.closest('.iscrizione-input-wrap').querySelector('input');
                createNationalityMenu(input, menu);
            });

            section.querySelector('[data-remove-child]').addEventListener('click', function(){
                section.remove();
                updateAddChildButton();
            });

            updateAddChildButton();
            updateBirthdateLimits();
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        if (addChildBtn) {
            addChildBtn.addEventListener('click', openAddChildNotice);
        }

        document.addEventListener('input', function(event){
            if (event.target && event.target.matches('input[type="date"]')) {
                validateBirthdateField(event.target);
            }
        });

        updateAddChildButton();
    }

    document.addEventListener('keydown', function(event){
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
        if (event.key === 'Escape' && classificazioneModal && classificazioneModal.classList.contains('is-open')) {
            closeClassificazioneModal();
        }
        if (event.key === 'Escape' && addChildNoticeModal && addChildNoticeModal.classList.contains('is-open')) {
            closeAddChildNotice();
        }
    });

    updateCta();
    applyRegistrationType();

    try {
        var savedType = window.sessionStorage.getItem(typeStorageKey);
        if (savedType === 'allievi' || savedType === 'scuola_calcio') {
            registrationType = savedType;
            applyRegistrationType();
        }

        var savedStep = window.sessionStorage.getItem(stepStorageKey);
        if (['player', 'health', 'documents', 'guardian', 'payment'].indexOf(savedStep) !== -1) {
            showStep(savedStep, false);
        }
    } catch (error) {}
})();
</script>

<?php
endwhile;
get_footer('societa');
?>
