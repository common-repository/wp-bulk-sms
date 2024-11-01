<?php

namespace WBS_WP_SMS_TO_BULK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Notifications
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */
#[\AllowDynamicProperties]
class WBS_SMS_TO_Notifications {

	public $wpsmstobulk;
	public $date;
	public $options;

	/**
	 * Wordpress Database
	 *
	 * @var string
	 */
	protected $db;

	/**
	 * Wordpress Table prefix
	 *
	 * @var string
	 */
	protected $tb_prefix;

	/**
	 * WP_SMS_TO_BULK_Notifications constructor.
	 */
	public function __construct() {
		global $wpsmstobulk, $wp_version, $wpdb;

		$this->sms       = $wpsmstobulk;
		$this->date      = WBS_WP_SMS_TO_BULK_CURRENT_DATE;
		$this->options   = WBS_SMS_TO_Option::getOptions();
		$this->db        = $wpdb;
		$this->tb_prefix = $wpdb->prefix;

		if ( isset( $this->options['notif_publish_new_post'] ) ) {
			add_action( 'add_meta_boxes', array( $this, 'notification_meta_box' ) );
			add_action( 'publish_post', array( $this, 'new_post' ), 10, 2 );
		}

		// Wordpress new version
		if ( isset( $this->options['notif_publish_new_wpversion'] ) ) {
			$update = get_site_transient( 'update_core' );
			if(is_object($update) AND isset($update->updates)) {
				$update = $update->updates;
			}else{
				$update = array();
			}

			if ( isset( $update[1] ) ) {
				if ( $update[1]->current > $wp_version and $this->sms->GetCredit() ) {
					if ( get_option( 'wp_last_send_notification' ) == false ) {
						$this->sms->to  = array( $this->options['admin_mobile_phone_number'] );
						$this->sms->msg = sprintf( __( 'WordPress %s is available! Please update now', 'wp-sms-to-bulk' ), $update[1]->current );
						$this->sms->SendSMS();

						update_option( 'wp_last_send_notification', true );
					}
				} else {
					update_option( 'wp_last_send_notification', false );
				}
			}

		}

		if ( isset( $this->options['notif_register_new_user'] ) ) {
			add_action( 'user_register', array( $this, 'new_user' ), 10, 1 );
		}

		if ( isset( $this->options['notif_new_comment'] ) ) {
			add_action( 'wp_insert_comment', array( $this, 'new_comment' ), 99, 2 );
		}

		if ( isset( $this->options['notif_user_login'] ) ) {
			add_action( 'wp_login', array( $this, 'login_user' ), 99, 2 );
		}

		// Check the send to author of the post is enabled or not
		if ( WBS_SMS_TO_Option::getOption( 'notif_publish_new_post_author' ) ) {
			// Add transition publish post
			add_action( 'transition_post_status', array( $this, 'transition_publish' ), 10, 3 );
		}
	}

	/**
	 * Add subscribe meta box to the post
	 */
	public function notification_meta_box() {
		add_meta_box( 'subscribe-meta-box', __( 'SMS', 'wp-sms-to-bulk' ), array(
			$this,
			'notification_meta_box_handler'
		), get_post_types(['public' => true,  'name' => 'post']), 'normal', 'high' );
	}

	/**
	 * New post manual send SMS
	 *
	 * @param $post
	 */
	public function notification_meta_box_handler( $post ) {
		global $wpdb;

	//	$get_group_result = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}wpsmstobulk_subscribes_group`" );
	//	$username_active  = $wpdb->query( "SELECT * FROM {$wpdb->prefix}wpsmstobulk_subscribes WHERE status = '1'" );
		include_once WBS_WP_SMS_TO_BULK_DIR . "includes/templates/meta-box.php";
	}

	/**
	 * Send SMS when a new post add
	 *
	 * @param $ID
	 * @param $post
	 *
	 * @return null
	 * @internal param $post_id
	 */
	public function new_post( $ID, $post ) {
		if ( sanitize_text_field($_REQUEST['wps_send_subscribe']) == 'yes' ) { 
                        $recipients = $this->db->get_col( "SELECT `meta_value` FROM `{$this->tb_prefix}usermeta` WHERE `meta_key` = 'mobile_phone' and `user_id` IN (SELECT `user_id` FROM `{$this->db->prefix}usermeta` where `meta_key` = 'wp_capabilities' and `meta_value` LIKE '%ubscriber%')" );                                                
                        $this->sms->to = $recipients;
         
			$notif_publish_new_post_words_count = isset($this->options['notif_publish_new_post_words_count']) ? intval($this->options['notif_publish_new_post_words_count']) : false;
			$words_limit = ($notif_publish_new_post_words_count === false) ? 10 : $notif_publish_new_post_words_count;
			$template_vars = array(
				'%post_title%'   => get_the_title( $ID ),
				'%post_content%' => wp_trim_words( $post->post_content, $words_limit ),
				'%post_url%'     => wp_get_shortlink( $ID ),
				'%post_date%'    => get_post_time( 'Y-m-d H:i:s', false, $ID, true ),
			);

			$message = str_replace( array_keys( $template_vars ), array_values( $template_vars ), sanitize_text_field($_REQUEST['wpsmstobulk_text_template'] ));

                        if ($recipients[1]) {
                         $this->sms->campaign_recipients = 'Subscribers';
                         } else {
                             $this->sms->campaign_recipients = $recipients;
                         }
                        
			$this->sms->msg = $message;
			$this->sms->SendSMS();
		}
	}

