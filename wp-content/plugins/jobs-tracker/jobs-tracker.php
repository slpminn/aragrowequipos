<?php
/**
 * Plugin Name:       Jobs Tracker
 * Plugin URI:        https://github.com/aragrow/jobs-tracker
 * Description:       Plugin keep lead and engagement information about jobs
 * Requires at least: 5.5
 * Requires PHP:      7.0
 * Author:            Aragrow
 * Author URI:        https://aragrow.com
 * Version:           1.1
 * Text Domain:       about-clients
 *
 * About-client is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * About-clients is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with About-clients. If not, see <https://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'JOBSTRACKER_VERSION' ) ) {
	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 */
	define( 'JOBSTRACKER_VERSION', '1.0.0.0' );
}

// Plugin Folder Path.
if ( ! defined( 'JOBSTRACKERPLUGIN_DIR' ) ) {
	define( 'JOBSTRACKER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL.
if ( ! defined( 'JOBSTRACKER_PLUGIN_URL' ) ) {
	define( 'JOBSTRACKER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File.
if ( ! defined( 'JOBSTRACKER_PLUGIN_FILE' ) ) {
	define( 'JOBSTRACKER_PLUGIN_FILE', plugin_dir_path(__FILE__) );
}

// Plugin Root File.
if ( ! defined( 'JOBSTRACKER_WITH_CLASSES_FILE' ) ) {
	define( 'JOBSTRACKER_WITH_CLASSES_FILE', __FILE__ );
}

// Plugin Root File.
if ( ! defined( 'JOBSTRACKER_PREFIX' ) ) {
	define( 'JOBSTRACKER_PREFIX', 'jt_' );
}

// Plugin Root File.
if ( ! defined( 'JOBSTRACKER_ITEMS_PER_PAGE' ) ) {
	define( 'JOBSTRACKER_ITEMS_PER_PAGE', 50 );
}

if (get_option('JOBSTRACKER_debug')) define( 'JOBSTRACKER_DEBUG', 1 );
else define( 'JOBSTRACKER_DEBUG', 1 );

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

// Register the activation, deactivate, and delete hook.
require_once dirname( __FILE__ ) . '/src/installers/Jobs-Tracker-Install.php';
new JobsTrackerInstall(__FILE__);

// Register the activation, deactivate, and delete hook.
require_once dirname( __FILE__ ) . '/src/menus/Jobs-Tracker-Admin-Menu.php';
new JobsTrackerAdminMenu(__FILE__);

// Define the class and the function.
require_once dirname( __FILE__ ) . '/src/Jobs-Tracker.php';
new JobsTracker(__FILE__);

function load_jobstracker_textdomain() {
	$path = dirname( plugin_basename(__FILE__)) . '/languages';
	$result = load_plugin_textdomain('jobstracker', 
		false,
    	$path);

		if (!$result) {
			$locale = apply_filters('plugin_locale', get_locale(), dirname( plugin_basename(__FILE__)));
			die("Could not find $path/" . dirname( plugin_basename(__FILE__)) . "-$locale.mo.");
		}		
}
add_action('plugins_loaded', 'load_jobstracker_textdomain');