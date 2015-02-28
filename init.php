<?php
/********************************************************************************
 *
 * Plugin Name: Feature Me: Call to Action Widget
 * Plugin URI: http://www.phasechangedigital.com/plugins/feature-me
 * Description: A powerful widget that allows you to easily create a call to action on your website.
 * Author: Phase Change Digital
 * Version: 2.0b
 * Author URI: http://www.phasechangedigital.com
 * License: GNU General Public License, version 2
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Tags: feature, widget, featured-post, featured-page, feature-me, feature-widget,cta, call to action,
 * featured post, feature me, call to action widget, feature me cta widget, feature me cta widget,
 * feature me call to action widget, featured content, featured content widget
 *
 *
 * Copyright 2012-2014 Ian Banks
 *
 ********************************************************************************
 *
 *      Table of Contents
 *
 *      1.0 - Initialize Plugin
 *          1.1 - Include files
 *          1.2 - Setup Plugin
 *      2.0 - Enqueue Widget Files
 *          2.1 - add_admin_scripts
 *      3.0 - Display Messages
 *          3.1 - Compare Versions
 *          3.2 - fm_activate_msg
 *          3.3 - fm_pointer_show_welcome
 *          3.4 - fm_pointer_widget_location
 *      4.0 - Settings Setup
 *          4.1 - fm_settings_init - Initialize Settings Page
 *
 *
 ********************************************************************************/

/********************************************************************************
 * 1.0 - Initialize Plugin
 * Initializes Widget, options, and any messages.
 *
 * @since 1.1.0
 ********************************************************************************/


/**
 * 1.1 - Include files
 *
 * @since 1.1.0
 */
include_once( plugin_dir_path( __FILE__ ) . 'classes/fmcta_setup.php' );
include_once( plugin_dir_path( __FILE__ ) . 'classes/fmcta_widget.php' );
include_once( plugin_dir_path( __FILE__ ) . 'classes/fmcta_admin_settings.php' );
include_once( plugin_dir_path( __FILE__ ) . 'lib/settings.php' );
include_once( plugin_dir_path( __FILE__ ) . 'lib/fmcta_pointer.php' );
include_once( plugin_dir_path( __FILE__ ) . 'lib/shortcodes.php' );

function fm_add_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('jquery-ui-sortable');
	//wp_enqueue_style( "featureme-css", plugin_dir_path( __FILE__ ) . "featureme.css" );
	wp_enqueue_style('featuremecss', get_option('fm-settings-css'));

}

add_action( 'wp_enqueue_script', 'fm_add_scripts' );

/**
 * 1.2 - Setup Plugin
 * Creates the $fm object and runs the init method to setup the widget.
 *
 * @since 1.1.0
 */
$fm = new fmSetup();
$fm->init();


/********************************************************************************
 * 2.0 - Enqueue Widget Files
 *
 * @since 1.1.0
 ********************************************************************************/

/**
 * 2.1 - add_admin_scripts
 * Enqueue's widget files to be used only on the widgets page
 *
 * @param $hook
 */
function add_admin_scripts( $hook ) {
	if ( $hook == 'widgets.php' ) {
		wp_enqueue_media();
		wp_enqueue_script( 'fmcta_widget', plugin_dir_url( __FILE__ ) . 'js/fmcta_widget.min.js' );
		wp_enqueue_style( 'fmcta_widget_style_admin', plugin_dir_url( __FILE__ ) . 'styles/css/admin.css' );
	}
}

add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );

/**
 * 2.2 - fm_pointer_load
 * Initiates pointer
 *
 * @see fmcta_pointer.php
 *
 */
add_action( 'admin_enqueue_scripts', 'fm_pointer_load', 1000 );


/********************************************************************************
 * 3.0 - Display Messages
 *
 * @since 1.1.0
 ********************************************************************************/

/**
 * 3.1 - Compare Versions
 * Display message if the versions are different. ie. just installed or upgraded.
 * Launches fm_activate_msg
 *
 * @since 1.1.0
 */
if ( $fm->compare_versions() == false ) {
	add_action( 'widgets_init', 'fm_activate_msg' );
}

/**
 * 3.2 - fm_activate_msg
 * Sets a message (set_fm_pointer) when admin_notices hook is performed
 *
 * @since 1.1.0
 */
function fm_activate_msg() {
	add_action( 'admin_notices', 'fm_pointer_show_welcome' );
}


/**
 * 3.3 - fm_pointer_show_welcome
 * Shows welcome message
 * Updates option fields to match so the messages only displays once upon
 *  plugin installation
 *
 * @since 1.1.1
 */
function fm_pointer_show_welcome() {

	$p['fm_pointer_welcome'] = array(
		'target'  => '#menu-appearance',
		'options' => array(
			'content'  => sprintf( '<h3> %s </h3> <p> %s </p>',
				__( 'Feature Me - Getting Started', 'feature-me' ),
				__( 'Thanks for installing Feature Me! Create your first call to action in the <strong>widgets menu</strong> here!',
					'feature-me' )
			),
			'position' => array( 'edge' => 'left', 'align' => 'middle' ),
		)
	);

	return $p;
}

add_filter( 'fm_admin_pointers-plugins', 'fm_pointer_show_welcome' ); //display message on main plugins page
add_filter( 'fm_admin_pointers-update', 'fm_pointer_show_welcome' ); //display message on update page
add_filter( 'fm_admin_pointers-dashboard', 'fm_pointer_show_welcome' ); //display message on main dashboard page


/**
 * 3.4 - fm_pointer_widget_location
 * Displays a pointer message when user lands on the widgets page
 *
 * @since 1.2
 *
 * @return mixed
 */
function fm_pointer_widget_location() {
	$p['fm_pointer_widget_location'] = array(
		'target'  => "div[id$='_feature_me-__i__'] h4",
		'options' => array(
			'content'  => sprintf( '<h3> %s </h3> <p> %s </p>',
				__( 'Feature Me - Getting Started', 'plugindomain' ),
				__( 'Looking for Feature Me? It\'s right here! Drag the widget to create your first call to action!',
					'plugindomain' )
			),
			'position' => array( 'edge' => 'left', 'align' => 'middle' ),

		)
	);

	return $p;
}

add_filter( 'fm_admin_pointers-widgets', 'fm_pointer_widget_location' );

/********************************************************************************
 * 4.0 - Settings Setup
 *
 * @since 2.0
 ********************************************************************************/

/**
 * 4.1 - fm_settings_init - Initialize Settings Page
 */
function fm_settings_init(){
	//add_submenu_page('options-general.php','Feature Me','Feature Me','manage_options', 'feature-me', 'fm_settings_page');

}
add_action('admin_menu', 'fm_settings_init', 10);

$fm_settings_page = new \fm\fm_admin_settings();
