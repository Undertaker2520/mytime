<?php
/**
 * Crea o aggiorna le pagine del sito con i pattern del tema.
 *
 * SOLO PER IL SITO LOCALE: sovrascrive il contenuto delle pagine (i riassunti restano).
 * Su OVH i contenuti si portano con Strumenti → Esporta / Importa, non con questo script.
 *
 * Uso (dalla cartella del sito Local): wp eval-file C:\sviluppo\mytime\strumenti\crea-pagine.php
 * Richiede le foto segnaposto nella Libreria media con titolo "Segnaposto Pexels: <nome>" (vedi README).
 * La Privacy non viene toccata. Link interni relativi, così funzionano anche su OVH.
 */

if ( 'local' !== wp_get_environment_type() ) {
	WP_CLI::error( 'Solo per il sito locale: questo script sovrascrive le pagine.' );
}

// Se un pattern manca (tema non attivo, cache) ci si ferma prima di scrivere qualsiasi pagina.
function mt_pattern( $slug ) {
	$p = WP_Block_Patterns_Registry::get_instance()->get_registered( 'mytime/' . $slug );
	if ( ! $p ) {
		WP_CLI::error( "Pattern mancante: mytime/$slug. Il tema My Time è attivo?" );
	}
	return $p['content'];
}
function mt_foto( $nome ) {
	$a = get_posts( array( 'post_type' => 'attachment', 'title' => 'Segnaposto Pexels: ' . $nome, 'numberposts' => 1, 'post_status' => 'inherit' ) );
	if ( ! $a ) {
		WP_CLI::error( "Foto mancante nella Libreria media: Segnaposto Pexels: $nome" );
	}
	return $a[0]->ID;
}
function mt_h( $testo, $livello = 2 ) {
	$attr = 2 === $livello ? '' : ' {"level":' . $livello . '}';
	return "<!-- wp:heading$attr -->\n<h$livello class=\"wp-block-heading\">$testo</h$livello>\n<!-- /wp:heading -->\n\n";
}
function mt_p( $html, $piccolo = false ) {
	return $piccolo
		? "<!-- wp:paragraph {\"fontSize\":\"small\"} -->\n<p class=\"has-small-font-size\">$html</p>\n<!-- /wp:paragraph -->\n\n"
		: "<!-- wp:paragraph -->\n<p>$html</p>\n<!-- /wp:paragraph -->\n\n";
}
function mt_bottone( $etichetta, $href, $contorno = false ) {
	$attr = $contorno ? ' {"className":"is-style-outline"}' : '';
	$cls  = $contorno ? ' is-style-outline' : '';
	return "<!-- wp:buttons -->\n<div class=\"wp-block-buttons\"><!-- wp:button$attr -->\n<div class=\"wp-block-button$cls\"><a class=\"wp-block-button__link wp-element-button\" href=\"$href\">$etichetta</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n";
}
function mt_img( $id, $taglia = 'large' ) {
	$u   = wp_get_attachment_image_url( $id, $taglia );
	$alt = esc_attr( get_post_meta( $id, '_wp_attachment_image_alt', true ) );
	return "<!-- wp:image {\"id\":$id,\"sizeSlug\":\"$taglia\",\"linkDestination\":\"none\",\"aspectRatio\":\"3/2\",\"scale\":\"cover\",\"style\":{\"border\":{\"radius\":\"12px\"}}} -->\n<figure class=\"wp-block-image size-$taglia has-custom-border\"><img src=\"$u\" alt=\"$alt\" class=\"wp-image-$id\" style=\"border-radius:12px;aspect-ratio:3/2;object-fit:cover\"/></figure>\n<!-- /wp:image -->\n\n";
}
// Griglia adattiva: al massimo $colonne, mai più strette di $minimo (su telefono una colonna).
function mt_griglia( $interno, $colonne, $minimo ) {
	return "<!-- wp:group {\"layout\":{\"type\":\"grid\",\"columnCount\":$colonne,\"minimumColumnWidth\":\"$minimo\"}} -->\n<div class=\"wp-block-group\">$interno</div>\n<!-- /wp:group -->\n\n";
}
// $larghezze facoltative, per esempio array( '33.33%', '66.66%' ).
function mt_colonne( array $colonne, array $larghezze = array() ) {
	$out = "<!-- wp:columns -->\n<div class=\"wp-block-columns\">";
	foreach ( $colonne as $i => $c ) {
		$w    = $larghezze[ $i ] ?? '';
		$out .= $w
			? "<!-- wp:column {\"width\":\"$w\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:$w\">$c</div>\n<!-- /wp:column -->\n\n"
			: "<!-- wp:column -->\n<div class=\"wp-block-column\">$c</div>\n<!-- /wp:column -->\n\n";
	}
	return $out . "</div>\n<!-- /wp:columns -->\n\n";
}
// Domanda frequente: blocco Dettagli (si apre e chiude senza JavaScript).
function mt_faq( $domanda, $risposta ) {
	return "<!-- wp:details -->\n<details class=\"wp-block-details\"><summary>$domanda</summary>" . mt_p( $risposta ) . "</details>\n<!-- /wp:details -->\n\n";
}
// Scheda corso dal pattern: ancora (#zumba), colore, foto in cima e testi.
function mt_corso( $nome, $ancora, $colore, $foto, $descrizione, $dove ) {
	$c = mt_pattern( 'scheda-corso' );
	$c = str_replace( '{"className":"mt-scheda-corso","borderColor":"corso-azzurro"', '{"anchor":"' . $ancora . '","className":"mt-scheda-corso","borderColor":"' . $colore . '"', $c );
	$c = preg_replace( '#<div class="wp-block-group mt-scheda-corso has-border-color has-corso-azzurro-border-color"([^>]*)>#', '<div id="' . $ancora . '" class="wp-block-group mt-scheda-corso has-border-color has-' . $colore . "-border-color\"$1>\n" . mt_img( mt_foto( $foto ) ), $c, 1 );
	return str_replace(
		array( 'Nome del corso', '[DA COMPLETARE: descrizione del corso in 2–3 righe]', '[DA COMPLETARE: sede, giorni e orari, per esempio Camigliano · martedì 18:30, venerdì 18:30]' ),
		array( $nome, $descrizione, $dove ),
		$c
	);
}
function mt_sede( $nome, $tipo, $foto ) {
	$c = str_replace( array( '<h2 class="wp-block-heading">Camigliano</h2>', 'Palestrina della scuola primaria' ), array( "<h2 class=\"wp-block-heading\">$nome</h2>", $tipo ), mt_pattern( 'scheda-sede' ) );
	return preg_replace( '#(<div class="wp-block-group mt-scheda-sede[^>]*>)#', "$1\n" . mt_img( mt_foto( $foto ) ), $c, 1 );
}
function mt_orari( $sede, $righe ) {
	$t    = str_replace( 'Orari a Camigliano', "Orari a $sede", mt_pattern( 'tabella-orari' ) );
	$body = '';
	foreach ( $righe as $r ) {
		$body .= '<tr><td>' . implode( '</td><td>', $r ) . '</td></tr>';
	}
	return preg_replace( '#<tbody>.*</tbody>#s', "<tbody>$body</tbody>", $t );
}
// Scheda piccola dell'anteprima in Home: tutta cliccabile verso la scheda in Corsi.
function mt_mini_corso( $nome, $ancora, $foto, $sedi ) {
	return "<!-- wp:group {\"className\":\"mt-mini-corso\"} -->\n<div class=\"wp-block-group mt-mini-corso\">" . mt_img( mt_foto( $foto ), 'medium' )
		. mt_h( "<a href=\"/corsi/#$ancora\">$nome</a>", 3 ) . mt_p( $sedi, true ) . "</div>\n<!-- /wp:group -->\n\n";
}
function mt_proposta( $titolo, $testo ) {
	return "<!-- wp:group {\"className\":\"mt-proposta\",\"backgroundColor\":\"accent-2\",\"style\":{\"border\":{\"radius\":\"16px\"},\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|40\",\"bottom\":\"var:preset|spacing|40\",\"left\":\"var:preset|spacing|40\",\"right\":\"var:preset|spacing|40\"}}}} -->\n<div class=\"wp-block-group mt-proposta has-accent-2-background-color has-background\" style=\"border-radius:16px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)\">"
		. mt_h( $titolo, 3 ) . mt_p( $testo ) . "</div>\n<!-- /wp:group -->\n\n";
}

