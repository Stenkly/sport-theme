<?php
/**
 * Template Name: Pagina Iscriviti (Società)
 *
 * @package Sport_Theme
 */

get_header('societa');

while ( have_posts() ) : the_post();
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
                    </div>

                    <label class="iscrizione-acceptance">
                        <input type="checkbox" data-regolamento-check>
                        <span>Ho letto e accetto il regolamento.</span>
                    </label>

                    <a href="#modulo-iscrizione" class="iscrizione-cta is-disabled" aria-disabled="true" data-iscrizione-cta>
                        Inizia l'iscrizione <span aria-hidden="true">→</span>
                    </a>
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
            <h2>Dati del Giocatore</h2>

            <form class="iscrizione-player-form" action="#" method="post" data-player-form>
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
                        <input id="giocatore-data-nascita" type="date" name="giocatore_data_nascita" min="1990-01-01" max="<?php echo esc_attr( date('Y-m-d') ); ?>" required>
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
            <h2>Dati del Genitore</h2>
            <div class="iscrizione-placeholder">
                <p>Questa sarà la prossima parte dell'iscrizione. Per ora abbiamo collegato il bottone continua e la validazione dei dati del giocatore.</p>
            </div>
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
</main>

<script>
(function(){
    var modal = document.querySelector('[data-regolamento-modal]');
    var openBtn = document.querySelector('[data-regolamento-open]');
    var closeEls = document.querySelectorAll('[data-regolamento-close]');
    var acceptBtn = document.querySelector('[data-regolamento-accept]');
    var checkbox = document.querySelector('[data-regolamento-check]');
    var cta = document.querySelector('[data-iscrizione-cta]');
    var intro = document.querySelector('[data-iscrizione-intro]');
    var formSection = document.querySelector('[data-iscrizione-form]');
    var backBtn = document.querySelector('[data-iscrizione-back]');
    var playerForm = document.querySelector('[data-player-form]');
    var playerNext = document.querySelector('[data-player-next]');
    var stepTwo = document.querySelector('[data-iscrizione-step-two]');
    var stepTwoBack = document.querySelector('[data-step-two-back]');
    var playerErrors = document.querySelector('[data-player-errors]');
    var extraChildren = document.querySelector('[data-extra-children]');
    var addChildBtn = document.querySelector('[data-add-child]');
    var addChildrenBlock = document.querySelector('[data-add-children-block]');
    var nextChildIndex = 1;

    if (!modal || !openBtn || !checkbox || !cta || !intro || !formSection) return;

    function updateCta() {
        var accepted = checkbox.checked;
        cta.classList.toggle('is-disabled', !accepted);
        cta.setAttribute('aria-disabled', accepted ? 'false' : 'true');
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

    openBtn.addEventListener('click', openModal);
    closeEls.forEach(function(el){ el.addEventListener('click', closeModal); });
    checkbox.addEventListener('change', updateCta);

    if (acceptBtn) {
        acceptBtn.addEventListener('click', function(){
            checkbox.checked = true;
            updateCta();
            closeModal();
        });
    }

    cta.addEventListener('click', function(event){
        if (!checkbox.checked) {
            event.preventDefault();
            openModal();
            return;
        }

        event.preventDefault();
        intro.hidden = true;
        formSection.hidden = false;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    if (backBtn) {
        backBtn.addEventListener('click', function(){
            formSection.hidden = true;
            if (stepTwo) stepTwo.hidden = true;
            intro.hidden = false;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    if (playerNext && playerForm && stepTwo) {
        playerNext.addEventListener('click', function(){
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

            formSection.hidden = true;
            stepTwo.hidden = false;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    if (stepTwoBack && stepTwo) {
        stepTwoBack.addEventListener('click', function(){
            stepTwo.hidden = true;
            formSection.hidden = false;
            window.scrollTo({ top: 0, behavior: 'smooth' });
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

        function childOrdinal(number) {
            return ['primo', 'secondo', 'terzo', 'quarto'][number - 1] || number + '°';
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
            var visibleChildren = 1 + document.querySelectorAll('.iscrizione-child-section').length;
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
            var visibleChildren = 1 + document.querySelectorAll('.iscrizione-child-section').length;
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
            fields.appendChild(createChildField(childIndex, 'data_nascita', 'Data di nascita', 'date', { min: '1990-01-01', max: '<?php echo esc_js( date('Y-m-d') ); ?>', icon: 'fa-regular fa-calendar' }));
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
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        if (addChildBtn) {
            addChildBtn.addEventListener('click', addChildSection);
        }

        updateAddChildButton();
    }

    document.addEventListener('keydown', function(event){
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });

    updateCta();
})();
</script>

<?php
endwhile;
get_footer('societa');
?>
