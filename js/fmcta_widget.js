/**
 * This file gets all Feature Me widgets on page and binds actions to the element ID's.
 */
jQuery(document).ready(function($){

    /*--Script Setup--*/
    //Launch on initial page load
    fmcta_get_ids(); //get id's for each feature me widget
    fm_cta_menu_click(); //launch menu
    fmcta_image_uploader('fm-step-2-options'); //launch image uploader
    fmcta_image_uploader('fm-step-4-options'); //launch image uploader

    //detect ajax calls...launch on widget save event
    jQuery(document).ajaxSuccess(function(evt, request, settings) {
        var widget_id_base = 'feature_me';
        if(settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=' + widget_id_base) != -1) {
            fmcta_get_ids(); //get id's for each feature me widget
            fm_cta_menu_click(); //launch menu
            fmcta_image_uploader('fm-step-2-options'); //launch image uploader
            fmcta_image_uploader('fm-step-4-options'); //launch image uploader
        }
    });

});

function fm_listen(fm_id,hide){
    console.log("fm_listen launched for id: "+fm_id);

    jQuery(function($){

        /*--Cache Variables--*/

        //Step 1 - Choose a Landing Page
        var fmcta_landing_type = $("."+fm_id+"-fmcta_landing_type");
        var fmcta_type_url = $("."+fm_id+"-fmcta_type_url");
        var fmcta_feature = $("."+fm_id+"-fmcta_feature");

        //Step 2 - Select an Image
        var fmcta_use_image = $("."+fm_id+"-fmcta_use_image");
        var fmcta_use_image_label = $("."+fm_id+"-fmcta_use_image_label");
        var fmcta_image_url = $("."+fm_id+"-fmcta_image_url");
        var fmcta_image_uploader_label = $("."+fm_id+"-fmcta_image_uploader");

        //Step 3 - Customize Content
        var fmcta_heading_title_type = $("."+fm_id+"-fmcta_heading_title_type"); //title options
        var fmcta_heading_title_type_label = $("."+fm_id+"-fmcta_heading_title_type_label"); //title options label
        var fmcta_heading_title_content = $("#"+fm_id+"-fmcta_heading_title_content"); //title content
        var fmcta_description_type = $("."+fm_id+"-fmcta_description_type"); //description options
        var fmcta_description_type_label = $("."+fm_id+"-fmcta_description_type_label"); //description options label
        var fmcta_description_type_content = $("."+fm_id+"-fmcta_description_type_content"); //description content

        //Step 4 - Choose a Button
        var fmcta_button_uploader = $("."+fm_id+"-fmcta_button_uploader");

        var defaultTitle= $("#"+fm_id+"-defaultTitle");
        var type = $("."+fm_id+"-type");
        var type_link = $("."+fm_id+"-type_link");


        var linkURL = $("#"+fm_id+"-linkURL");

        var currentSelection;

        /*--Step 1 - Choose a Landing Page--*/

        /*--Toggle Landing Page Options--*/
        //Hide custom title if the "default" is checked
        if (fmcta_landing_type.eq(0).attr('checked') == "checked") {
            fmcta_type_url.hide();
            fmcta_feature.show();
        }
        //Show body if the "custom" button is checked
        if (fmcta_landing_type.eq(1).attr('checked') == "checked") {
            fmcta_type_url.show();
            console.log('initial run through')
            fmcta_feature.hide();
        }
        //Hide custom title when "default" is clicked
        fmcta_landing_type.eq(0).on('click', function () {
            fmcta_type_url.hide();
            fmcta_feature.show();

            //show featured image option in image section if clicked
            fmcta_use_image_label.eq(1).show();

            //show post/page title option in content section if clicked
            fmcta_heading_title_type_label.eq(1).show();

            fmcta_description_type_label.eq(1).show();
        });
        //Show custom title when "custom" is clicked
        fmcta_landing_type.eq(1).on('click', function () {
            fmcta_type_url.show();
            console.log('click');
            fmcta_feature.hide();

            //hide featured image option in image section if clicked
            fmcta_use_image_label.eq(1).hide();

            //hide post/page title option in content section if clicked
            fmcta_heading_title_type_label.eq(1).hide();

            fmcta_description_type_label.eq(1).hide();
        });

        /*--Step 2 - Select an image--*/

        /*--Toggle Image Options--*/

        //Since external landing pages don't have featured images, hide image option for featured post selected.
        if(fmcta_landing_type.eq(1).attr('checked')=='checked'){
            fmcta_use_image.eq(1).hide();
        }
        if(fmcta_landing_type.eq(0).attr('checked')=='checked'){
            fmcta_use_image.eq(1).show();
        }
        if(fmcta_use_image.eq(1).attr('checked')=='checked' || fmcta_use_image.eq(2).attr('checked')=='checked'){
            fmcta_image_uploader_label.hide();
        }
        if(fmcta_use_image.eq(0).attr('checked')=='checked'){
            fmcta_image_uploader_label.show();
        }

        fmcta_use_image.eq(0).on('click',function(){
            fmcta_image_uploader_label.show();
        });
        fmcta_use_image.eq(1).on('click',function(){
            fmcta_image_uploader_label.hide();
        });
        fmcta_use_image.eq(2).on('click',function(){
            fmcta_image_uploader_label.hide();
        });



        fmcta_heading_title_type.on("change", function () {
            currentSelection = $("#"+fm_id+"-feature option:selected").text();
        });

        defaultTitle.on("click", function () {
            if (defaultTitle.attr("checked")) {
                featureMeTitle.attr("value", currentSelection);
            }
        });

        /*--Toggle Heading Title Options--*/
        //Hide custom title if the "default" is checked
        if (fmcta_heading_title_type.eq(0).attr('checked') == "checked") {
            fmcta_heading_title_content.show();
        }
        //Hide custom title if "none" is checked
        if (fmcta_heading_title_type.eq(1).attr('checked') == "checked") {
            fmcta_heading_title_content.hide();
        }
        //Show body if the "custom" button is checked
        if (fmcta_heading_title_type.eq(2).attr('checked') == "checked") {
            fmcta_heading_title_content.hide();
        }

        //Hide custom title when "default" is clicked
        fmcta_heading_title_type.eq(0).on('click', function () {
            fmcta_heading_title_content.show();
        });
        //Hide custom title if "none" is clicked
        fmcta_heading_title_type.eq(1).on('click', function () {
            fmcta_heading_title_content.hide();
        });
        //Show custom title when "custom" is clicked
        fmcta_heading_title_type.eq(2).on('click', function () {
            console.log('third option');
            fmcta_heading_title_content.hide();
        });


        /*--Toggle Custom Body Options--*/
        //Hide body if the "excerpt" button is checked
        if (fmcta_description_type.eq(0).attr('checked') == "checked") {
            fmcta_description_type_content.show();
            console.log('custom description');
        }
        if (fmcta_description_type.eq(1).attr('checked') == "checked") {
            fmcta_description_type_content.hide();
            console.log('excerpt description');
        }
        //Show body if the "custom" button is checked
        if (fmcta_description_type.eq(2).attr('checked') == "checked") {
            fmcta_description_type_content.hide();
            console.log('no description');
        }

        //Hide body when "excerpt" button is clicked
        fmcta_description_type.eq(0).on('click', function () {
            fmcta_description_type_content.show();
        });
        fmcta_description_type.eq(1).on('click', function () {
            fmcta_description_type_content.hide();
        });
        //Show body when "custom" button is clicked
        fmcta_description_type.eq(2).on('click', function () {
            fmcta_description_type_content.hide();
        });



        /*--Toggle Link Title Options--*/
        //TODO: FIX THIS SECTION. Comments are not correct, and neither are the conditional variables.
        //Hide custom title if the "default" is checked
        if (type_link.eq(0).attr('checked') == "checked") {
            //fmcta_heading_title_content.hide();
        }
        //Hide custom title if "none" is checked
        if (type_link.eq(1).attr('checked') == "checked") {
            //fmcta_heading_title_content.hide();
        }
        //Show body if the "custom" button is checked
        if (type_link.eq(2).attr('checked') == "checked") {
            //fmcta_heading_title_content.show();
        }
        //Hide custom title when "default" is clicked
        type_link.eq(0).on('click', function () {
            //fmcta_heading_title_content.hide();
        });
        //Hide custom title if "none" is clicked
        type_link.eq(1).on('click', function () {
            //fmcta_heading_title_content.hide();
        });
        //Show custom title when "custom" is clicked
        type_link.eq(2).on('click', function () {
            //fmcta_heading_title_content.show();
        });


    });
}

