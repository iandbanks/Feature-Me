jQuery(document).ready( function($) {

    //Check if Feature Me has any widgets thus far
    var fm_length=$('div[id$="_feature_me-"]').length;
    if(fm_length > 1){
        fm_open_pointer(0);
    }

    function fm_open_pointer(i) {
        pointer = fmPointer.pointers[i];
        options = $.extend( pointer.options, {
            close: function() {
                $.post( ajaxurl, {
                    pointer: pointer.pointer_id,
                    action: 'dismiss-wp-pointer'
                });
            }
        });

        $(pointer.target).pointer( options ).pointer('open');
    }
});