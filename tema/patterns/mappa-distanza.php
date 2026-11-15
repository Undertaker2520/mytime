<?php
/**
 * Title: Mappa e distanza dalle palestre
 * Slug: mytime/mappa-distanza
 * Categories: mytime
 * Description: Mappa di OpenStreetMap che si carica solo al clic; il visitatore scrive il suo indirizzo e vede quanto dista da ogni palestra. Le posizioni delle palestre sono nell'attributo data-sedi (latitudine e longitudine).
 */
?>
<!-- wp:html -->
<div class="mt-mappa" data-sedi='[{"nome":"Camigliano","lat":43.8835,"lon":10.6020},{"nome":"Lunata","lat":43.8565,"lon":10.5681}]'>
	<p class="mt-mappa-nota">[DA COMPLETARE: le posizioni delle palestre sono approssimative (centro del paese): sostituirle con quelle vere quando arrivano gli indirizzi]</p>
	<p>La mappa viene da OpenStreetMap: si carica solo se premi il pulsante. Se cerchi un indirizzo, OpenStreetMap riceve l'indirizzo che scrivi e il tuo indirizzo IP (vedi la <a href="/privacy/">Privacy</a>).</p>
	<button type="button" class="wp-element-button mt-mappa-mostra">Mostra la mappa</button>
	<form class="mt-mappa-form" hidden>
		<label for="mt-mappa-indirizzo">Il tuo indirizzo</label>
		<div class="mt-mappa-campo">
			<input id="mt-mappa-indirizzo" type="text" autocomplete="street-address" placeholder="Via, numero, comune" required>
			<button type="submit" class="wp-element-button">Calcola la distanza</button>
		</div>
	</form>
	<ul class="mt-mappa-risultati" aria-live="polite"></ul>
	<div class="mt-mappa-area" hidden></div>
	<noscript><p>Per usare la mappa serve JavaScript. Gli indirizzi delle palestre sono in <a href="/sedi-e-orari/">Sedi e orari</a>.</p></noscript>
</div>
<!-- /wp:html -->
