<?php
/**
 * Shortcodes to enhance website with feature-me features
 */

/**
 * fm_content
 * [fm_content login="true|false"]Content[/fm_content]
 * Use this shortcode to display content specifically for users who are logged in or logged out
 *
 * @param $atts
 * @param null $content
 *
 * @return string|null
 */
function fm_content( $atts, $content = null ) {
	$args = shortcode_atts(
		array(
			'login' => 'true',
		), $atts, 'fm_content' );


	if ( is_user_logged_in() == true && $args['login'] == 'true' ) {
		return do_shortcode( $content );
	} elseif ( is_user_logged_in() == false && $args['login'] == 'false' ) {
		return do_shortcode( $content );
	} else {
		return;
	}
}
add_shortcode( 'fm_content', 'fm_content' );

/**
 * fm_user
 * [fm_user display="first|last|full|display"]
 * Displays the current username
 * @param $atts
 * @return string|null
 */
function fm_user( $atts ){
	$atts = shortcode_atts(
		array(
			'display' => 'display',
		), $atts, 'fm_user');

	global $current_user, $display_name;
	get_currentuserinfo(); //now access the global variable $current_user;

	if($atts['display'] == 'first'){
		return $current_user->user_firstname;
	} elseif ($atts['display'] == 'last' ){
		return $current_user->user_lastname;
	} elseif ( $atts['display'] == 'full' ){
		return $current_user->user_firstname . ' ' . $current_user->user_lastname;
	} elseif ( $atts['display'] == 'display' ){
		return $current_user->display_name;
	}
}
add_shortcode( 'fm_user', 'fm_user' );