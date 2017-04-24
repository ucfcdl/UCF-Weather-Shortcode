<?php
/*
Plugin Name: UCF Weather Shortcode
Description: Provides a shortcode for displaying the current weather using the UCF Weather service.
Version: 1.0.0
Author: UCF Web Communications
License: GPL3
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'UCF_WEATHER__PLUGIN_FILE', __FILE__ );
define( 'UCF_WEATHER__PLUGIN_URL', plugins_url( basename( dirname( __FILE__ ) ) ) );
define( 'UCF_WEATHER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UCF_WEATHER__STATIC_URL', UCF_WEATHER__PLUGIN_URL . '/static' );
define( 'UCF_WEATHER__STYLES_URL', UCF_WEATHER__STATIC_URL . '/css' );

if ( ! function_exists( 'ucf_weather_activate' ) ) {
	function ucf_weather_activate() {
		return;
	}

	register_activation_hook( UCF_WEAHTER__PLUGIN_FILE, 'ucf_weather_activate' );
}

if ( ! function_exists( 'ucf_weather_deactivate' ) ) {
	function ucf_weather_deactivate() {
		return;
	}

	register_deactivation_hook( UCF_WEAHTER__PLUGIN_FILE, 'ucf_weather_deactivate' );
}

if ( ! function_exists( 'ucf_weather_init' ) ) {
	/**
	 * Called when all plugins are loaded.
	 * Add all actions and hooks here.
	 * 
	 * @author Jim Barnes
	 * @since 1.0.0
	 **/
	function ucf_weather_init() {
		return;
	}

	add_action( 'plugins_loaded', 'ucf_weather_init' );
}

?>
