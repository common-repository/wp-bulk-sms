<?php
if ( sms_to_bulk_get_option( 'international_mobile_phone' ) ) {
	$sms_to_bulk_input_mobile_phone = " wp-sms-to-bulk-input-mobile_phone";
} else {
	$sms_to_bulk_input_mobile_phone = "";
}
?>
<table class="form-table">
    <tr>
        <th><label for="mobile_phone"><?php _e( 'Mobile Number', 'wp-sms-to-bulk' ); ?></label></th>
        <td>
            <input type="text" class="regular-text<?php echo esc_html($sms_to_bulk_input_mobile_phone) ?>" name="mobile_phone" value="" id="mobile_phone"/>
          
        </td>
    </tr>
</table>