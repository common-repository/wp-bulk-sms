<?php

namespace WBS_WP_SMS_TO_BULK;

/**
 * Class Admin
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */

class Admin {

	public $wpsmstobulk;
	protected $db;
	protected $tb_prefix;
	protected $options;

	public function __construct() {
		global $wpdb;

		$this->db        = $wpdb;
		$this->tb_prefix = $wpdb->prefix;
		$this->options   = WBS_SMS_TO_Option::getOptions();
		$this->init();

		// Add Actions
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar' ) );
		
                
                //check if any other SMSTO plugin has already added the action
                $hook_name = 'dashboard_glance_items';
                $search_value = 'dashboard_smsto_glance';
                global $wp_filter;

                if (isset($wp_filter[$hook_name])) {
                    $encoded_hook_name = json_encode($wp_filter[$hook_name]);
                    $no_of_occurances = substr_count($encoded_hook_name, $search_value);
                    if ($no_of_occurances == 0) {
                        add_action('dashboard_glance_items', array($this, 'dashboard_smsto_glance'));
                    }
                } else {
                    add_action('dashboard_glance_items', array($this, 'dashboard_smsto_glance'));
                }


                add_action( 'admin_menu', array( $this, 'admin_menu' ) );
                
		// Add Filters
		add_filter( 'plugin_row_meta', array( $this, 'meta_links' ), 0, 2 );
	}

	/**
	 * Include admin assets
	 */
	public function admin_assets() {
            
            
                wp_register_style( 'wpsmstobulk-documentation_style', WBS_WP_SMS_TO_BULK_URL . 'assets/css/documentation_style.min.css', true, WBS_WP_SMS_TO_BULK_VERSION );
		wp_enqueue_style( 'wpsmstobulk-documentation_style' );
                wp_enqueue_script( 'wpsmstobulk-documentation_settings', WBS_WP_SMS_TO_BULK_URL . 'assets/js/documentation_settings.min.js', true, WBS_WP_SMS_TO_BULK_VERSION );
            
		// Register admin-bar.css for whole admin area
		wp_register_style( 'wpsmstobulk-admin-bar', WBS_WP_SMS_TO_BULK_URL . 'assets/css/admin-bar.css', true, WBS_WP_SMS_TO_BULK_VERSION );
		wp_enqueue_style( 'wpsmstobulk-admin-bar' );

		if ( stristr( get_current_screen()->id, "wp-sms-to-bulk" ) ) {
			wp_register_style( 'wpsmstobulk-admin', WBS_WP_SMS_TO_BULK_URL . 'assets/css/admin.css', true, WBS_WP_SMS_TO_BULK_VERSION );
			wp_enqueue_style( 'wpsmstobulk-admin' );
			if ( is_rtl() ) {
				wp_enqueue_style( 'wpsmstobulk-rtl', WBS_WP_SMS_TO_BULK_URL . 'assets/css/rtl.css', true, WBS_WP_SMS_TO_BULK_VERSION );
			}

			wp_enqueue_style( 'wpsmstobulk-chosen', WBS_WP_SMS_TO_BULK_URL . 'assets/css/chosen.min.css', true, WBS_WP_SMS_TO_BULK_VERSION );
			wp_enqueue_script( 'wpsmstobulk-chosen', WBS_WP_SMS_TO_BULK_URL . 'assets/js/chosen.jquery.min.js', true, WBS_WP_SMS_TO_BULK_VERSION );
			wp_enqueue_script( 'wpsmstobulk-word-and-character-counter', WBS_WP_SMS_TO_BULK_URL . 'assets/js/jquery.word-and-character-counter.min.js', true, WBS_WP_SMS_TO_BULK_VERSION );
			wp_enqueue_script( 'wpsmstobulkrepeater', WBS_WP_SMS_TO_BULK_URL . 'assets/js/jquery.repeater.min.js', true, WBS_WP_SMS_TO_BULK_VERSION );
                        wp_enqueue_script( 'wpsmstobulkblocktimerepeater', WBS_WP_SMS_TO_BULK_URL . 'assets/js/jquery.repeater.min.js', true, WBS_WP_SMS_TO_BULK_VERSION );
			wp_enqueue_script( 'wpsmstobulk-admin', WBS_WP_SMS_TO_BULK_URL . 'assets/js/admin.js', true, WBS_WP_SMS_TO_BULK_VERSION );
		}
	}

