<?php
defined( 'ABSPATH' ) || exit;

// Twenty Twenty-Five carica solo il proprio style.css: il nostro va aggiunto.
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'mytime', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
	}
);

// Categoria "My Time" nell'inseritore dei blocchi: i soci trovano i pattern del sito tutti insieme.
add_action(
	'init',
	function () {
		register_block_pattern_category( 'mytime', array( 'label' => 'My Time' ) );
	}
);