// Dati: gli stessi pubblicati sul sito. Gli orari sono anche nelle tabelle delle sedi.
$corsi = array(
	array( 'Zumba', 'zumba', 'corso-azzurro', 'zumba', 'Allenamento a ritmo di musica latina e internazionale: passi semplici, tanta energia e il piacere di ballare in gruppo.', 'Camigliano · martedì 18:30, venerdì 18:30<br>Lunata · martedì 20:00, giovedì 20:00', 'Camigliano · Lunata' ),
	array( 'Pilates', 'pilates', 'corso-viola', 'pilates', 'Esercizi a corpo libero lenti e controllati per rinforzare addome e schiena, migliorare la postura e la flessibilità.', 'Camigliano · martedì 19:15 e 20:00, giovedì 19:15 e 20:00', 'Camigliano' ),
	array( 'Aerofantasy', 'aerofantasy', 'corso-verde', 'aerofantasy', 'Coreografie aerobiche a tempo di musica, per allenare il cuore e la coordinazione divertendosi.', 'Camigliano · mercoledì 19:30, venerdì 19:15', 'Camigliano' ),
	array( 'Allenamento funzionale', 'allenamento-funzionale', 'corso-giallo', 'funzionale', 'Esercizi che riprendono i movimenti di tutti i giorni per migliorare forza, equilibrio e resistenza, ognuno al proprio ritmo.', 'Camigliano · lunedì 19:30, mercoledì 20:15', 'Camigliano' ),
	array( 'GAG', 'gag', 'corso-azzurro', 'gag', 'Lezione dedicata a gambe, addominali e glutei: esercizi mirati per tonificare la parte bassa del corpo.', 'Lunata · giovedì 19:00', 'Lunata' ),
	array( 'Step GAG', 'step-gag', 'corso-viola', 'step', 'Gambe, addominali e glutei con lo step, la pedana su cui si sale e si scende a ritmo di musica.', 'Lunata · martedì 19:00', 'Lunata' ),
	array( 'HIIT GAG', 'hiit-gag', 'corso-verde', 'hiit', 'Gambe, addominali e glutei in versione HIIT: brevi intervalli di esercizio intenso alternati a pause di recupero.', 'Lunata · lunedì 21:00', 'Lunata' ),
	array( 'Tonificazione', 'tonificazione', 'corso-giallo', 'tonificazione', 'Esercizi per rinforzare e modellare tutti i gruppi muscolari, per sentirsi più forti e in forma.', 'Lunata · giovedì 20:45', 'Lunata' ),
);
$camigliano = array(
	array( 'lunedì', '19:30', 'Allenamento funzionale' ), array( 'martedì', '18:30', 'Zumba' ), array( 'martedì', '19:15 e 20:00', 'Pilates' ),
	array( 'mercoledì', '19:30', 'Aerofantasy' ), array( 'mercoledì', '20:15', 'Allenamento funzionale' ), array( 'giovedì', '19:15 e 20:00', 'Pilates' ),
	array( 'venerdì', '18:30', 'Zumba' ), array( 'venerdì', '19:15', 'Aerofantasy' ),
);
$lunata = array(
	array( 'lunedì', '21:00', 'HIIT GAG' ), array( 'martedì', '19:00', 'Step GAG' ), array( 'martedì', '20:00', 'Zumba' ),
	array( 'giovedì', '19:00', 'GAG' ), array( 'giovedì', '20:00', 'Zumba' ), array( 'giovedì', '20:45', 'Tonificazione' ),
);
$periodo = 'I corsi fanno parte dei corsi sportivi comunali del Comune di Capannori e durano dal 1° ottobre al 30 aprile.';
$invito  = mt_pattern( 'invito-contatti' );
$sede_c  = mt_sede( 'Camigliano', 'Palestrina della scuola primaria', 'sede-camigliano' );
$sede_l  = mt_sede( 'Lunata', 'Palestra della scuola primaria', 'sede-lunata' );