	/**
	 * Admin bar plugin
	 */
	public function admin_bar() {
		global $wp_admin_bar;
		if ( is_super_admin() && is_admin_bar_showing() ) {                   
                    $credit = get_option('wpsmstobulk_gateway_credit');
                    if ($credit AND isset($this->options['account_credit_in_menu']) AND!is_object($credit)) {
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
			'href'   => sanitize_text_field(WBS_WP_SMS_TO_BULK_ADMIN_URL) . '/admin.php?page=wp-sms-to-bulk'
		) );
	}
        
	/**
	 * Dashboard glance plugin
	 */
	public function dashboard_smsto_glance() {
		$subscribe = $this->db->get_var( "SELECT COUNT(*) FROM `{$this->db->prefix}usermeta` where `meta_key` = 'wp_capabilities' and `meta_value` LIKE '%ubscriber%'" );
		$credit    = get_option( 'wpsmstobulk_gateway_credit' );

		echo "<li class='wpsmstobulk-subscribe-count'><a href='" . esc_html(WBS_WP_SMS_TO_BULK_ADMIN_URL) . "users.php?role=subscriber'>" . sprintf( __( '%s WP Subscriber(s)', 'wp-sms-to-bulk' ), esc_html($subscribe) ) . "</a></li>";
		if ( ! is_object( $credit ) ) {
			echo "<li class='wpsmstobulk-credit-count'><a href='" . esc_html(WBS_WP_SMS_TO_BULK_ADMIN_URL) . "admin.php?page=wp-sms-to-bulk-settings&tab=gateway'>" . sprintf( __( '%s SMSto Credit', 'wp-sms-to-bulk' ), esc_html($credit) ) . "</a></li>";

		}
	}

	/**
	 * Administrator admin_menu
	 */
	public function admin_menu() {
		$hook_suffix = array();
		add_menu_page( __( 'WP - Bulk SMS - by SMS.to', 'wp-sms-to-bulk' ), __( 'Bulk SMS', 'wp-sms-to-bulk' ), 'wpsmstobulk_sendsms', 'wp-sms-to-bulk', array( $this, 'send_sms_callback' ), 'dashicons-smartphone' );
		$hook_suffix['send_sms'] = add_submenu_page( 'wp-sms-to-bulk', __( 'Send SMS', 'wp-sms-to-bulk' ), __( 'Send SMS', 'wp-sms-to-bulk' ), 'wpsmstobulk_sendsms', 'wp-sms-to-bulk', array( $this, 'send_sms_callback' ) );
		add_submenu_page( 'wp-sms-to-bulk', __( 'Reports', 'wp-sms-to-bulk' ), __( 'Reports', 'wp-sms-to-bulk' ), 'wpsmstobulk_outbox', 'wp-sms-to-bulk-outbox', array( $this, 'outbox_callback' ) );
                add_submenu_page( 'wp-sms-to-bulk', __( 'Documentation', 'wp-sms-to-bulk' ), __( 'Documentation', 'wp-sms-to-bulk' ), 'wpsmstobulk_outbox', 'wp-sms-to-bulk-documentation', array( $this, 'documentation_callback' ) );
                
		// Check GDPR compliance for Privacy menu
		if ( isset( $this->options['gdpr_wpsmstobulk_compliance'] ) and $this->options['gdpr_wpsmstobulk_compliance'] == 1 ) {
			$hook_suffix['privacy'] = add_submenu_page( 'wp-sms-to-bulk', __( 'Privacy', 'wp-sms-to-bulk' ), __( 'Privacy', 'wp-sms-to-bulk' ), 'manage_options', 'wp-sms-to-bulk-subscribers-privacy', array( $this, 'privacy_callback' ) );
		}

                // Add styles to menu pages
                foreach ($hook_suffix as $menu => $hook) {
                    add_action("load-{$hook}", array($this, $menu . '_assets'));
                }
        }

    /**
     * Callback send sms page.
     */
    public function send_sms_callback() {
        $page = new WBS_SMS_TO_SMS_Send();
        $page->render_page();
    }

    /**
	 * Callback outbox page.
	 */
	public function outbox_callback() {
		$page = new Outbox();
		$page->render_page();
	}
        
       
    /**
	 * Callback outbox page.
	 */
	public function documentation_callback() {
            require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/admin/documentation/class-wpsmstobulk-documentation.php';
            
		$page = new WBS_SMS_TO_Documentation();
		$page->render_page();
	}            

	/**
	 * Callback subscribers page.
	 */
	public function privacy_callback() {
		// Privacy class.
		require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/admin/privacy/class-wpsmstobulk-privacy.php';

		$page           = new WBS_SMS_TO_Privacy();
		$page->pagehook = get_current_screen()->id;
		$page->render_page();
	}

