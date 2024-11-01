<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class Wbs_wp_sms_to_bulk
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */
#[\AllowDynamicProperties]
class WBS_WP_SMS_TO_BULK { 

	public function __construct() {
		/*
		 * Plugin Loaded Action
		 */
		add_action( 'plugins_loaded', array( $this, 'wpsmstobulk_plugin_setup' ) );
                add_action( 'init', array( $this, 'wbs_sms_to_bulk_update_db' ) );
              
		/**
		 * Install And Upgrade plugin
		 */
		require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/class-wpsmstobulk-install.php';

		register_activation_hook( WBS_WP_SMS_TO_BULK_DIR . 'wp-sms-to-bulk.php', array( '\WBS_WP_SMS_TO_BULK\Install', 'install' ) );
                
                add_action('init', array( $this, 'redirect_to_general_url_handler' ) );
                add_action('init', array( $this, 'redirect_to_samepage_with_added_parameter' ) );
                    }

	/**
	 * Constructors plugin Setup
	 *
	 * @param Not param
	 */
	public function wpsmstobulk_plugin_setup() {
		// Load text domain
		add_action( 'init', array( $this, 'wbs_sms_to_bulk_load_textdomain' ) );

		$this->includes();
	}
        
        
	/**
	 * Redirect to specific tab
	 * If the page = =wp-sms-to-bulk-settings then redirect to tab=general
	 * 
	 */    
        
       public function redirect_to_general_url_handler() {
        if (substr($_SERVER["REQUEST_URI"], -20) == 'page=wp-sms-to-bulk-settings') {
            $url = $_SERVER["REQUEST_URI"] . '&tab=general';
            wp_redirect($url);
        }
    }

    
	/**
	 * Redirect to specific tab
	 * If the page = =wp-sms-to-bulk-outbox then redirect to response=message_id
	 * 
	 */    
        
       public function redirect_to_samepage_with_added_parameter() {
        if (substr($_SERVER["REQUEST_URI"], -26) == 'page=wp-sms-to-bulk-outbox') {
            $url = $_SERVER["REQUEST_URI"] . '&response=message_id';
            wp_redirect($url);
        }
    }    
    
    
    /**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 */
	public function wbs_sms_to_bulk_load_textdomain() {           
		load_plugin_textdomain( 'wbs-wp-smsto-bulk', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

        
    /**
	 * update db on update plugin
	 *
	 * @since 1.0.0
	 */
    public function wbs_sms_to_bulk_update_db() {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $installer_wpsms_ver = get_option('sms_to_bulk_db_version');

        if ($installer_wpsms_ver < WBS_WP_SMS_TO_BULK_VERSION) {

            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();

            // Add response and status for outbox
            $table_name = $wpdb->prefix . 'smstobulk_send';
            $column = $wpdb->get_results($wpdb->prepare(
                            "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
                            DB_NAME, $table_name, 'updated_at'
                    ));

            if (empty($column)) {
                $wpdb->query("ALTER TABLE {$table_name} ADD updated_at DATETIME AFTER date");
            }

            update_option('sms_to_bulk_db_version', WBS_WP_SMS_TO_BULK_VERSION);
        }
    }

    /**
	 * Includes plugin files
	 *
	 * @param Not param
	 */
	public function includes() {

		// Utility classes.
		require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/class-wpsmstobulk-features.php';
		require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/class-wpsmstobulk-notifications.php';

		if ( is_admin() ) {
			// Admin classes.
			require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/admin/class-wpsmstobulk-admin.php';
			require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/admin/class-wpsmstobulk-admin-helper.php';

			// Outbox class.
			require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/admin/outbox/class-wpsmstobulk-outbox.php';

			// Privacy class.
			require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/admin/privacy/class-wpsmstobulk-privacy-actions.php';
                        
			// Documentation class.
			require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/admin/documentation/class-wpsmstobulk-documentation.php';

			// Send class.
			require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/admin/send/class-wpsmstobulk-send.php';

			// Settings classes.
			require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/admin/settings/class-wpsmstobulk-settings.php';

}
		
		if ( ! is_admin() ) {
			// Front Class.
			require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/class-front.php';
		}


		// Template functions.
		require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/template-functions.php';
	}
}