/**
 * fm_cta_menu_click
 * This function is used to handle the display of widget settings.
 */
function fm_cta_menu_click(){

    console.log('fm_cta_menu_click function launched '+ Date.now());

    //Menu Clicks
    jQuery(function($){
        $('.fm-option-1').on('click',function(){
            $('.fm-step-1-options').slideToggle();
        });

        $('.fm-option-2').on('click',function(){
            $('.fm-step-2-options').slideToggle();
        });

        $('.fm-option-3').on('click',function(){
            $('.fm-step-3-options').slideToggle();
        });

        $('.fm-option-4').on('click',function(){
            $('.fm-step-4-options').slideToggle();
        });

        $('.fm-option-advanced').on('click',function(){
            $('.fm-step-advanced-options').slideToggle();
        });
    });
}

/**
 * fmcta_image_uploader
 * This function is used to upload custom images.
 */
function fmcta_image_uploader(selector){
    console.log('fmcta_image_uploader launched for: ' + selector);
    var _custom_media = true,
        _orig_send_attachment = wp.media.editor.send.attachment;

    jQuery("."+ selector + ' .button').click(function(e) {
        console.log('click');
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = jQuery(this);
        var id = button.attr('id').replace('upload', 'image_uri');
        console.log('Upload id: '+id);
        _custom_media = true;
        wp.media.editor.send.attachment = function(props, attachment){
            if ( _custom_media ) {
                jQuery("#"+id).val(attachment.url);
                console.log('sent');
            } else {

                return _orig_send_attachment.apply( this, [props, attachment] );
            }
        }

        wp.media.editor.open(button);
        return false;
    });

    jQuery('.add_media').on('click', function(){
        _custom_media = false;
    });
}

function fmcta_get_ids(){
    jQuery(function($){
        //Gather all the Feature Me Widgets on the page
        var fm = $('.widget:contains("feature_me")');

        //Remove the first element found since the ID hasn't been set yet.
        if(fm.length > 1){
            fm.splice(0,1);
        }
        else{
            //do nothing because there are no active feature me widgets
        }

        //Variable to hold the ID's of the elements on the page.
        var fm_ids = new Array(); //set up array to hold the ID's

        for(var i = 0; i < fm.length; i++){
            fm_ids[i] = fm.eq(i).attr('id');
        }
        console.log('fm_ids: '+fm_ids);
        //creates listening events for each Feature Me Widget on the page.
        for(var i = 0; i<fm_ids.length; i++){
            fm_ids[i] = fm_ids[i].replace(/widget-[0-9]*_/,'widget-');
            fm_listen(fm_ids[i]);
        }
    });

}