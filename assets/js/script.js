jQuery(document).ready(function ($) {
    // Check the GDPR enabled.
    if ($('#wpsmstobulk-gdpr-confirmation').length) {
        if ($('#wpsmstobulk-gdpr-confirmation').attr('checked')) {
            $("#wpsmstobulk-submit").removeAttr('disabled');
        } else {
            $("#wpsmstobulk-submit").attr('disabled', 'disabled');
        }
        $("#wpsmstobulk-gdpr-confirmation").click(function () {
            if (this.checked) {
                $("#wpsmstobulk-submit").removeAttr('disabled');
            } else {
                $("#wpsmstobulk-submit").attr('disabled', 'disabled');
            }
        });
    }

    $("#wpsmstobulk-subscribe #wpsmstobulk-submit").click(function () {
        $("#wpsmstobulk-result").hide();

        var verify = $("#newsletter-form-verify").val();

        subscriber = Array();
        subscriber['name'] = $("#wpsmstobulk-name").val();
        subscriber['mobile_phone'] = $("#wpsmstobulk-mobile_phone").val();
        subscriber['group_id'] = $("#wpsmstobulk-groups").val();
        subscriber['type'] = $('input[name=subscribe_type]:checked').val();

        $("#wpsmstobulk-subscribe").ajaxStart(function () {
            $("#wpsmstobulk-submit").attr('disabled', 'disabled');
            $("#wpsmstobulk-submit").text(wpsmstobulk_ajax_object.loading_text);
        });

        $("#wpsmstobulk-subscribe").ajaxComplete(function () {
            $("#wpsmstobulk-submit").removeAttr('disabled');
            $("#wpsmstobulk-submit").text(wpsmstobulk_ajax_object.subscribe_text);
        });
        if (subscriber['type'] === 'subscribe') {
            var method = 'POST';
        } else {
            var method = 'DELETE';
        }
        var data_obj = Object.assign({}, subscriber);
        var ajax = $.ajax({
            type: method,
            url: wpsmstobulk_ajax_object.ajaxurl,
            data: data_obj
        });
        ajax.fail(function (data) {
            var response = $.parseJSON(data.responseText);
            var message = null;

            if (typeof (response.error) != "undefined" && response.error !== null) {
                message = response.error.message;
            } else {
                message = wpsmstobulk_ajax_object.unknown_error;
            }

            $("#wpsmstobulk-result").fadeIn();
            $("#wpsmstobulk-result").html('<span class="wpsmstobulk-message-error">' + message + '</div>');
        });
        ajax.done(function (data) {
            var response = data;
            var message = response.message;

            $("#wpsmstobulk-result").fadeIn();
            $("#wpsmstobulk-step-1").hide();
            $("#wpsmstobulk-result").html('<span class="wpsmstobulk-message-success">' + message + '</div>');
            if (subscriber['type'] === 'subscribe' && verify === '1') {
                $("#wpsmstobulk-step-2").show();
            }
        });
    });

    $("#wpsmstobulk-subscribe #activation").on('click', function () {
        $("#wpsmstobulk-result").hide();
        subscriber['activation'] = $("#wpsmstobulk-ativation-code").val();

        $("#wpsmstobulk-subscribe").ajaxStart(function () {
            $("#activation").attr('disabled', 'disabled');
            $("#activation").text(wpsmstobulk_ajax_object.loading_text);
        });

        $("#wpsmstobulk-subscribe").ajaxComplete(function () {
            $("#activation").removeAttr('disabled');
            $("#activation").text(wpsmstobulk_ajax_object.activation_text);
        });

        var data_obj = Object.assign({}, subscriber);
        var ajax = $.ajax({
            type: 'PUT',
            url: wpsmstobulk_ajax_object.ajaxurl,
            data: data_obj
        });
        ajax.fail(function (data) {
            var response = $.parseJSON(data.responseText);
            var message = null;

            if (typeof (response.error) != "undefined" && response.error !== null) {
                message = response.error.message;
            } else {
                message = wpsmstobulk_ajax_object.unknown_error;
            }

            $("#wpsmstobulk-result").fadeIn();
            $("#wpsmstobulk-result").html('<span class="wpsmstobulk-message-error">' + message + '</div>');
        });
        ajax.done(function (data) {
            var response = data;
            var message = response.message;

            $("#wpsmstobulk-result").fadeIn();
            $("#wpsmstobulk-step-2").hide();
            $("#wpsmstobulk-result").html('<span class="wpsmstobulk-message-success">' + message + '</div>');
        });
    });
});