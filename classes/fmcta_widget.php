<?php

/********************************************************************************
 *  Copyright 2012-2015 Ian Banks
 *******************************************************************************
 *
 *      Table of Contents
 *
 *      0.0 - fmcta_widget
 *      1.0 - fmcta_widget
 *      1.1 - convert_variables
 *      1.2 - widget
 *      1.3 - form
 *      1.3 - generateCSS
 *
 *
 ********************************************************************************/
class fmcta_widget extends WP_Widget
{

    /**
     * 1.0 - fmcta_widget
     * Assigns widget classname and description
     */
    public function fmcta_widget()
    {
        $widget_options = array(

            'classname' => 'feature-me',
            'description' => 'A powerful widget that allows you to easily create a call to action on your website.',
        );

        parent::WP_WIDGET('feature_me', 'FM: Call to Action Widget', $widget_options);
    }

    /**
     * 1.1 - convert_variables
     * Converts variables from old versions of Feature Me to the current version.
     */
    public function convert_variables()
    {

        //Convert CTA Title value. From 1.3
        if (isset($instance['title'])) {
            $instance['fmcta_heading_title_content'] = $instance['title'];
        }

        //Convert Type of Title value (ie. a custom for custom title, or default to use post title). From 1.3
        if (isset($instance['type'])) {
            $instance['fmcta_heading_title_option'] = $instance['type'];
        }

        //Convert the type of link. Whether to show it, use the learn more default, or hide it. From 1.3
        if (isset($instance['type_link'])) {
            //@todo convert this variable
        }

        if (isset($instance['copy'])) {
            $instance['fmcta_description_option'] = $instance['copy'];
        }

        /**
         * Convert the CTA description text. From 1.3
         */
        if (isset($instance['body'])) {
            $instance['fmcta_description_content'] = $instance['body'];
            //unset( $instance['body'] );
        }

        if (isset($instance['use_image'])) {
            if ($instance['use_image'] == "t") {
                $instance['fmcta_image_option'] = "feature";
            } else {
                $instance['fmcta_image_option'] = "none";
            }
        }

        if (isset($instance['feature'])) {
            $instance['fmcta_feature_id'] = $instance['feature'];
            //unset( $instance['feature'] );
        }

        if (isset($instance['class'])) {
            $instance['fmcta_class'] = $instance['class'];
            //unset( $instance['class'] );
        }

        if (isset($instance['linkText'])) {
            $instance['fmcta_button_text'] = $instance['linkText'];
            //unset( $instance['class'] );
        }

        if (isset($instance['type_url'])) {
            if ($instance['type_url'] == "custom") {

            } else {
                $instance['fmcta_landing_option'] = 'default';
            }

            //unset( $instance['type_url'] );
        }

        if (isset($instance['linkURL'])) {
            //Conversion here
            //unset( $instance['linkURL'] );
        }

        if (isset($instance['fmcta_heading_link_option'])) {
            //Conversion here
            //unset( $instance['fmcta_heading_link_option'] );
        }
    }

