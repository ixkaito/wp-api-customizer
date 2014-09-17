<?php
/*
Plugin Name: WP API Customizer
Plugin URI:  https://github.com/ixkaito/wp-api-customizer
Author:      KITE
Author URI:  http://kiteretz.com
Version:     0.0.1
Description:
Text Domain: wp-api-customizer
Domain Path: /languages
License:     GPLv2
*/

define( 'WP_API_CUSTOMIZER_URL', plugins_url( '', __FILE__ ) );
define( 'WP_API_CUSTOMIZER_PATH', dirname( __FILE__ ) );

class WP_API_Customizer {

	private $ver   = '';
	private $langs = '';

	public $domain = 'wp-api-customizer';

	public function __construct() {
		$data = get_file_data( __FILE__, array( 'ver' => 'Version', 'langs' => 'Domain Path' ) );
		$this->ver   = $data['ver'];
		$this->langs = $data['langs'];

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		// add_filter( 'json_prepare_post', array( $this, 'customizer' ), 10, 3);
	}

	public function plugins_loaded() {
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . $this->langs );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_menu() {
		add_options_page(
			__( 'WP API Customizer' ),
			__( 'WP API Customizer' ),
			'manage_options',
			'wp-api-customizer',
			array( $this, 'options_page' )
		);
	}

	public function admin_init() {
		if ( isset( $_POST['_wpnonce'] ) && $_POST['_wpnonce'] ) {
			// save something
			wp_safe_redirect( 'options-general.php?page=wp-api-customizer' );
		}
	}

	public function options_page() {
		// form
	}

	public function customizer( $data, $post, $context ) {
		$data['post_meta'] = array(
			'a' => get_post_meta( $post['ID'], 'public_post_meta_a', true ),
			'b' => get_post_meta( $post['ID'], 'public_post_meta_b', true )
		);
		return $data;
	}

}

new WP_API_Customizer();
