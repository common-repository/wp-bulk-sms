<?php

namespace WBS_WP_SMS_TO_BULK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Install
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */
#[\AllowDynamicProperties]
class Install {

	public function __construct() {
		add_action( 'wpmu_new_blog', array( $this, 'add_table_on_create_blog' ), 10, 1 );
		add_filter( 'wpmu_drop_tables', array( $this, 'remove_table_on_delete_blog' ) );
	}

	/**
	 * Adding new MYSQL Table in Activation Plugin
	 *
	 * @param Not param
	 */
	public static function create_table( $network_wide ) {
		global $wpdb;

		if ( is_multisite() && $network_wide ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );

				self::table_sql();

				restore_current_blog();
			}
		} else {
			self::table_sql();
		}

	}

	/**
	 * Table SQL
	 *
	 * @param Not param
	 */
	public static function table_sql() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'smstobulk_send';
		if ( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {
			$create_wpsmstobulk_send = ( "CREATE TABLE IF NOT EXISTS {$table_name}(
            ID int(10) NOT NULL auto_increment,
            _id VARCHAR(50),
            type VARCHAR(10),
            cost REAL,
            message_count INTEGER,
            sms_parts INTEGER,
            sent INTEGER,
            failed INTEGER,
            pending INTEGER,
            date DATETIME,
            updated_at DATETIME,
            sender VARCHAR(20) NOT NULL,
            message TEXT NOT NULL,
            recipient TEXT NOT NULL,
            response TEXT NOT NULL,
            status varchar(100) NOT NULL,
            PRIMARY KEY(ID)) $charset_collate" );

			dbDelta( $create_wpsmstobulk_send );
		}
                
                
                
	}

	/**
	 * Creating plugin tables
	 *
	 * @param $network_wide
	 */
	static function install( $network_wide ) {
		global $sms_to_bulk_db_version;

		self::create_table( $network_wide );

		add_option( 'sms_to_bulk_db_version', WBS_WP_SMS_TO_BULK_VERSION );

		// Delete notification new wp_version option
		delete_option( 'wp_notification_new_wp_version' );

		if ( is_admin() ) {
			self::upgrade();
		}
	}

	/**
	 * Upgrade plugin requirements if needed
	 */
	static function upgrade() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$installer_wpsms_ver = get_option( 'sms_to_bulk_db_version' );

		if ( $installer_wpsms_ver < WBS_WP_SMS_TO_BULK_VERSION ) {

			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();

			// Add response and status for outbox
			$table_name = $wpdb->prefix . 'smstobulk_send';
			$column     = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
				DB_NAME, $table_name, 'updated_at'
			) );

			if ( empty( $column ) ) {
				$wpdb->query( "ALTER TABLE {$table_name} ADD updated_at DATETIME AFTER date" );
			}

			update_option( 'sms_to_bulk_db_version', WBS_WP_SMS_TO_BULK_VERSION );
		}
	}

	/**
	 * Creating Table for New Blog in wordpress
	 *
	 * @param $blog_id
	 */
	public function add_table_on_create_blog( $blog_id ) {
		if ( is_plugin_active_for_network( 'wp-sms-to-bulk/wp-sms-to-bulk.php' ) ) {
			switch_to_blog( $blog_id );

			self::table_sql();

			restore_current_blog();
		}
	}

	/**
	 * Remove Table On Delete Blog Wordpress
	 *
	 * @param $tables
	 *
	 * @return array
	 */
	public function remove_table_on_delete_blog( $tables ) {

		foreach ( array( 'wpsmstobulk_subscribes', 'wpsmstobulk_subscribes_group', 'wpsmstobulk_send' ) as $tbl ) {
			$tables[] = $this->tb_prefix . $tbl;
		}

		return $tables;
	}
}

new Install();