    /**
     * 1.2 - widget
     * Outputs content to display on the website.
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);


        // Convert previous $instance data to new $instances data for form.
        $this->convert_variables();

        wp_enqueue_style("featuremecss", plugins_url("feature-me") . "/featureme.css");
        wp_add_inline_style('featuremecss', get_option('fm-settings-css'));


        ?>
        <article class="feature-me <?php if (isset($instance['class'])) {
            echo $instance['class'];
        } ?>">


            <?php echo $before_widget;
            //print_r($instance);

            // Render the Widget.
            if (isset($instance['fmcta_element_order'])) {
                foreach ($instance['fmcta_element_order'] as $element) {
                    switch ($element) {
                        case "title":
                            echo $this->fmcta_render_title($instance, $before_title, $after_title);
                            break;
                        case "image":
                            echo $this->fmcta_render_image($instance);
                            break;
                        case "description":
                            $this->fmcta_render_description($instance);
                            break;
                        case "button":
                            echo $this->fmcta_render_button($instance);
                            break;
                        default:
                            echo $element . "<br/>";
                            break;
                    }
                }
            }

            // @todo Save title in fmcta_heading_title_content so we don't have to run a query each time.

            ?>
            <?php echo $after_widget; ?>
        </article>

    <?php
    } //end widget

    /******************************************************************************
     * 1.3 - form
     * Form to display in the Widget Admin
     *
     * @param array $instance
     *
     * @return string|void
     ******************************************************************************/
    public function form($instance)
    {

        // Convert previous $instance data to new $instances data for form.
        $this->convert_variables();

        // Access the global post variable for later use in queries
        global $post;


        //echo $instance variable for easier development
        //echo '<pre>' . print_r( $instance, true ) . '</pre>';

        //echo $this->generateCSS(); //generate CSS to page

        /**
         * @todo enqueue css
         */
        ?>
        <div class="fm_id"> <?php echo $this->id; ?> </div>
        <div class="fm_widget" id="<?php echo $this->id; ?>">


            <?php
            /**********Step 1**********/
            ?>
            <div class="fm-step-1">
                <h4 class="title fm-option-title fm-arrow <?php echo $this->get_field_id('fm-option-1') ?>">
                    Step 1: Choose a Landing Page</h4>

                <div class="<?php echo $this->get_field_id('fm-step-1-options'); ?> fm-step-1-options">

                    <p>
                        <label for="<?php echo $this->get_field_id('fmcta_landing_option') ?>"><strong
                                class="description">
                                What kind of landing page do you want to link to?</strong></label><br/>
                        <select name="<?php echo $this->get_field_name('fmcta_landing_option') ?>"
                                class="<?php echo $this->get_field_id('fmcta_landing_option') ?>" style="width: 100%;">
                            <option <?php
                            if (isset ($instance['fmcta_landing_option'])) {
                                if ($instance['fmcta_landing_option'] == "default") {
                                    echo 'selected="selected"';
                                }
                            } else {
                                echo 'checked="checked"';
                            } ?> value="default">Page/Post on this Website
                            </option>
                            <option <?php if (isset ($instance['fmcta_landing_option'])) {
                                if ($instance['fmcta_landing_option'] == "external") {
                                    echo 'selected="selected"';
                                }
                            } ?> value="external">External Website
                            </option>
                        </select>
                    </p>

                    <div class="<?php echo $this->get_field_id('fmcta_landing_href'); ?>">
                        <p>
                            <label for="<?php echo $this->get_field_id('fmcta_landing_href'); ?>"><strong
                                    class="description">Enter an external URL</strong><br/></label>
                            <select name="<?php echo $this->get_field_name('fmcta_landing_href_protocol'); ?>"
                                    style="width:23%; display: inline-block;">
                                <option <?php if (isset($instance['fmcta_landing_href_protocol'])) {
                                    if ($instance['fmcta_landing_href_protocol'] == "http://") {
                                        echo 'selected="selected"';
                                    }
                                } else {
                                    echo 'selected="selected"';
                                } ?> value="http://">http://
                                </option>
                                <option <?php if (isset($instance['fmcta_landing_href_protocol'])) {
                                    if ($instance['fmcta_landing_href_protocol'] == "https://") {
                                        echo 'selected="selected"';
                                    }
                                } ?> value="https://">https://
                                </option>
                            </select>
                            <input type="text" name="<?php echo $this->get_field_name('fmcta_landing_href'); ?>"
                                   id="<?php echo $this->get_field_id('fmcta_landing_href'); ?>"
                                   value="<?php if (isset ($instance['fmcta_landing_href'])) {
                                       echo esc_attr($instance['fmcta_landing_href']);
                                   } ?>"
                                   placeholder="example.com, www.example.com"
                                   style="width: 73%; display: inline-block; height: 28px; vertical-align: middle;"/>
                        </p>
                    </div>


                    <p class="<?php echo $this->get_field_id('fmcta_feature_id') ?>">
                        <label for="<?php echo $this->get_field_id('fmcta_feature_id'); ?>"><strong
                                class="description">Select a page to link to</strong><br/></label>
                        <select name="<?php echo $this->get_field_name('fmcta_feature_id'); ?>"
                                class="feature-me-select"
                                style="width:100%;" id="<?php echo $this->get_field_id('fmcta_feature_id') ?>">

                            <?php
                            // Get a list of post types to execute queries for
                            $post_types = get_post_types(array(
                                    'public' => true, //only show public post types
                                )
                            );

