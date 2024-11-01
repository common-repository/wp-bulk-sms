<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Check get_plugin_data function exist
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */   
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Set Plugin path and url defines.
define( 'WBS_WP_SMS_TO_BULK_URL', plugin_dir_url( dirname( __FILE__ ) ) );
define( 'WBS_WP_SMS_TO_BULK_DIR', plugin_dir_path( dirname( __FILE__ ) ) );


// Get plugin Data.
$plugin_data = get_plugin_data( WBS_WP_SMS_TO_BULK_DIR . 'wp-sms-to-bulk.php' );

// Set another useful Plugin defines.
define( 'WBS_WP_SMS_TO_BULK_VERSION', $plugin_data['Version'] );
define( 'WBS_WP_SMS_TO_BULK_ADMIN_URL', get_admin_url() );
define( 'WBS_WP_SMS_TO_BULK_SITE', 'https://wordpress.org/plugins/wp-bulk-sms/' );
define( 'WBS_WP_SMS_TO_BULK_MOBILE_REGEX', '/^[\+|\(|\)|\d|\- ]*$/' );
define( 'WBS_WP_SMS_TO_BULK_CURRENT_DATE', date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );