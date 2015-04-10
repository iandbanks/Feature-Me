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
        register_setting( 'fmcta_settings_group', 'fmcta_excluded_post_types' );
        /*register_setting( 'myoption-group', 'some_other_option' );
        register_setting( 'myoption-group', 'option_etc' );*/

        /* General Settings */
        add_settings_section(
            'fmcta_settings_general', // ID
            'Global Settings', // css
            array( $this, 'print_general_info' ), // Callback
            'feature-me-admin' // Page
        );

        add_settings_field(
            'fmcta_settings_post_types',
            'Post Types',
            array( $this, 'fmcta_settings_post_types' ),
            'feature-me-admin',
            'fmcta_settings_general'
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
		echo '<p class="description">Change global settings for all Feature Me CTA\'s</p>';
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
        <section class="css-button-radius">
            <h4><strong>Button Radius</strong></h4>
            <p style="vertical-align: middle; display:table-row;"><label for="fmcta-css-button-radius-top-left" style="display:table-cell; width: 100px;">Top Left</label>
                <input type="text" id="fmcta-css-button-radius-top-left" style="display:table-cell;" name="fmcta_settings[css_button_radius-top-left]" /></p>
            <p>
                <label for="fmcta-css-button-radius-top-right">Top Right</label>
                <input type="text" id="fmcta-css-button-radius-top-right" name="fmcta_settings[css_button_radius-top-right]" />
            </p>
        </section>
        <?php
		//);
	}
    public function css_callback() {
		printf(
			'<textarea type="text" id="css" name="fmcta_settings[css]" style="%s" >%s</textarea>', 'width:50%; height:300px;',
			isset( $this->options['css'] ) ? esc_attr( $this->options['css'] ) : $this->default_css
		);
	}

    /**
     * fmcta_settings_post_types()
     *
     */
    public function fmcta_settings_post_types() { ?>
        <section class="post-types">
            <h4><strong>Exclude post types from Feature Me</strong></h4>
            <p class="description">By default all post types are included. If you don't want a post type to display in the feature me widget, select it here:</p>
            <?php
            $post_types = get_post_types(array(
                    'public' => true, //only show public post types
                )
            );

            sort($post_types); //sort alphabetically
            ?>

            <ul>
            <?php
            foreach( $post_types as $type ){
                ?>
                <li>
                    <?php if(get_option('fmcta_excluded_post_types') ){
                    ?>
                    <input type="checkbox" name="fmcta_excluded_post_types[]" value="<?php echo $type ?>" id="exclude_<?php echo $type ?>" <?php if( in_array($type, get_option('fmcta_excluded_post_types') ) ){ echo 'checked="checked"'; } ?> > <label for="exclude_<?php echo $type ?>"><?php echo ucfirst( $type) ?></label></li>
                    <?php
                    } else{ ?>
                    <input type="checkbox" name="fmcta_excluded_post_types[]" value="<?php echo $type ?>" id="exclude_<?php echo $type ?>" > <label for="exclude_<?php echo $type ?>"><?php echo ucfirst( $type) ?></label></li>

                <?php }
            }
            ?>
            </ul>
        </section>
        <?php
    }
}