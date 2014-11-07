<?php

/********************************************************************************
 *
 * FM Pointer code.
 *
 * Original code from Integrating With WordPress’ UI: Admin Pointers by
 * Stephen Harris at http://bit.ly/NkCAU3
 *
 * Copyright 2012-2014 Ian Banks
 *
 ********************************************************************************/

function fm_pointer_load( $hook_suffix ) {

    // Don't run on WP < 3.6
    if ( get_bloginfo( 'version' ) < '3.6' )
        return;

    $screen = get_current_screen();
    $screen_id = $screen->id;

    // Get pointers for this screen
    $pointers = apply_filters( 'fm_admin_pointers-' . $screen_id, array() );

    if ( ! $pointers || ! is_array( $pointers ) )
        return;

    // Get dismissed pointers
    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
    $valid_pointers =array();

    // Check pointers and remove dismissed ones.
    foreach ( $pointers as $pointer_id => $pointer ) {

        // Sanity check
        if ( in_array( $pointer_id, $dismissed ) || empty( $pointer )  || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) )
            continue;

        $pointer['pointer_id'] = $pointer_id;

        // Add the pointer to $valid_pointers array
        $valid_pointers['pointers'][] =  $pointer;
    }

    // No valid pointers? Stop here.
    if ( empty( $valid_pointers ) )
        return;

    // Add pointers style to queue.
    wp_enqueue_style( 'wp-pointer' );

    // Add pointers script to queue. Add custom script.
    wp_enqueue_script( 'fm-pointer', plugins_url() . '/feature-me/js/pointer.js', array( 'wp-pointer' ) );

    // Add pointer options to script.
    wp_localize_script( 'fm-pointer', 'fmcta_pointer', $valid_pointers );
}