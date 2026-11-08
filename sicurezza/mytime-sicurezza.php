<?php
/**
 * Plugin Name: My Time – Sicurezza
 * Description: Protezioni del sito ASD My Time: XML-RPC spento, nomi utente non scopribili, aggiornamenti automatici, niente modifica dei file dal pannello, meta description dai riassunti.
 * Version: 0.1.0
 * Author: Nicholas Barsanti
 * Author URI: https://nicholasbarsanti.it
 * Requires at least: 6.7
 * Requires PHP: 7.4
 */

defined( 'ABSPATH' ) || exit;

// Niente editor dei file di temi e plugin in WP Admin.
defined( 'DISALLOW_FILE_EDIT' ) || define( 'DISALLOW_FILE_EDIT', true );

// Aggiornamenti automatici: core (anche le versioni maggiori), plugin e temi.
add_filter( 'allow_major_auto_core_updates', '__return_true' );
add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );

// XML-RPC spento, pingback compresi. Su Apache xmlrpc.php è anche bloccato da .htaccess.
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', '__return_empty_array' );

// Enumerazione degli utenti: nessuna strada pubblica porta ai nomi utente.
add_action(
	'template_redirect',
	function () {
		if ( is_author() ) { // Copre anche ?author=1, prima del redirect canonico di WordPress.
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	},
	1
);
add_filter(
	'rest_endpoints',
	function ( $endpoints ) {
		if ( ! is_user_logged_in() ) {
			unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $endpoints;
	}
);
add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, $name ) {
		return 'users' === $name ? false : $provider;
	},
	10,
	2
);
add_filter(
	'oembed_response_data',
	function ( $data ) {
		unset( $data['author_name'], $data['author_url'] );
		return $data;
	}
);
// Stesso messaggio per nome utente sbagliato e password sbagliata.
add_filter(
	'authenticate',
	function ( $user ) {
		if ( is_wp_error( $user ) && array_intersect( $user->get_error_codes(), array( 'invalid_username', 'invalid_email', 'incorrect_password' ) ) ) {
			return new WP_Error( 'authentication_failed', '<strong>Errore:</strong> nome utente o password non corretti.' );
		}
		return $user;
	},
	99
);
// "Password dimenticata" con un utente inesistente risponde come se l'email fosse partita.
add_action(
	'lostpassword_post',
	function ( $errors, $user_data ) {
		if ( ! $user_data && ! $errors->has_errors() ) {
			wp_safe_redirect( add_query_arg( 'checkemail', 'confirm', wp_login_url() ) );
			exit;
		}
	},
	10,
	2
);

// Meta description dal riassunto di pagine e articoli (pannello "Riassunto" nell'editor).
add_action(
	'init',
	function () {
		add_post_type_support( 'page', 'excerpt' );
	}
);
add_action(
	'wp_head',
	function () {
		if ( is_singular() && has_excerpt() ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_strip_all_tags( get_the_excerpt() ) ) );
		}
	},
	1
);
