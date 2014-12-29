<?php

/********************************************************************************
 *  Copyright 2012-2014 Ian Banks
 *******************************************************************************
 *
 *      Table of Contents
 *
 *      0.0 - fmcta_widget
 *      1.0 - fmcta_widget
 *      1.1 - widget
 *      1.2 - form
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
     * 1.1 - widget
     * Outputs content to display on the website.
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        //Step 1 - Choose a Landing Page to Link To
        $fmcta_feature = ($instance['fmcta_feature']) ? esc_attr(strip_tags($instance['fmcta_feature'])) : "";
        $fmcta_landing_type = ($instance['fmcta_landing_type']) ? esc_attr(strip_tags($instance['fmcta_landing_type'])) : "";

        $fmcta_type_url = (isset($instance['fmcta_type_url'])) ? esc_attr(strip_tags($instance['fmcta_type_url'])) : "";
        if (strpos($fmcta_type_url, 'http://') === false) {
            $fmcta_type_url = 'http://' . $fmcta_type_url;
        }

        //Step 2 - Choose an Image
        $fmcta_use_image = (isset($instance['fmcta_use_image'])) ? esc_attr(strip_tags($instance['fmcta_use_image'])) : "upload";
        $fmcta_image_uri = ($instance['fmcta_image_uri']) ? esc_attr(strip_tags($instance['fmcta_image_uri'])) : "";


        //Step 3 - Customize Content
        $fmcta_heading_title_type = (isset($instance['fmcta_heading_title_type'])) ? esc_attr(strip_tags($instance['fmcta_heading_title_type'])) : "custom";
        $fmcta_heading_title_content = ($instance['fmcta_heading_title_content']) ? esc_attr(strip_tags($instance['fmcta_heading_title_content'])) : "";

        $fmcta_description_type = (isset($instance['fmcta_description_type'])) ? esc_attr(strip_tags($instance['fmcta_description_type'])) : "excerpt";
        $fmcta_description_type_content = ($instance['fmcta_description_type_content']) ? esc_attr(strip_tags($instance['fmcta_description_type_content'])) : "";

        //Step 4 - Choose a Button
        $fmcta_button_type = (isset($instance['fmcta_button_type'])) ? esc_attr(strip_tags($instance['fmcta_button_type'])) : "";
        $fmcta_button_image_uri = (isset($instance['fmcta_button_image_uri'])) ? esc_attr(strip_tags($instance['fmcta_button_image_uri'])) : "";
        $fmcta_button_text = (isset($instance['fmcta_button_text'])) ? esc_attr(strip_tags($instance['fmcta_button_text'])) : "";

        //Advanced
        $class = (isset($instance['class'])) ? esc_attr(strip_tags($instance['class'])) : "";
        $header_link = (isset($instance['header_link'])) ? esc_attr(strip_tags($instance['header_link'])) : "false";
        $fmcta_image_placement = (isset($instance['fmcta_image_placement'])) ? esc_attr(strip_tags($instance['fmcta_image_placement'])) : "above";
        $fm_url;
        $useLink; //bool to determine whether or not to use a link

        wp_enqueue_style("featureme-css", plugins_url("feature-me") . "/featureme.css");


        ?>
        <article class="feature-me <?php echo $class; ?>">
            <?php echo $before_widget;
            //print_r($instance);
            $the_feature = new WP_QUERY(array(
                'p' => $fmcta_feature,
                'post_type' => array('post', 'page'),
                'posts_per_page' => '1'
            ));

            while ($the_feature->have_posts()):
            $the_feature->the_post();

            //Render Title and Featured Image

            if (isset($instance['fmcta_image_placement'])) {

                if ($instance['fmcta_image_placement'] == "above") {
                    echo $this->fmcta_render_image($instance);
                    echo $this->fmcta_render_title($instance, $before_title, $after_title);
                } else if ($instance['fmcta_image_placement'] == 'below') {
                    echo $this->fmcta_render_title($instance, $before_title, $after_title);
                    echo $this->fmcta_render_image($instance);
                } else {
                    echo "An error occurred. Please resave the widget.";
                }
            } else {
                echo $this->fmcta_render_title($instance, $before_title, $after_title);
            }


            /*--CTA Description--*/

            switch ($fmcta_description_type) {
                case 'excerpt':
                    the_excerpt();
                    break;
                case 'custom':
                    echo "<p>" . $fmcta_description_type_content . "</p>";
                    break;
                case 'none':
                    break;
            }

            /*--CTA Button--*/

            echo '<p>' . $this->fmcta_render_button($instance) . '</p>';

            endwhile;

            wp_reset_query();

            ?>
            <?php echo $after_widget; ?>
        </article>

    <?php
    } //end widget

    /**
     * 1.2 - form
     * Form to display in the Widget Admin
     *
     * @param array $instance
     *
     * @return string|void
     */
    public
    function form($instance)
    {
        $fmcta_featured_id;
        if (isset($instance['fmcta_feature'])) {
            $fmcta_featured_id = $instance['fmcta_feature'];
        }
        //print_r($instance);

        echo $this->generateCSS(); //generate CSS to page
        /**
         * @todo enqueue css
         */
        ?>


        <div class="fm_widget" id="<?php echo $this->id; ?>">


        <?php
        /**********Step 1**********/
        ?>
        <div class="fm-step-1">

            <h4 class="title fm-option-title fm-option-1">
                <span class="fm-arrow">&#x25bc;</span> Step 1: Choose a Landing Page</h4>

            <div class="fm-step-1-options">
                <p>
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_landing_type') ?>"
                           class="<?php echo $this->get_field_id('fmcta_landing_type') ?>" value="default"
                           id="<?php echo $this->get_field_id('fmcta_landing_type') ?>_1"
                        <?php
                        if (isset ($instance['fmcta_landing_type'])) {
                            if ($instance['fmcta_landing_type'] == "default" || $instance['fmcta_landing_type'] == "") {
                                echo 'checked="checked"';
                            }
                        } else {
                            echo 'checked="checked"';
                        } ?> /><!--/fm_landing_1-->

                    <label for="<?php echo $this->get_field_id('fmcta_landing_type'); ?>_1">Page/Post on this
                        Website</label></p>

                <p>
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_landing_type') ?>"
                           class="<?php echo $this->get_field_id('fmcta_landing_type') ?>" value="external"
                           id="<?php echo $this->get_field_id('fmcta_landing_type') ?>_2"
                        <?php
                        if (isset ($instance['fmcta_landing_type'])) {
                            if ($instance['fmcta_landing_type'] == "external") {
                                echo 'checked="checked"';
                            }
                        }  ?> /><!--/fm_landing_2-->
                    <label for="<?php echo $this->get_field_id('fmcta_landing_type') ?>_2">External Website</label>
                </p>

                <p><input type="text" name="<?php echo $this->get_field_name('fmcta_type_url'); ?>"
                          id="<?php echo $this->get_field_id('fmcta_type_url'); ?>"
                          class="<?php echo $this->get_field_id('fmcta_type_url'); ?>"
                          value="<?php if (isset ($instance['fmcta_type_url'])) {
                              echo esc_attr($instance['fmcta_type_url']);
                          } ?>"
                          placeholder="http://example.com, example.com, /"
                          style="width:100%;"/></p>


                <p class="<?php echo $this->get_field_id('fmcta_feature') ?>">
                    <label for="<?php echo $this->get_field_id('fmcta_feature'); ?>"><strong class="description">Select
                            a Page or Post</strong><br/></label>
                    <select name="<?php echo $this->get_field_name('fmcta_feature'); ?>" class="feature-me-select"
                            style="width:100%;" id="<?php echo $this->get_field_id('fmcta_feature') ?>">

                        <option selected="selected" value="<?php
                        if (isset ($instance['fmcta_feature'])) {
                            echo esc_attr($instance['fmcta_feature']);
                        } ?>"><?php
                            if (!empty($fmcta_featured_id)) {
                                $selected_feature = new WP_QUERY(array(
                                    'p' => $fmcta_featured_id,
                                    'post_type' => array('post', 'page'),
                                    'posts_per_page' => '1'
                                ));
                                while ($selected_feature->have_posts()): $selected_feature->the_post();
                                    echo the_title();

                                endwhile;
                                wp_reset_query();
                            } else {
                                echo 'Select a Post or Page';
                            }
                            ?>
                        </option>

                        <optgroup label="Pages">
                            <?php

                            //PAGES

                            $fmcta_feature_list_pages = new WP_QUERY(array(
                                'posts_per_page' => '-1',
                                'orderby' => 'title',
                                'order' => 'ASC',
                                'post_type' => 'page'
                            ));
                            while ($fmcta_feature_list_pages->have_posts()): $fmcta_feature_list_pages->the_post();
                                ?>

                                <option value="<?php echo the_ID(); ?>"><?php echo the_title(); ?></option>

                            <?php endwhile;
                            wp_reset_query(); ?>
                        </optgroup>

                        <optgroup label="Posts">
                            <?php

                            //POSTS

                            $fmcta_feature_list_posts = new WP_QUERY(array(
                                'posts_per_page' => '-1',
                                'orderby' => 'title',
                                'order' => 'ASC',
                                'post_type' => 'post'
                            ));

                            while ($fmcta_feature_list_posts->have_posts()): $fmcta_feature_list_posts->the_post();
                                ?>

                                <option value="<?php echo the_ID(); ?>"><?php echo the_title(); ?></option>

                            <?php endwhile;
                            wp_reset_query(); ?>
                        </optgroup>
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

            <h4 class="title fm-option-title fm-option-2"><span class="fm-arrow">&#x25bc;</span> Step 2: Choose an
                Image
            </h4>

            <div class="fm-step-2-options">
                <p class="description" style="margin-top:15px; padding:0;">What image do you want to use in this Call To
                    Action?</p>

                <p>
                     <span class="<?php echo $this->get_field_id('fmcta_use_image'); ?>_label"><input type="radio"
                                                                                                      id="<?php echo $this->get_field_id('fmcta_use_image'); ?>_1"
                                                                                                      class="<?php echo $this->get_field_id('fmcta_use_image'); ?>"
                                                                                                      name="<?php echo $this->get_field_name('fmcta_use_image'); ?>"
                                                                                                      value="upload" <?php
                         if (isset($instance['fmcta_use_image'])) {
                             if ($instance['fmcta_use_image'] == "upload") {
                                 echo 'checked="checked"';
                             }
                         } ?>  />
                     </span>
                    <label
                        for="<?php echo $this->get_field_id('fmcta_use_image'); ?>_1">Upload an image
                        <small> (recommended)</small>
                    </label>

                    <span class="<?php echo $this->get_field_id('fmcta_use_image'); ?>_label"><br/>

                    <input type="radio" id="<?php echo $this->get_field_id('fmcta_use_image'); ?>_2"
                           class="<?php echo $this->get_field_id('fmcta_use_image'); ?>"
                           name="<?php echo $this->get_field_name('fmcta_use_image'); ?>"
                           value="feature" <?php
                    if (isset($instance['fmcta_use_image'])) {
                        if ($instance['fmcta_use_image'] == 'feature') {
                            echo 'checked="checked"';
                        }
                    } ?>  />
                    <label
                        for="<?php echo $this->get_field_id('fmcta_use_image'); ?>_2">Use Page/Post Featured
                        Image</label>
                    </span>
                     <span class="<?php echo $this->get_field_id('fmcta_use_image'); ?>_label">
                    <br/>
                    <input type="radio" id="<?php echo $this->get_field_id('fmcta_use_image'); ?>_3"
                           class="<?php echo $this->get_field_id('fmcta_use_image'); ?>"
                           name="<?php echo $this->get_field_name('fmcta_use_image'); ?>"
                           value="none" <?php
                    if (isset($instance['fmcta_use_image'])) {
                        if ($instance['fmcta_use_image'] == 'none') {
                            echo 'checked="checked"';
                        }
                    } ?>  />
                         </span>
                    <label
                        for="<?php echo $this->get_field_id('fmcta_use_image'); ?>_3">Off</label>
                </p>
                <p class="fmcta_image_uploader <?php echo $this->get_field_id('fmcta_image_uploader'); ?>">
                    <input type="text" name="<?php echo $this->get_field_name('fmcta_image_uri'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_image_uri') ?>"
                           class="<?php echo $this->get_field_id('fmcta_image_uri') ?> fmcta_image_uri"
                           value="<?php
                           if (isset($instance['fmcta_image_uri'])) {
                               echo $instance['fmcta_image_uri'];
                           } else {
                               echo '';
                           } ?>" placeholder="Paste URI or Click &rarr;"/>
                    <input class="button fmcta_upload <?php echo $this->get_field_id('fmcta_upload'); ?>"
                           name="<?php echo $this->get_field_name('fmcta_upload'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_upload') ?>" value="Upload"/>
                </p>


                <!--<div class="<?php echo $this->get_field_id('image_preview') ?>"><?php echo get_the_post_thumbnail($instance['fmcta_feature'], array(
                    150,
                    226
                )); ?> </div>
