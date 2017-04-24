<?php
/**
 * Handles registering and implementing the shortcode.
 **/
if ( ! class_exists( 'UCF_Weather_Shortcode' ) ) {
	class UCF_Weather_Shortcode {
		/**
		 * Registers the shortcode.
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function register_shortcode() {
			add_shortcode( 'ucf-weather', array( 'UCF_Weather_Shortcode', 'callback' ) );
		}

		/**
		 * Registers the shortcode with the `WP-Shortcode-Interface`
		 * Plugin if it is installed.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * 
		 * @param $shortcodes Array | Array of registered shortcodes.
		 * @return Array | Modified array of shortcodes.
		 **/
		public static function register_shortcode_interface( $shortcodes ) {
			
		}

		/**
		 * Outputs weather data
		 * @author Jim Barnes
		 * @since 1.0.0
		 * 
		 * @param $atts Array<string> | Array of attributes
		 * @param $content string | Content within the shortcode
		 * @return string | The markup to be displayed.
		 **/
		public static function callback( $atts, $content='' ) {
			$atts = shortcode_atts( array(
				'feed'   => 'default',
				'layout' => 'default',
				'theme'  => 'default'
			), $atts );

			return UCF_Weather_Common::display_weather( $atts );
		}
	}
}
