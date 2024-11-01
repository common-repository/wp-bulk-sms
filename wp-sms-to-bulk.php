<?php
/**
 * Plugin Name: WP - Bulk SMS
 * Plugin URI: https://wordpress.org/plugins/wp-bulk-sms/
 * Description: A powerful Bulk SMS Messaging/Texting plugin for WordPress - This plugin is a fork from https://wordpress.org/plugins/wp-sms/ by VeronaLabs
 * Version: 1.0.12
 * Author: SMS.to
 * Author URI: https://sms.to
 * Text Domain: wbs-wp-smsto-bulk
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Load Plugin Defines
 */
require_once 'includes/defines.php';

/**
 * Load plugin Special Functions
 */
require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/functions.php';

    /**
     * Get plugin options
     */
    $wpsmstobulk_option = get_option('wpsmstobulk_settings');    

    /**
     * Initial gateway
     */
    require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/class-wpsmstobulk-gateway.php';

    $wpsmstobulk = wbs_sms_to_bulk_initial_gateway();    

    /**
     * Load Plugin
     */
    require WBS_WP_SMS_TO_BULK_DIR . 'includes/class-wpsmstobulk.php';

    new WBS_WP_SMS_TO_BULK();
