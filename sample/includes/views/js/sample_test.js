function onButton1Click() {
    jQuery.ajax(
        ajax_object.ajax_url, {
            "data": {
                "action": "axis_sample_test_action",
                "param": "foo"
            },
            "success": function(data, textStatus, jqXHR) {
                jQuery('div#div1').append("<br>ajax callback success! " + data + ".</br>");
            }
        }
    );
}

function onButton2Click() {
    jQuery('div#div1').append("<br>button clicked.<br>");
}

function registerEventHandlers() {

    var button1 = jQuery('input#button1');
    var button2 = jQuery('input#button2');

    button1.click(onButton1Click);
    button2.click(onButton2Click);
}

function init() {
    registerEventHandlers();
}

jQuery(document).ready(init);