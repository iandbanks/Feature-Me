function fm_listen(e,t){jQuery(function($){var t=$("."+e+"-fmcta_element_order");t.sortable({placeholder:"ui-state-highlight",cursor:"move"}),t.disableSelection();var c=$("."+e+"-fmcta_landing_option"),i=$("."+e+"-fmcta_landing_href"),o=$("."+e+"-fmcta_feature_id"),n=$("."+e+"-fmcta_image_option"),a=$("."+e+"-fmcta_image_option_label"),d=$("."+e+"-fmcta_image_href"),h=$("."+e+"-fmcta_image_uploader"),f=$("."+e+"-fmcta_heading_title_option"),l=$("."+e+"-fmcta_heading_title_option_label"),r=$("#"+e+"-fmcta_heading_title_content"),_=$("."+e+"-fmcta_description_option"),u=$("."+e+"-fmcta_description_option_label"),m=$("."+e+"-fmcta_description_content"),s=$("."+e+"-fmcta_button_option"),k=$("."+e+"-fmcta_button_uploader"),p=$("."+e+"-fmcta_button_title_field"),g=$("#"+e+"-defaultTitle"),q=$("."+e+"-type"),w=$("."+e+"-type_link"),v=$("#"+e+"-linkURL"),y;"checked"==c.eq(0).attr("checked")&&(i.hide(),o.show()),"checked"==c.eq(1).attr("checked")&&(i.show(),o.hide()),c.eq(0).on("click",function(){i.hide(),o.show(),a.eq(1).show(),l.eq(1).show(),u.eq(1).show()}),c.eq(1).on("click",function(){i.show(),o.hide(),a.eq(1).hide(),l.eq(1).hide(),u.eq(1).hide()}),"checked"==c.eq(1).attr("checked")&&n.eq(1).hide(),"checked"==c.eq(0).attr("checked")&&n.eq(1).show(),("checked"==n.eq(1).attr("checked")||"checked"==n.eq(2).attr("checked"))&&h.hide(),"checked"==n.eq(0).attr("checked")&&h.show(),n.eq(0).on("click",function(){h.show()}),n.eq(1).on("click",function(){h.hide()}),n.eq(2).on("click",function(){h.hide()}),f.on("change",function(){y=$("#"+e+"-feature option:selected").text()}),g.on("click",function(){g.attr("checked")&&featureMeTitle.attr("value",y)}),"checked"==f.eq(0).attr("checked")&&r.show(),"checked"==f.eq(1).attr("checked")&&r.hide(),"checked"==f.eq(2).attr("checked")&&r.hide(),f.eq(0).on("click",function(){r.show()}),f.eq(1).on("click",function(){r.hide()}),f.eq(2).on("click",function(){r.hide()}),"upload"==s.val()?(k.show(),p.hide()):k.hide(),s.on("change",function(){"upload"==$(this).val()?(k.show(),p.hide()):"none"==$(this).val()?(p.hide(),k.hide()):(p.show(),k.hide())}),"checked"==_.eq(0).attr("checked")&&m.show(),"checked"==_.eq(1).attr("checked")&&m.hide(),"checked"==_.eq(2).attr("checked")&&m.hide(),_.eq(0).on("click",function(){m.show()}),_.eq(1).on("click",function(){m.hide()}),_.eq(2).on("click",function(){m.hide()}),"checked"==w.eq(0).attr("checked"),"checked"==w.eq(1).attr("checked"),"checked"==w.eq(2).attr("checked"),w.eq(0).on("click",function(){}),w.eq(1).on("click",function(){}),w.eq(2).on("click",function(){})})}function fm_cta_menu_click(e){jQuery(function($){$("."+e+"-fm-option-1").on("click",function(){$("."+e+"-fm-step-1-options").slideToggle(),$(this).toggleClass("fm-arrow-open")}),$("."+e+"-fm-option-2").on("click",function(){$("."+e+"-fm-step-2-options").slideToggle()}),$("."+e+"-fm-option-3").on("click",function(){$("."+e+"-fm-step-3-options").slideToggle()}),$("."+e+"-fm-option-4").on("click",function(){$("."+e+"-fm-step-4-options").slideToggle()}),$("."+e+"-fm-option-5").on("click",function(){$("."+e+"-fm-step-5-options").slideToggle()})})}function fmcta_image_uploader(e){var t=!0,c=wp.media.editor.send.attachment;jQuery("."+e+" .button").click(function(e){var i=wp.media.editor.send.attachment,o=jQuery(this),n=o.attr("id").replace("upload","image_href");return t=!0,wp.media.editor.send.attachment=function(e,i){return t?void jQuery("#"+n).val(i.url):c.apply(this,[e,i])},wp.media.editor.open(o),!1}),jQuery(".add_media").on("click",function(){t=!1})}function fmcta_get_ids(){jQuery(function($){var e=$('.widget:contains("feature_me")');e.length>1&&e.splice(0,1);for(var t=new Array,c=0;c<e.length;c++)t[c]=e.eq(c).attr("id");for(var c=0;c<t.length;c++)t[c]=t[c].replace(/widget-[0-9]*_/,"widget-"),fm_listen(t[c]),fm_cta_menu_click(t[c])})}jQuery(document).ready(function($){fmcta_get_ids(),fm_cta_menu_click(),fmcta_image_uploader("fm-step-2-options"),fmcta_image_uploader("fm-step-4-options"),jQuery(document).ajaxSuccess(function(e,t,c){var i="feature_me";-1!=c.data.search("action=save-widget")&&-1!=c.data.search("id_base="+i)&&(fmcta_get_ids(),fm_cta_menu_click(),fmcta_image_uploader("fm-step-2-options"),fmcta_image_uploader("fm-step-4-options"))})});