                            unset($post_types['attachment']);

                            sort($post_types); // Sort the post types alphabetically

                            // Run foreach and wp_query inside each to output correct options/groups

                            foreach ($post_types as $type) {
                                // Set the optgroup for ease of use
                                echo '<optgroup label="' . strtoupper($type) . '">';

                                // Initiate WP_Query
                                $fmcta_feature_id_list_types = new WP_QUERY(array(
                                    'posts_per_page' => '-1',
                                    'orderby' => 'title',
                                    'order' => 'ASC',
                                    'post_type' => $type
                                ));
                                while ($fmcta_feature_id_list_types->have_posts()): $fmcta_feature_id_list_types->the_post();
                                    $post_id = $post->ID;
                                    ?>

                                    <option <?php if (isset($instance['fmcta_feature_id']) && $instance['fmcta_feature_id'] == $post_id) {
                                        echo 'selected="selected"';
                                    } ?> value="<?php echo the_ID(); ?>"><?php echo the_title(); ?></option>

                                <?php endwhile;
                                wp_reset_query();

                                // Close optgroup
                                echo '</optgroup>';
                            }
                            ?>
                        </select>
                    </p>

                </div>
                <!--.fm-step-1-options-->
            </div>
            <!--.fm-step-1-->

            <?php
            /**********Step 2**********/
            ?>
            <!--Step 2: Choose an Image-->
            <div class="fm-step-2">

                <h4 class="title fm-option-title fm-arrow <?php echo $this->get_field_id('fm-option-2'); ?>">Step 2:
                    Choose an
                    Image
                </h4>

                <div class="fm-step-2-options <?php echo $this->get_field_id('fm-step-2-options'); ?>">
                    <p class="description" style="margin-top:15px; padding:0;">What image do you want to use in this
                        Call To
                        Action?</p>

                    <p>
                     <span class="<?php echo $this->get_field_id('fmcta_image_option'); ?>_label"><input type="radio"
                                                                                                         id="<?php echo $this->get_field_id('fmcta_image_option'); ?>_1"
                                                                                                         class="<?php echo $this->get_field_id('fmcta_image_option'); ?>"
                                                                                                         name="<?php echo $this->get_field_name('fmcta_image_option'); ?>"
                                                                                                         value="upload" <?php
                         if (isset($instance['fmcta_image_option'])) {
                             if ($instance['fmcta_image_option'] == "upload") {
                                 echo 'checked="checked"';
                             }
                         } else {
                             echo 'checked = "checked"';
                         } ?>  />

                        <label
                            for="<?php echo $this->get_field_id('fmcta_image_option'); ?>_1">Upload an image
                            <small> (recommended)</small>
                        </label>
                         </span>

                    <span class="<?php echo $this->get_field_id('fmcta_image_option'); ?>_label"><br/>

                    <input type="radio" id="<?php echo $this->get_field_id('fmcta_image_option'); ?>_2"
                           class="<?php echo $this->get_field_id('fmcta_image_option'); ?>"
                           name="<?php echo $this->get_field_name('fmcta_image_option'); ?>"
                           value="feature" <?php
                    if (isset($instance['fmcta_image_option'])) {
                        if ($instance['fmcta_image_option'] == 'feature') {
                            echo 'checked="checked"';
                        }
                    } ?>  />
                    <label
                        for="<?php echo $this->get_field_id('fmcta_image_option'); ?>_2">Use Page/Post Featured
                        Image</label>
                    </span>
                     <span class="<?php echo $this->get_field_id('fmcta_image_option'); ?>_label">
                    <br/>
                    <input type="radio" id="<?php echo $this->get_field_id('fmcta_image_option'); ?>_3"
                           class="<?php echo $this->get_field_id('fmcta_image_option'); ?>"
                           name="<?php echo $this->get_field_name('fmcta_image_option'); ?>"
                           value="none" <?php
                    if (isset($instance['fmcta_image_option'])) {
                        if ($instance['fmcta_image_option'] == 'none') {
                            echo 'checked="checked"';
                        }
                    } ?>  />

