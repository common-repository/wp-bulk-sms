window.$ = jQuery
jQuery(document).ready(function () {
    if (jQuery('#wps-send-subscribe').val() == 'yes') {
        jQuery('#wpsmstobulk-select-subscriber-group').show();
        jQuery('#wpsmstobulk-custom-text').show();
    }

    jQuery("#wps-send-subscribe").change(function () {
        if (this.value == 'yes') {
            jQuery('#wpsmstobulk-select-subscriber-group').show();
            jQuery('#wpsmstobulk-custom-text').show();
        } else {
            jQuery('#wpsmstobulk-select-subscriber-group').hide();
            jQuery('#wpsmstobulk-custom-text').hide();
        }

    });
});