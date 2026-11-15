<?php
/**
 * Title: Mappa e distanza dalle palestre
 * Slug: mytime/mappa-distanza
 * Categories: mytime
 * Description: Mappa di OpenStreetMap con le palestre; il visitatore scrive il suo indirizzo e vede quanto dista da ognuna. Le posizioni delle palestre sono nell'attributo data-sedi (latitudine e longitudine).
 */
?>
<!-- wp:html -->
<div class="mt-mappa" data-sedi='[{"nome":"Camigliano","lat":43.8835,"lon":10.6020},{"nome":"Lunata","lat":43.8565,"lon":10.5681}]'>
	<form class="mt-mappa-form">
		<label for="mt-mappa-indirizzo">Il tuo indirizzo</label>
		<div class="mt-mappa-campo">
			<input id="mt-mappa-indirizzo" type="text" autocomplete="street-address" placeholder="Via, numero, comune" required>
			<button type="submit" class="wp-element-button">Calcola la distanza</button>
		</div>
	</form>
	<ul class="mt-mappa-risultati" aria-live="polite"></ul>
	<div class="mt-mappa-area"></div>
	<p class="mt-mappa-nota">Mappa e ricerca degli indirizzi sono di OpenStreetMap, che riceve il tuo indirizzo IP e l'indirizzo che cerchi (vedi la <a href="/privacy/">Privacy</a>). [DA COMPLETARE: le posizioni delle palestre sono approssimative (centro del paese), da sostituire con quelle vere quando arrivano gli indirizzi]</p>
	<noscript><p>Per usare la mappa serve JavaScript. Gli indirizzi delle palestre sono in <a href="/sedi-e-orari/">Sedi e orari</a>.</p></noscript>
</div>
<!-- /wp:html -->
