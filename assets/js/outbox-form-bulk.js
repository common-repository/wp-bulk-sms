window.$ = jQuery
var interval;
var noOfCycles = 1;
var currentView;

$(document).ready(function () {

    //the requests are performed every 3 seconds
    interval = setInterval(function () {

        var ajaxurl = $('#ajax-url').val();
        var data = {
            'action': 'wbs_get_updates_from_db_bulk',
            'post_type': 'POST',
            'name': 'Get Updates from DB'
        };

        jQuery.post(ajaxurl, data, function (response) {

            //use this to know if we are in messages or campaigns screen

            if (window.location.href.indexOf("response=message_id") > -1) {
                currentView = "messages";
            } else
            if (window.location.href.indexOf("response=campaign_id") > -1) {
                currentView = "campaigns";
            } else
            if (window.location.href.indexOf("page=wp-sms-to-bulk-outbox") > -1) {
                currentView = "messages";
            }
            response.forEach(updateItems);

            //1 cycle is 24 seconds => 10 cycles is 240 seconds which is equal to 4 minutes
            //so we query db 10 times in 4 minutes, then it stops
            noOfCycles = noOfCycles + 1;
            if (noOfCycles === 10) {
                clearInterval(interval);
            }
        }, 'json');
    }, 24000);

    function updateItems(item, index) {

        var _id = item['_id'];
        var cost = item['cost'];
        var status = item['status'];
        var type = item['type'];
        var failed = item['failed'];
        var sms_parts = item['sms_parts'];
        var pending = item['pending'];
        var sent = item['sent'];

        var tr = $("#outbox-filter td:contains(" + _id + ")").closest("tr").find("td");

        if (currentView === "messages") {
            if (typeof tr[5] != "undefined" && tr[5] != null) {
                if (cost != null) {
                    tr[5].innerHTML = cost;
                }
            }
            if (typeof tr[6] != "undefined" && tr[6] != null) {
                tr[6].innerHTML = '<span class=' + getStatusRelevantClass(status) + '>' + status + '</span>';
            }

        } else if
                (currentView === "campaigns") {
            if (typeof tr[3] != "undefined" && tr[3] != null) {
                tr[3].innerHTML = type;
            }
            if (typeof tr[4] != "undefined" && tr[4] != null) {
                if (cost != null) {
                    tr[4].innerHTML = cost;
                }
            }
            if (typeof tr[6] != "undefined" && tr[6] != null) {
                tr[6].innerHTML = parseInt(sent) + parseInt(failed) + parseInt(pending);
            }

            if (typeof tr[7] != "undefined" && tr[7] != null) {
                tr[7].innerHTML = sms_parts;
            }
            if (typeof tr[8] != "undefined" && tr[8] != null) {
                tr[8].innerHTML = sent;
            }
            if (typeof tr[9] != "undefined" && tr[9] != null) {
                tr[9].innerHTML = failed;
            }
            if (typeof tr[10] != "undefined" && tr[10] != null) {
                tr[10].innerHTML = pending;
            }

            if (typeof tr[12] != "undefined" && tr[12] != null) {
                tr[12].innerHTML = '<span class=' + getStatusRelevantClass(status) + '>' + status + '</span>';
            }
        }
    }
});

/**
 * According to the status we get the relevant class
 * 
 * @author Christodoulou Panikos
 * @email christodoulou.panicos@cytanet.com.cy
 * @return string
 */
function getStatusRelevantClass(status) {

    if ((status === 'SENT') || (status === 'DELIVERED') || (status === 'DONE')) {
        return "sms_to_bulk_status_success";
    }
    if ((status === 'FAILED') ||
            (status === 'REJECTED') ||
            (status === 'UNDELIVERED') ||
            (status === 'EXPIRED') ||
            (status === 'UNSUBSCRIBED')) {
        return "sms_to_bulk_status_fail";
    }
    if ((status === 'ONGOING') ||
            (status === 'SENDING') ||
            (status === 'QUEUED') ||
            (status === 'SCHEDULED')) {
        return "sms_to_bulk_status_processing";

    }
}



