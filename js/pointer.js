jQuery(document).ready( function($) {

    console.log('Pointer Ready');
    //Check if Feature Me has any widgets thus far
    var fm_length=$('div[id$="_feature_me-"]').length;
    console.log('fm_length = ' + fm_length);
    if(fm_length >= 0){
        fm_open_pointer(0);
    }

    function fm_open_pointer(i) {
        console.log('Pointer');
        pointer = fmcta_pointer.pointers[i];
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