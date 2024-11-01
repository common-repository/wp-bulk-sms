<?php

namespace WBS_WP_SMS_TO_BULK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // No direct access allowed ;)

/**
 * Class Settings
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 * 
 */
#[\AllowDynamicProperties]
class WBS_SMS_TO_Settings {

	public $setting_name;
	public $options = array();

	public function __construct() {
		$this->setting_name = 'wpsmstobulk_settings';
		$this->get_settings();
		$this->options = get_option( $this->setting_name );
              
		if ( empty( $this->options ) ) {
			update_option( $this->setting_name, array() );
		}
                
		add_action( 'admin_menu', array( $this, 'add_settings_menu' ), 11 );

		if ( isset( $_GET['page'] ) and sanitize_text_field($_GET['page']) == 'wp-sms-to-bulk-settings' or isset( $_POST['option_page'] ) and sanitize_text_field($_POST['option_page']) == 'wpsmstobulk_settings' ) {
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
                              
	}

	/**
	 * Add WP-SMSto Package admin page settings
	 * */
	public function add_settings_menu() {
		add_submenu_page( 'wp-sms-to-bulk', __( 'Settings', 'wp-sms-to-bulk' ), __( 'Settings', 'wp-sms-to-bulk' ), 'wpsmstobulk_setting', 'wp-sms-to-bulk-settings', array(
			$this,
			'render_settings'
		) );
	}

	/**
	 * Gets saved settings from WP core
	 *
	 * @since           2.0
	 * @return          array
	 */
	public function get_settings() {
		$settings = get_option( $this->setting_name );
                
               $url = site_url();
         
		if ( ! $settings ) {
			update_option( $this->setting_name, array(
                            'gateway_wpsmstobulk_sender_id' => 'SMSto',
                            'login_sms' => 1,
                            'international_mobile_phone' => 1,
                            'gateway_wpsmstobulk_callback_url' => $url, 
                            'login_no_of_retries_value' => 3,
                            'add_mobile_phone_field' => 1,
                            'account_credit_in_menu' => 1,
                            'account_credit_in_sendsms' => 1,
			) );        
		}
		return apply_filters( 'wpsmstobulk_get_settings', $settings );
	}

	/**
	 * Registers settings in WP core
	 *
	 * @since           2.0
	 * @return          void
	 */
	public function register_settings() {
		if ( false == get_option( $this->setting_name ) ) {
			add_option( $this->setting_name );
		}

		foreach ( $this->get_registered_settings() as $tab => $settings ) {
			add_settings_section(
				'wpsmstobulk_settings_' . $tab,
				__return_null(),
				'__return_false',
				'wpsmstobulk_settings_' . $tab
			);                        
               
			if ( empty( $settings ) ) {
				return;
			}

			foreach ( $settings as $option ) {
				$name = isset( $option['name'] ) ? $option['name'] : '';

				add_settings_field(
					'wpsmstobulk_settings[' . $option['id'] . ']',
					$name,
					array( $this, $option['type'] . '_callback' ),
					'wpsmstobulk_settings_' . $tab,
					'wpsmstobulk_settings_' . $tab,
					array(
						'id'      => isset( $option['id'] ) ? $option['id'] : null,
						'desc'    => ! empty( $option['desc'] ) ? $option['desc'] : '',
						'name'    => isset( $option['name'] ) ? $option['name'] : null,
						'section' => $tab,
						'size'    => isset( $option['size'] ) ? $option['size'] : null,
						'options' => isset( $option['options'] ) ? $option['options'] : '',
						'std'     => isset( $option['std'] ) ? $option['std'] : ''
					)
				);
                        
				register_setting( $this->setting_name, $this->setting_name, array( $this, 'settings_sanitize' ) );
			}
		}
                 }
                 
	/**
	 * Gets settings tabs
	 *
	 * @since               2.0
	 * @return              array Tabs list
	 */
	public function get_tabs() {
		$tabs = array(
			'general'       => __( 'General', 'wp-sms-to-bulk' ),
			'gateway'       => __( 'Gateway', 'wp-sms-to-bulk' ),
			'feature'       => __( 'Features', 'wp-sms-to-bulk' ),
			'notifications' => __( 'Notifications', 'wp-sms-to-bulk' ),
		);

		return $tabs;
	}

	/**
	 * Sanitizes and saves settings after submit
	 *
	 * @since               2.0
	 *
	 * @param               array $input Settings input
	 *
	 * @return              array New settings
	 */
	public function settings_sanitize( $input = array() ) {            

		if ( empty( $_POST['_wp_http_referer'] ) ) {
			return $input;
		}

		parse_str( sanitize_text_field($_POST['_wp_http_referer']), $referrer );

		$settings = $this->get_registered_settings();
		$tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'wp';

		$input = $input ? $input : array();
		$input = apply_filters( 'wpsmstobulk_settings_' . $tab . '_sanitize', $input );

		// Loop through each setting being saved and pass it through a sanitization filter
		foreach ( $input as $key => $value ) {

			// Get the setting type (checkbox, select, etc)
			$type = isset( $settings[ $tab ][ $key ]['type'] ) ? $settings[ $tab ][ $key ]['type'] : false;

			if ( $type ) {
				// Field type specific filter
				$input[ $key ] = apply_filters( 'wpsmstobulk_settings_sanitize_' . $type, $value, $key );
			}

			// General filter
			$input[ $key ] = apply_filters( 'wpsmstobulk_settings_sanitize', $value, $key );
		}

		// Loop through the whitelist and unset any that are empty for the tab being saved
		if ( ! empty( $settings[ $tab ] ) ) {
			foreach ( $settings[ $tab ] as $key => $value ) {

				// settings used to have numeric keys, now they have keys that match the option ID. This ensures both methods work
				if ( is_numeric( $key ) ) {
					$key = $value['id'];
				}

				if ( empty( $input[ $key ] ) ) {
					unset( $this->options[ $key ] );
				}

			}
		}

		// Merge our new settings with the existing
		$output = array_merge( $this->options, $input );

		add_settings_error( 'wpsmstobulk-notices', '', __( 'Settings updated', 'wp-sms-to-bulk' ), 'updated' );

		return $output;

	}

	/**
	 * Get settings fields
	 *
	 * @since           2.0
	 * @return          array Fields
	 */
	public function get_registered_settings() {

		$options = array(
			'enable'  => __( 'Enable', 'wp-sms-to-bulk' ),
			'disable' => __( 'Disable', 'wp-sms-to-bulk' )
		);

               
		$settings = apply_filters( 'sms_to_bulk_registered_settings', array(
			// General tab
			'general'       => apply_filters( 'sms_to_bulk_general_settings', array(
				'admin_title'         => array(
					'id'   => 'admin_title',
					'name' => __( 'Mobile', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),                   
				'admin_mobile_phone_number' => array(
					'id'   => 'admin_mobile_phone_number',
					'name' => __( 'Operator mobile phone number', 'wp-sms-to-bulk' ),
					'type' => 'international_phone_number',
                                        'desc' => __( 'Operator mobile phone number to get any sms notifications<br>', 'wp-sms-to-bulk' )                                    
				),
				'admin_title_wpsmstobulk_privacy' => array(
					'id'   => 'admin_title_wpsmstobulk_privacy',
					'name' => __( 'Privacy', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'gdpr_wpsmstobulk_compliance'     => array(
					'id'      => 'gdpr_wpsmstobulk_compliance',
					'name'    => __( 'GDPR Enhancements', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Enable GDPR related features in this page. Read our GDPR documentation to learn more.', 'wp-sms-to-bulk' ),
				),
			) ),

			// Gateway tab
			'gateway'       => apply_filters( 'sms_to_bulk_gateway_settings', array(
				// Gateway
				'gayeway_title'             => array(
					'id'   => 'gayeway_title',
					'name' => __( 'Gateway information', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'gateway_wpsmstobulk_api_key'          => array(
					'id'   => 'gateway_wpsmstobulk_api_key',
					'name' => __( 'API Key', 'wp-sms-to-bulk' ),
					'type' => 'text',
					'desc' => __( 'Enter Api Key', 'wp-sms-to-bulk' )
				),  
                                'gateway_wpsmstobulk_callback_url'          => array(
					'id'   => 'gateway_wpsmstobulk_callback_url',
					'name' => __( 'Callback URL', 'wp-sms-to-bulk' ),
					'type' => 'text',
					'desc' => __( 'Your WordPress callback URL to update Reports<br>If you must change it, please do it with caution', 'wp-sms-to-bulk' )
				),                                                        
				'gateway_wpsmstobulk_sender_id'         => array(
					'id'   => 'gateway_wpsmstobulk_sender_id',
					'name' => __( 'Sender', 'wp-sms-to-bulk' ),
					'type' => 'text11chars',
					'std'  => WBS_SMS_TO_Gateway::from(),
					'desc' => __( 'Sender number or sender ID - 11 characters max. <br>Can contain only letters digits and spaces.', 'wp-sms-to-bulk' )
				),
				'wpsmstobulk_gateway_key'               => array(
					'id'   => 'wpsmstobulk_gateway_key',
					'name' => __( 'API key', 'wp-sms-to-bulk' ),
					'type' => 'text',
					'desc' => __( 'Enter API key of gateway', 'wp-sms-to-bulk' )
				),
				// Gateway status
				'wpsmstobulk_gateway_status_title'      => array(
					'id'   => 'wpsmstobulk_gateway_status_title',
					'name' => __( 'Gateway status', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'account_credit'            => array(
					'id'      => 'account_credit',
					'name'    => __( 'Status', 'wp-sms-to-bulk' ),
					'type'    => 'html',
					'options' => WBS_SMS_TO_Gateway::status(),
				),
				'account_response'          => array(
					'id'      => 'account_response',
					'name'    => __( 'Result request', 'wp-sms-to-bulk' ),
					'type'    => 'html',
					'options' => WBS_SMS_TO_Gateway::response(),
				),
				'bulk_send'                 => array(
					'id'      => 'bulk_send',
					'name'    => __( 'Bulk send', 'wp-sms-to-bulk' ),
					'type'    => 'html',
					'options' => WBS_SMS_TO_Gateway::bulk_status(),
				),
				// Account credit
				'account_credit_title'      => array(
					'id'   => 'account_credit_title',
					'name' => __( 'Account balance', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'account_credit_in_menu'    => array(
					'id'      => 'account_credit_in_menu',
					'name'    => __( 'Show in admin menu', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Show your account credit in admin menu.', 'wp-sms-to-bulk' )
				),
				'account_credit_in_sendsms' => array(
					'id'      => 'account_credit_in_sendsms',
					'name'    => __( 'Show in send SMS page', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Show your account credit in send SMS page.', 'wp-sms-to-bulk' )
				),
                            
 
			) ),

			
			// Feature tab
			'feature'       => apply_filters( 'sms_to_bulk_feature_settings', array(
				'mobile_phone_field'                     => array(
					'id'   => 'mobile_phone_field',
					'name' => __( 'Mobile field', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'add_mobile_phone_field'                 => array(
					'id'      => 'add_mobile_phone_field',
					'name'    => __( 'Add Mobile number field', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Add Mobile number to user profile and register form.', 'wp-sms-to-bulk' )
				),
				'international_mobile_phone_title'       => array(
					'id'   => 'international_mobile_phone_title',
					'name' => __( 'International Telephone Input', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'international_mobile_phone'             => array(
					'id'      => 'international_mobile_phone',
					'name'    => __( 'Enable for mobile phone fields', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Adds country code in mobile phone field', 'wp-sms-to-bulk' )
				),
				'international_mobile_phone_only_countries'      => array(
					'id'      => 'international_mobile_phone_only_countries',
					'name'    => __( 'Only Countries', 'wp-sms-to-bulk' ),
					'type'    => 'countryselect',
					'options' => $this->get_countries_list(),
					'desc'    => __( 'In the dropdown Country select display only the countries you specify.', 'wp-sms-to-bulk' )
				),
				'international_mobile_phone_preferred_countries' => array(
					'id'      => 'international_mobile_phone_preferred_countries',
					'name'    => __( 'Prefix Countries', 'wp-sms-to-bulk' ),
					'type'    => 'countryselect',
					'options' => $this->get_countries_list(),
					'desc'    => __( 'Specify the countries to appear at the top of the list.', 'wp-sms-to-bulk' )
				),
			) ),
			// Notifications tab
			'notifications' => apply_filters( 'sms_to_bulk_notifications_settings', array(
				// Publish new post
				'notif_publish_new_post_title'            => array(
					'id'   => 'notif_publish_new_post_title',
					'name' => __( 'Published new posts', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'notif_publish_new_post'                  => array(
					'id'      => 'notif_publish_new_post',
					'name'    => __( 'Status', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Send an SMS to Wordpress subscribers When publish new post.', 'wp-sms-to-bulk' )
				),
				'notif_publish_new_post_words_count'     => array(
					'id'      => 'notif_publish_new_post_words_count',
					'name'    => __( 'Post content words count', 'wp-sms-to-bulk' ),
					'type'    => 'number_not_required',
					'desc'    => __( 'The number of word cropped in Post Content publish notification. Default : 10', 'wp-sms-to-bulk' )
				),
				'notif_publish_new_post_template'         => array(
					'id'   => 'notif_publish_new_post_template',
					'name' => __( 'Message body', 'wp-sms-to-bulk' ),
					'type' => 'textarea',
					'desc' => __( 'Enter the contents of the sms message.', 'wp-sms-to-bulk' ) . '<br>' .
					          sprintf(
						          __( 'Post title: %s, Post content: %s, Post url: %s, Post date: %s', 'wp-sms-to-bulk' ),
						          '<code>%post_title%</code>',
						          '<code>%post_content%</code>',
						          '<code>%post_url%</code>',
						          '<code>%post_date%</code>'
					          )
				),
				// Publish new post
				'notif_publish_new_post_author_title'     => array(
					'id'   => 'notif_publish_new_post_author_title',
					'name' => __( 'Author of the post', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'notif_publish_new_post_author'           => array(
					'id'      => 'notif_publish_new_post_author',
					'name'    => __( 'Status', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Send an SMS to the author of the post when that post is published.', 'wp-sms-to-bulk' )
				),
				'notif_publish_new_post_author_post_type' => array(
					'id'      => 'notif_publish_new_post_author_post_type',
					'name'    => __( 'Post Types', 'wp-sms-to-bulk' ),
					'type'    => 'multiselect',
					'options' => $this->get_list_post_type( array( 'show_ui' => 1 ) ),
					'desc'    => __( 'Select post types that you want to use this option.<br>Must select at least one to enable.', 'wp-sms-to-bulk' )
				),
				'notif_publish_new_post_author_template'  => array(
					'id'   => 'notif_publish_new_post_author_template',
					'name' => __( 'Message body', 'wp-sms-to-bulk' ),
					'type' => 'textarea',
					'desc' => __( 'Enter the contents of the sms message.', 'wp-sms-to-bulk' ) . '<br>' .
					          sprintf(
						          __( 'Post title: %s, Post content: %s, Post url: %s, Post date: %s', 'wp-sms-to-bulk' ),
						          '<code>%post_title%</code>',
						          '<code>%post_content%</code>',
						          '<code>%post_url%</code>',
						          '<code>%post_date%</code>'
					          )
				),
				// Publish new wp version
				'notif_publish_new_wpversion_title'       => array(
					'id'   => 'notif_publish_new_wpversion_title',
					'name' => __( 'The new release of WordPress', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'notif_publish_new_wpversion'             => array(
					'id'      => 'notif_publish_new_wpversion',
					'name'    => __( 'Status', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Send an SMS to Operator mobile phone number when a new release of WordPress.', 'wp-sms-to-bulk' )
				),
				// Register new user
				'notif_register_new_user_title'           => array(
					'id'   => 'notif_register_new_user_title',
					'name' => __( 'Register a new user', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'notif_register_new_user'                 => array(
					'id'      => 'notif_register_new_user',
					'name'    => __( 'Status', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Send an SMS to Operator mobile phone number and to the user when registers on wordpress.', 'wp-sms-to-bulk' )
				),
				'notif_register_new_user_operator_template'  => array(
					'id'   => 'notif_register_new_user_operator_template',
					'name' => __( 'Message body for Operator', 'wp-sms-to-bulk' ),
					'type' => 'textarea',
					'desc' => __( 'Enter the contents of the sms message.', 'wp-sms-to-bulk' ) . '<br>' .
					          sprintf(
						          __( 'User login: %s, User email: %s, Register date: %s', 'wp-sms-to-bulk' ),
						          '<code>%user_login%</code>',
						          '<code>%user_email%</code>',
						          '<code>%date_register%</code>'
					          )
				),
				'notif_register_new_user_template'        => array(
					'id'   => 'notif_register_new_user_template',
					'name' => __( 'Message body for User', 'wp-sms-to-bulk' ),
					'type' => 'textarea',
					'desc' => __( 'Enter the contents of the sms message.', 'wp-sms-to-bulk' ) . '<br>' .
					          sprintf(
						          __( 'User login: %s, User email: %s, Register date: %s', 'wp-sms-to-bulk' ),
						          '<code>%user_login%</code>',
						          '<code>%user_email%</code>',
						          '<code>%date_register%</code>'
					          )
				),
				// New comment
				'notif_new_comment_title'                 => array(
					'id'   => 'notif_new_comment_title',
					'name' => __( 'New comment', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'notif_new_comment'                       => array(
					'id'      => 'notif_new_comment',
					'name'    => __( 'Status', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Send an SMS to Operator mobile phone number when get a new comment.', 'wp-sms-to-bulk' )
				),
				'notif_new_comment_template'              => array(
					'id'   => 'notif_new_comment_template',
					'name' => __( 'Message body', 'wp-sms-to-bulk' ),
					'type' => 'textarea',
					'desc' => __( 'Enter the contents of the sms message.', 'wp-sms-to-bulk' ) . '<br>' .
					          sprintf(
						          __( 'Comment author: %s, Author email: %s, Author url: %s, Author IP: %s, Comment date: %s, Comment content: %s', 'wp-sms-to-bulk' ),
						          '<code>%comment_author%</code>',
						          '<code>%comment_author_email%</code>',
						          '<code>%comment_author_url%</code>',
						          '<code>%comment_author_IP%</code>',
						          '<code>%comment_date%</code>',
						          '<code>%comment_content%</code>'
					          )
				),
				// User login
				'notif_user_login_title'                  => array(
					'id'   => 'notif_user_login_title',
					'name' => __( 'User login', 'wp-sms-to-bulk' ),
					'type' => 'header'
				),
				'notif_user_login'                        => array(
					'id'      => 'notif_user_login',
					'name'    => __( 'Status', 'wp-sms-to-bulk' ),
					'type'    => 'checkbox',
					'options' => $options,
					'desc'    => __( 'Send an SMS to Operator mobile phone number when user is login.', 'wp-sms-to-bulk' )
				),
				'notif_user_login_template'               => array(
					'id'   => 'notif_user_login_template',
					'name' => __( 'Message body', 'wp-sms-to-bulk' ),
					'type' => 'textarea',
					'desc' => __( 'Enter the contents of the sms message.', 'wp-sms-to-bulk' ) . '<br>' .
					          sprintf(
						          __( 'Username: %s, Nickname: %s', 'wp-sms-to-bulk' ),
						          '<code>%username_login%</code>',
						          '<code>%display_name%</code>'
					          )
				),
			) ),
		) );

		return $settings;
	}

	public function header_callback( $args ) {
		echo '<hr/>';
	}

	public function html_callback( $args ) {
                echo wp_kses_post($args['options']);
	}

	public function notice_callback( $args ) {
                echo wp_kses_post($args['desc']);
	}

	public function checkbox_callback( $args ) {
		$checked = isset( $this->options[ $args['id'] ] ) ? checked( 1, $this->options[ $args['id'] ], false ) : '';
		$html    = '<input type="checkbox" id="wpsmstobulk_settings[' . $args['id'] . ']" name="wpsmstobulk_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>';
		$html    .= '<label for="wpsmstobulk_settings[' . $args['id'] . ']"> ' . __( 'Active', 'wp-sms-to-bulk' ) . '</label>';
		$html    .= '<p class="description"> ' . $args['desc'] . '</p>';

                echo  wp_kses_normalize_entities($html);
	}

	public function text11chars_callback( $args ) {
		if ( isset( $this->options[ $args['id'] ] ) and $this->options[ $args['id'] ] ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

                $maxlength="11";
       
		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input required type="text" class="' . $size . '-text" id="wpsmstobulk_settings[' . $args['id'] . ']" name="wpsmstobulk_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '" maxlength="' . esc_attr( $maxlength ) . '"/>';
		$html .= '<p class="description"> ' . $args['desc'] . '</p>';

		echo  wp_kses_normalize_entities($html);
	}        
	public function text_callback( $args ) {
		if ( isset( $this->options[ $args['id'] ] ) and $this->options[ $args['id'] ] ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input type="text" class="' . $size . '-text" id="wpsmstobulk_settings[' . $args['id'] . ']" name="wpsmstobulk_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<p class="description"> ' . $args['desc'] . '</p>';

		echo  wp_kses_normalize_entities($html);
	}
        
        
        
	public function international_phone_number_callback( $args ) {
   
            echo '<hr/>';
            
		if ( isset( $this->options[ $args['id'] ] ) and $this->options[ $args['id'] ] ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}
                
                

                if ( sms_to_bulk_get_option( 'international_mobile_phone' ) ) {
                        $sms_to_bulk_input_mobile_phone = " wp-sms-to-bulk-input-mobile_phone";
                } else {
                        $sms_to_bulk_input_mobile_phone = "";
                }                 

		$html = '<input type="text" class="regular-text' . $sms_to_bulk_input_mobile_phone . '" id="wpsmstobulk_settings[' . $args['id'] . ']" name="wpsmstobulk_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<p class="description"> ' . $args['desc'] . '</p>';

		echo  wp_kses_normalize_entities($html);


                        

	}        

	public function number_not_required_callback( $args ) {
            
                $style="width:15%";

            
		if ( isset( $this->options[ $args['id'] ] ) ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$max  = isset( $args['max'] ) ? $args['max'] : 999999;
		$min  = isset( $args['min'] ) ? $args['min'] : 1;
		$step = isset( $args['step'] ) ? $args['step'] : 1;

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input type="number" step="' . esc_attr( $step ) . '" style="' . esc_attr( $style ). '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="wpsmstobulk_settings[' . $args['id'] . ']" name="wpsmstobulk_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<p class="description"> ' . $args['desc'] . '</p>';

		echo  wp_kses_normalize_entities($html);
	}        

	public function textarea_callback( $args ) {
		if ( isset( $this->options[ $args['id'] ] ) ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<textarea class="large-text" cols="50" rows="5" id="wpsmstobulk_settings[' . $args['id'] . ']" name="wpsmstobulk_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
		$html .= '<p class="description"> ' . $args['desc'] . '</p>';

		echo wp_kses_post($html);
	}

	public function missing_callback( $args ) {
		echo '&ndash;';

		return false;
	}


	public function multiselect_callback( $args ) {
		if ( isset( $this->options[ $args['id'] ] ) ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$html     = '<select id="wpsmstobulk_settings[' . $args['id'] . ']" name="wpsmstobulk_settings[' . $args['id'] . '][]" multiple="true" class="chosen-select"/>';
		$selected = '';

		foreach ( $args['options'] as $k => $name ) :
			foreach ( $name as $option => $name ):
				if ( isset( $value ) AND is_array( $value ) ) {
					if ( in_array( $option, $value ) ) {
						$selected = " selected='selected'";
					} else {
						$selected = '';
					}
				}
				$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
			endforeach;
		endforeach;

		$html .= '</select>';
		$html .= '<p class="description"> ' . $args['desc'] . '</p>';

                echo  wp_kses_normalize_entities($html);

	}

	public function countryselect_callback( $args ) {
		if ( isset( $this->options[ $args['id'] ] ) ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$html     = '<select id="wpsmstobulk_settings[' . $args['id'] . ']" name="wpsmstobulk_settings[' . $args['id'] . '][]" multiple="true" class="chosen-select"/>';
		$selected = '';

		foreach ( $args['options'] as $option => $country ) :
			if ( isset( $value ) AND is_array( $value ) ) {
				if ( in_array( $country['code'], $value ) ) {
					$selected = " selected='selected'";
				} else {
					$selected = '';
				}
			}
			$html .= '<option value="' . $country['code'] . '" ' . $selected . '>' . $country['name'] . '</option>';
		endforeach;

		$html .= '</select>';
		$html .= '<p class="description"> ' . $args['desc'] . '</p>';

		echo  wp_kses_normalize_entities($html);
	}


	public function render_settings() {           
		$active_tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $this->get_tabs() ) ? sanitize_text_field($_GET['tab']) : 'general';

		ob_start();
		?>
        <div class="wrap wpsmstobulk-settings-wrap">
			<?php do_action( 'sms_to_bulk_settings_page' ); ?>
            <h2><?php _e( 'Settings', 'wp-sms-to-bulk' ) ?></h2>
            <div class="wpsmstobulk-tab-group">
                <ul class="wpsmstobulk-tab">
                    <li id="wpsmstobulk-logo" class="wpsmstobulk-logo-group">
                        <img src="<?php echo esc_html(WBS_WP_SMS_TO_BULK_URL); ?>assets/images/intergo-telecom-logo.png"/>
                        <p><?php echo sprintf( __( 'WP - Bulk SMS - by SMS.to v%s', 'wp-sms-to-bulk' ), esc_html(WBS_WP_SMS_TO_BULK_VERSION) ); ?></p>
						<?php do_action( 'sms_to_bulk_after_setting_logo' ); ?>
                    </li>              
					<?php                                    
					foreach ( $this->get_tabs() as $tab_id => $tab_name ) {

						$tab_url = add_query_arg( array(
							'settings-updated' => false,
							'tab'              => $tab_id
						) );

						$active = $active_tab == $tab_id ? 'active' : '';
                                              
						echo '<li><a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="' . $active . '">';
						echo esc_html($tab_name);
                                                echo '</a></li>';
                                                
					}
					?>
                </ul>
				<?php echo esc_html(settings_errors( 'wpsmstobulk-notices' )); ?>
                <div class="wpsmstobulk-tab-content">
                    <form method="post" action="options.php">
                        <table class="form-table">
							<?php
							settings_fields( $this->setting_name );
							do_settings_fields( 'wpsmstobulk_settings_' . $active_tab, 'wpsmstobulk_settings_' . $active_tab );
							?>
                        </table>
						<?php submit_button(); ?>
                    </form>
                </div>
            </div>
        </div>
		<?php
                echo  wp_kses_normalize_entities(ob_get_clean());
	}

	/*
	 * Get list Post Type
	 */
	public function get_list_post_type( $args = array() ) {

		// vars
		$post_types = array();

		// extract special arg
		$exclude   = array();
		$exclude[] = 'attachment';
		$exclude[] = 'acf-field'; //Advance custom field
		$exclude[] = 'acf-field-group'; //Advance custom field Group
		$exclude[] = 'vc4_templates'; //Visual composer
		$exclude[] = 'vc_grid_item'; //Visual composer Grid
		$exclude[] = 'acf'; //Advance custom field Basic
		$exclude[] = 'wpcf7_contact_form'; //contact 7 Post Type
		$exclude[] = 'shop_order'; //WooCommerce Shop Order
		$exclude[] = 'shop_coupon'; //WooCommerce Shop coupon

		// get post type objects
		$objects = get_post_types( $args, 'objects' );
		foreach ( $objects as $k => $object ) {
			if ( in_array( $k, $exclude ) ) {
				continue;
			}
			if ( $object->_builtin && ! $object->public ) {
				continue;
			}
			$post_types[] = array( $object->cap->publish_posts . '|' . $object->name => $object->label );
		}

		// return
		return $post_types;
	}

	/**
	 * Get countries list
	 *
	 * @return array|mixed|object
	 */
	public function get_countries_list() {
		// Load countries list file
		$file   = WBS_WP_SMS_TO_BULK_DIR . 'assets/countries.json';
		$file   = file_get_contents( $file );
		$result = json_decode( $file, true );

		return $result;
	}
        
       
        
}

new WBS_SMS_TO_Settings();