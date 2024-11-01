<?php

namespace WBS_WP_SMS_TO_BULK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * WP_SMS_TO_BULK gateway class
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */
#[\AllowDynamicProperties]
class WBS_SMS_TO_Gateway {

	public $client_id;
	public $secret;
        public $api_key;
	public $has_key = false;
	public $validateNumber = "";
	public $help = false;
	public $bulk_send = true;
	public $from;
	public $to;
        public $campaign_recipients;        
        public $_id;
	public $msg;
	protected $db;
	protected $tb_prefix;
	public $options;

	public function __construct() {
		global $wpdb;

		$this->db        = $wpdb;
		$this->tb_prefix = $wpdb->prefix;
		$this->options   = WBS_SMS_TO_Option::getOptions();               

		if ( isset( $this->options['send_unicode'] ) and $this->options['send_unicode'] ) {
			//add_filter( 'sms_to_bulk_msg', array( $this, 'applyUnicode' ) );
		}

		// Add Filters
		add_filter( 'sms_to_bulk_to', array( $this, 'modify_bulk_send' ) );
	}

	/**
	 * Initial Gateway
	 *
	 * @return mixed
	 */
	public static function initial() {
		// Include gateway
		include_once WBS_WP_SMS_TO_BULK_DIR . 'includes/gateways/class-wpsmstobulk-gateway-wpsmstobulk.php';
                $gateway_name = 'wpsmstobulk'; 
                $class_name = '\\WBS_WP_SMS_TO_BULK\\Gateway\\' . $gateway_name;
                $wpsmstobulk        = new $class_name();
                        
                        

		// Set client_id and secret
                $wpsmstobulk->api_key = WBS_SMS_TO_Option::getOption( 'gateway_wpsmstobulk_api_key' );
                $wpsmstobulk->sender_id = WBS_SMS_TO_Option::getOption( 'gateway_wpsmstobulk_sender_id' );
               

		$gateway_key = WBS_SMS_TO_Option::getOption( 'wpsmstobulk_gateway_key' );

		// Set api key
		if ( $wpsmstobulk->has_key && $gateway_key ) {
			$wpsmstobulk->has_key = $gateway_key;
		}

		// Show gateway help configuration in gateway page
		if ( $wpsmstobulk->help ) {
			add_action( 'sms_to_bulk_after_gateway', function () {
				echo ' < p class="description" > ' . esc_html($wpsmstobulk->help) . '</p > ';
			} );
		}

		// Check unit credit gateway
		if ( $wpsmstobulk->unitrial == true ) {
			$wpsmstobulk->unit = __( 'Credit', 'wp - sms' );
		} else {
			$wpsmstobulk->unit = __( 'SMS', 'wp - sms' );
		}

		// Set sender id
		if ( ! $wpsmstobulk->from ) {
			$wpsmstobulk->from = WBS_SMS_TO_Option::getOption( 'gateway_wpsmstobulk_sender_id' );
		}

		// Unset gateway key field if not available in the current gateway class.
		add_filter( 'sms_to_bulk_gateway_settings', function ( $filter ) {
			global $wpsmstobulk;

			if ( ! $wpsmstobulk->has_key ) {
				unset( $filter['wpsmstobulk_gateway_key'] );
			}

			return $filter;
		} );
           
		// Return gateway object
		return $wpsmstobulk;                
	}

	/**
	 * @param $sender
	 * @param $message
	 * @param $to
	 * @param $response
	 * @param string $status
	 *
	 * @return false|int
	 */
	public function log_message($_id,  $sender, $message, $to, $response, $status = 'ONGOING' ) { 
		return $this->db->insert(
			$this->tb_prefix . "smstobulk_send",
			array(
                                '_id'      => $_id,
				'date'      => WBS_WP_SMS_TO_BULK_CURRENT_DATE,
				'sender'    => $sender,
				'message'   => $message,
				'recipient' => implode( ',', $to ),
				'response'  => 'message_id '.var_export( $response, true ),
				'status'    => $status,
			)
		);
	}
        
	/**
	 * @param $sender
	 * @param $message
	 * @param $to
	 * @param $response
	 * @param string $status
	 *
	 * @return false|int
	 */
	public function log_campaign($_id, $sender, $message, $to, $response, $status = 'ONGOING' ) {
		return $this->db->insert(
			$this->tb_prefix . "smstobulk_send",
			array(
                                '_id'      => $_id,
				'date'      => WBS_WP_SMS_TO_BULK_CURRENT_DATE,
				'sender'    => $sender,
				'message'   => $message,
				'recipient' => $to,
				'response'  => 'campaign_id '.var_export( $response, true ),
				'status'    => $status,
			)
		);
	}        

