<?php
/**
 * Handles plugin configuration
 */

if ( !class_exists( 'UCF_Weather_Config' ) ) {

	class UCF_Weather_Config {
		public static
			$option_prefix = 'ucf_weather_',
			$option_defaults = array(
				'feed_url_base'        => 'https://weather.smca.ucf.edu',
				'include_css'          => true,
				'use_transient'        => true,
				'transient_expiration' => 300
			);

		public static function get_layouts() {
			$layouts = array(
				'classic' => 'Classic Layout',
			);

			$layouts = apply_filters( self::$option_prefix . 'get_layouts', $layouts );

			return $layouts;
		}

		/**
		 * Creates options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin activation.
		 *
		 * @return void
		 **/
		public static function add_options() {
			$defaults = self::$option_defaults; // don't use self::get_option_defaults() here (default options haven't been set yet)

			add_option( self::$option_prefix . 'feed_url_base', $defaults['feed_url_base'] );
			add_option( self::$option_prefix . 'include_css', $defaults['include_css'] );
			add_option( self::$option_prefix . 'use_transient', $defaults['use_transient'] );
			add_option( self::$option_prefix . 'transient_expiration', $defaults['transient_expiration'] );
		}

		/**
		 * Deletes options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin uninstallation.
		 *
		 * @return void
		 **/
		public static function delete_options() {
			delete_option( self::$option_prefix . 'feed_url_base' );
			delete_option( self::$option_prefix . 'include_css' );
			delete_option( self::$option_prefix . 'use_transient' );
			delete_option( self::$option_prefix . 'transient_expiration' );
		}

		/**
		 * Returns a list of default plugin options. Applies any overridden
		 * default values set within the options page.
		 *
		 * @return array
		 **/
		public static function get_option_defaults() {
			$defaults = self::$option_defaults;

			// Apply default values configurable within the options page:
			$configurable_defaults = array(
				'feed_url_base'        => get_option( self::$option_prefix . 'feed_url_base' ),
				'include_css'          => get_option( self::$option_prefix . 'include_css' ),
				'use_transient'        => get_option( self::$option_prefix . 'use_transient' ),
				'transient_expiration' => get_option( self::$option_prefix . 'transient_expiration' )
			);

			$configurable_defaults = self::format_options( $configurable_defaults );

			// Force configurable options to override $defaults, even if they are empty:
			$defaults = array_merge( $defaults, $configurable_defaults );

			return $defaults;
		}

		/**
		 * Returns an array with plugin defaults applied.
		 *
		 * @param array $list
		 * @param boolean $list_keys_only Modifies results to only return array key
		 *                                values present in $list.
		 * @return array
		 **/
		public static function apply_option_defaults( $list, $list_keys_only=false ) {
			$defaults = self::get_option_defaults();
			$options = array();

			if ( $list_keys_only ) {
				foreach ( $list as $key => $val ) {
					$options[$key] = !empty( $val ) ? $val : $defaults[$key];
				}
			}
			else {
				$options = array_merge( $defaults, $list );
			}

			$options = self::format_options( $options );

			return $options;
		}

		/**
		 * Performs typecasting, sanitization, etc on an array of plugin options.
		 *
		 * @param array $list
		 * @return array
		 **/
		public static function format_options( $list ) {
			foreach ( $list as $key => $val ) {
				switch ( $key ) {
					case 'transient_expiration':
						$list[$key] = floatval( $val );
						break;
					case 'include_css':
					case 'use_transient':
						$list[$key] = filter_var( $val, FILTER_VALIDATE_BOOLEAN );
						break;
					default:
						break;
				}
			}

			return $list;
		}

		/**
		 * Convenience method for returning an option from the WP Options API
		 * or a plugin option default.
		 *
		 * @param $option_name
		 * @return mixed
		 **/
		public static function get_option_or_default( $option_name ) {
			// Handle $option_name passed in with or without self::$option_prefix applied:
			$option_name_no_prefix = str_replace( self::$option_prefix, '', $option_name );
			$option_name = self::$option_prefix . $option_name_no_prefix;

			$option = get_option( $option_name );
			$option_formatted = self::apply_option_defaults( array(
				$option_name_no_prefix => $option
			), true );

			return $option_formatted[$option_name_no_prefix];
		}

		/**
		 * Initializes setting registration with the Settings API.
		 **/
		public static function settings_init() {
			// Register settings
			register_setting( 'ucf_weather', self::$option_prefix . 'feed_url_base' );
			register_setting( 'ucf_weather', self::$option_prefix . 'include_css' );
			register_setting( 'ucf_weather', self::$option_prefix . 'use_transient' );
			register_setting( 'ucf_weather', self::$option_prefix . 'transient_expiration' );

			// Register setting sections
			add_settings_section(
				'ucf_weather_section_general', // option section slug
				'General Settings', // formatted title
				'', // callback that echoes any content at the top of the section
				'ucf_weather' // settings page slug
			);

			// Register fields
			add_settings_field(
				self::$option_prefix . 'feed_url_base',
				'UCF Weather JSON Feed Base URL',  // formatted field title
				array( 'UCF_Weather_Config', 'display_settings_field' ), // display callback
				'ucf_weather',  // settings page slug
				'ucf_weather_section_general',  // option section slug
				array(  // extra arguments to pass to the callback function
					'label_for'   => self::$option_prefix . 'feed_url_base',
					'description' => 'The default base URL to use for weather feeds from weather.smca.ucf.edu.',
					'type'        => 'text'
				)
			);
			add_settings_field(
				self::$option_prefix . 'include_css',
				'Include Default CSS',  // formatted field title
				array( 'UCF_Weather_Config', 'display_settings_field' ),  // display callback
				'ucf_weather',  // settings page slug
				'ucf_weather_section_general',  // option section slug
				array(  // extra arguments to pass to the callback function
					'label_for'   => self::$option_prefix . 'include_css',
					'description' => 'Include the default css stylesheet for event results within the theme.<br>Leave this checkbox checked unless your theme provides custom styles for event results.',
					'type'        => 'checkbox'
				)
			);

			add_settings_field(
				self::$option_prefix . 'use_transient',
				'Cache Weather Data', // formatted field title
				array( 'UCF_Weather_Config', 'display_settings_field' ), // display callback
				'ucf_weather',
				'ucf_weather_section_general',
				array(
					'label_for'   => self::$option_prefix . 'use_transient',
					'description' => 'If checked, weather data will be cached for a period of time.',
					'type'        => 'checkbox'
				)
			);

			add_settings_field(
				self::$option_prefix . 'transient_expiration',
				'Transient Data Expiration',  // formatted field title
				array( 'UCF_Weather_Config', 'display_settings_field' ),  // display callback
				'ucf_weather',  // settings page slug
				'ucf_weather_section_general',  // option section slug
				array(  // extra arguments to pass to the callback function
					'label_for'   => self::$option_prefix . 'transient_expiration',
					'description' => 'The length of time, in minutes, weather data should be cached before fresh data is fetched. Updates to this value will not take effect until after any existing transient events data expires.',
					'type'        => 'number',
					'hidden'      => true
				)
			);
		}

		/**
		 * Displays an individual setting's field markup.
		 **/
		public static function display_settings_field( $args ) {
			$option_name   = $args['label_for'];
			$description   = $args['description'];
			$field_type    = $args['type'];
			$hidden        = isset( $args['hidden'] ) ? $args['hidden'] : false;
			$current_value = self::get_option_or_default( $option_name );
			$markup        = '';

			switch ( $field_type ) {
				case 'checkbox':
					ob_start();
				?>
					<input type="checkbox" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" <?php echo ( $current_value == true ) ? 'checked' : ''; ?>>
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;
				case 'number':
					ob_start();
				?>
					<input type="number" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $current_value; ?>">
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;

				case 'text':
				default:
					ob_start();
				?>
					<input type="text" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $current_value; ?>">
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;
			}
		?>

		<?php
			echo $markup;
		}


		/**
		 * Registers the settings page to display in the WordPress admin.
		 **/
		public static function add_options_page() {
			$page_title = 'UCF Weather Settings';
			$menu_title = 'UCF Weather';
			$capability = 'manage_options';
			$menu_slug  = 'ucf_weather';
			$callback   = array( 'UCF_Weather_Config', 'options_page_html' );

			return add_options_page(
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback
			);
		}


		/**
		 * Displays the plugin's settings page form.
		 **/
		public static function options_page_html() {
			ob_start();
		?>

		<div class="wrap">
			<h1><?php echo get_admin_page_title(); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'ucf_weather' );
				do_settings_sections( 'ucf_weather' );
				submit_button();
				?>
			</form>
		</div>

		<?php
			echo ob_get_clean();
		}

		/**
		 * Enqueues the front end assets
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function enqueue_frontend_assets() {
			if ( self::get_option_or_default( 'include_css' ) ) {
				wp_enqueue_style( 'ucf-weather-css', UCF_WEATHER__STYLES_URL . '/ucf-weather.min.css' );
			}
		}

	}
}

?>
