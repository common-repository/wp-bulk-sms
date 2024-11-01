<?php

use WBS_WP_SMS_TO_BULK\WBS_SMS_TO_Gateway;
use WBS_WP_SMS_TO_BULK\WBS_SMS_TO_Option;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */
function wbs_sms_to_bulk_initial_gateway() {
    require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/class-wpsmstobulk-option.php';

    return WBS_SMS_TO_Gateway::initial();
}

add_action('rest_api_init', 'wbs_register_route_bulk');

add_action('wp_ajax_wbs_get_updates_from_db_bulk', 'wbs_get_updates_from_db_bulk');
add_action('wp_ajax_nopriv_wbs_get_updates_from_db_bulk', 'wbs_get_updates_from_db_bulk');

require_once WBS_WP_SMS_TO_BULK_DIR . 'includes/class-wpsmstobulk-option.php';



/**
 * Register Route
 * 
 * @author Christodoulou Panikos
 * @email christodoulou.panicos@cytanet.com.cy
 */
function wbs_register_route_bulk() {
    register_rest_route('wp-sms-to-bulk', 'get_post', array(
        'methods' => 'POST',
        'callback' => 'wbs_update_db_bulk',
        'permission_callback' => '__return_true'
    ));
}


/**
 * Get users with a phone
 * Because the country prefix is saved on wordpress user record once edited
 * we are filtering the users to show us only those that their phone number length 
 * is greater than 5 so the user who has phone  = +357 will not be selected
 * 
 */
function wbs_get_users_with_phone($key_item) {
    $users = get_users(array(
                        'role' => $key_item,
                    ));

    $users = array_filter(
        $users,
        function( $user ) {
            return strlen( get_user_meta( $user->ID, 'mobile_phone', true ) ) > 5;
        }
    );

    return $users;
}

/**
 * Update DB
 * 
 * @author Christodoulou Panikos
 * @email christodoulou.panicos@cytanet.com.cy
 * @param WP_REST_Request
 * @return Response
 */
function wbs_update_db_bulk(WP_REST_Request $request) {

    global $wpdb;
    $table = $wpdb->prefix . 'smstobulk_send';
    //parameters is an array
    $parameters = $request->get_params();

    if ((isset($parameters['status'])) &&
            (isset($parameters['messageId'])) &&
            (isset($parameters['phone'])) &&
            (isset($parameters['trackingId'])) &&
            (isset($parameters['price']))) {

        $status = $parameters['status'];
        $messageId = $parameters['messageId'];
        $trackingId = $parameters['trackingId'];
        $price = $parameters['price'];
        $updated_at = WBS_WP_SMS_TO_BULK_CURRENT_DATE;

        $wpdb->query("UPDATE $table SET status='$status', updated_at='$updated_at', cost='$price', type='sms' WHERE response Like '%$messageId%' and response Like '%message_id%'");
        $wpdb->query("UPDATE $table SET status='$status', 'updated_at='$updated_at'  WHERE response Like '%$trackingId%' and response Like '%campaign_id%'");
    }
}



/**
 * Update DB
 * 
 * @author Christodoulou Panikos
 * @email christodoulou.panicos@cytanet.com.cy
 * @param WP_REST_Request
 * @return Response
 */
function wbs_get_updates_from_db_bulk() {

    global $wpdb;

    //update campaign records in DB
    \WBS_WP_SMS_TO_BULK\Admin\WBS_SMS_TO_Helper::wbs_update_db_bulk();

    //get last 15 mins interval
    $time_interval_update = date('Y-m-d H:i:s', current_time('timestamp') - (60 * 15));
    $time_interval_create = date('Y-m-d H:i:s', current_time('timestamp') - (60 * 60 * 24 * 3));

    $get_latest_updated_data = $wpdb->get_results(
            $wpdb->prepare(
                    "SELECT _id, cost, status, type, failed, sms_parts, pending, sent FROM $wpdb->prefix" . "smstobulk_send WHERE "
                    . "(updated_at > %s or updated_at is null) and date > %d LIMIT 1000",
                    $time_interval_update, $time_interval_create
            ), ARRAY_A
    );

    $response_init = array();
    $response_init['latest_updated_data'] = json_encode($get_latest_updated_data);
    $response = array_map('sanitize_text_field', $response_init);
    echo  wp_kses_post($response['latest_updated_data']);
    exit;
}

/**
 * Retrieve User mobile phone from wp_usermeta 
 * 
 * @param mobile_phone, mobile_phone number
 * @return User
 */
function wbs_get_user_by_mobile_phone_bulk($db_field, $value) {
    global $wpdb;

    $user_id = $wpdb->get_row(
            $wpdb->prepare(
                    "SELECT user_id FROM $wpdb->prefix" . "usermeta WHERE meta_key = %s
				 AND REPLACE(meta_value, ' ', '') = %d LIMIT 1",
                    $db_field, $value
            )
    );

    if ($user_id) {
        $array = json_decode(json_encode(sanitize_text_field($user_id)), true);
        $user = get_user_by('ID', $array ["user_id"]);
        return $user;
    } else {
        return null;
    }
}

