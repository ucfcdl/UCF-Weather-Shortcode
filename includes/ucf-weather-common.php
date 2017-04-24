<?php
/**
 * Includes common layout elements and filters
 **/
if ( ! class_exists( 'UCF_Weather_Common' ) ) {
	class UCF_Weather_Common {
		/**
		 * Returns the formatted html for the weather.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $args Array | Accepts `feed` and `layout` as options.
		 * @return string | The formatted html
		 **/
		public static function display_weather( $args ) {
			$output = '';
			$data = UCF_Weather_Feed::get_weather_data( $args['feed'] );

			switch( $args['feed'] ) {
				case 'today':
					$output = self::display_today_default( $data );
					$output = apply_filters( 'ucf_weather_today_' . $args['layout'], $data, $output );
					break;
				case 'extended':
					$output = self::display_extended_default( $data );
					$output = apply_filters( 'ucf_weather_extended_' . $args['layout'], $data, $output );
					break;
				case 'default':
				default:
					$output = self::display_default( $data );
					//$output = apply_filters( 'ucf_weather_default_' . $args['layout'], $data, $output );
					
					break;
			}

			return $output;
		}

		/**
		 * Displays the default template for the `today` weather feed.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $data Object | The weather data
		 * @return string | The html formatted data.
		 **/
		public static function display_today_default( $data ) {

		}

		/**
		 * Displays the default template for the `extended` weather feed.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $data Object | The weather data
		 * @return string | The html formatted data.
		 **/
		public static function display_extended_default( $data ) {

		}

		/**
		 * Displays the default template for the `default` weather feed.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $data Object | The weather data
		 * @return string | The html formatted data.
		 **/
		public static function display_default( $data ) {
			ob_start();
			$icon = self::get_weather_icon( $data->condition );
		?>
			<span class="<?php echo $icon; ?>"></span>
			<span class="weather-location">Orlando, FL</span>
			<span class="vertical-rule"></span>
			<span class="temp"><?php echo $data->temp; ?>F</span>
		<?php
			return ob_get_clean();
		}

		/**
		 * Translates the weather conditions from our feed
		 * to a weather icon.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $condition string | The weather condition
		 * @return string | The css icon classes.
		 **/
		private static function get_weather_icon( $condition ) {
			$retval = null;
			$icon_prefix = "wi wi-";
			$icons_to_conditions = array(
				'day-sunny' => array(
					'fair',
					'default'
				),
				'hot' => array(
					'hot',
					'haze'
				),
				'cloudy' => array(
					'overcast',
					'partly cloudy',
					'mostly cloudy'
				),
				'snowflake-cold' => array(
					'blowing snow',
					'cold',
					'snow'
				),
				'showers' => array(
					'showers',
					'drizzle',
					'mixed rain/sleet',
					'mixed rain/hail',
					'mixed snow/sleet',
					'hail',
					'freezing drizzle'
				),
				'cloudy-gusts' => array(
					'windy'
				),
				'fog' => array(
					'dust',
					'smoke',
					'foggy'
				),
				'storm-showers' => array(
					'scattered thunderstorms',
					'scattered thundershowers',
					'scattered showers',
					'freezing rain',
					'isolated thunderstorms',
					'isolated thundershowers'
				),
				'lightning' => array(
					'tornado',
					'severe thunderstorms'
				)
			);

			$condition = strtolower( $condition );

			foreach( $icons_to_conditions as $icon => $condition_array ) {
				if ( in_array( $condition, $condition_array ) ) {
					$retval = $icon_prefix . $icon;
				}
			}

			return $retval ? $retval : $icon_prefix . 'wi wi-day-sunny';
		}
	}
}
?>
