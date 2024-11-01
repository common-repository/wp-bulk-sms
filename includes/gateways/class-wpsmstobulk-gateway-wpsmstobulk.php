<?php

namespace WBS_WP_SMS_TO_BULK\Gateway;

/**
 * Class Wpsmstobulk
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */

class wpsmstobulk extends \WBS_WP_SMS_TO_BULK\WBS_SMS_TO_Gateway {

    public $wsdl_link;
    public $tariff;
    public $unitrial = true;
    public $unit;
    public $flash = "enable";
    public $isflash = false;
    public $callback_url ; 

    public function __construct() {
        parent::__construct();
        $this->validateNumber = "+35799000001, +35799000002, +35799000003";
        $this->api_key = false;
        $this->bulk_send = true;
        $this->tariff = $this->getTariff();
        $this->wsdl_link = $this->getWsdl_link();
    }

    /**
     * Returns the balance of sms.to account
     * 
     */      
    public static function getTariff() {
        $tariff = "https://auth.sms.to";
        return $tariff;
    }    
    
    /**
     * Returns the wsdl_link
     * 
     */    
    public static function getWsdl_link() {

        $wsdl_link = "https://api.sms.to";
        return $wsdl_link;
    }        
       
    public function SendSMS() {        
    
        /**
         * Modify sender number
         *
         * @since 3.4
         *
         * @param string $this ->from sender number.
         */
        //$this->from = apply_filters('sms_to_bulk_from', $this->from);
        $this->from = apply_filters('sms_to_bulk_from', substr($this->from, 0, 11));
        /**
         * Modify Receiver number
         *
         * @since 3.4
         *
         * @param array $this ->to receiver number
         */
        $this->to = apply_filters('sms_to_bulk_to', $this->to);     
        
        /**
         * Modify campaign recipients
         *
         * @since 3.4
         *
         * @param array $this ->campaign recipients
         */
        $this->campaign_recipients = apply_filters('sms_to_bulk_campaign_recipients', $this->campaign_recipients);      
        
        /**
         * Modify _id
         *
         * @since 3.4
         *
         * @param array $this ->_id
         */
        $this->_id = apply_filters('sms_to_bulk__id', $this->_id);             
        /**
         * Modify text message
         *
         * @since 3.4
         *
         * @param string $this ->msg text message.
         */
        $api_key = $this->api_key;
        // Get the credit.
        $credit = $this->GetCredit();   

        $no_of_characters = $this->CountNumberOfCharacters();    
        
        if ($no_of_characters>480) {
            return new \WP_Error('account-credit', __('You have exceeded the max limit of 480 characters', 'wp-sms-to-bulk'));
        } 
        
        // Check gateway credit
        if (is_wp_error($credit)) {
            // Log the result
             if (!isset($this->to[1])) {
            $this->log_message($this->_id, $this->from, $this->msg, $this->to, $credit->get_error_message(), 'FAILED');
            } else {
                $this->log_campaign($this->_id, $this->from, $this->msg, $this->campaign_recipients, $credit->get_error_message(), 'FAILED');
            }

            return $credit;
        }

        $this->msg = apply_filters('sms_to_bulk_msg', $this->msg);

        $bodyContent = array(
            'sender_id' => $this->from,
            'to' => $this->to,
            'message' => $this->msg,
        );
        if ((!isset($this->to[1])) && (isset($this->to[0]))) {
            $bodyContent['to'] = $this->to[0];
        } 
        
        if  (isset($this->options['gateway_wpsmstobulk_callback_url']))  {
            $callback_url = apply_filters('sms_to_bulk_callback', $this->options['gateway_wpsmstobulk_callback_url']); 
            $bodyContent['callback_url'] = $callback_url.'/wp-json/wp-sms-to-bulk/get_post';
        }
        
        if (empty($api_key)) {
            return [
                'error' => true,
                'reason' => 'Invalid Credentials',
                'data' => null,
                'status' => 'FAILED'
            ];
        }

        if ($this->isflash) {
            $_sms = '/fsms';
        } else {
            $_sms = '/sms';
        }
        

        if ($bodyContent) {
            $body = json_encode($bodyContent);
        }



        $url = $this->getWsdl_link() . $_sms . '/send';

        $headers = array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
            'X-Smsto-Integration-Name'  => 'wpbulksms'
        );

        $args = array(
            'body' => $body,
            'timeout' => '15',
            'redirection' => '10',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers,
        );


        $response = wp_remote_post($url, $args);

        $_id = '';

        if (!is_wp_error($response) && 200 == wp_remote_retrieve_response_code($response)) {
            $body = json_decode(wp_remote_retrieve_body($response));

            if (isset($body->message_id)) {
                $_id = $body->message_id;
            } else if (isset($body->campaign_id)) {
                $_id = $body->campaign_id;
            }

            if (isset($body->success)) {
                if ($body->success == "true") {
                    // Log the result

                    if (!isset($this->to[1])) {
                        $this->log_message($_id, $this->from, $this->msg, $this->to, $response, 'ONGOING');
                    } else {
                        $this->log_campaign($_id, $this->from, $this->msg, $this->campaign_recipients, $response, 'ONGOING');
                    }

                    /**
                     * Run hook after send sms.
                     *
                     * @since 2.4
                     *
                     * @param string $response result output.
                     */
                    do_action('sms_to_bulk_send', $response);

                    return $response;
                }
            }
        } else {
            $error = $response->get_error_message();
            if (isset($error)) {
                $response = print_r($error, 1);
                $_id = $response;
            }
            $response = [
                'error' => true,
                'reason' => $response,
                'data' => $bodyContent,
                'status' => 'FAILED'
            ];
            do_action('sms_to_bulk_send', $response);



            if (!isset($this->to[1])) {
                $this->log_message($_id, $this->from, $this->msg, $this->to, $response, 'FAILED');
            } else {
                $this->log_campaign($_id, $this->from, $this->msg, $this->campaign_recipients, $response, 'FAILED');
            }

            return new \WP_Error('send-sms', $response);
        }
    }

    public function GetCredit() {
        // Check api
        if (!$this->api_key) {
            return new \WP_Error('account-credit', __('API not set', 'wp-sms-to-bulk'));
        }
        if (isset($this->to[1])){
            $result = 'campaign';
        } else
        {
            $result = 'message';
        }
        
        $response = wp_remote_get(\WBS_WP_SMS_TO_BULK\Gateway\wpsmstobulk::getTariff() . '/api/balance?api_key=' . $this->api_key);
        if (!is_wp_error($response) && 200 == wp_remote_retrieve_response_code($response)) {
            $body = json_decode(wp_remote_retrieve_body($response));
            return round($body->balance, 2) . ' '. $body->currency;
        } else {
            return new \WP_Error('account-credit', 'Unable to send your '.$result);
        }
    }
  
    /**
     * Count no of characters
     * 
     * @author Christodoulou Panikos
     * @email christodoulou.panicos@cytanet.com.cy
     * return integer
     */    
    
    public function CountNumberOfCharacters() {
        $numberOfCharacters = strlen($this->msg);
        return $numberOfCharacters;
    }

}
