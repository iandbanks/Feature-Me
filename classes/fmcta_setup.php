<?php
/********************************************************************************
 *  Copyright 2012-2013 Ian Banks
 *******************************************************************************
 *
 *      Table of Contents
 *
 *      1.0 - fmSetup Class
 *          1.1 - Variables
 *              1.1.1 - var $featureme_v
 *              1.1.2 - var $widget_class
 *              1.1.3 - var upgrade_message
 *          1.2 - init
 *          1.3 - register
 *          1.4 - compare_versions
 *          1.5 - get_version
 *          1.6 - set_message
 *          1.7 - display_message
 *          1.8 - featureme_set_options
 *
 *
 ********************************************************************************/



/********************************************************************************
 * 1.0  - fmSetup Class
 *
 * @since 1.1.0
 *
 * @todo add method to update settings fields
 ********************************************************************************/
class fmSetup{

    /**
     * @var string $featureme_v Feature Me Version
     */
    protected $featureme_v = '1.2';
    /**
     * @var string $widget_class Widget Class
     */
    protected $widget_class = "fmcta_widget";
    /**
     * @var array $upgrade_message Array with the messages to display for Feature Me activation.
     */
    protected $upgrade_message = array(
        '1.1.0' => "<p><strong>FEATURE ME - CTA WIDGET</strong>: <br/>Thanks for trying out Feature Me CTA! <a href='/wp-admin/widgets.php'>Set up a widget now&rarr;</a></p>
        <div style='width:100%; border-bottom:1px dashed #ccc; height:1px;'>&nbsp;</div><p>If you like Feature Me, <a href=\"http://wordpress.org/support/view/plugin-reviews/feature-me\">please rate it.</a></p>",
    '1.1.1' =>"<h2><strong>Feature Me: Call to Action Widget</strong></h2><p>Thanks for using
    Feature Me! Get started by <a
href='/wp-admin/widgets.php'>setting up a <strong>call to action</strong> widget now&rarr;</a></p>",
    '1.2' =>"<h2><strong>Feature Me: Call to Action Widget</strong></h2><p>Thanks for using
    Feature Me! Get started by <a
href='/wp-admin/widgets.php'>setting up a <strong>call to action</strong> widget now&rarr;</a></p><p>If you are
upgading from a previous version, some options have changed and you will need to
<a href='/wp-admin/widgets.php'>update some settings on your widget.</a></p>");


    /**************************************************
     * 1.2 - init
     * @since 1.0
     **************************************************/
    public function init(){
        add_action('widgets_init', array($this,'register'));
        return;
    }

    /**
     * 1.3 - register
     * Registers the widget if the fmcta_widget class exists
     *
     * @since 1.1.0
     */
    public function register(){

        try{
            if(class_exists($this->widget_class,false)){
                register_widget($this->widget_class);
            }
            else{
                throw new Exception("Widget cannot be initialized. Class 'fmcta_widget' not found.",001);
            }
        }
        catch(Exception $e){
            $this->set_message(array($e->getMessage()),"error");
            $this->display_message();
        }

        return;
    }

    /**
     * 1.4 - compare_versions
     * Compares current version and new version of Feature Me
     * Returns true if versions match
     * Returns false if versions DO NOT match
     *
     * @return bool
     *
     * @since 1.1.0
     */
    function compare_versions(){
        $match;
        //first check if featureme_v and featureme_v_new exist
        if(get_option('featureme_v') && get_option('featureme_v_new') ){

            //now compare versions
            if(get_option('featureme_v_new') == get_option('featureme_v')){
                //versions match but are empty. return false.
                if(get_option('featureme_v') == ""){
                    $match=false;

                }
                else{
                    $match = true;
                }

            }
            //versions don't match. return false.
            else{
                update_option('featureme_v_new',$this->featureme_v);
                $match = false;
            }
        }
        //version fields do not exist. return false since there was no pre-existing version.
        else{
            $match = false;
        }
        return $match;
    }

    /**
     * 1.5 - get_version
     * Returns the current version of Feature Me
     * @return string $version
     *
     * @since 1.1.0
     */
    public function get_version(){
        $version = $this->featureme_v;
        return $version;
    }

    /**
     * 1.6 - set_message
     * Sets a message to be displayed
     *
     * @param string $message
     * @param string $type
     * @return string $html
     *
     * @since 1.1.0
     */
    public function set_message($message, $type){
        foreach($message as $key => $val){
            if(strpos($this->featureme_v,$key) >= 0){
                if($val==""){
                    return "No message"; //If there is no message pull out
                }
                $message=$val;
                break;
            }
        }

        $html.="<div class ='$type'>$message</div>";
        return $html;

    }

    /**
     * 1.7 - display_message
     *
     * @param string $type
     * @return string
     *
     * @since 1.1.0
     */
    public function display_message($type="updated"){
        if($type=="updated"){
            //add_action('admin_notices',array($this,'set_message'));
            return "<div class ='$type'>".$this->upgrade_message[$this->featureme_v]."</div>";
        }
        else if($type=="pointer"){
            return <<<EOD
                    <script>
                    jQuery(document).ready( function($) {
                    var options = {“content”:”<h3>Title<\/h3><p>Contents<\/p>”,”position”:{“edge”:”left”,”align”:”center”}};
                    if ( ! options )
                    return;
                    options = $.extend( options, {
                    close: function() {
                    //to do
                    }
                    });
                    $(‘#menu-appearance’).pointer( options ).pointer(“open”);
                    });
                    </script>
EOD;

        }
        else{
            return;
        }
    }

    /**
     * 1.8 - featureme_set_options
     *
     * @param (array) $options
     * @return bool|string
     *
     * @since 1.1.0
     */
    public function featureme_set_options($options){
        foreach($options as $key => $val){
            update_option($key,$val);
        }
        return 'Option Set Successfully';
    }

}