                        <label
                            for="<?php echo $this->get_field_id('fmcta_image_option'); ?>_3">Off</label>
                         </span>
                    </p>
                    <p class="fmcta_image_uploader <?php echo $this->get_field_id('fmcta_image_uploader'); ?>">
                        <input type="text" name="<?php echo $this->get_field_name('fmcta_image_href'); ?>"
                               id="<?php echo $this->get_field_id('fmcta_image_href') ?>"
                               class="<?php echo $this->get_field_id('fmcta_image_href') ?> fmcta_image_href"
                               value="<?php
                               if (isset($instance['fmcta_image_href'])) {
                                   echo $instance['fmcta_image_href'];
                               } else {
                                   echo '';
                               } ?>" placeholder="Paste URI or Click &rarr;" style="width: 75%; vertical-align: middle;"/>
                        <input type="button" class="button fmcta_upload <?php echo $this->get_field_id('fmcta_upload'); ?>"
                               name="<?php echo $this->get_field_name('fmcta_upload'); ?>"
                               id="<?php echo $this->get_field_id('fmcta_upload') ?>" value="Upload"/>
                    </p>
                </div>
                <!--/.fm-step-2-options-->
            </div>
            <!--/.fm-step-2-->

            <?php
            /**********Step 3**********/
            ?>

            <div class="fm-step-3">

                <h4 class="title fm-option-title fm-arrow <?php echo $this->get_field_id('fm-option-3'); ?>">Step 3:
                    Customize
                    Your Content
                </h4>

                <div class="fm-step-3-options <?php echo $this->get_field_id('fm-step-3-options'); ?>">
                    <h4 class="title">CTA Title</h4>

                    <p><!--Use a Custom Title-->
                    <span class="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>_label">
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_heading_title_option'); ?>"
                           value="custom"
                           class="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>_1" <?php
                    if (isset ($instance['fmcta_heading_title_option'])) {
                        if ($instance['fmcta_heading_title_option'] == "custom" || $instance['fmcta_heading_title_option'] == "") {
                            echo 'checked="checked"';
                        }
                    } else {
                        echo 'checked="checked"';
                    } ?>  /> <!-- End Radio Button -->

                    <label for="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>_1">Custom Title
                        <small>Recommended</small>
                    </label></span>
                        <!--/Custom Title-->
                        <br/>
                    <span class="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>_label">

