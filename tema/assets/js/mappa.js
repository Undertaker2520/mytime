/*
 * Mappa della pagina Contatti: Leaflet (servito dal tema, caricato prima di questo file) con le tessere di OpenStreetMap.
 * La distanza è in linea d'aria; per il percorso stradale c'è il link a OpenStreetMap.
 */
( function () {
	const box = document.querySelector( '.mt-mappa' );
	if ( ! box || ! window.L ) {
		return;
	}
	const sedi = JSON.parse( box.dataset.sedi );
	const form = box.querySelector( '.mt-mappa-form' );
	const campo = form.querySelector( 'input' );
	const area = box.querySelector( '.mt-mappa-area' );
	const risultati = box.querySelector( '.mt-mappa-risultati' );
	const ridotto = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	let mappa, segnaposto;

	// Distanza in km tra due punti [lat, lon] (formula dell'emisenoverso).
	function distanzaKm( a, b ) {
		const rad = ( x ) => ( x * Math.PI ) / 180;
		const dLat = rad( b[ 0 ] - a[ 0 ] );
		const dLon = rad( b[ 1 ] - a[ 1 ] );
		const h = Math.sin( dLat / 2 ) ** 2 + Math.cos( rad( a[ 0 ] ) ) * Math.cos( rad( b[ 0 ] ) ) * Math.sin( dLon / 2 ) ** 2;
		return 2 * 6371 * Math.asin( Math.sqrt( h ) );
	}

	function messaggio( testo ) {
		risultati.replaceChildren( Object.assign( document.createElement( 'li' ), { textContent: testo } ) );
	}

	mappa = window.L.map( area, { scrollWheelZoom: false, zoomAnimation: ! ridotto, fadeAnimation: ! ridotto, markerZoomAnimation: ! ridotto } );
	window.L.tileLayer( 'https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	} ).addTo( mappa );
	sedi.forEach( ( s ) => window.L.marker( [ s.lat, s.lon ], { title: s.nome, alt: 'Palestra di ' + s.nome } ).addTo( mappa ).bindPopup( 'Palestra di ' + s.nome ) );
	mappa.fitBounds( sedi.map( ( s ) => [ s.lat, s.lon ] ), { padding: [ 60, 60 ] } );

	form.addEventListener( 'submit', async ( evento ) => {
		evento.preventDefault();
		const indirizzo = campo.value.trim();
		if ( ! indirizzo ) {
			return;
		}
		messaggio( 'Cerco l\'indirizzo…' );
		let punto;
		try {
			const url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&countrycodes=it&accept-language=it&q=' + encodeURIComponent( indirizzo );
			punto = ( await ( await fetch( url, { headers: { Accept: 'application/json' } } ) ).json() )[ 0 ];
		} catch ( e ) {
			messaggio( 'Il servizio di ricerca non risponde: riprova tra poco.' );
			return;
		}
		if ( ! punto ) {
			messaggio( 'Indirizzo non trovato: prova ad aggiungere il comune, per esempio "Via Roma 1, Capannori".' );
			return;
		}
		const da = [ Number( punto.lat ), Number( punto.lon ) ];
		const righe = sedi
			.map( ( s ) => ( { s, km: distanzaKm( da, [ s.lat, s.lon ] ) } ) )
			.sort( ( a, b ) => a.km - b.km )
			.map( ( { s, km } ) => {
				const li = document.createElement( 'li' );
				const nome = Object.assign( document.createElement( 'strong' ), { textContent: s.nome } );
				const link = Object.assign( document.createElement( 'a' ), {
					href: 'https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=' + da.join( ',' ) + ';' + s.lat + ',' + s.lon,
					textContent: 'Percorso in auto',
					target: '_blank',
					rel: 'noopener',
				} );
				li.append( nome, ': ' + km.toFixed( 1 ).replace( '.', ',' ) + ' km in linea d\'aria · ', link );
				return li;
			} );
		risultati.replaceChildren( ...righe );
		if ( segnaposto ) {
			segnaposto.setLatLng( da );
		} else {
			const colore = getComputedStyle( document.documentElement ).getPropertyValue( '--wp--preset--color--accent-1' ).trim();
			segnaposto = window.L.circleMarker( da, { radius: 9, color: colore, weight: 3, fillOpacity: 0.9 } ).addTo( mappa ).bindPopup( 'Il tuo indirizzo' );
		}
		mappa.fitBounds( [ da, ...sedi.map( ( s ) => [ s.lat, s.lon ] ) ], { padding: [ 40, 40 ] } );
	} );
}() );