// Apertura con foto (velatura al 70% come nel pattern).
$foto_ap  = mt_foto( 'apertura' );
$u        = wp_get_attachment_image_url( $foto_ap, 'full' );
$apertura = str_replace(
	array( '"dimRatio":70,', 'has-background-dim-70 has-background-dim"></span>' ),
	array( '"url":"' . $u . '","id":' . $foto_ap . ',"dimRatio":70,', 'has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-' . $foto_ap . '" alt="" src="' . $u . '" data-object-fit="cover"/>' ),
	mt_pattern( 'apertura' )
);

$mini = $schede = '';
foreach ( $corsi as $c ) {
	$mini   .= mt_mini_corso( $c[0], $c[1], $c[3], $c[6] );
	$schede .= mt_corso( $c[0], $c[1], $c[2], $c[3], $c[4], $c[5] );
}
$avvisi = '<!-- wp:query {"queryId":1,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16rem"}} -->
<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-date /-->

<!-- wp:post-excerpt /-->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>Al momento non ci sono avvisi.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->

';
// Gli stessi pulsanti dell'invito (Chiama e WhatsApp per ogni numero), per la scheda di Contatti.
$bottoni_contatti = '<!-- wp:buttons -->' . explode( '<!-- /wp:buttons -->', explode( '<!-- wp:buttons -->', $invito, 2 )[1] )[0] . "<!-- /wp:buttons -->\n\n";
function mt_scheda_contatti( $interno ) {
	return "<!-- wp:group {\"className\":\"mt-scheda-contatti\",\"backgroundColor\":\"accent-2\",\"style\":{\"border\":{\"radius\":\"16px\"},\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|40\",\"bottom\":\"var:preset|spacing|40\",\"left\":\"var:preset|spacing|40\",\"right\":\"var:preset|spacing|40\"}}}} -->\n<div class=\"wp-block-group mt-scheda-contatti has-accent-2-background-color has-background\" style=\"border-radius:16px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)\">$interno</div>\n<!-- /wp:group -->\n\n";
}