                    <!--Page/Post Title-->


	                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_heading_title_option'); ?>"
                               value="post"
                               class="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>"
                               id="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>_2" <?php
                        if (isset ($instance['fmcta_heading_title_option'])) {
                            if ($instance['fmcta_heading_title_option'] == "post") {
                                echo 'checked="checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>_2">Post/Page
                        Title</label>
                        </span>
                        <br/>
                    <span class="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>_label">

                    <!--No Title-->
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_heading_title_option'); ?>"
                           value="none"
                           class="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>_3" <?php
                    if (isset($instance['fmcta_heading_title_option'])) {
                        if ($instance['fmcta_heading_title_option'] == "none") {
                            echo 'checked="checked"';
                        }
                    } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_heading_title_option'); ?>_3">Hide Title</label>
                        </span>
                        <!--/No Title-->
                    </p>

                    <!--Custom Title Field-->
                    <div id="<?php echo $this->get_field_id('fmcta_heading_title_content'); ?>">
                        <p><input type="text" placeholder="Enter an Attention Grabbing title!" class="<?php echo
                            $this->get_field_id('fmcta_heading_title_content'); ?>"
                                  name="<?php echo $this->get_field_name('fmcta_heading_title_content'); ?>"
                                  value="<?php
                                  if (isset($instance['fmcta_heading_title_content'])) {
                                      echo esc_attr($instance['fmcta_heading_title_content']);
                                  } ?>"
                                  style="width:100%;"/>
                        </p>
                    </div>
                    <!--/Custom Title Field-->

                    <div class="divide">&nbsp;</div>

                    <div class="<?php echo $this->get_field_id('fmcta_heading_link_option'); ?>">
                        <h4 class="title">Link the CTA Title? </h4>
                        <select name="<?php echo $this->get_field_name('fmcta_heading_link_option'); ?>">
                            <option <?php
                            if (isset($instance['fmcta_heading_link_option'])) {
                                if ($instance['fmcta_heading_link_option'] == true) {
                                    echo 'selected="selected"';
                                }
                            } else {
                                echo 'selected="selected"';
                            } ?> value="true">Yes - Link the title
                            </option>

                            <option <?php if (isset($instance['fmcta_heading_link_option']) && $instance['fmcta_heading_link_option'] == false) {
                                echo 'selected="selected"';
                            } ?> value="false">No - Don't link the title
                            </option>
                        </select>
                        </p>
                        <!--/Link Heading-->
                    </div>


                    <div class="divide">&nbsp;</div>

                    <h4 class="title">Description</h4>

                    <p>
                        <!--Custom Body-->
                    <span class="<?php echo $this->get_field_id('fmcta_description_option'); ?>_label">
                    <input type="radio" class="<?php echo $this->get_field_id('fmcta_description_option'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_description_option'); ?>_1" value="custom"
                           name="<?php echo $this->get_field_name('fmcta_description_option'); ?>"
                        <?php
                        if (isset ($instance['fmcta_description_option'])) {
                            if (esc_attr($instance['fmcta_description_option']) == 'custom' || esc_attr($instance['fmcta_description_option']) == '') {
                                echo 'checked="checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_description_option'); ?>_1">Custom</label>
                        </span>
                        <!--/Custom Body-->

                        <!--Default Body-->
                    <span class="<?php echo $this->get_field_id('fmcta_description_option'); ?>_label">
                        <br/>
                    <input type="radio" class="<?php echo $this->get_field_id('fmcta_description_option'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_description_option'); ?>_2" value="excerpt"
                           name="<?php echo $this->get_field_name('fmcta_description_option'); ?>"
                        <?php
                        if (isset ($instance['fmcta_description_option'])) {
                            if (esc_attr($instance['fmcta_description_option']) == 'excerpt') {
                                echo 'checked="checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_description_option'); ?>_2">Post/Page
                        Except</label>
                        </span>
                        <!--/Default Body-->

                        <!--No Body-->
                    <span class="<?php echo $this->get_field_id('fmcta_description_option'); ?>_label"><br/>
                    <input type="radio" class="<?php echo $this->get_field_id('fmcta_description_option'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_description_option'); ?>_3" value="none"
                           name="<?php echo $this->get_field_name('fmcta_description_option'); ?>"
                        <?php if (isset ($instance['fmcta_description_option'])) {
                            if (esc_attr($instance['fmcta_description_option']) == 'none') {
                                echo 'checked="checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_description_option'); ?>_3">Hide</label>
                    <!--/No Body-->
                    &nbsp;

                    </p>

                    <div class="<?php echo $this->get_field_id('fmcta_description_content'); ?>">
                        <p><textarea id="<?php echo $this->get_field_id('fmcta_description_content'); ?>"
                                     name="<?php echo $this->get_field_name('fmcta_description_content'); ?>"
                                     style="width:100%;"
                                     placeholder="Describe in a couple words how this brings value to your users."><?php
                                if (isset($instance['fmcta_description_content'])) {
                                    echo ($instance['fmcta_description_content'] == '') ? "" : esc_attr($instance['fmcta_description_content']);
                                } ?></textarea>
                        </p>
                    </div>
                </div>
                <!--/.fm-step-3-options-->
            </div>
            <!--/.fm-step-3-->
            <?php
            /**********Step 4**********/
            ?>
            <!--STEP 4 - Customize your Button -->
            <div class="fm-step-4">
                <h4 class="title fm-option-title fm-arrow <?php echo $this->get_field_id('fm-option-4'); ?>">Step 4:
                    Customize Your Button</h4>

                <div class="fm-step-4-options <?php echo $this->get_field_id('fm-step-4-options'); ?>">

                    <h4 class="title">Choose a Button Type</h4>

                    <select name="<?php echo $this->get_field_name('fmcta_button_option'); ?>"
                            class="<?php echo $this->get_field_id('fmcta_button_option'); ?>" style="width: 100%;">
                        <option <?php if (isset($instance['fmcta_button_option']) && $instance['fmcta_button_option'] == "text") {
                            echo 'selected="selected"';
                        } ?> value="text">Text Link
                        </option>
                        <option <?php if (isset($instance['fmcta_button_option']) && $instance['fmcta_button_option'] == "css") {
                            echo 'selected="selected"';
                        } ?> value="css">CSS Button
                        </option>
                        <option <?php if (isset($instance['fmcta_button_option']) && $instance['fmcta_button_option'] == "uploadt") {
                            echo 'selected="selected"';
                        } ?> value="upload">Upload Button Image
                        </option>
                        <option <?php if (isset($instance['fmcta_button_option']) && $instance['fmcta_button_option'] == "none") {
                            echo 'selected="selected"';
                        } ?> value="none">No Button
                        </option>
                    </select>


                    <div class="fmcta_button_uploader <?php echo $this->get_field_id('fmcta_button_uploader'); ?>">
                        <p>
                            <input type="text" name="<?php echo $this->get_field_name('fmcta_button_image_uri'); ?>"
                                   id="<?php echo $this->get_field_id('fmcta_button_image_uri') ?>"
                                   class="<?php echo $this->get_field_id('fmcta_button_image_uri') ?> fmcta_button_image_uri"
                                   value="<?php
                                   if (isset ($instance['fmcta_button_image_uri'])) {
                                       echo $instance['fmcta_button_image_uri'];
                                   } ?>"
                                   placeholder="Paste URI or Click &rarr;"/>
                            <input class="button fmcta_upload <?php echo $this->get_field_id('fmcta_upload'); ?>"
                                   name="<?php echo $this->get_field_name('fmcta_upload'); ?>"
                                   id="<?php echo $this->get_field_id('fmcta_upload') ?>" value="Upload"/>
                        </p>
                    </div>

                    <!-- Button Title -->
                    <div class="<?php echo $this->get_field_id('fmcta_button_title_field'); ?>">
                        <h4 class="title">CTA Button Title</h4>


                        <p><input type="text" name="<?php echo $this->get_field_name('fmcta_button_text'); ?>"
                                  id="<?php echo $this->get_field_id('fmcta_button_text'); ?>"
                                  value="<?php
                                  if (isset($instance['fmcta_button_text'])) {
                                      echo esc_attr($instance['fmcta_button_text']);
                                  } ?>"
                                  placeholder="Read More!, Act Now!"
                                  style="width:100%;"/></p>
                    </div>
                    <!-- END Button Title -->


                </div>
                <!--/.fm-step-4-options-->
            </div>
            <!--/.fm-step-4-->

            <?php
            /********** Step 5 - Customization **********/
            ?>
            <!--STEP Placement-->
            <div class="fm-step-5">
                <!--Link Heading-->
                <h4 class="title fm-option-title fm-arrow <?php echo $this->get_field_id('fm-option-5'); ?> fm-arrow-closed">
                    Step 5: Other Customization
                </h4>

                <div class="fm-step-5-options <?php echo $this->get_field_id('fm-step-5-options'); ?>">

                    <h4 class="title">Order CTA Elements</h4>

                    <p>Drag and Drop the oder that you want to display the CTA</p>
                    <ul id="<?php echo $this->get_field_id('fmcta_element_order'); ?>"
                        class="<?php echo $this->get_field_id('fmcta_element_order'); ?> fmcta_sortable">
                        <li class="ui-state-default fmcta_order_<?php if (isset ($instance['fmcta_element_order'])) {
                            echo $instance['fmcta_element_order'][0];
                        } ?> fmcta_order">
                            <?php if (isset($instance['fmcta_element_order'])) {
                                echo strtoupper($instance['fmcta_element_order'][0]);
                            } else {
                                echo "Title";
                            } ?>
                            <input type="hidden" name="<?php echo $this->get_field_name('fmcta_element_order') ?>[]"
                                   value="<?php if (isset($instance['fmcta_element_order'])) {
                                       echo $instance['fmcta_element_order'][0];
                                   } else {
                                       echo "title";
                                   } ?>">
                        </li>
                        <li class="ui-state-default fmcta_order_<?php if (isset ($instance['fmcta_element_order'])) {
                            echo $instance['fmcta_element_order'][1];
                        } ?> fmcta_order"><?php if (isset($instance['fmcta_element_order'])) {
                                echo strtoupper($instance['fmcta_element_order'][1]);
                            } else {
                                echo "Image";
                            } ?>
                            <input type="hidden" name="<?php echo $this->get_field_name('fmcta_element_order') ?>[]"
                                   value="<?php if (isset($instance['fmcta_element_order'])) {
                                       echo $instance['fmcta_element_order'][1];
                                   } else {
                                       echo "image";
                                   } ?>">
                        </li>
                        <li class="ui-state-default fmcta_order_<?php if (isset ($instance['fmcta_element_order'])) {
                            echo $instance['fmcta_element_order'][2];
                        } ?> fmcta_order"><?php if (isset($instance['fmcta_element_order'])) {
                                echo strtoupper($instance['fmcta_element_order'][2]);
                            } else {
                                echo "Description";
                            } ?>
                            <input type="hidden" name="<?php echo $this->get_field_name('fmcta_element_order') ?>[]"
                                   value="<?php if (isset($instance['fmcta_element_order'])) {
                                       echo $instance['fmcta_element_order'][2];
                                   } else {
                                       echo "description";
                                   } ?>"></li>
                        <li class="ui-state-default fmcta_order_<?php if (isset ($instance['fmcta_element_order'])) {
                            echo $instance['fmcta_element_order'][3];
                        } ?> fmcta_order"><?php if (isset($instance['fmcta_element_order'])) {
                                echo strtoupper($instance['fmcta_element_order'][3]);
                            } else {
                                echo "Button";
                            } ?>
                            <input type="hidden" name="<?php echo $this->get_field_name('fmcta_element_order') ?>[]"
                                   value="<?php if (isset($instance['fmcta_element_order'])) {
                                       echo $instance['fmcta_element_order'][3];
                                   } else {
                                       echo "button";
                                   } ?>"></li>
                    </ul>

                    <div class="divide">&nbsp;</div>

                    <h4 class="title">Add Custom CSS Class to Widget</h4>

                    <p>
                        <small>You can add a CSS class to add custom styling</small>
                        <input type="text" name="<?php echo $this->get_field_name('fmcta_class'); ?>"
                               value="<?php if (isset ($instance['fmcta_class'])) {
                                   echo esc_attr($instance['fmcta_class']);
                               } ?>" style="width:100%;"/>
                    </p>

                </div>
                <!--/.fm-step-5-options-->
            </div>
            <!--/.fm-step-Placement-->

            <div class="divide">&nbsp;</div>

            <p>If you like Feature Me,
                <a href="http://wordpress.org/support/view/plugin-reviews/feature-me" target="_blank">please rate
                    it.</a></p>


        </div><!--/fm_widget-->
    <?php
    } //form


    /**
     * fmcta_render_title
     * Use this method to render the widget title.
     *
     * @param array $instance
     * @param string $before_title
     * @param string $after_title
     */
    public function fmcta_render_title($instance, $before_title, $after_title)
    {

        /*--CTA Title--*/

        switch (true) {
            //CTA is using the page/post title
            case (isset($instance['fmcta_heading_title_option']) && $instance['fmcta_heading_title_option'] == "post"):

                //Generate a link for header
                if ($instance['fmcta_heading_link_option'] == "true") {
                    //Generate default link via permalink
                    if ($instance['fmcta_landing_option'] == "default") {
                        echo $before_title . '<a href="' . get_permalink($instance['fmcta_feature_id']) . '"\>' . get_the_title($instance['fmcta_feature_id']) . '</a>' . $after_title;
                    } //Generate custom link via text fmcta_landing_href field
                    else {
                        echo $before_title . '<a href="' . $instance['fmcta_landing_href_protocol'] . $instance['fmcta_landing_href'] . '">' . $instance['fmcta_heading_title_content'] . '</a>' . $after_title;
                    }
                } else {
                    echo $before_title . $instance['fmcta_heading_title_content'] . $after_title;
                }
                break;

            //CTA is using a custom title
            case (isset($instance['fmcta_heading_title_option']) && $instance['fmcta_heading_title_option'] == "custom"): //Custom Title

                //Generate a link for Header
                if (isset($instance['fmcta_heading_link_option']) && $instance['fmcta_heading_link_option'] == "true") {
                    //Generate default link via permalink
                    if ($instance['fmcta_landing_option'] == "default") {
                        echo $before_title . '<a href="' . get_permalink() . '"\>' . $instance['fmcta_heading_title_content'] . '</a>' . $after_title;
                    } //Generate custom link via text fmcta_landing_href field
                    else {
                        echo $before_title . '<a href="' . $instance['fmcta_landing_href_protocol'] . $instance['fmcta_landing_href'] . '">' . $instance['fmcta_heading_title_content'] . '</a>' . $after_title;
                    }
                } else {
                    echo $before_title . $instance['fmcta_heading_title_content'] . $after_title;
                }
                break;

            //CTA is not using a title
            case (isset($instance['fmcta_heading_title_option']) && $instance['fmcta_heading_title_option'] == "none"):
                break;

            // For some reason if none of the above work, default to
            default:
                break;

        }
    }

    /**
     * fmcta_render_image
     * Use this function to render the image from the widget.
     *
     * @param array $instance
     */
    public function fmcta_render_image($instance)
    {
        echo '<div class="fmcta_feature_id_image">';

        if ($instance['fmcta_image_option'] == 'feature') { ?>
            <a href="<?php
            if ($instance['fmcta_landing_option'] == "default") {
                echo get_the_permalink($instance['fmcta_feature_id']);
            } else {
                echo $instance['fmcta_landing_href_protocol'] . $instance['fmcta_landing_href'];
            } ?>" title="<?php echo get_the_title($instance['fmcta_feature_id']); ?>">
                <?php echo get_the_post_thumbnail($instance['fmcta_feature_id'], 'full', array('class' => 'fmcta_thumb'));
                ?></a>
        <?php
        } else if ($instance['fmcta_image_option'] == "upload") {
            ?>
            <a href="<?php if ($instance['fmcta_landing_option'] == "default") {
                echo get_the_permalink($instance['fmcta_feature_id']);
            } else {
                echo $instance['fmcta_landing_href_protocol'] . $instance['fmcta_landing_href'];
            } ?>" title="<?php echo $instance['fmcta_heading_title_content']; ?>">
                <img src="<?php echo $instance['fmcta_image_href']; ?>"
                     alt="<?php echo $instance['fmcta_heading_title_content']; ?>"/></a>

        <?php
        } else if($instance['fmcta_image_option'] == "none"){
            // Don't output an image
        }
        echo '</div><!--/.fmcta_feature_id_image-->';
    }

    public function fmcta_render_description($instance)
    {
        /*--CTA Description--*/

        if (isset($instance['fmcta_desctiption_option'])) {
            switch ($instance['fmcta_description_option']) {
                case 'excerpt':
                    $the_post = get_post($instance['fmcta_feature_id']);
                    setup_postdata($the_post);
                    echo the_excerpt();
                    break;
                case 'custom':
                    echo "<p>" . $instance['fmcta_description_content'] . "</p>";
                    break;
                case 'none':
                    break;
            }
        }

    }

    public function fmcta_render_button($instance)
    {

        //If the user doesn't want to display the button, stop here.
        if (isset($instance['fmcta_button_option'])) {
            if ($instance['fmcta_button_option'] == "none" || $instance['fmcta_button_text'] == "") {
                return;
            }
        }

        //Initiate variables
        $url;
        $class = "fmcta-link";
        $button_content = "";
        $button_text = (isset($instance['fmcta_button_text'])) ? $instance['fmcta_button_text'] : "";
        $button_image = (isset($instance['fmcta_button_image_uri'])) ? $instance['fmcta_button_image_uri'] : "";

        //Get the appropriate URL
        if ($instance['fmcta_landing_option'] == 'default') {
            $url = get_permalink($instance['fmcta_feature_id']);
        } else {
            $url = $instance['fmcta_landing_href_protocol'] . $instance['fmcta_landing_href'];
        }

        //Get the appropriate button type.
        if (isset($instance['fmcta_button_option']) && $instance['fmcta_button_option'] == "text") {
            $class .= " fmcta-text-only";
            $button_content = $button_text;
        } else if (isset($instance['fmcta_button_option']) && $instance['fmcta_button_option'] == "css") {
            $class .= " fmcta-button";
            $button_content = $button_text;
        } else if (isset($instance['fmcta_button_option']) && $instance['fmcta_button_option'] == "upload") {
            $button_content = "<img src='$button_image' alt='$button_text' />";
        }

        $link = "<p><a href='$url' class='$class' >$button_content</a></p>";

        return $link;

    }

} //featureme