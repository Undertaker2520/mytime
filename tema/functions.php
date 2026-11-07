<?php
defined( 'ABSPATH' ) || exit;

// Categoria "My Time" nell'inseritore dei blocchi: i soci trovano i pattern del sito tutti insieme.
add_action(
	'init',
	function () {
		register_block_pattern_category( 'mytime', array( 'label' => 'My Time' ) );
	}
);
