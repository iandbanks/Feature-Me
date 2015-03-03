<?php
/**
 * Created by PhpStorm.
 * User: fiveq
 * Date: 12/29/14
 * Time: 11:39 AM
 */

namespace fm;


class fm_admin_settings {

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;
	private $default_css = '
/*----------Feature Me Styles----------*/

.feature-me .featureme-thumb{
    width:100%;
    height:auto;
}
.feature-me p{
    margin: 10px 0 0 0;
}

.fmcta_featured_image{
    text-align:center;
}

/*----------Buttons----------*/
/*
 * CSS Button code thanks to Steven Wanderski at http://css3buttongenerator.com
 */


.feature-me a.fmcta-button{
    background: #d9aa34;
    background-image: -webkit-linear-gradient(top, #d9aa34, #c2952b);
    background-image: -moz-linear-gradient(top, #d9aa34, #c2952b);
    background-image: -ms-linear-gradient(top, #d9aa34, #c2952b);
    background-image: -o-linear-gradient(top, #d9aa34, #c2952b);
    background-image: linear-gradient(to bottom, #d9aa34, #c2952b);
    border-radius: 3px;
    color: #ffffff;
    font-size: 16px;
    padding: 10px 0px 10px 0px;
    margin: 10px 0;
    text-decoration: none;
    width:100%;
    display:block;
    text-align: center;
}

.feature-me a.fmcta-button:hover {
    background: #d9aa34;
    background-image: -webkit-linear-gradient(top, #d9aa34, #a67d1e);
    background-image: -moz-linear-gradient(top, #d9aa34, #a67d1e);
    background-image: -ms-linear-gradient(top, #d9aa34, #a67d1e);
    background-image: -o-linear-gradient(top, #d9aa34, #a67d1e);
    background-image: linear-gradient(to bottom, #d9aa34, #a67d1e);
    text-decoration: none;
}
.feature-me a.fmcta-button:visited,
.feature-me a.fmcta-button:link{
    color:#ffffff !important;
}';

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(
			'Feature Me',
			'Feature Me',
			'manage_options',
			'feature-me-admin',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'fmcta_settings_settings' );
		?>
		<div class="wrap">
			<h2>Feature Me Settings</h2>
            <?php //@todo Add settings options for each Feature Me element. Dropdowns, color pickers, etc. ?>
            <p>This page is still in progress</p>

			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'fmcta_settings_group' );
				do_settings_sections( 'feature-me-admin' );
				submit_button();
				?>
			</form>
		</div>
	<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {

		register_setting(
			'fmcta_settings_group', // Option group
			'fmcta_settings_settings', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

        /* General Settings */
        add_settings_section(
            'fmcta_settings_general', // ID
            'Filter out Post Types', // css
            array( $this, 'print_general_info' ), // Callback
            'feature-me-admin' // Page
        );

        /* Style Settings */
		add_settings_section(
			'fmcta_settings_styles', // ID
			'Feature Me Styles', // css
			array( $this, 'print_section_info' ), // Callback
			'feature-me-admin' // Page
		);

		add_settings_field(
			'css_button',
			'CSS Button',
			array( $this, 'css_button' ),
			'feature-me-admin',
			'fmcta_settings_styles'
		);
        add_settings_field(
			'css',
			'CSS',
			array( $this, 'css_callback' ),
			'feature-me-admin',
			'fmcta_settings_styles'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 *
	 * @return array
	 */

	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['css'] ) ) {
			$new_input['css'] = esc_textarea( $input['css'] );
		}

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_general_info() {
		echo '<p class="description">Enter General Settings</p>';
	}

    /**
	 * Print the Section text
	 */
	public function print_section_info() {
		echo '<p class="description">Enter Style Settings</p>';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function css_button() {
		//printf(
        ?>
			<label for="fmcta-css-button-radius"><strong>CSS Button Radius</strong></label><br/>
            <input type="text" id="fmcta-css-button-radius" name="fmcta_settings[css_button_radius]" />
        <?php
		//);
	}
    public function css_callback() {
		printf(
			'<textarea type="text" id="css" name="fmcta_settings[css]" style="%s" >%s</textarea>', 'width:50%; height:300px;',
			isset( $this->options['css'] ) ? esc_attr( $this->options['css'] ) : $this->default_css
		);
	}
}