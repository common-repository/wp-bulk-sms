<?php

namespace WBS_WP_SMS_TO_BULK;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class Send SMS Page
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 */ 
#[\AllowDynamicProperties]
class WBS_SMS_TO_SMS_Send {

    public $wpsmstobulk;
    protected $db;
    protected $tb_prefix;
    protected $options;

    public function __construct() {
        global $wpdb, $wpsmstobulk;

        $this->db = $wpdb;
        $this->tb_prefix = $wpdb->prefix;
        $this->sms = $wpsmstobulk;
        $this->options = WBS_SMS_TO_Option::getOptions();
    }

    /**
     * Sending sms admin page
     *
     * @param Not param
     */
    public function render_page() {
        $get_users_mobile_phone = $this->db->get_col("SELECT `meta_value` FROM `{$this->db->prefix}usermeta` WHERE `meta_key` = 'mobile_phone' and `meta_value` != '' and LENGTH(`meta_value`) > 5");        
            $wpsmstobulk_list_of_role = array();
            foreach (wp_roles()->role_names as $key_item => $val_item) {
                $wpsmstobulk_list_of_role[$key_item] = array(
                    "name" => $val_item,
                    "count" => count(wbs_get_users_with_phone($key_item))
                 );
            }
                 
        $gateway_name = 'wpsmstobulk';

        $credit = get_option('wpsmstobulk_gateway_credit');
        
        if (isset($_POST['SendSMS'])) {


            if (isset($_POST['wp_send_to'])) {

                $wp_send_to = sanitize_text_field($_POST['wp_send_to']);
                
                 if ($wp_send_to == "wp_users") { 
                    $this->sms->to = $get_users_mobile_phone;
                    $this->sms->campaign_recipients = 'WordPress users';
                } else if ($wp_send_to == "wp_tellephone") {
                    $this->sms->to = array_map( 'sanitize_text_field', explode(",", $_POST['wp_get_number']) );
                    // remove all spaces from the numbers    
                    foreach (($this->sms->to) as $key => $value) {
                        $this->sms->to[$key] = str_replace(' ', '', $value);
                    }
                } else if ($wp_send_to == "wp_tellephones") {
                    $this->sms->to = array_map( 'sanitize_text_field', explode(",", $_POST['wp_get_numbers']) );       
                    // remove all spaces from the numbers    
                    foreach (($this->sms->to) as $key => $value) {
                        $this->sms->to[$key] = str_replace(' ', '', $value);
                    }
                    if (isset($this->sms->to[1])) {      
                        $this->sms->campaign_recipients = 'Numbers';        
                    }
                } else if ($wp_send_to == "wp_role") {
                    $to = array();
                    add_action('pre_user_query', array(WBS_SMS_TO_SMS_Send::class, 'get_query_user_mobile_phone'));
                    $list = get_users(array(
                        'meta_key' => 'mobile_phone',
                        'meta_value' => '',
                        'meta_compare' => '!=',
                        'role' => sanitize_text_field($_POST['wpsmstobulk_group_role']),
                        'fields' => 'all'
                            ));
                    remove_action('pre_user_query', array(WBS_SMS_TO_SMS_Send::class, 'get_query_user_mobile_phone'));
                    foreach ($list as $user) {
                        $to[] = $user->mobile_phone;
                    }
                    $this->sms->to = $to;
                    $this->sms->campaign_recipients = 'Role-'.sanitize_text_field($_POST['wpsmstobulk_group_role']);          
                }
            }
           
            if ((isset($this->sms->to[0])) && (!empty($this->sms->to[0]))) {
                if ($_POST['wp_get_message']) {
                   if ($_POST['wp_get_sender']) {
                    $this->sms->from = sanitize_text_field($_POST['wp_get_sender']);
                    $this->sms->msg = sanitize_textarea_field($_POST['wp_get_message']);

                    if (isset($_POST['wp_flash']) AND sanitize_text_field($_POST['wp_flash']) == 'true') {
                        $this->sms->isflash = true;
                    } else {
                        $this->sms->isflash = false;
                    }

                    if (isset($_POST['wpsmstobulk_scheduled']) AND isset($_POST['schedule_status']) AND $_POST['schedule_status'] AND $_POST['wpsmstobulk_scheduled']) {
                        $response = Scheduled::add(sanitize_text_field($_POST['wpsmstobulk_scheduled']), $this->sms->from, $this->sms->msg, $this->sms->to);
                    } else {
                        $response = $this->sms->SendSMS();
                    }
                    
                    if (is_wp_error($response)) {
                        $error = $response->get_error_message();
                        if (isset($error['reason'])) {
                        $response = print_r($error['reason'], 1);
                        } else {
                            $response = isset($response->errors["send-sms"][0]->message) ? $response->errors["send-sms"][0]->message : "";
                        }
                        
                            echo "<div class='error'><p>" . sprintf(__('<strong>SMS was not sent! </strong> %s', 'wp-sms-to-bulk'), esc_html($response)) . "</p></div>";
                        } else {
                            if (isset($this->sms->to[1])) {
                                $href = esc_url(WBS_WP_SMS_TO_BULK_ADMIN_URL . '/admin.php?page=wp-sms-to-bulk-outbox&response=campaign_id');
                            } else {
                                $href = esc_url(WBS_WP_SMS_TO_BULK_ADMIN_URL . '/admin.php?page=wp-sms-to-bulk-outbox&response=message_id');
                            }
                            echo "<div class='updated'><p>" . __('<a style="font-weight:bold" href=' . "$href" . '> SMS sending in process. Please check Reports</a> ', 'wp-sms-to-bulk') . "</p></div>";
                            $credit = WBS_SMS_TO_Gateway::credit();
                        }
                    } else {
                        echo "<div class='error'><p>" . __('Please enter SMS sender.', 'wp-sms-to-bulk') . "</p></div>";
                    }
                } else {
                    echo "<div class='error'><p>" . __('Please enter your SMS message.', 'wp-sms-to-bulk') . "</p></div>";
                }
            } else {
                echo "<div class='error'><p>" . __('Please enter valid recipient.', 'wp-sms-to-bulk') . "</p></div>";
            }
        }

        include_once WBS_WP_SMS_TO_BULK_DIR . "includes/admin/send/send-sms.php";
    }

    /**
     * Custom Query for Get All User Mobile in special Role
     */
    public static function get_query_user_mobile_phone($user_query) {
        global $wpdb;

        $user_query->query_fields .= ', m1.meta_value AS mobile_phone ';
        $user_query->query_from .= " JOIN {$wpdb->usermeta} m1 ON (m1.user_id = {$wpdb->users}.ID AND m1.meta_key = 'mobile_phone') ";

        return $user_query;
    }

}

new WBS_SMS_TO_SMS_Send();
