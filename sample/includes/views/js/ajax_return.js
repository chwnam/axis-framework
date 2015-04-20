jQuery(document).ready(function() {
   jQuery('#ajax-return').click(function() {
       jQuery.ajax(
           ajax_object.ajax_url, {
               'data': {
                   'action': 'axis_sample_ajax_return'
               },
               'success': function(data) {
                    jQuery('div#output').html('').html(data);
               }
           }
       );
    });
});