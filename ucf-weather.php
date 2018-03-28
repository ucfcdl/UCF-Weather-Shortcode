<?php
/*
Plugin Name: UCF Weather Shortcode
Description: Provides a shortcode for displaying the current weather using the UCF Weather service.
Version: 1.0.2
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
define( 'UCF_WEATHER__SCRIPT_URL', UCF_WEATHER__STATIC_URL . '/js' );

include_once 'includes/ucf-weather-config.php';
include_once 'includes/ucf-weather-feed.php';
include_once 'includes/ucf-weather-shortcode.php';
include_once 'includes/ucf-weather-common.php';
include_once 'admin/ucf-weather-admin.php';

if ( ! function_exists( 'ucf_weather_activate' ) ) {
	function ucf_weather_activate() {
		UCF_Weather_Config::add_options();
	}

	register_activation_hook( UCF_WEATHER__PLUGIN_FILE, 'ucf_weather_activate' );
}

if ( ! function_exists( 'ucf_weather_deactivate' ) ) {
	function ucf_weather_deactivate() {
		UCF_Weather_Config::delete_options();
	}

	register_deactivation_hook( UCF_WEATHER__PLUGIN_FILE, 'ucf_weather_deactivate' );
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
		// Settings
		add_action( 'admin_init', array( 'UCF_Weather_Config', 'settings_init' ) );
		add_action( 'admin_menu', array( 'UCF_Weather_Config', 'add_options_page' ) );
		add_action( 'admin_enqueue_scripts', array( 'UCF_Weather_Admin', 'enqueue_admin_assets' ), 10, 1 );

		// Register the shortcode
		add_action( 'init', array( 'UCF_Weather_Shortcode', 'register_shortcode' ) );
		// Add frontend assets
		add_action( 'wp_enqueue_scripts', array( 'UCF_Weather_Config', 'enqueue_frontend_assets' ), 10, 0 );
	}

	add_action( 'plugins_loaded', 'ucf_weather_init' );
}

?>
