<div class="wrap">
    <h2><?php _e( 'Reports', 'wp-sms-to-bulk' ); ?></h2>
    <?php $list_table->views(); ?>
    <form id="outbox-filter" method="get">
        <input type="hidden" name="page" value="<?php echo esc_html( $_REQUEST['page'] ); ?>"/>
        <input type="hidden" name="response" value="<?php echo esc_html($_REQUEST['response']); ?>"/>
	<?php $list_table->search_box( __( 'Search', 'wp-sms-to-bulk' ), 'search_id' ); ?>
        </br> </br>
        <table width="100%">
          <tr>
            <td><p align="left"><a target="_blank" href="https://support.sms.to/support/solutions/articles/43000513531-how-to-check-sms-logs-status">Click here for information on how messages are charged</a></p></td>
            <td><p align="right"><a target="_blank" href="https://sms.to/contact-us">Contact Support</a></p></td>
          </tr>
        </table> 
	<?php $list_table->display(); ?>
        <input type="hidden" id="ajax-url" value="<?php echo admin_url('admin-ajax.php'); ?>">
    </form>
</div>