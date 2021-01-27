# UCF Weather Shortcode #

Provides a shortcode for displaying weather from the UCF weather service.


## Description ##

Provides a shortcode for displaying weather from the UCF weather service.


## Installation ##

### Manual Installation ###
1. Upload the plugin files (unzipped) to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the "Plugins" screen in WordPress
3. Configure settings at Settings > UCF Weather.

### WP CLI Installation ###
1. `$ wp plugin install --activate https://github.com/UCF/UCF-Weather-Shortcode/archive/master.zip`.  See [WP-CLI Docs](http://wp-cli.org/commands/plugin/install/) for more command options.
2. Configure settings at Settings > UCF Weather.


## Changelog ##

### 1.1.0 ###
Enhancements:
* Replaced the weather font icon library with inline SVG inclusion
* Added missing cache-busting for enqueued assets
* Modified existing markup and styles as necessary for accessibility and clarity, and to clean up some existing styles
* Updated default weather service URL
* Upgraded packages to latest versions; implemented linter configs and issue/PR templates; removed Bower

Bug Fixes:
* Made sure weather feed `$data` isn't false-y before attempting to access its properties in weather display functions

### 1.0.3 ###
Bugfixes:
* Fixed undefined index notices on the plugin's options page

### 1.0.2 ###
Enhancements:
* Updated `UCF_Weather_Common::get_weather_icon()` to be a public method, allowing weather icon classes to be fetched within custom layouts and elsewhere.

### 1.0.1 ###
Bug Fixes:
* Removed `is_plugin_active()` check in `ucf-weather.php` to prevent errors when `wp-admin/includes/plugin.php` is not loaded.

### 1.0.0 ###
* Initial release

## Upgrade Notice ##

n/a


## Installation Requirements ##

None


## Development & Contributing ##

NOTE: this plugin's readme.md file is automatically generated.  Please only make modifications to the readme.txt file, and make sure the `gulp readme` command has been run before committing readme changes.

