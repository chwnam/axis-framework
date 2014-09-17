function onButton1Click() {

    var div1 = jQuery('div#div1');

    div1.append("button clicked.<br>");
}

function registerEventHandlers() {

    var button1 = jQuery('input#button1');

    button1.click(onButton1Click);
}

function init() {
    registerEventHandlers();
}

jQuery(document).ready(init);