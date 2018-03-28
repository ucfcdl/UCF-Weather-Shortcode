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
			$theme = $args['theme'] ? $args['theme'] : 'default';
			$layout = $args['layout'] ? $args['layout'] : 'default';

			switch( $args['feed'] ) {
				case 'today':
					$output = self::display_today_default( $data, $theme );
					if ( has_filter( 'ucf_weather_today_' . $layout ) ) {
						$output = apply_filters( 'ucf_weather_today_' . $args['layout'], $data, $output );
					}
					break;
				case 'extended':
					$output = self::display_extended_default( $data, $theme );
					if ( has_filter( 'ucf_weather_extended_' . $layout ) ) {
						$output = apply_filters( 'ucf_weather_extended_' . $args['layout'], $data, $output );
					}
					break;
				case 'default':
				default:
					$output = self::display_default( $data, $theme );
					if ( has_filter( 'ucf_weather_default_' . $layout ) ) {
						$output = apply_filters( 'ucf_weather_default_' . $args['layout'], $data, $output );
					}
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
		public static function display_today_default( $data, $theme ) {
			$date = new DateTime( $data->date );
			$today_icon = self::get_weather_icon( $data->today->condition );
			$tonight_icon = self::get_weather_icon( $data->tonight->condition, true );
			ob_start();
		?>
			<div class="weather today-forecast theme-<?php echo $theme; ?>">
				<time datetime="<?php echo $date->format( 'Y-m-d' ); ?>">
					<?php echo $date->format( 'D, M j' ); ?>
				</time>
				<div class="today">
					<span class="<?php echo $today_icon; ?>"></span>
					<span class="vertical-rule"></span>
					<span class="temp"><?php echo $data->today->temp; ?>F</span>
				</div>
				<div class="tonight">
					<span class="<?php echo $tonight_icon; ?>"></span>
					<span class="vertical-rule"></span>
					<span class="temp"><?php echo $data->tonight->temp; ?>F</span>
				</div>
			</div>
		<?php
			return ob_get_clean();
		}

		/**
		 * Displays the default template for the `extended` weather feed.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $data Object | The weather data
		 * @return string | The html formatted data.
		 **/
		public static function display_extended_default( $data, $theme ) {
			ob_start();
		?>
			<div class="weather extended-forecast theme-<?php echo $theme; ?>">
		<?php
			foreach( $data->days as $day ) :
			$date = new DateTime( $day->date );
			$icon = self::get_weather_icon( $day->condition );
		?>
			<div class="forecast-day">
				<span class="<?php echo $icon; ?>"></span>
				<time datetime="<?php echo $date->format( 'Y-m-d' ); ?>">
					<?php echo $date->format( 'D, M j' ); ?>
				</time>
				<div class="today">
					<span class="vertical-rule"></span>
					<div class="temp">
						<label>Hi:</label>
						<span class="temp"><?php echo $day->tempMax; ?>F</span>
					</div>
					<div class="temp">
						<label>Lo:</label>
						<span class="temp"><?php echo $day->tempMin; ?>F</span>
					</div>
				</div>
			</div>
		<?php
			endforeach;
		?>
			</div>
		<?php
			return ob_get_clean();
		}

		/**
		 * Displays the default template for the `default` weather feed.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $data Object | The weather data
		 * @return string | The html formatted data.
		 **/
		public static function display_default( $data, $theme ) {
			ob_start();
			$icon = self::get_weather_icon( $data->condition );
		?>
			<span class="weather theme-<?php echo $theme; ?>">
				<span class="<?php echo $icon; ?>"></span>
				<span class="weather-location">Orlando, FL</span>
				<span class="vertical-rule"></span>
				<span class="temp"><?php echo $data->temp; ?>F</span>
			</span>
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
		public static function get_weather_icon( $condition, $night=false ) {
			$icon_suffix = null;
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

			$night_icons = array(
				'day-sunny' => 'night-clear',
				'hot' => 'night-clear',
				'cloudy' => 'night-cloudy',
				'snowflake-cold' => 'night-snow',
				'showers' => 'night-showers',
				'cloudy-gusts' => 'night-cloudy-gusts',
				'fog' => 'night-fog',
				'storm-showers' => 'night-storm-showers',
				'lightning' => 'night-lightning'
			);

			$condition = strtolower( $condition );

			foreach( $icons_to_conditions as $icon => $condition_array ) {
				if ( in_array( $condition, $condition_array ) ) {
					$icon_suffix = $icon;
				}
			}

			$icon_suffix = $icon_suffix ? $icon_suffix : 'day-sunny';

			if ( $night ) {
				return $icon_prefix . $night_icons[$icon_suffix];
			}

			return $icon_prefix . $icon_suffix;
		}
	}
}
?>
