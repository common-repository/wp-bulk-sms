<div class="wrap">
    <h2><?php _e( 'Send SMS', 'wp-sms-to-bulk' ); ?></h2>
    <div class="postbox-container" style="padding-top: 20px;">
        <div class="meta-box-sortables">
            <div class="postbox">
                <h2 class="hndle" style="cursor: default;padding: 0 10px 10px 10px;font-size: 13px;">
                    <span><?php _e( 'Send SMS form', 'wp-sms-to-bulk' );  
                    $options = \WBS_WP_SMS_TO_BULK\WBS_SMS_TO_Option::getOptions();
                        if ((!isset($options['gateway_wpsmstobulk_api_key'])) || ($options['gateway_wpsmstobulk_api_key'] == '')) {
                            $href = WBS_WP_SMS_TO_BULK_ADMIN_URL . '/admin.php?page=wp-sms-to-bulk-settings&tab=gateway';
                            _e( ' : Please configure your API KEY to send SMS <a href='."$href".'> Click here to configure</a> ', 'wp-sms-to-bulk' );
                        } else {
                            $api_key = $options['gateway_wpsmstobulk_api_key'];
                            $response = wp_remote_get(\WBS_WP_SMS_TO_BULK\Gateway\wpsmstobulk::getTariff() . '/api/balance?api_key=' . $api_key);
                            $body = json_decode(wp_remote_retrieve_body($response));

                            if ((404 == wp_remote_retrieve_response_code($response)) && (!is_wp_error($response))) {
                                _e(' : API unable to communicate with our SMS.to servers', 'wp-sms-to-bulk');
                            } else
                            if ((200 == wp_remote_retrieve_response_code($response)) && (!is_wp_error($response))) {
                                if ($body->balance < 0.05) {
                                    _e(' : Insufficident funds to send SMS', 'wp-sms-to-bulk');
                                }
                            }
                        }
                        ?>
                    </span></h2>          


                <div class="inside">
                    <form method="post" action="">
                        <?php wp_nonce_field('update-options'); ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wp_get_sender"><?php _e( 'Send from', 'wp-sms-to-bulk' ); ?>:</label>
                                </th>
                                <td>
                                    <input type="text" name="wp_get_sender" id="wp_get_sender" value="<?php echo esc_html($this->sms->from); ?>" maxlength="11"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="select_sender"><?php _e( 'Send to', 'wp-sms-to-bulk' ); ?>:</label>
                                </th>
                                <td>
                                    <select name="wp_send_to" id="select_sender">

                                        <option value="wp_users" id="wp_users"><?php _e( 'WordPress Users', 'wp-sms-to-bulk' ); ?></option>
                                        
                                        <option value="wp_role" id="wp_role"<?php $mobile_phone_field = \WBS_WP_SMS_TO_BULK\WBS_SMS_TO_Option::getOption( 'add_mobile_phone_field' );
                                                                                ?>><?php _e( 'Role', 'wp-sms-to-bulk' ); ?></option>
                                        <option value="wp_tellephone" id="wp_tellephone"><?php _e( 'Single Number', 'wp-sms-to-bulk' ); ?></option>
                                        <option value="wp_tellephones" id="wp_tellephones"><?php _e( 'Paste Number(s)', 'wp-sms-to-bulk' ); ?></option>
                                    </select>
                                        <select name="wpsmstobulk_group_role" class="wpsmstobulk-value wprole-group">
											<?php
											foreach ( $wpsmstobulk_list_of_role as $key_item => $val_item ):
												?>
                                                <option value="<?php echo esc_html($key_item); ?>"<?php if ( $val_item['count'] < 1 ) {
													echo " disabled";
												} ?>><?php _e( $val_item['name'], 'wp-sms-to-bulk' ); ?>
                                                    (<?php echo sprintf( __( '<b>%s</b> Users have a mobile phone number.', 'wp-sms-to-bulk' ), esc_html($val_item['count']) ); ?>
                                                    )
                                                </option>
						<?php endforeach; ?>
                                        </select>
                                    <span class="wpsmstobulk-value wpsmstobulk-users">
					<span><?php echo sprintf( __( '<b>%s</b> Users have a mobile phone number.', 'wp-sms-to-bulk' ), esc_html(count( $get_users_mobile_phone ) )); ?></span>
				    </span>
                                    <span class="wpsmstobulk-value wpsmstobulk-number">
                                        <div class="clearfix"></div>
                                        <?php
                                        if ( sms_to_bulk_get_option( 'international_mobile_phone' ) ) {
                                                $sms_to_bulk_input_mobile_phone = " wp-sms-to-bulk-input-mobile_phone";
                                        } else {
                                                $sms_to_bulk_input_mobile_phone = "";
                                        }
                                        ?>                                        
                                        <br>
                                        <input type="text" class="regular-text<?php echo esc_html($sms_to_bulk_input_mobile_phone) ?>" name="wp_get_number" id="wp_get_number"/>
                                        <div class="clearfix"></div>
                                        <span style="font-size: 14px"><?php echo sprintf( __( 'Enter mobile phone', 'wp-sms-to-bulk' ), esc_html($this->sms->validateNumber) ); ?></span>
                                    </span>                                    
                                    <span class="wpsmstobulk-value wpsmstobulk-numbers">
                                        <div class="clearfix"></div>
                                        <textarea cols="80" rows="5" style="direction:ltr;margin-top 5px;" id="wp_get_numbers" name="wp_get_numbers"></textarea>
                                        <div class="clearfix"></div>
                                        <span style="font-size: 14px"><?php echo sprintf( __( 'For example: <code>%s</code>', 'wp-sms-to-bulk' ), esc_html($this->sms->validateNumber )); ?></span>
                                    </span>
                                </td>
                            </tr>

				<?php if ( ! $this->sms->bulk_send ) : ?>
                                <tr>
                                    <td></td>
                                    <td><?php _e( 'This gateway does not support sending bulk message and used first number to sending sms.', 'wp-sms-to-bulk' ); ?></td>
                                </tr>
							<?php endif; ?>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="wp_get_message"><?php _e( 'Message', 'wp-sms-to-bulk' ); ?>:</label>
                                </th>
                                <td>
                                    <textarea dir="auto" maxlength = "480" cols="80" rows="5" name="wp_get_message" id="wp_get_message"></textarea><br/>
                                    <p class="number">
					<?php  
                                            $credit = get_option('wpsmstobulk_gateway_credit');
                                            if ($credit AND isset($options['account_credit_in_sendsms']) AND !is_object($credit)) {
                                                if ($options['account_credit_in_sendsms'] == '1') {
                                                    echo __('Your account credit', 'wp-sms-to-bulk') . ': ' . esc_html($credit); 
                                                }
                                            }
                                            if ($credit AND !is_object($credit)) {
                                                    if (get_option('wpsmstobulk_gateway_credit')) {
                                                        update_option( 'wpsmstobulk_gateway_credit', $credit );
                                                    }
                                            }                                            
                                        ?>
                                    </p>
                            <input type="hidden" 
                                   value= "<?php $credit = get_option('wpsmstobulk_gateway_credit'); echo esc_html($credit); ?> " 
                                   id="wp_credit_sms">  </input> 
                                </td>
                            </tr>

				<?php if ( $this->sms->flash == "enable" ) { ?>
                                <tr>
                                    <td><?php _e( 'Send a Flash', 'wp-sms-to-bulk' ); ?>:</td>
                                    <td>
                                        <input type="radio" id="flash_yes" name="wp_flash" value="true"/>
                                        <label for="flash_yes"><?php _e( 'Yes', 'wp-sms-to-bulk' ); ?></label>
                                        <input type="radio" id="flash_no" name="wp_flash" value="false" checked="checked"/>
                                        <label for="flash_no"><?php _e( 'No', 'wp-sms-to-bulk' ); ?></label>
                                        <br/>
                                        <p class="description"><?php _e( 'Flash sends messages which are opened without being asked if Provider/Country/Network allow', 'wp-sms-to-bulk' ); ?></p>
                                    </td>
                                </tr>
							<?php } ?>
                            <tr>
                                <td>
                                    <p class="submit" style="padding: 0;">
                                        <input type="submit" class="button-primary" name="SendSMS" value="<?php _e( 'Send SMS', 'wp-sms-to-bulk' ); ?>"/>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>