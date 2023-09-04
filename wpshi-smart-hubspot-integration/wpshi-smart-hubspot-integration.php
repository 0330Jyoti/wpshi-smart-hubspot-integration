<?php
/**
 * Plugin Name:       WPSHI Smart Hubspot Integration
 * Plugin URI:        https://profiles.wordpress.org/iqbal1486/
 * Description:       WP Smart Zoho help you to manage and synch possible WordPress data like customers, orders, products to the Zoho modules as per your settings options.
 * Version:           2.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Geekerhub
 * Author URI:        https://profiles.wordpress.org/iqbal1486/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wpshi-smart-hubspot
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

define( 'WPSHI_VERSION', '1.0.0' );

if (! defined('WPSHI_ADMIN_URL') ) {
    define('WPSHI_ADMIN_URL', get_admin_url());
}

if (! defined('WPSHI_PLUGIN_FILE') ) {
    define('WPSHI_PLUGIN_FILE', __FILE__);
}

if (! defined('WPSHI_PLUGIN_PATH') ) {
    define('WPSHI_PLUGIN_PATH', plugin_dir_path(WPSHI_PLUGIN_FILE));
}

if (! defined('WPSHI_PLUGIN_URL') ) {
    define('WPSHI_PLUGIN_URL', plugin_dir_url(WPSHI_PLUGIN_FILE));
}

if (! defined('WPSHI_REDIRECT_URI') ) {
    define('WPSHI_REDIRECT_URI', admin_url( 'admin.php?page=wpshi_smart_hubspot_process' ));
}

if (! defined('WPSHI_SETTINGS_URI') ) {
    define('WPSHI_SETTINGS_URI', admin_url( 'admin.php?page=wpshi-smart-hubspot-integration' ));
}

if (! defined('WPSHI_HUBSPOTAPIS_URL') ) {
    $tld = "com";
    $wpshi_smart_hubspot_settings  = get_option( 'wpshi_smart_hubspot_settings' );
    if( !empty($wpshi_smart_hubspot_settings['data_center'])){
        $tld = end(explode(".", parse_url( $wpshi_smart_hubspot_settings['data_center'], PHP_URL_HOST)));
    }
    define('WPSHI_HUBSPOTAPIS_URL', 'https://www.zohoapis.'.$tld);
}

function wpshi_smart_hubspot_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class.activator.php';
	$WPSHI_Smart_Hubspot_Activator = new WPSHI_Smart_Hubspot_Activator();
    $WPSHI_Smart_Hubspot_Activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpszi-smart-zoho-deactivator.php
 */
function wpshi_smart_hubspot_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class.deactivator.php';
    WPSHI_Smart_Hubspot_Deactivator::deactivate();
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpshi-smart-hubspot.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wpshi_smart_hubspot_run() {
    $plugin = new WPSHI_Smart_Hubspot();
	$plugin->run();
}

register_activation_hook( __FILE__, 'wpshi_smart_hubspot_activate' );
register_deactivation_hook( __FILE__, 'wpshi_smart_hubspot_deactivate' );

wpshi_smart_hubspot_run();

function wpshi_smart_hubspot_textdomain_init() {
    load_plugin_textdomain( 'wpshi-smart-hubspot', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'wpshi_smart_hubspot_textdomain_init');
?>