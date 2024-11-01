=== Webcomic Creator Studio ===
Contributors: none
Tags: webcomic
Requires at least: 3.5
Tested up to: 4.4.2
Stable tag: 1.0.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create and publish a webcomic to your Wordpress site using your own characters and backgrounds. 
*Warning:This plugin is no longer supported as of 2016. Use at your own risk*

== Description ==

*Warning:This plugin is no longer supported as of 2016. Use at your own risk*
Webcomic Creator Studio allows you to generate and publish 3-panel webcomics automatically using character and background images you provide, with your own custom text and title and captions. The comics are saved as image files and displayed on posts. It includes a navigation system for visitors to move through your comic archives and a queue system that will automatically post new comics on the days you want to schedule updates (or you can use a button to immediately post a new comic).

= Features =

*   Comics are saved as images onto your server and automatically posted from a queue according to an update schedule that you set. Choose which days a new comic is added and comics will be pulled from the queue and published live to your site on those days. This allows you to create your comics days or weeks in advance and not have to worry about being around to post them.
*   Characters and backgrounds can be uploaded easily through the options menu, using the Wordpress Media Library. A character can have as many images as you want and you can choose which image to use in each panel.
*   Previews of the next comic are automatically generated which show only the first of the 3 panels.
*   The full transcript of a comic is automatically stored and used as the "alt" property when a comic is displayed.
*   First/Last/Next/Previous/Random navigation links are automatically added beneath each comic.
*   Shortcodes give you more control over where things are placed on your site. [webcomic-preview] when added to a page with a comic on it (even in the sidebar) will show the next comic preview, and [webcomic-display-comic] will display the latest comic wherever you place it.
*   Word or thought bubbles are automatically sized according to the size of your character so they fit in the panels. You can change the font size from a dropdown menu to make sure that the text fits.


== Installation ==


1. Upload the plugin files to the `/wp-content/plugins/webcomic-creator-studio` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Webcomic Creator Studio screen to configure the plugin settings
4. On that settings page, the 'characters' and 'backgrounds' tabs will allow you to upload image files for your comic. It is recommended to use .png files. Characters should be facing to the right. If you want to display backgrounds behind them, you'll need to make the area around the character transparent in the character's image file.
5. Under 'Webcomics' on the main wordpress menu, you'll find the option to add new comics. When you're done creating a comic, there is a button to save it to the server.
6. Once a comic is saved to the server, you'll find your webcomic queue, also under the 'Webcomics' menu on the main wordpress menu. New comics need to be moved from the "unqueued" list to the "queued" list in order to be published. They will be automatically posted to your site according to the update schedule you chose in the plugin options.
7. Use the shortcode [webcomic-display-comic] to display your latest comic anywhere on your site. Use the shortcode [webcomic-preview] to show the first panel of the comic that's coming up next.
8. When comics are published, they will be posts with the category name that you listed in the comic settings (default: Webcomic).
9. In some themes, you will need to turn off the Post Excerpt generation in the settings page.

== Frequently Asked Questions ==

= How do I get new characters or backgrounds? =

Draw them yourself. That's the point of the plugin, to make it easier to showcase your own comic creations. I've included a few sample characters so you can get a feel for the system, but there is only 1 image for each character and they are not meant to take the place of doing your own work and creating your own images.

= Why do my characters have white blocks behind them when they appear in comics? =

You need to make the area around the character transparent so the background shows through it. JPEG files do not support transparency. Use .png (recommended) or .gif format for your character images.

= Why is the title showing up incorrectly in the options screen after I upload a background image? =

Wordpress will, at that point, still be showing the old or default title, but it is stored correctly in the database. When you make a comic you will see the title you set and not the wrong one. You can reload the options page if you want to see the background image listed with the correct title.

= How can I edit a comic that I already saved? =

You can't. Comics are saved as image files and can't be edited. You'll have to create the comic again and delete the old one.

== Screenshots ==

1. The screen in the admin section where you create a new comic. It is automatically previewed as you go.
2. The lower portion of the comic creation screen in the admin section.
3. The comic queue. New comics, when saved, are added to the unqueued list. Once you move them to the queued list, they are available to be published.
4. The options page in the admin section.
5. Backgrounds can be uploaded from a tab in the options page in the admin section.
6. Characters can also be created from another tab in the options page. Once you create a character you can upload images for that character.
7. An example of a comic published to a website, with the navigation links and a preview of the next comic in line.

== Changelog ==

= 1.0.0.7 =

* Added warning - plugin no longer supported

= 1.0.0.6 = 

* Fixed bug in error logging

= 1.0.0.5 =

* Added logging for certain fatal errors and improved safety of logging function.

= 1.0.0.4 =

* Fixed display error in webcomic category-- the default category was set to the same keyword as the taxonomy type. 

* Added additional input checks; categories are now restricted to all lowercase.

= 1.0.0.3 =

* Fixed Typo in an Element ID in the creation form.

= 1.0.0.2 = 

* Fixed error in uninstall.php that was preventing uninstallation of the plugin.

= 1.0.0.1 =

* Added option to decide whether or not to generate post excerpts to increase compatibility with some themes.

= 1.0 =

* This is the first version of Webcomic Creator Studio. All beta testing was done in-house.

== Upgrade Notice ==

= 1.0 =
This version hasn't been tested by the public and may contain bugs. Security precautions have been taken to make the plugin secure but I make no guarantees.

