<?php
// create custom plugin settings menu

$default_css = '
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
    color: #ffffff !important;
}';

add_action('admin_menu', 'fm_create_menu');

function fm_create_menu() {

	//create new top-level menu
	add_options_page( 'Feature Me Settings', 'Feature Me Settings', 'administrator', 'fm-settings','fm_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'fm_register_settings' );
}


function fm_register_settings() {
	//register our settings
	register_setting( 'fm-settings-css', 'fm-settings-css' );
}

function fm_settings_page() {

	global $default_css;

	?>
	<div class="wrap">
		<h2>Feature Me Settings</h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'fm-settings-css' ); ?>
			<?php do_settings_sections( 'fm-settings-css' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">CSS</th>
					<td><textarea name="fm-settings-css" style="width:50%; height: 300px;"><?php
							/*if ( !get_option('fm-settings-css') ){
								update_option('fm-settings-css', $default_css );
							} else {*/
								echo esc_attr( get_option('fm-settings-css') );
							//}?></textarea></td>
				</tr>
			</table>

			<?php submit_button(); ?>

		</form>
	</div>
<?php }