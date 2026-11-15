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
