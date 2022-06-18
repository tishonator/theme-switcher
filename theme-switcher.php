<?php

/*
Plugin Name: Theme Switcher
Description: Allow your readers to switch themes by URL parameter.
Version: 1.0.0
Author: tishonator
Author URI: http://tishonator.com/
*/

add_action( 'init', 'tisho_set_current_theme_cookie');

add_filter('template', 'tisho_switch_theme');
add_filter('stylesheet', 'tisho_switch_theme');
add_filter( 'pre_option_stylesheet', 'tisho_switch_theme' );

function tisho_set_current_theme_cookie() {

	// check if theme to display is set as query string parameter
	if ( !empty( $_GET["wptheme"] )
		 && preg_match( "/^[a-zA-Z\d]+$/", $_GET["wptheme"] ) /*t only letters and number t*/ ) {

		$wptheme = $_GET["wptheme"];
		
		$expire = time() + 30000000;
		$cookiehash = ( defined( 'COOKIEHASH' ) ) ? COOKIEHASH : '';
		setcookie( "wptheme" . $cookiehash,
				   stripslashes($_GET["wptheme"]),
				   $expire,
				   COOKIEPATH
				  );
				  
		remove_query_arg('wptheme');
	}
}

function tisho_switch_theme($theme) {

	if ( !empty( $_GET["wptheme"] )
		 && preg_match( "/^[a-zA-Z\d]+$/", $_GET["wptheme"] ) /*t only letters and number t*/ ) {
		
		return $_GET["wptheme"];
	}
	
	$cookiehash = ( defined( 'COOKIEHASH' ) ) ? COOKIEHASH : '';

	// if theme is set in cookies
	if ( ! empty($_COOKIE["wptheme" . $cookiehash] ) ) {

		return $_COOKIE["wptheme" . $cookiehash];
	}
	
	// by default returns the directory of the current theme
	return '';
}