-->
            </div>
            <!--/.fm-step-2-options-->
        </div>
        <!--/.fm-step-2-->

        <?php
        /**********Step 3**********/
        ?>

        <div class="fm-step-3">

            <h4 class="title fm-option-title fm-option-3"><span class="fm-arrow">&#x25bc;</span> Step 3: Customize Your
                Content
            </h4>

            <div class="fm-step-3-options">
                <h4 class="title">CTA Title</h4>

                <p><!--Custom-->
                    <span class="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>_label">
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_heading_title_type'); ?>"
                           value="custom"
                           class="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>_1" <?php
                    if (isset ($instance['fmcta_heading_title_type'])) {
                        if ($instance['fmcta_heading_title_type'] == "custom" || $instance['fmcta_heading_title_type'] == "") {
                            echo 'checked="checked"';
                        }
                    } ?>  />

                    <label for="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>_1">Custom Title
                        <small>Recommended</small>
                    </label></span>
                    <!--/Custom Title-->
                    <span class="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>_label">
                    <br/>
                    <!--Page/Post Title-->
                    <input type="radio" name="<?php
                    if (isset ($instance['fmcta_heading_title_type'])) {
                        echo $this->get_field_name('fmcta_heading_title_type');
                    } ?>"
                           value="post"
                           class="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>_2" <?php
                    if (isset ($instance['fmcta_heading_title_type'])) {
                        if ($instance['fmcta_heading_title_type'] == "post") {
                            echo 'checked="checked"';
                        }
                    } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>_2">Post/Page
                        Title</label>
                        </span>
                    <span class="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>_label">
                    <br/>
                    <!--No Title-->
                    <input type="radio" name="<?php
                    if (isset ($instance['fmcta_heading_title_type'])) {
                        echo $this->get_field_name('fmcta_heading_title_type');
                    } ?>"
                           value="none"
                           class="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>_3" <?php
                    if (isset($instance['fmcta_heading_title_type'])) {
                        if ($instance['fmcta_heading_title_type'] == "none") {
                            echo 'checked="checked"';
                        }
                    } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_heading_title_type'); ?>_3">Hide Title</label>
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

                <h4 class="title">Description</h4>

                <p>
                    <!--Custom Body-->
                    <span class="<?php echo $this->get_field_id('fmcta_description_type'); ?>_label">
                    <input type="radio" class="<?php echo $this->get_field_id('fmcta_description_type'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_description_type'); ?>_1" value="custom"
                           name="<?php echo $this->get_field_name('fmcta_description_type'); ?>"
                        <?php
                        if (isset ($instance['fmcta_description_type'])) {
                            if (esc_attr($instance['fmcta_description_type']) == 'custom' || esc_attr($instance['fmcta_description_type']) == '') {
                                echo 'checked="checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_description_type'); ?>_1">Custom</label>
                        </span>
                    <!--/Custom Body-->

                    <!--Default Body-->
                    <span class="<?php echo $this->get_field_id('fmcta_description_type'); ?>_label">
                        <br/>
                    <input type="radio" class="<?php echo $this->get_field_id('fmcta_description_type'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_description_type'); ?>_2" value="excerpt"
                           name="<?php echo $this->get_field_name('fmcta_description_type'); ?>"
                        <?php
                        if (isset ($instance['fmcta_description_type'])) {
                            if (esc_attr($instance['fmcta_description_type']) == 'excerpt') {
                                echo 'checked="checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_description_type'); ?>_2">Post/Page Except</label>
                        </span>
                    <!--/Default Body-->

                    <!--No Body-->
                    <span class="<?php echo $this->get_field_id('fmcta_description_type'); ?>_label"><br/>
                    <input type="radio" class="<?php echo $this->get_field_id('fmcta_description_type'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_description_type'); ?>_3" value="none"
                           name="<?php echo $this->get_field_name('fmcta_description_type'); ?>"
                        <?php if (isset ($instance['fmcta_description_type'])) {
                            if (esc_attr($instance['fmcta_description_type']) == 'none') {
                                echo 'checked="checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_description_type'); ?>_3">Hide</label>
                    <!--/No Body-->
                    &nbsp;

                </p>

                <div class="<?php echo $this->get_field_id('fmcta_description_type_content'); ?>">
                    <p><textarea id="<?php echo $this->get_field_id('fmcta_description_type_content'); ?>"
                                 name="<?php echo $this->get_field_name('fmcta_description_type_content'); ?>"
                                 style="width:100%;"
                                 placeholder="Describe in a couple words how this brings value to your users."><?php
                            if (isset($instance['fmcta_description_type_content'])) {
                                echo ($instance['fmcta_description_type_content'] == '') ? "" : esc_attr($instance['fmcta_description_type_content']);
                            } ?></textarea>
                    </p>
                </div>
            </div>
            <!--/.fm-step-3-options-->
        </div>
        <!--/.fm-step-3-->
        <?php
        /**********Step 3**********/
        ?>
        <!--STEP 4 - Customize your Button -->
        <div class="fm-step-4">
            <h4 class="title fm-option-title fm-option-4"><span class="fm-arrow">&#x25bc;</span> Step 4:
                Customize Your Button</h4>

            <div class="fm-step-4-options">
                <h4 class="title">CTA Button Title</h4>
                <!--Link Title Options-->

                <p><input type="text" name="<?php echo $this->get_field_name('fmcta_button_text'); ?>"
                          id="<?php echo $this->get_field_id('fmcta_button_text'); ?>"
                          value="<?php
                          if (isset($instance['fmcta_button_text'])) {
                              echo esc_attr($instance['fmcta_button_text']);
                          } ?>"
                          placeholder="Read More!, Act Now!"
                          style="width:100%;"/></p>
                <!--/Link Title Options-->

                <h4 class="title">Choose a Button Type</h4>

                <p>
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_button_type'); ?>" value="none"
                           id="<?php echo $this->get_field_id('fmcta_button_type'); ?>_none"
                           class="<?php echo $this->get_field_id('fmcta_button_type'); ?>"
                        <?php
                        if (isset($instance['fmcta_button_type'])) {
                            if ($instance['fmcta_button_type'] == "none") {
                                echo 'checked = "checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_button_type'); ?>_none">None
                        <small>Useful for image-only CTA's!</small>
                    </label>
                </p>
                <p>
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_button_type'); ?>" value="text"
                           id="<?php echo $this->get_field_id('fmcta_button_type'); ?>_text"
                           class="<?php echo $this->get_field_id('fmcta_button_type'); ?>"
                        <?php
                        if (isset($instance['fmcta_button_type'])) {
                            if ($instance['fmcta_button_type'] == "text") {
                                echo 'checked = "checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_button_type'); ?>_text">Text</label>
                </p>

                <p>
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_button_type'); ?>" value="css"
                           id="<?php echo $this->get_field_id('fmcta_button_type'); ?>_css"
                           class="<?php echo $this->get_field_id('fmcta_button_type'); ?>"
                        <?php
                        if (isset($instance['fmcta_button_type'])) {
                            if ($instance['fmcta_button_type'] == "css") {
                                echo 'checked = "checked"';
                            }
                        } ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_button_type'); ?>_css">CSS</label>
                </p>

                <p>
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_button_type'); ?>" value="upload"
                           id="<?php echo $this->get_field_id('fmcta_button_type'); ?>_upload"
                           class="<?php echo $this->get_field_id('fmcta_button_type'); ?>"
                        <?php
                        if (isset($instance['fmcta_button_type'])) {
                            if ($instance['fmcta_button_type'] == "upload") {
                                echo 'checked = "checked"';
                            }
                        }  ?>  />
                    <label for="<?php echo $this->get_field_id('fmcta_button_type'); ?>_upload">Upload a Button
                        Image</label>

                <div class="fmcta_button_uploader <?php echo $this->get_field_id('fmcta_button_uploader'); ?>">
                    <input type="text" name="<?php echo $this->get_field_name('fmcta_button_image_uri'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_button_image_uri') ?>"
                           class="<?php echo $this->get_field_id('fmcta_button_image_uri') ?> fmcta_button_image_uri"
                           value="<?php
                           if (isset ($instance['fmcta_button_image_uri'])) {
                               echo $instance['fmcta_button_image_uri'];
                           } ?>"
                           placeholder="Paste URI or Click &rarr;"/>
                    <input class="button fmcta_upload <?php echo $this->get_field_id('fmcta_button_upload'); ?>"
                           name="<?php echo $this->get_field_name('fmcta_button_upload'); ?>"
                           id="<?php echo $this->get_field_id('fmcta_button_upload') ?>" value="Upload"/>
                </div>
                </p>

            </div>
            <!--/.fm-step-4-options-->
        </div>
        <!--/.fm-step-4-->

        <!--STEP Advanced-->
        <div class="fm-step-advanced">
            <!--Link Heading-->
            <h4 class="title fm-option-title fm-option-advanced"><span class="fm-arrow">&#x25bc;</span> Advanced</h4>

            <div class="fm-step-advanced-options">
                <p>Title / Image Placement <br/>

                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_image_placement'); ?>"
                           value="above"
                           id="<?php echo $this->get_field_id('fmcta_image_placement'); ?>_1"
                        <?php if (isset ($instance['fmcta_image_placement'])) {
                            if ($instance['fmcta_image_placement'] == "above" || $instance['fmcta_image_placement'] == "") {
                                echo 'checked="checked"';
                            }
                        }
                        ?>
                        />
                    <label for="<?php echo $this->get_field_id('fmcta_image_placement'); ?>_1"><em>Above</em>
                        title</label>
                    &nbsp;
                    <input type="radio" name="<?php echo $this->get_field_name('fmcta_image_placement'); ?>"
                           value="below"
                           id="<?php echo $this->get_field_id('fmcta_image_placement'); ?>_2"
                        <?php if (isset($instance['fmcta_image_placement'])) {
                            if ($instance['fmcta_image_placement'] == "below") {
                                echo 'checked="checked"';
                            }
                        } ?>
                        />
                    <label for="<?php echo $this->get_field_id('fmcta_image_placement'); ?>_2"><em>Below</em>
                        title</label>


                </p>


                <p>Link the Title? <br/>
                    <input type="radio" name="<?php echo $this->get_field_name('header_link'); ?>" value="true"
                           class="<?php echo $this->get_field_id('header_link'); ?>"
                           id="<?php echo $this->get_field_id('header_link'); ?>_1" <?php
                    if (isset($instance['header_link'])) {
                        if ($instance['header_link'] == "true" || $instance['header_link'] == "") {
                            echo 'checked="checked"';
                        }
                    } ?>  />
                    <label for="<?php echo $this->get_field_id('header_link'); ?>_1">Yes</label>
                    &nbsp;
                    <input type="radio" name="<?php echo $this->get_field_name('header_link'); ?>" value="false"
                           class="<?php echo $this->get_field_id('header_link'); ?>"
                           id="<?php echo $this->get_field_id('header_link'); ?>_2" <?php
                    if (isset($instance['header_link'])) {
                        if ($instance['header_link'] == "false") {
                            echo 'checked="checked"';
                        }
                    } ?>  />
                    <label for="<?php echo $this->get_field_id('header_link'); ?>_2">No</label></p>
                <!--/Link Heading-->

                <div class="divide">&nbsp;</div>

                <h3 class="title">Advanced</h3>

                <p><label for="<?php echo $this->get_field_id('class'); ?>"><strong>Custom CSS class:</strong><br/>
                        <small>You can add a CSS class to add custom styling</small>
                    </label>
                    <input type="text" name="<?php echo $this->get_field_name('class'); ?>"
                           value="<?php if (isset ($instance['class'])) {
                               echo esc_attr($instance['class']);
                           } ?>" style="width:100%;"/>
                </p>

                <div class="divide">&nbsp;</div>

            </div>
            <!--/.fm-step-advanced-options-->
        </div>
        <!--/.fm-step-advanced-->

        <p>If you like Feature Me,
            <a href="http://wordpress.org/support/view/plugin-reviews/feature-me" target="_blank">please rate it.</a></p>


        </div><!--/fm_widget-->
    <?php
    } //form

    /**
     * 1.3 - generateCSS
     * Adds CSS to page for widgets
     *
     * @todo enqueue css so it doesn't duplicate each time.
     * @return string
     */
    private
    function generateCSS()
    {
        //@todo utilize dashicons for stars http://melchoyce.github.io/dashicons/
        $id = $this->id;
        $stars = plugins_url('feature-me/img/star.png');
        $css = <<<EOD
            <style>
			.divide{
				/*border-bottom:1px solid #7de0ff;*/
				border-bottom:1px dotted #ccc;
				box-shadow:0 1px 0 #fff;
				width:100%;
				height:1px;
				margin:20px 0 20px 0;
			}

			a img{
				border:none;
			}
			.$id-options{
			    /*display:none;*/
			    width:80%;
                margin:-10px auto 0 auto;
			}
			.fm-arrow{
			    font-size:75%;
			    color: #c0c0c0;
			}
			.fm-option-title{
			    cursor: pointer;
			    border-bottom: 1px solid #e5e5e5;
			    padding: 10px;
			    margin:0;
			}
			.fm-step-1-options,
			.fm-step-2-options,
			.fm-step-3-options,
			.fm-step-4-options,
			.fm-step-advanced-options{
			    display:none;
			    padding: 0 10px;
			}
			.fm-step-1,
			.fm-step-2,
			.fm-step-3,
			.fm-step-4,
			.fm-step-advanced{
			    background: #fafafa;
			    border: 1px solid #e5e5e5;
			    margin-top:10px;
			    padding:0
			}
			.fmcta_image_uri{
			    height:30px;
			}
			.fmcta_upload{
			    width:85px !important;
			    height:30px !important;
			    vertical-align:top !important;
			    text-align:center;
			}

            .rating {
                overflow: hidden;
                display: inline-block;
                font-size: 0;
                position: relative;
                margin:15px 0 !important;
            }
            .rating-input {
                float: right;
                width: 16px;
                height: 16px;
                padding: 0;
                margin: 0 0 0 -16px;
                opacity: 0;
            }
            .rating:hover .rating-star:hover,
            .rating:hover .rating-star:hover ~ .rating-star,
            .rating-input:checked ~ .rating-star {
                background-position: 0 0;
            }
            .rating-star,
            .rating:hover .rating-star {
                position: relative;
                float: right;
                display: block;
                width: 16px;
                height: 16px;
                background: url('$stars') 0 -16px;
            }
		</style>
EOD;

        return $css;
    }


    /**
     * fmcta_render_title
     * Use this method to render the widget title.
     *
     * @param array $instance
     * @param string $before_title
     * @param string $after_title
     */
    public
    function fmcta_render_title($instance, $before_title, $after_title)
    {

        /*--CTA Title--*/

        switch (true) {
            //CTA is using the page/post title
            case (isset($instance['fmcta_heading_title_type']) && $instance['fmcta_heading_title_type'] == "post"):

                //Generate a link for header
                if ($instance['header_link'] == "true") {
                    //Generate default link via permalink
                    if ($instance['fmcta_landing_type'] == "default") {
                        echo $before_title . '<a href="' . get_permalink() . '"\>' . the_title('', '', false) . '</a>' . $after_title;
                    } //Generate custom link via text fmcta_type_url field
                    else {

                        echo $before_title . '<a href="' . $instance['fmcta_type_url'] . '">' . $instance['fmcta_heading_title_content'] . '</a>' . $after_title;
                    }
                } else {
                    echo $before_title . $instance['fmcta_heading_title_content'] . $after_title;
                }
                break;

            //CTA is using a custom title
            case (isset($instance['fmcta_heading_title_type']) && $instance['fmcta_heading_title_type'] == "custom"): //Custom Title

                //Generate a link for Header
                if (isset($instance['header_link']) && $instance['header_link'] == "true") {
                    //Generate default link via permalink
                    if ($instance['fmcta_landing_type'] == "default") {
                        echo $before_title . '<a href="' . get_permalink() . '"\>' . $instance['fmcta_heading_title_content'] . '</a>' . $after_title;
                    } //Generate custom link via text fmcta_type_url field
                    else {
                        echo $before_title . '<a href="' . $instance['fmcta_type_url'] . '">' . $instance['fmcta_heading_title_content'] . '</a>' . $after_title;
                    }
                } else {
                    echo $before_title . $instance['fmcta_heading_title_content'] . $after_title;
                }
                break;

            //CTA is not using a title
            case (isset($instance['fmcta_heading_title_type']) && $instance['fmcta_heading_title_type'] == "none"):
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
        echo '<div class="fmcta_featured_image">';

        if ($instance['fmcta_use_image'] == 'feature') {
            ?>
        <a href="<?php if ($instance['fmcta_landing_type'] == "default") {
            the_permalink();
        } else {
            echo $instance['fmcta_type_url'];
        } ?>" title="<?php echo $instance['fmcta_heading_title_content']; ?>">
            <?php the_post_thumbnail($instance->thumb_size, array('class' => 'fmcta_thumb'));
            ?></a><?php
        } else if ($instance['fmcta_use_image'] == "upload") {
            ?>
            <a href="<?php if ($instance['fmcta_landing_type'] == "default") {
                the_permalink();
            } else {
                echo $instance['fmcta_type_url'];
            } ?>" title="<?php echo $instance['fmcta_heading_title_content']; ?>">
                <img src="<?php echo $instance['fmcta_image_uri']; ?>"
                     alt="<?php echo $instance['fmcta_heading_title_content']; ?>"/></a>

        <?php
        }
        echo '</div><!--/.fmcta_featured_image-->';
    }

    public
    function fmcta_render_button($instance)
    {

        //If the user doesn't want to display the button, stop here.
        if (isset($instance['fmcta_button_type'])) {
            if ($instance['fmcta_button_type'] == "none" || $instance['fmcta_button_text'] == "") {
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
        if ($instance['fmcta_landing_type'] == 'default') {
            $url = get_the_permalink();
        } else {
            $url = $instance['fmcta_type_url'];
        }

        //Get the appropriate button type.
        if (isset($instance['fmcta_button_type']) && $instance['fmcta_button_type'] == "text") {
            $class .= " fmcta-text-only";
            $button_content = $button_text;
        } else if (isset($instance['fmcta_button_type']) && $instance['fmcta_button_type'] == "css") {
            $class .= " fmcta-button";
            $button_content = $button_text;
        } else if (isset($instance['fmcta_button_type']) && $instance['fmcta_button_type'] == "upload") {
            $button_content = "<img src='$button_image' alt='$button_text' />";
        }

        $link = "<a href='$url' class='$class' >$button_content</a>";

        return $link;

    }

} //featureme