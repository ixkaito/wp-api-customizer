<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
} else {
	delete_option( 'wp-api-customizer-options' );
}