	/**
	 * Send SMS when a new user registered
	 * works - to send SMS to admin - must have phone set up in settings - General
	 * @param $user_id
	 */
	public function new_user( $user_id ) {

		$user = get_userdata( $user_id );

		$template_vars = array(
			'%user_login%'    => $user->user_login,
			'%user_email%'    => $user->user_email,
			'%date_register%' => $this->date,
		);

		if ( WBS_SMS_TO_Option::getOption( 'admin_mobile_phone_number' ) ) {
			// Send SMS to admin - must have phone set up in settings - General
			$this->sms->to  = array( $this->options['admin_mobile_phone_number'] );
			$message        = str_replace( array_keys( $template_vars ), array_values( $template_vars ), $this->options['notif_register_new_user_operator_template'] );
			$this->sms->msg = $message;
			$this->sms->SendSMS();
		}

		// Modify request value.
		$request = apply_filters( 'sms_to_bulk_from_notify_user_register', $_REQUEST );

		// Send SMS to user register.
		if ( isset( $user->mobile_phone ) OR $request AND ! is_array( $request ) ) {
			if ( isset( $user->mobile_phone ) ) {
				$this->sms->to = array( $user->mobile_phone );
			} else if ( $request ) {
				$this->sms->to = array( $request );
			}
			$message        = str_replace( array_keys( $template_vars ), array_values( $template_vars ), $this->options['notif_register_new_user_template'] );
			$this->sms->msg = $message;
			$this->sms->SendSMS();
		}
	}

	/**
	 * Send SMS when new comment add
	 * works
	 * @param $comment_id
	 * @param $comment_object
	 */
	public function new_comment( $comment_id, $comment_object ) {

		if ( $comment_object->comment_type == 'order_note' ) {
			return;
		}

		if ( $comment_object->comment_type == 'edd_payment_note' ) {
			return;
		}

		$this->sms->to  = array( $this->options['admin_mobile_phone_number'] );
		$template_vars  = array(
			'%comment_author%'       => $comment_object->comment_author,
			'%comment_author_email%' => $comment_object->comment_author_email,
			'%comment_author_url%'   => $comment_object->comment_author_url,
			'%comment_author_IP%'    => $comment_object->comment_author_IP,
			'%comment_date%'         => $comment_object->comment_date,
			'%comment_content%'      => $comment_object->comment_content
		);
		$message        = str_replace( array_keys( $template_vars ), array_values( $template_vars ), $this->options['notif_new_comment_template'] );
		$this->sms->msg = $message;
		$this->sms->SendSMS();
	}

	/**
	 * Send SMS when user logged in
	 * works
	 * @param $username_login
	 * @param $username
	 */
	public function login_user( $username_login, $username ) {

		if ( WBS_SMS_TO_Option::getOption( 'admin_mobile_phone_number' ) ) {
			$this->sms->to = array( $this->options['admin_mobile_phone_number'] );

			$template_vars  = array(
				'%username_login%' => $username->user_login,
				'%display_name%'   => $username->display_name
			);
			$message        = str_replace( array_keys( $template_vars ), array_values( $template_vars ), $this->options['notif_user_login_template'] );
			$this->sms->msg = $message;
			$this->sms->SendSMS();
		}
	}


	/**
	 * Send sms to author of the post if published
	 *
	 * @param $ID
	 * @param $post
	 */
	public function new_post_published( $ID, \WP_Post $post ) {
		$message       = '';
		$template_vars = array(
			'%post_title%'   => get_the_title( $ID ),
			'%post_content%' => wp_trim_words( $post->post_content, 10 ),
			'%post_url%'     => wp_get_shortlink( $ID ),
			'%post_date%'    => get_post_time( 'Y-m-d H:i:s', false, $ID, true ),
		);
		$template      = isset( $this->options['notif_publish_new_post_author_template'] ) ? $this->options['notif_publish_new_post_author_template'] : '';
		if ( $template ) {
			$message = str_replace( array_keys( $template_vars ), array_values( $template_vars ), $template );
		}
		$this->sms->to  = array( get_user_meta( $post->post_author, 'mobile_phone', true ) );
		$this->sms->msg = $message;
		$this->sms->SendSMS();
	}

	/**
	 * Add only on publish transition actions
	 * worked
	 * @param $new_status
	 * @param $old_status
	 * @param $post
	 */
	function transition_publish( $new_status, $old_status, $post ) {

		if ( 'publish' === $new_status && 'publish' !== $old_status ) {
			$post_types_option = WBS_SMS_TO_Option::getOption( 'notif_publish_new_post_author_post_type' );
                    
			// Check selected post types or not?
			if ( $post_types_option AND is_array( $post_types_option ) ) {
                           
				// Initialize values
				$post_types = array();
				foreach ( $post_types_option as $post_publish_type ) {
                                    $value = array_map( 'sanitize_text_field', explode("|", $post_publish_type) );        
                                    $post_types[ $value[1] ] = $value[0];
				}
                               
                         	if ( array_key_exists( $post->post_type, $post_types )  ) {
					$this->new_post_published( $post->ID, $post );
				}
			}
		}
	}

}

new WBS_SMS_TO_Notifications();