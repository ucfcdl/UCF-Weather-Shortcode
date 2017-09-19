<?php
/**
 * Handles fetching weather data
 **/
if ( ! class_exists( 'UCF_Weather_Feed' ) ) {
	class UCF_Weather_Feed {
		/**
		 * Retrieves weather data.
		 * @author Jim Barnes
		 * @since 1.0.0
		 *
		 * @param $feed string | The feed to retrieve
		 * @return Object | The weather data object.
		 **/
		public static function get_weather_data( $feed='default' ) {
			$base_url = UCF_Weather_Config::get_option_or_default( 'feed_url_base' );
			$timeout = UCF_Weather_Config::get_option_or_default( 'transient_expiration' );
			$use_transient = UCF_Weather_Config::get_option_or_default( 'use_transient' );

			$args = array();

			// Append url params based on feed type
			switch( $feed ) {
				case 'today':
					$args['data'] = 'forecastToday';
					break;
				case 'extended':
					$args['data'] = 'forecastExtended';
					break;
				default:
					break;
			}

			$url = $base_url . '?' . http_build_query( $args );

			// By default items is false.
			$data = false;

			// If using transients, try to get the transient.
			if ( $use_transient ) {
				$transient_name = self::get_transient_name( $url );
				$data = get_transient( $transient_name );
			}

			if ( $data === false ) {
				$response = wp_remote_get( $url, array( 'timeout' => 15 ) );

				if ( is_array( $response ) ) {
					$data = json_decode( wp_remote_retrieve_body( $response ) );
				} else {
					$data = false;
				}

				if ( $data && $use_transient ) {
					set_transient( $transient_name, $data, $timeout * 60 ); // Timeout is stored as minutes.
				}
			}

			return $data;

		}

		/**
		 * Returns the name of the transient based on the url string.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $url string | The url to be retrieved.
		 * @return string | The name of the transient to be stored.
		 **/
		private static function get_transient_name( $url ) {
			return 'ucf_weather_' . md5( $url );
		}
	}
}
?>
