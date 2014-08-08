<script>
            jQuery(document).ready(function ($) {

                var featureMeTitle = $("#<?php echo $this->get_field_id('title'); ?>");
                var currentSelection;
                var currentID = '<?php echo $this->id; ?>';

                if(currentID == "feature_me-__i__"){
                    //console.log('Error');
                }
                else{
                    //console.log('No Error');
                }

                $('.feature-me-select').on('click',function(){
                    var widgetID = $('this').parents('.widget').attr('id');
                    console.log('widgetID'+widgetID);
                    var currentID = widgetID.replace(/widget-[0-9]*_/,"");
                    console.log("currentID"+currentID);
                });

                $("#<?php echo $this->get_field_id('feature') ?>").on("change", function () {
                    currentSelection = $("#<?php echo $this->get_field_id('feature') ?> option:selected").text();

                });

                $("#<?php echo $this->get_field_id('defaultTitle'); ?>").on("click", function () {
                    if ($("#<?php echo $this->get_field_id('defaultTitle'); ?>").attr("checked")) {
                        featureMeTitle.attr("value", currentSelection);
                    }
                });

                /*--Toggle Title Options--*/
                //Hide custom title if the "default" button is checked
                if ($(".<?php echo $this->get_field_id('type') ?>").eq(0).attr('checked') == "checked") {
                    $("#<?php echo $this->get_field_id('title') ?>").hide();
                }
                //Show body if the "custom" button is checked
                if ($(".<?php echo $this->get_field_id('type') ?>").eq(1).attr('checked') == "checked") {
                    $("#<?php echo $this->get_field_id('title') ?>").show();
                }
                //Hide body when "excerpt" button is clicked
                $(".<?php echo $this->get_field_id('type') ?>").eq(0).on('click', function () {
                    $("#<?php echo $this->get_field_id('title') ?>").hide();
                });
                //Show body when "custom" button is clicked
                $(".<?php echo $this->get_field_id('type') ?>").eq(1).on('click', function () {
                    $("#<?php echo $this->get_field_id('title') ?>").show();
                });


                /*--Toggle Custom Text Options--*/
                //Hide body if the "excerpt" button is checked
                if ($(".<?php echo $this->get_field_id('copy') ?>").eq(0).attr('checked') == "checked") {
                    $("#<?php echo $this->get_field_id('body') ?>").hide();
                }
                //Show body if the "custom" button is checked
                if ($(".<?php echo $this->get_field_id('copy') ?>").eq(1).attr('checked') == "checked") {
                    $("#<?php echo $this->get_field_id('body') ?>").show();
                }
                //Hide body when "excerpt" button is clicked
                $(".<?php echo $this->get_field_id('copy') ?>").eq(0).on('click', function () {
                    $("#<?php echo $this->get_field_id('body') ?>").hide();
                });
                //Show body when "custom" button is clicked
                $(".<?php echo $this->get_field_id('copy') ?>").eq(1).on('click', function () {
                    $("#<?php echo $this->get_field_id('body') ?>").show();
                });

                //Toggle Display of Options
                var <?php echo preg_replace("-","_",$this->id); ?>_count = 1;
                $(".<?php echo $this->id ?>-show-options").on('click',function(e){

                    if(<?php echo preg_replace("-","_",$this->id); ?>_count % 2 != 0){
                        $(this).siblings('span').html('&#x25bc;');
                        $(this).text('Hide Options');
                    }
                    else{
                        $(this).siblings('span').html('&#x25b6;');
                        $(this).text('Show Options');
                    }

                    $(".<?php echo $this->id ?>-options").slideToggle({complete: function(){<?php echo preg_replace("-","_",$this->id); ?>_count++; console.log(<?php echo preg_replace("-","_",$this->id); ?>_count)}});

                    e.preventDefault();
                    return false;

                });


            });
        </script>