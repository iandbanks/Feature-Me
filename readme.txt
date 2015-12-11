=== Feature Me: Call to Action Widget ===
Contributors: iandbanks
Donate Link: http://www.phase-change.org/donate/
Tags: feature, widget, featured-post, featured-page, feature-me, feature-widget,cta, call to action, featured post, feature me, call to action widget, feature me cta widget, feature me cta widget
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily create a call to action to any page or post on your website.

== Description ==
Feature Me CTA Widget is a simple, yet powerful, widget that allows you to easily create a call to action to any page or post on your. It pulls the pages and posts from the WordPress database and allows a user to choose one to display an excerpt and featured image in a widget area. Use this widget to feature prominent articles, or promote actions on the website.

IT’S EASY TO USE. If your theme uses widgets, you can use Feature Me. Just select the post or page you want to feature in any widget area, customize some preferences and save. Done.

IT SAVES TIME. Forget about endless sticky posts. With Feature Me, you won't waste your time swapping out sticky
posts to display your posts and pages in a prominent location. When you use Feature Me,
you there is no need to code a featured page on your website ever again.

== Screenshots ==
1. Feature Me widget Admin
2. Feature Me Post/Page as a sidebar CTA

== Installation ==
1. Upload `feature-me.zip` using the WordPress plugin uploader
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Can I upload a custom image for my featured post? =
Not at this time. This widget pulls images from the page/post featured image.

= No photo shows up even when "Featured Image" is turned on =
Make sure that your theme supports featured images. If it doesn't, add the following code to your themes
functions
.php file:

`add_theme_support( 'post-thumbnails' );`

If your theme DOES support featured images, check to make sure that the post or page you have chosen has a featured
image attached to it.

= Can I customize the CSS of the Feature Me widget? =
Yes. The widget uses a css class "feature-me" from which you can target all other elements of the widget and change
the look via css.

You can also use your enter in your own css classes in the "Custom CSS Class" field.

= My website uses a CSS framework. Can Feature Me work with different frameworks? =
Yes. You can utilize the "Custom CSS Class" field and enter in framework specific classes. For example if you are
using the 960.gs by Nathan Smith, you could enter in "grid_4" or similar. If you use bootstrap,
you could enter "span4" etc.

= Can I just display the image and link and not any text? =
Yes. Select the custom body option and leave it blank.

= How can I change the CTA button color? =
To change the button color navigate to the featureme.css stylesheet:
1. Go to the plugins > editor.
2. Choose 'Feature Me - CTA Widget' from the plugin dropdown menu and click "select"
3. Click 'feature-me/featureme.css' on the right side

The button is defined in 3 areas: .feature-me a.fmBtn, .feature-me a.fmBtn:hover, .feature-me a.fmBtn:visited

Enter your own values or use http://css3buttongenerator.com to generate a button. Replace featureme.css values with
the values you generated.

**WARNING** If you mess up the values here, you might need to completely re-install
feature-me to get it back. I HIGHLY RECOMMEND copying the styles to your computer before tweaking anything.


== Upgrade Notice ==

= 2.0 =
We've made Feature Me easier and more intuitive with massive improvements front and back!

= 1.2 =
More powerful and easier to use!

= 1.1.1 =
Bug fixes and welcome tool-tip

= 1.1.0 =
Bug fixes, optimization and TONS more options!
You may need to re-save pre-existing Feature Me widgets.

= 1.0.0 =
First version of the plugin.


== Changelog ==

= 2.0 =
* New Feature: Fully WordPress 4.2!
* New Feature: Settings slide to open making the widget easier to manage.
* New Feature: Drag and drop the order you want your CTA to display.
* New Feature: Select from all available post types. Great for highlighting products in your store!
* New Feature: New settings section (Settings > Feature Me) allow for greater control over many global CTA settings.
* New Feature: Added a custom CSS setting so your CSS won't be overwritten on future plugin updates.
* Enhancement: Javascript on Widget admin page has been optimized.
* Enhancement: More settings to allow better control of the widget.


= 1.2 =
* New Feature: Added Pointer messages to show where Feature Me is located.
* New Feature: Upload a CTA image.
* New Feature: Upload button images.
* New Feature: Select from 4 pre-defined CSS3 buttons.
* Enhancement: Feature Me is now better organized.
* Enhancement: Feature Me options are now organized in easy to access accordion menus.
* Enhancement: Javascript on Widget admin page has been optimized.
* Enhancement: Feature Me 1.1.x user settings will be automatically converted to 1.2 settings.
* Bug Fix: Removed annoying admin message that displayed first time Feature Me was installed.
* Bug Fix: Fixed PHP errors on admin page some website administrators were reporting


= 1.1.1 =
* "Feature Me - CTA Widget" is now "Feature Me: Call to Action Widget".
* Bug Fix: Fixed an issue where the Feature Me welcome message would display any time a user disabled any plugin.
* New Feature: Added Pointer tips to help users find where Feature Me is. Special thanks to [BIRGIRE](http://wordpress.stackexchange.com/users/26350/birgire) for helping me with this.

= 1.1.0 =
* "Feature Me" is now "Feature Me - CTA Widget".
* Bug Fix: Fixed an issue where default radio buttons weren't selected when widget is created.
* Bug Fix: Fixed an issue where the widget title in the admin menu displayed incorrect title. The correct title will now display on the widget title admin
* Bug Fix: Fixes an issue where the message "Select a Featured Page or Post to display" would display when leaving the custom body field empty.
* New Feature: Option to hide Title text
* New Feature: Option to hide Body text
* New Feature: Option to hide CTA Link title
* New Feature: Option to link Title heading
* New Feature: CTA Link is now a CSS3 button

= 1.0.0 =
* Select a post or page to feature via select menu
* Uses post title or a custom title
* Uses post excerpt or custom body text
* Make use of a post/page feature image
* Customize the "read more" link title
* "Read more" text can link to post/page or to a custom url
* Allows use of custom classes - write one or more class names for css customization