	/**
	 * Apply Unicode for non-English characters
	 *
	 * @param string $msg
	 *
	 * @return string
	 */
	public function applyUnicode( $msg = '' ) {
		$encodedMessage = bin2hex( mb_convert_encoding( $msg, 'utf-16', 'utf-8' ) );

		return $encodedMessage;
	}

	/**
	 * @var
	 */
	static $get_response;

	/**
	 * @return mixed|void
	 */
	public static function gateway() {
		$gateways = array(
			''               => array(
				'default' => __( 'Please select your gateway', 'wpsmstobulk' ),
			),
			'cyprus'         => array(
				'websmscy' => 'websms.com.cy',
				'smsnetgr' => 'sms.net.gr',
                                'wpsmstobulk' => 'sms.to',
			),
		
		);

		return apply_filters( 'wpsmstobulk_gateway_list', $gateways );
	}

	/**
	 * @return string
	 */
	public static function status() {
		global $wpsmstobulk;

		//Check that, Are we in the Gateway WP_SMS_TO_BULK tab setting page or not?
		if ( is_admin() AND isset( $_REQUEST['page'] ) AND isset( $_REQUEST['tab'] ) AND sanitize_text_field($_REQUEST['page']) == 'wp-sms-to-bulk-settings' AND sanitize_text_field($_REQUEST['tab']) == 'gateway' ) {

			// Get credit
			$result = $wpsmstobulk->GetCredit();

			if ( is_wp_error( $result ) ) {
				// Set error message
				self::$get_response = var_export( $result->get_error_message(), true );

				// Update credit
				update_option( 'wpsmstobulk_gateway_credit', 0 );

				// Return html
				return '<div class="wpsmstobulk-no-credit"><span class="dashicons dashicons-no"></span> ' . __( 'Deactive!', 'wp-sms-to-bulk' ) . '</div>';
			}
			// Update credit
			if ( ! is_object( $result ) ) {
				update_option( 'wpsmstobulk_gateway_credit', $result );
			}
			self::$get_response = var_export( $result, true );

			// Return html
			return '<div class="wpsmstobulk-has-credit"><span class="dashicons dashicons-yes"></span> ' . __( 'Active!', 'wp-sms-to-bulk' ) . '</div>';
		}
	}

	/**
	 * @return mixed
	 */
	public static function response() {
		return self::$get_response;
	}

	/**
	 * @return mixed
	 */
	public static function help() {
		global $wpsmstobulk;

		// Get gateway help
		return $wpsmstobulk->help;
	}

	/**
	 * @return mixed
	 */
	public static function from() {
		global $wpsmstobulk;
		// Get gateway from
		return $wpsmstobulk->from;
	}

	/**
	 * @return string
	 */
	public static function bulk_status() {
		global $wpsmstobulk;

		// Get bulk status
		if ( $wpsmstobulk->bulk_send == true ) {
			// Return html
			return '<div class="wpsmstobulk-has-credit"><span class="dashicons dashicons-yes"></span> ' . __( 'Supported', 'wp-sms-to-bulk' ) . '</div>';
		} else {
			// Return html
			return '<div class="wpsmstobulk-no-credit"><span class="dashicons dashicons-no"></span> ' . __( 'Does not support!', 'wp-sms-to-bulk' ) . '</div>';
		}
	}

	/**
	 * @return int
	 */
	public static function credit() {
		global $wpsmstobulk;

		// Get credit
		$result = $wpsmstobulk->GetCredit();

		if ( is_wp_error( $result ) ) {
			update_option( 'wpsmstobulk_gateway_credit', 0 );

			return 0;
		}

		if ( ! is_object( $result ) ) {
			update_option( 'wpsmstobulk_gateway_credit', $result );
		}

		return $result;
	}

	/**
	 * Modify destination number
	 *
	 * @param array $to
	 *
	 * @return array/string
	 */
	public function modify_bulk_send( $to ) {
		global $wpsmstobulk;
		if ( ! $wpsmstobulk->bulk_send ) {
			return array( $to[0] );
		}

		return $to;
	}

}
