<table class="form-table">
    <tr valign="top">
        <th scope="row">
            <label for="wps-send-subscribe"><?php _e( 'Send this post to Wordpress subscribers?', 'wp-sms-to-bulk' ); ?>:</label>
        </th>
        <td>
            <select name="wps_send_subscribe" id="wps-send-subscribe">
                <option value="0" selected><?php _e( 'Please select', 'wp-sms-to-bulk' ); ?></option>
                <option value="yes"><?php _e( 'Yes' ); ?></option>
                <option value="no"><?php _e( 'No' ); ?></option>
            </select>
        </td>
    </tr>

    <tr valign="top" id="wpsmstobulk-custom-text">
        <th scope="row">
            <label for="wpsmstobulk-text-template"><?php _e( 'Text template', 'wp-sms-to-bulk' ); ?>:</label>
        </th>
        <td>
            <textarea cols="80" rows="5" id="wpsmstobulk-text-template" name="wpsmstobulk_text_template"><?php
	            echo esc_html(sms_to_bulk_get_option( 'notif_publish_new_post_template' )); ?></textarea>
            <p class="description data">
		        <?php _e( 'Input data:', 'wp-sms-to-bulk' ); ?>
                <br/><?php _e( 'Post title', 'wp-sms-to-bulk' ); ?>: <code>%post_title%</code>
                <br/><?php _e( 'Post content', 'wp-sms-to-bulk' ); ?>: <code>%post_content%</code>
                <br/><?php _e( 'Post url', 'wp-sms-to-bulk' ); ?>: <code>%post_url%</code>
                <br/><?php _e( 'Post date', 'wp-sms-to-bulk' ); ?>: <code>%post_date%</code>
            </p>
        </td>
    </tr>
</table>