$pagine = array(
	'home'               => array(
		'Home',
		$apertura
		. mt_h( 'I nostri corsi' ) . mt_griglia( $mini, 4, '14rem' ) . mt_bottone( 'Scopri i corsi', '/corsi/' )
		. mt_pattern( 'come-iscriversi' )
		. mt_h( 'Dove ci trovi' ) . mt_griglia( $sede_c . $sede_l, 2, '20rem' ) . mt_bottone( 'Vedi tutti gli orari', '/sedi-e-orari/', true )
		. mt_h( 'Altre proposte' ) . mt_griglia(
			mt_proposta( 'Fit &amp; Walk', '[DA COMPLETARE: descrizione]' )
			. mt_proposta( 'My Time Focus', 'Allenamento in piccoli gruppi, da 3 a 6 persone, personalizzato.' )
			. mt_proposta( 'My Time Performance', 'Per aumentare e migliorare le proprie capacità. [DA COMPLETARE: dettagli]' ),
			3,
			'16rem'
		)
		. mt_h( 'Avvisi' ) . $avvisi
		. $invito,
	),
	'corsi'              => array(
		'Corsi',
		mt_p( $periodo ) . mt_griglia( $schede, 3, '18rem' ) . mt_p( '[DA COMPLETARE: Step e Stretching sono attivi quest\'anno? Dove e quando?]' ) . $invito,
	),
	'sedi-e-orari'       => array(
		'Sedi e orari',
		mt_p( 'Orari 2026-2027. ' . $periodo )
		. mt_colonne( array( $sede_c, mt_orari( 'Camigliano', $camigliano ) ) )
		. mt_colonne( array( $sede_l, mt_orari( 'Lunata', $lunata ) ) )
		. $invito,
	),
	'iscrizioni-e-costi' => array(
		'Iscrizioni e costi',
		mt_colonne(
			array(
				mt_h( 'Domande frequenti' ) . mt_p( 'Non trovi la risposta? Chiamaci o scrivici su WhatsApp: ti rispondiamo volentieri.' ),
				mt_faq( 'Quando iniziano e finiscono i corsi?', 'Durano 7 mesi, dal 1° ottobre al 30 aprile. Fanno parte dei corsi sportivi comunali del Comune di Capannori.' )
				. mt_faq( 'Quanto costano?', 'Le tariffe comunali 2026-2027 vanno da 140 a 180 € a seconda della fascia (ragazzi, adulti, over 65); l\'eventuale mese di proroga costa fino a 28 €. [DA COMPLETARE: tariffe dei corsi My Time]. Tutti i dettagli sono sul <a href="https://www.comune.capannori.lu.it/notizia/giovedi-1-ottobre-iniziano-i-corsi-sportivi-comunali-2026-2027-iscrizioni-aperte/">sito del Comune di Capannori</a>.' )
				. mt_faq( 'Come mi iscrivo?', 'Contattaci per telefono, WhatsApp o email: ti spieghiamo tutto noi. Le iscrizioni non si fanno online.' )
				. mt_faq( 'Quali documenti servono?', '[DA COMPLETARE: per esempio il certificato medico]' )
				. mt_faq( 'Dove si svolgono i corsi?', 'Nelle palestre delle scuole primarie di Camigliano e Lunata. Giorni e orari sono in <a href="/sedi-e-orari/">Sedi e orari</a>.' )
				. mt_faq( 'Chi può partecipare?', '[DA COMPLETARE: età e livello richiesti per ciascun corso]' )
				. mt_faq( 'Posso provare una lezione prima di iscrivermi?', '[DA COMPLETARE]' ),
			),
			array( '33.33%', '66.66%' )
		)
		. mt_pattern( 'come-iscriversi' )
		. $invito,
	),
	'contatti'           => array(
		'Contatti',
		mt_colonne(
			array(
				mt_scheda_contatti(
					mt_h( 'Chiamaci o scrivici' )
					. mt_p( 'Per informazioni su corsi, orari e iscrizioni: ti rispondiamo volentieri. Gli stessi numeri sono anche su WhatsApp.' )
					. $bottoni_contatti
					. mt_p( 'Email: <a href="mailto:mytime.asd@libero.it">mytime.asd@libero.it</a><br>Instagram: <a href="https://www.instagram.com/mytime.sd/">@mytime.sd</a><br>Facebook: <a href="https://www.facebook.com/profile.php?id=100063509229571">ASD My Time</a> [DA COMPLETARE: confermare che la pagina è ancora attiva]' )
					. mt_p( '[DA COMPLETARE: a chi corrispondono i due numeri]', true )
				),
				mt_h( 'Quanto sei lontano dalle palestre?' ) . mt_pattern( 'mappa-distanza' ),
			)
		),
	),
);

foreach ( $pagine as $slug => list( $titolo, $contenuto ) ) {
	$esistente = get_page_by_path( $slug );
	$dati      = array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $titolo, 'post_name' => $slug, 'post_content' => $contenuto );
	if ( $esistente ) {
		$dati['ID'] = $esistente->ID;
	}
	// wp_update_post conserva i campi non passati, riassunto compreso.
	$id = $esistente ? wp_update_post( wp_slash( $dati ), true ) : wp_insert_post( wp_slash( $dati ), true );
	if ( is_wp_error( $id ) ) {
		WP_CLI::error( "$slug: " . $id->get_error_message() );
	}
	if ( 'home' === $slug ) {
		update_post_meta( $id, '_wp_page_template', 'page-no-title' );
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $id );
	}
	WP_CLI::log( "$slug → $id" );
}
