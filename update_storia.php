<?php
require_once('/Users/stanoje/Local Sites/ac-taverne/app/public/wp-load.php');

$page = get_page_by_path('storia');
if (!$page) {
    echo "Pagina storia non trovata.";
    die();
}

$subtitle = "La storia della prima squadra di Taverne affonda le sue radici negli anni Venti, quando nacque il Football Club Stella Taverne, prima vera formazione locale, seppur con notizie frammentarie e denominazioni variabili. Inizialmente si giocava nella zona di Livorno, lungo il corso del Vedeggio, con una caratteristica maglia nera impreziosita da una stella bianca sul petto. Successivamente il campo si spostò a Taverne Superiore, nel Comune di Sigirino, e negli anni Quaranta, con il F.C. Taverne, nell’area della stazione, tra il fiume e la ferrovia.";

$content = <<<HTML
<h3>IDENTITÀ DEL CLUB</h3>
<h4>Nel secondo dopoguerra nacque l’Associazione Calcio Taverne, sostenuta da grande entusiasmo popolare.</h4>
<p>La società divenne un punto di riferimento sportivo per tutta la valle, grazie a un ambiente favorevole, buoni allenatori e una solida comunità. A questo periodo, definito “eroico”, sono legati nomi importanti come Mario Banfi, Flaminio Petrocchi e Gino Gova, mentre già negli anni Trenta si distinsero a livello regionale i fratelli Zambelli, in particolare il portiere Emilio, soprannominato “Zamorra”.</p>
<p>Un momento chiave arrivò nel 1950, quando l’AC Taverne entrò ufficialmente nella Federazione calcistica ticinese partecipando al campionato di Quarta Divisione, ottenendo subito la promozione in Terza. La crescita proseguì fino alla stagione 1956-57, in cui, sotto la guida di Bruno Passardi, la squadra conquistò il titolo di campione di Terza Divisione. L’anno successivo, con Dino Leoni alla guida, il Taverne si confermò vincendo sia il campionato sia il Trofeo Ticino, segnando una delle pagine più significative della sua storia.</p>

<h3>EVOLUZIONE NEL TEMPO</h3>
<h4>Nel corso della sua storia, il Taverne ha costruito un percorso solido e coerente, caratterizzato da tappe significative e da una crescita costante nel panorama calcistico regionale e nazionale.</h4>
<p>Tra gli anni Ottanta e Novanta, la prima squadra ha partecipato con regolarità ai campionati di Seconda e Terza Lega, consolidando la propria presenza e gettando le basi per i successi futuri. Un primo importante salto di qualità si registra nella stagione 2009-2010, quando il Taverne conquista il terzo rango in Seconda Lega regionale ottenendo la promozione in Seconda Lega Interregionale. Si tratta di un traguardo storico, che segna l’ingresso del club, a partire dal 2010, in un contesto di competizione nazionale.</p>
<p>Nel campionato 2011-2012, il Taverne tenta la scalata alla Seconda Lega élite, categoria già raggiunta con merito due anni prima e sfortunatamente persa nella stagione successiva. L’annata seguente, 2012-2013, si apre con segnali positivi: la squadra si presenta briosa e promettente, mantenendo il primo posto in classifica al termine del girone d’andata.</p>
<p>A partire dalla stagione 2019-2020, il Taverne milita stabilmente in Prima Lega Classic, raggiungendo il livello più alto nella storia della società dopo alcune stagioni di consolidamento nei campionati interregionali.</p>

<h3>IL SETTORE GIOVANILE E I SUCCESSI</h3>
<h4>Parallelamente ai risultati della prima squadra, il club ha sempre attribuito grande importanza al settore giovanile. I ragazzi delle categorie Allievi, sempre più numerosi, rappresentano una risorsa fondamentale e una prospettiva concreta per il futuro.</h4>
<p>Il loro sviluppo è affidato a dirigenti, allenatori e preparatori che privilegiano una crescita progressiva e duratura rispetto ai risultati immediati.</p>
<p>Nel corso degli anni, il club ha collezionato numerosi successi, tra cui:</p>
<ul>
<li>Campione ticinese di Terza Divisione e promozione in Seconda Lega (stagione 1956-1957)</li>
<li>Campione ticinese di Seconda Divisione (stagione 1958-1959)</li>
<li>Vincitore di gruppo di Terza Divisione e promozione in Seconda Lega (stagione 1979-1980)</li>
<li>Vincitore di gruppo di Terza Lega e promozione in Seconda Lega (stagioni 1992-1993 e 2004-2005)</li>
<li>Terzo posto in Seconda Lega regionale e promozione in Seconda Lega Interregionale (stagione 2009-2010)</li>
</ul>
<p>A questi si aggiungono i risultati del settore giovanile e della seconda squadra:</p>
<ul>
<li>Campione ticinese Allievi A e promozione nella categoria Interregionale (stagione 1986-1987)</li>
<li>Seconda squadra campione di gruppo in Quinta Lega e promossa in Quarta Lega (stagione 2007-2008)</li>
</ul>
<p>Di particolare rilievo anche il percorso nelle competizioni regionali: il Taverne ha conquistato sei Coppe Ticino, stabilendo un record prestigioso, e ha ottenuto un primo e un secondo posto nella Coppa Campioni del calcio regionale ticinese.</p>
<p>Dalla stagione attuale, la prima squadra si presenta con un nuovo assetto societario, segnando l’inizio di una nuova fase nel percorso di sviluppo del club, nel segno della continuità e dell’attenzione alla propria storia.</p>
HTML;

wp_update_post([
    'ID' => $page->ID,
    'post_content' => $content,
    'post_excerpt' => $subtitle
]);

echo "Storia aggiornata con successo!";
