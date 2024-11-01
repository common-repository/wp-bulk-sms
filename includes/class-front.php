<?php

namespace WBS_WP_SMS_TO_BULK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Wbs_sms_to_front
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */
#[\AllowDynamicProperties]
class WBS_SMS_TO_Front {

	public function __construct() {

		$this->options = WBS_SMS_TO_Option::getOptions();

		// Load assets
		add_action( 'wp_enqueue_scripts', array( $this, 'front_assets' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar' ) );
	}

	/**
	 * Include front table
	 *
	 * @param  Not param
	 */
	public function front_assets() {

		//Register admin-bar.css for whole admin area
		wp_register_style( 'wpsmstobulk-admin-bar', WBS_WP_SMS_TO_BULK_URL . 'assets/css/admin-bar.css', true, WBS_WP_SMS_TO_BULK_VERSION );
		wp_enqueue_style( 'wpsmstobulk-admin-bar' );

		// Check if "Disable Style" in frontend is active or not
		if ( empty( $this->options['disable_style_in_front'] ) or ( isset( $this->options['disable_style_in_front'] ) and ! $this->options['disable_style_in_front'] ) ) {
			wp_register_style( 'wpsmstobulk-subscribe', WBS_WP_SMS_TO_BULK_URL . 'assets/css/subscribe.css', true, WBS_WP_SMS_TO_BULK_VERSION );
			wp_enqueue_style( 'wpsmstobulk-subscribe' );
		}
	}

	/**
	 * Admin bar plugin
	 */
	public function admin_bar() {
		global $wp_admin_bar;
		if ( is_super_admin() && is_admin_bar_showing() ) {
			$credit = get_option( 'wpsmstobulk_gateway_credit' );
			if ( $credit AND isset( $this->options['account_credit_in_menu'] ) AND ! is_object( $credit ) ) {
                                $wp_admin_bar->add_node(
                                    array(
                                        'id' => 'wp-credit-sms',
                                        'parent' => 'top-secondary',
                                        'title' => '<span class="ab-icon"></span>' . '<span id="wp-credit-sms-title">'.$credit.'</span>',
                                        'href' => WBS_WP_SMS_TO_BULK_ADMIN_URL . '/admin.php?page=wp-sms-to-bulk-settings&tab=gateway'
                                    )
                                );                                
                            }
			}

		$wp_admin_bar->add_menu( array(
			'id'     => 'wp-send-sms',
			'parent' => 'new-content',
			'title'  => __( 'SMSTO', 'wp-sms-to-bulk' ),
			'href'   => WBS_WP_SMS_TO_BULK_ADMIN_URL . '/admin.php?page=wp-sms-to-bulk'
		) );
	}
}

new WBS_SMS_TO_Front();