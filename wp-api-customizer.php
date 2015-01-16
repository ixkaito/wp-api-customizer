<?php
/*
Plugin Name: WP API Customizer
Plugin URI:  https://github.com/ixkaito/wp-api-customizer
Author:      Kite
Author URI:  http://kiteretz.com
Version:     0.0.2
Description: Make post meta data (custom field values) available for JSON REST API (WP API) when unauthenticated.
Text Domain: wp-api-customizer
Domain Path: /languages
License:     GPLv2
*/

define( 'WP_API_CUSTOMIZER_URL', plugins_url( '', __FILE__ ) );
define( 'WP_API_CUSTOMIZER_PATH', dirname( __FILE__ ) );

class WP_API_Customizer {

	public $version   = '';
	public $languages = '';
	public $domain    = '';
	public $options   = '';

	public function __construct() {
		$data = get_file_data( __FILE__, array( 'version' => 'Version', 'languages' => 'Domain Path', 'domain' => 'Text Domain' ) );
		$this->version   = $data['version'];
		$this->languages = $data['languages'];
		$this->domain    = $data['domain'];
		$this->options   = $this->domain . '-options';
		$this->nonce     = $this->domain . '-nonce';

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded() {
		$options = get_option( $this->options );

		load_plugin_textdomain( $this->domain, false, dirname( plugin_basename( __FILE__ ) ) . $this->languages );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_filter( 'json_prepare_post', array( $this, 'json_prepare_post' ), 10, 3);
	}

	public function admin_enqueue_scripts() {
		if ( isset( $_GET['page'] ) && $_GET['page'] === $this->domain ) {
			wp_enqueue_style(
				'admin-' . $this->domain . '-style',
				plugins_url( 'css/admin-' . $this->domain . '.css', __FILE__ ),
				array(),
				$this->version,
				'all'
			);

			wp_enqueue_script(
				'admin-' . $this->domain . '-script',
				plugins_url( 'js/admin-' . $this->domain . '.min.js', __FILE__ ),
				array( 'jquery' ),
				$this->version,
				true
			);
		}
	}

	public function admin_menu() {
		add_menu_page(
			__( 'WP API Customizer', $this->domain ),
			__( 'WP API Customizer', $this->domain ),
			'manage_options',
			$this->domain,
			array( $this, 'options_page' ),
			'dashicons-admin-generic'
		);
	}

	public function admin_init() {
		if ( isset( $_POST[ $this->nonce ] ) && $_POST[ $this->nonce ] ) {
			if ( check_admin_referer( $this->options, $this->nonce ) ) {
				if ( isset( $_POST[ $this->options ] ) ) {
					$options = $_POST[ $this->options ];
					update_option( $this->options, $options );
				} else {
					update_option( $this->options, '' );
				}
				wp_safe_redirect( menu_page_url( $this->domain, false ) );
			}
		}
	}

	public function options_page() {
		?>
		<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
			<h2 style="margin-bottom: 15px;"><?php _e( 'WP API Customizer', $this->domain ); ?></h2>
			<form action="" method="post">
				<?php
					wp_nonce_field( $this->options, $this->nonce );
					$options = get_option( $this->options );
				?>
				<table class="wp-list-table widefat fixed" id="<?php echo esc_attr( $this->options ); ?>">
					<thead>
						<tr>
							<th class="column-remove" id="cb" scope="col"></th>
							<th scope="col"><?php _e( 'JSON Attribute', $this->domain ); ?></th>
							<th scope="col"><?php _e( 'Custom Field Name', $this->domain ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th class="column-add" scope="col"><a href="#" class="dashicons-before dashicons-plus add-option"></a></th>
							<th scope="col"><?php _e( 'JSON Attribute', $this->domain ); ?></th>
							<th scope="col"><?php _e( 'Custom Field Name', $this->domain ); ?></th>
						</tr>
					</tfoot>
					<tbody id="the-list">
						<?php if ( isset( $options ) && is_array( $options ) ) : ?>
							<?php foreach ( $options as $key => $option ) : ?>
								<tr>
									<th class="column-remove" scope="row">
										<a href="#" class="dashicons-before dashicons-minus remove-option"></a>
									</th>
									<td>
										<input type="text" placeholder="" value="<?php echo esc_attr( $option['json-attribute'] ); ?>" name="<?php echo esc_attr( "$this->options[$key][json-attribute]" ); ?>" class="text" id="<?php echo esc_attr( "{$this->options}_{$key}_json-attribute" ); ?>" />
									</td>
									<td>
										<input type="text" placeholder="" value="<?php echo esc_attr( $option['custom-field-name'] ); ?>" name="<?php echo esc_attr( "$this->options[$key][custom-field-name]" ); ?>" class="text" id="<?php echo esc_attr( "{$this->options}_{$key}_custom-field-name" ); ?>" />
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />
				</p>
			</form>
		</div><!-- /.wrap -->
		<?php
	}

	public function json_prepare_post( $data, $post, $context ) {

		$options = get_option( $this->options );

		if ( isset( $options ) && is_array( $options ) ) {
			foreach ( $options as $key => $option ) {
				$attribute         = $option['json-attribute'];
				$custom_field_name = $option['custom-field-name'];
				$data['post_meta'][ $attribute ] = get_post_meta( $post['ID'], $custom_field_name, true );
			}

		}

		return $data;
	}

}

$wp_api_customizer = new WP_API_Customizer();
