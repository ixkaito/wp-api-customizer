<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function wp_api_customizer_uninstall() {
	global $wp_api_customizer;
	delete_option( $wp_api_customizer->options );
}

wp_api_customizer_unistall();
