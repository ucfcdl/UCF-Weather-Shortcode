<?php
/**
 * Handles admin enqueing and logic
 **/
if ( ! class_exists( 'UCF_Weather_Admin' ) ) {
	class UCF_Weather_Admin {
		/**
		 * Enqueues the admin js file
		 * @author Jim Barnes
		 * @since 1.0.0
		 * 
		 * @param $hook string | The current hook (screen) that is being called.
		 **/
		public static function enqueue_admin_assets( $hook ) {
			if ( $hook === 'settings_page_ucf_weather' ) {
				wp_enqueue_script( 'ucf-weather-admin-js', UCF_WEATHER__SCRIPT_URL . '/ucf-weather-admin.min.js', array( 'jquery' ), null, true );
			}
		}
	}
}
