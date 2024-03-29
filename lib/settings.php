<?php
/**
 * Generated by the WordPress Option Page generator
 * at http://jeremyhixon.com/wp-tools/option-page/
 */

class Raudio {
	private $raudio_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'raudio_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'raudio_page_init' ) );
	}

	public function raudio_add_plugin_page() {
		add_menu_page(
			'Raudio', // page_title
			'Raudio', // menu_title
			'manage_options', // capability
			'raudio', // menu_slug
			array( $this, 'raudio_create_admin_page' ), // function
			'dashicons-format-audio', // icon_url
			75 // position
		);
	}

	public function raudio_create_admin_page() {
		$this->raudio_options = get_option( 'raudio_options' ); ?>

		<div class="wrap">
			<h2>Raudio</h2>
			<p>Webradio player for Wordpress</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'raudio_option_group' );
					do_settings_sections( 'raudio-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function raudio_page_init() {
		register_setting(
			'raudio_option_group', // option_group
			'raudio_options', // option_name
			array( $this, 'raudio_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'raudio_setting_section', // id
			'Settings', // title
			array( $this, 'raudio_section_info' ), // callback
			'raudio-admin' // page
		);

		add_settings_field(
			'stream_url', // id
			'Stream URL', // title
			array( $this, 'stream_url_callback' ), // callback
			'raudio-admin', // page
			'raudio_setting_section' // section
		);

		add_settings_field(
			'autoplay', // id
			'Autoplay', // title
			array( $this, 'autoplay_callback' ), // callback
			'raudio-admin', // page
			'raudio_setting_section' // section
		);
	}

	public function raudio_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['stream_url'] ) ) {
			$sanitary_values['stream_url'] = sanitize_text_field( $input['stream_url'] );
		}

		if ( isset( $input['autoplay'] ) ) {
			$sanitary_values['autoplay'] = $input['autoplay'];
		}

		return $sanitary_values;
	}

	public function raudio_section_info() {
		
	}

	public function stream_url_callback() {
		printf(
			'<input class="regular-text" type="text" name="raudio_options[stream_url]" id="stream_url" value="%s">',
			isset( $this->raudio_options['stream_url'] ) ? esc_attr( $this->raudio_options['stream_url']) : ''
		);
	}

	public function autoplay_callback() {
		printf(
			'<input type="checkbox" name="raudio_options[autoplay]" id="autoplay" value="autoplay" %s> <label for="autoplay_1">Play stream immediately on page visit</label>',
			( isset( $this->raudio_options['autoplay'] ) && $this->raudio_options['autoplay'] === 'autoplay' ) ? 'checked' : ''
		);
	}

}
if ( is_admin() )
	$raudio = new Raudio();

/* 
 * Retrieve this value with:
 * $raudio_options = get_option( 'raudio_options' ); // Array of All Options
 * $stream_url = $raudio_options['stream_url']; // Stream URL
 * $autoplay = $raudio_options['autoplay']; // Autoplay
 */
