<?php
defined( 'ABSPATH' ) || exit;

// Twenty Twenty-Five carica solo il proprio style.css: il nostro va aggiunto.
add_action(
	'wp_enqueue_scripts',
	function () {
		// La versione è la data di modifica del file: ogni cambiamento svuota la cache dei browser.
		wp_enqueue_style( 'mytime', get_stylesheet_uri(), array(), (string) filemtime( get_stylesheet_directory() . '/style.css' ) );
	}
);

// Script della mappa solo nelle pagine che la contengono (Corsi, Sedi e orari, Contatti): nel resto del sito niente JavaScript nostro.
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( is_singular() && false !== strpos( (string) get_post_field( 'post_content', get_queried_object_id() ), 'mt-mappa' ) ) {
			wp_enqueue_style( 'leaflet', get_theme_file_uri( 'assets/leaflet/leaflet.css' ), array(), '1.9.4' );
			wp_enqueue_script( 'leaflet', get_theme_file_uri( 'assets/leaflet/leaflet.js' ), array(), '1.9.4', array( 'strategy' => 'defer', 'in_footer' => true ) );
			$file = get_stylesheet_directory() . '/assets/js/mappa.js';
			wp_enqueue_script( 'mytime-mappa', get_theme_file_uri( 'assets/js/mappa.js' ), array( 'leaflet' ), (string) filemtime( $file ), array( 'strategy' => 'defer', 'in_footer' => true ) );
		}
	}
);

// Solo la prima immagine della pagina (la foto dell'Apertura o della prima scheda) si carica subito; le altre quando servono.
add_filter(
	'wp_omit_loading_attr_threshold',
	function () {
		return 1;
	}
);

// Categoria "My Time" nell'inseritore dei blocchi: i soci trovano i pattern del sito tutti insieme.
add_action(
	'init',
	function () {
		register_block_pattern_category( 'mytime', array( 'label' => 'My Time' ) );
	}
);
