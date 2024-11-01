window.$  = jQuery
    jQuery(document).ready(function () {
        
        $wp_credit_sms_title = jQuery( "#wp-credit-sms-title" );
        $wp_credit_sms_bulk_value = jQuery( "#wp_credit_sms" ).val();
        $wp_credit_sms_title.html($wp_credit_sms_bulk_value);
   
        jQuery(".wpsmstobulk-value").hide();
        jQuery(".wpsmstobulk-users").show();

        jQuery("select#select_sender").change(function () {
            var get_method = "";
            jQuery("select#select_sender option:selected").each(
                function () {
                    get_method += jQuery(this).attr('id');
                }
            );
            if (get_method == 'wp_users') {
                jQuery(".wpsmstobulk-value").hide();
                jQuery(".wpsmstobulk-users").fadeIn();
            } 
           else if (get_method == 'wp_tellephones') {
                jQuery(".wpsmstobulk-value").hide();
                jQuery(".wpsmstobulk-numbers").fadeIn();
                jQuery("#wp_get_numbers").focus();
            } 
           else if (get_method == 'wp_tellephone') {
                jQuery(".wpsmstobulk-value").hide();
                jQuery(".wpsmstobulk-number").fadeIn();
                jQuery("#wp_get_number").focus();
            }             
            else if (get_method == 'wp_role') {
                jQuery(".wpsmstobulk-value").hide();
                jQuery(".wprole-group").fadeIn();
            }
        });


     
        jQuery("#datepicker").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i:00",
            time_24hr: true,
            minuteIncrement: "10",
            minDate: "today",
            disableMobile: true,
            defaultDate: new Date()
        });

        jQuery("#schedule_status").change(function () {
            if (jQuery(this).is(":checked")) {
                jQuery('#schedule_date').show();
            } else {
                jQuery('#schedule_date').hide();
            }
        });
        
        
        jQuery("#wp_get_message").counter({
            count: 'up',
            goal: 'sky',
            msg: 'characters'
            
        });


    });
