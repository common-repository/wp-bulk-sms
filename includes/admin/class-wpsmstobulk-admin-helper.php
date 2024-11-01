<?php

namespace WBS_WP_SMS_TO_BULK\Admin;

/**
 * Class Wbs_sms_to_helper
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mehrshaddarzi, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */
#[\AllowDynamicProperties]
class WBS_SMS_TO_Helper {

    /**
     * Show Admin Wordpress Ui Notice
     *
     * @param string $text where Show Text Notification
     * @param string $model Type Of Model from list : error / warning / success / info
     * @param boolean $close_button Check Show close Button Or false for not
     * @param  boolean $echo Check Echo or return in function
     * @param string $style_extra add extra Css Style To Code
     *
     * @author Mehrshad Darzi
     * @return string Wordpress html Notice code
     */
    public static function notice($text, $model = "info", $close_button = true, $echo = true, $style_extra = 'padding:12px;') {
        $text = '
        <div class="notice notice-' . $model . '' . ( $close_button === true ? " is-dismissible" : "" ) . '">
           <div style="' . $style_extra . '">' . $text . '</div>
        </div>
        ';
        if ($echo) {
            echo wp_kses_normalize_entities($text);
        } else {
            return $text;
        }
    }

    /**
     * Update Campaign data in DB
     * 
     * @author Christodoulou Panikos
     * @email christodoulou.panicos@cytanet.com.cy
     * @no param
     * @updates db
     */
    public static function wbs_update_db_bulk() {

        global $wpdb;
        $table = $wpdb->prefix . 'smstobulk_send';
        //parameters is an array

        $options = \WBS_WP_SMS_TO_BULK\WBS_SMS_TO_Option::getOptions();

        if ((isset($options['gateway_wpsmstobulk_api_key'])) && ($options['gateway_wpsmstobulk_api_key'] != '')) {
            $api_key = $options['gateway_wpsmstobulk_api_key'];

            $url = \WBS_WP_SMS_TO_BULK\Gateway\wpsmstobulk::getWsdl_link() . '/campaigns?limit=100&page=1';


            $headers = array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            );


            $args = array(
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => $headers,
            );

            $response = wp_remote_get($url, $args);

            if (!is_wp_error($response) && 200 == wp_remote_retrieve_response_code($response)) {
                $body = json_decode(wp_remote_retrieve_body($response));
            }

            $data_responses = $body->data;
            if (isset($data_responses)) {
                foreach ($data_responses as $data_response) {
                    $_id = $data_response->_id;
                    $status = $data_response->status;
                    $type = $data_response->type;
                    $cost = $data_response->client_total_cost;
                    $failed_messages = $data_response->failed_messages;
                    $sms_parts = $data_response->sms_count;
                    $pending_messages = $data_response->pending_messages;
                    $sent = ($data_response->sent_messages) - $pending_messages - $failed_messages;

                    $wpdb->query("UPDATE $table SET type='$type', cost='$cost', status='$status', failed='$failed_messages',  sms_parts='$sms_parts', pending='$pending_messages',  sent='$sent' WHERE response Like '%$_id%' and response Like '%campaign_id%'");
                }
            }
            $url = \WBS_WP_SMS_TO_BULK\Gateway\wpsmstobulk::getWsdl_link() . '/messages?limit=100&page=1';
            $response = wp_remote_get($url, $args);

            if (!is_wp_error($response) && 200 == wp_remote_retrieve_response_code($response)) {
                $body = json_decode(wp_remote_retrieve_body($response));
            }
            $data_responses = $body->data;
            if (isset($data_responses)) {
                foreach ($data_responses as $data_response) {
                    $messageId = $data_response->id;
                    $status = $data_response->status;
                    $cost = $data_response->cost;
                    $updated_at = $data_response->updated_at;
                    $wpdb->query("UPDATE $table SET status='$status', updated_at='$updated_at', cost='$cost' WHERE response Like '%$messageId%' and response Like '%message_id%'");
                }
            }
        }
    }

}
