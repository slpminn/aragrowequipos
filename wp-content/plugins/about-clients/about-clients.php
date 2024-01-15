<?php
/**
 * Plugin Name:       About Clients
 * Plugin URI:        https://github.com/aragrow/about-clients
 * Description:       Plugin keep lead and engagement information about clients
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
if ( ! defined( 'ABOUTCLIENTS_VERSION' ) ) {
	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 */
	define( 'ABOUTCLIENTS_VERSION', '1.0.0.0' );
}

// Plugin Folder Path.
if ( ! defined( 'ABOUTCLIENTSPLUGIN_DIR' ) ) {
	define( 'ABOUTCLIENTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL.
if ( ! defined( 'ABOUTCLIENTS_PLUGIN_URL' ) ) {
	define( 'ABOUTCLIENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File.
if ( ! defined( 'ABOUTCLIENTS_PLUGIN_FILE' ) ) {
	define( 'ABOUTCLIENTS_PLUGIN_FILE', plugin_dir_path(__FILE__) );
}

// Plugin Root File.
if ( ! defined( 'ABOUTCLIENTS_WITH_CLASSES_FILE' ) ) {
	define( 'ABOUTCLIENTS_WITH_CLASSES_FILE', __FILE__ );
}

// Plugin Root File.
if ( ! defined( 'ABOUTCLIENTS_PREFIX' ) ) {
	define( 'ABOUTCLIENTS_PREFIX', 'ac_' );
}

// Plugin Root File.
if ( ! defined( 'ABOUTCLIENTS_ITEMS_PER_PAGE' ) ) {
	define( 'ABOUTCLIENTS_ITEMS_PER_PAGE', 50 );
}

if (get_option('aboutclients_debug')) define( 'ABOUTCLIENTS_DEBUG', 1 );
else define( 'ABOUTCLIENTS_DEBUG', 0 );

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

// Register the activation, deactivate, and delete hook.
require_once dirname( __FILE__ ) . '/src/About-Clients-Install.php';
new AboutClientsInstall(__FILE__);

// Register the activation, deactivate, and delete hook.
require_once dirname( __FILE__ ) . '/src/About-Clients-Admin-Menu.php';
new AboutClientsAdminMenu(__FILE__);

// Define the class and the function.
require_once dirname( __FILE__ ) . '/src/About-Clients.php';
new AboutClients(__FILE__);

function load_aboutclients_textdomain() {
	$path = dirname( plugin_basename(__FILE__)) . '/languages';
	$result = load_plugin_textdomain('aboutclients', 
		false,
    	$path);

		if (!$result) {
			$locale = apply_filters('plugin_locale', get_locale(), dirname( plugin_basename(__FILE__)));
			die("Could not find $path/" . dirname( plugin_basename(__FILE__)) . "-$locale.mo.");
		}		
}
add_action('plugins_loaded', 'load_aboutclients_textdomain');