	/**
     * Load send SMS page assets
     */
    public function send_sms_assets() {
        wp_enqueue_style('jquery-flatpickr', WBS_WP_SMS_TO_BULK_URL . 'assets/css/flatpickr.min.css', true, WBS_WP_SMS_TO_BULK_VERSION);
        wp_enqueue_script('jquery-flatpickr', WBS_WP_SMS_TO_BULK_URL . 'assets/js/flatpickr.min.js', array('jquery'), WBS_WP_SMS_TO_BULK_VERSION);
        wp_register_script('wpsmstobulk-send-sms', WBS_WP_SMS_TO_BULK_URL . 'assets/js/send-sms.js', true, WBS_WP_SMS_TO_BULK_VERSION);
        wp_enqueue_script('wpsmstobulk-send-sms');
        
        wp_register_script( 'wpsmstobulk-meta-box', WBS_WP_SMS_TO_BULK_URL . 'assets/js/meta-box.js', true, WBS_WP_SMS_TO_BULK_VERSION );
        wp_enqueue_script( 'wpsmstobulk-meta-box');  
    }

	/**
	 * Load privacy page assets
	 */
	public function privacy_assets() {
		$pagehook = get_current_screen()->id;

		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );
                
                wp_register_script( 'wpsmstobulk-privacy', WBS_WP_SMS_TO_BULK_URL . 'assets/js/privacy.js', true, WBS_WP_SMS_TO_BULK_VERSION );
                wp_enqueue_script( 'wpsmstobulk-privacy');
                
		add_meta_box( 'privacy-meta-1', esc_html( get_admin_page_title() ), array( WBS_SMS_TO_Privacy::class, 'privacy_meta_html_gdpr' ), $pagehook, 'side', 'core' );
		add_meta_box( 'privacy-meta-2', __( 'Export User’s Data related to WP - Bulk SMS - by SMS.to', 'wp-sms-to-bulk' ), array( WBS_SMS_TO_Privacy::class, 'privacy_meta_html_export' ), $pagehook, 'normal', 'core' );
		add_meta_box( 'privacy-meta-3', __( 'Erase User’s Data related to WP - Bulk SMS - by SMS.to', 'wp-sms-to-bulk' ), array( WBS_SMS_TO_Privacy::class, 'privacy_meta_html_delete' ), $pagehook, 'normal', 'core' );
                
    
                
	}

	/**
	 * Administrator add Meta Links
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function meta_links( $links, $file ) {
		if ( $file == 'wp-sms-to-bulk/wp-sms-to-bulk.php' ) {
			$rate_url = 'http://wordpress.org/support/view/plugin-reviews/wp-sms-to-bulk?rate=5#postform';
			$links[]  = '<a href="' . $rate_url . '" target="_blank" class="wpsmstobulk-plugin-meta-link" title="' . __( 'Click here to rate and review this plugin on WordPress.org', 'wp-sms-to-bulk' ) . '">' . __( 'Rate this plugin', 'wp-sms-to-bulk' ) . '</a>';

		//	$newsletter_wpsmstobulk_url = WBS_WP_SMS_TO_BULK_SITE . '/newsletter';
		//	$links[]        = '<a href="' . $newsletter_wpsmstobulk_url . '" target="_blank" class="wpsmstobulk-plugin-meta-link" title="' . __( 'Click here to rate and review this plugin on WordPress.org', 'wp-sms-to-bulk' ) . '">' . __( 'Subscribe to our Email Newsletter', 'wp-sms-to-bulk' ) . '</a>';
		}

		return $links;
	}

	/**
	 * Adding new capability in the plugin
	 */
	public function add_cap() {
		// Get administrator role
		$role = get_role( 'administrator' );

		$role->add_cap( 'wpsmstobulk_sendsms' );
		$role->add_cap( 'wpsmstobulk_outbox' );
		$role->add_cap( 'wpsmstobulk_subscribers' );
		$role->add_cap( 'wpsmstobulk_setting' );
	}

	/**
	 * Initial plugin
	 */
	private function init() {
		if ( isset( $_GET['action'] ) ) {
			if ( sanitize_text_field($_GET['action']) == 'wpsmstobulk-hide-newsletter' ) {
				update_option( 'wpsmstobulk_hide_newsletter', true );
			}
		}

		if ( ! get_option( 'wpsmstobulk_hide_newsletter' ) ) {
			//add_action( 'sms_to_bulk_settings_page', array( $this, 'admin_newsletter' ) );
		}

		// Check exists require function
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			include( ABSPATH . "wp-includes/pluggable.php" );
		}

		// Add plugin caps to admin role
		if ( is_admin() and is_super_admin() ) {
			$this->add_cap();
		}
	}

}

new Admin();